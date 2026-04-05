# Documento Tecnico de Cambios - Taller A

**Fecha:** 15 de marzo de 2026
**Plataforma:** Taller A (Legal Tech)
**Stack:** Laravel 7 + PHP 7.4 (Backend) / React 18 + TypeScript + MUI (Frontend)

---

## Resumen Ejecutivo

Se realizaron tres bloques de cambios principales:

1. **Integracion de estado de documentos con AUCO API** en el modulo de Firmas Electronicas
2. **Descuento automatico de tokens (creditos)** al solicitar firmas o validaciones de antecedentes
3. **Modulo administrativo de gestion de tokens** para ajustar creditos por empresa

---

## 1. Consulta de Estado de Documentos en AUCO

### Objetivo
Mostrar en tiempo real el estado de cada documento enviado a firma electronica (Firmado / Pendiente) y permitir descargar el documento firmado.

### Variables de entorno agregadas

**Archivo:** `Back/.env`

```
API_AUCO_ENVIRONMENT=https://api.auco.ai/v1.5/ext
API_AUCO_PUBLIC=puk_u3GvHkqN0h4S9GCfZwBnqDdoV0MJ6GgH
API_AUCO_PRIVATE=prk_sSQJEHhor3OCjxfCBSJvkjNMDgDj7o8i
API_AUCO_EMAIL=admin@tallera.co
```

- `API_AUCO_PUBLIC`: Llave publica para consultas GET (estado de documentos)
- `API_AUCO_PRIVATE`: Llave privada para operaciones POST (subida de documentos)

### Backend: SignController.php

**Archivo:** `Back/app/Http/Controllers/SignController.php`
**Ubicacion:** Task `load`, despues de obtener los registros (lineas 261-284)

**Cambio:** Se agrego consulta batch al API de AUCO para obtener el estado de cada documento. Se extraen los codigos unicos de los registros y se consulta `GET /document?code={code}` por cada uno.

```php
$status = [];
try {
    $codes = array_unique(array_column($rows, 'code'));
    $client = new Client(['headers' => [
        'Authorization' => env('API_AUCO_PUBLIC')
    ]]);
    foreach ($codes as $code) {
        $response = $client->request('GET',
            sprintf('%s/%s?code=%s', env('API_AUCO_ENVIRONMENT'), 'document', trim($code)),
            ['http_errors' => false]
        );
        if ($response->getStatusCode() == 200) {
            if ($data = json_decode($response->getBody(), true)) {
                $status[$code] = [
                    'done' => strtoupper(trim($data['status'])) === 'FINISH',
                    'link' => isset($data['url']) ? $data['url'] : null
                ];
            }
        }
    }
} catch (\Exception $e) {}
```

**Campos nuevos en la respuesta JSON de cada registro:**

| Campo  | Tipo     | Descripcion                                      |
|--------|----------|--------------------------------------------------|
| `done` | bool/null | `true` = Firmado, `false` = Pendiente, `null` = Sin datos |
| `link` | string/null | URL S3 pre-firmada del documento (si esta disponible) |

**Notas:**
- Se usa `array_unique` para evitar consultas duplicadas cuando multiples firmantes comparten el mismo documento
- El bloque esta envuelto en `try-catch` para que un fallo de AUCO no rompa el listado
- Si AUCO no responde, los campos `done` y `link` quedan en `null`

### Frontend: Signs.tsx

**Archivo:** `Page/pages/Signs.tsx`

**Cambio 1 - Columna "Estado" (linea 263-273):**

Se agrego una columna con Chips de MUI coloreados:
- Verde (`#4CAF50`): "Firmado" cuando `done === true`
- Rojo (`#F44336`): "Pendiente" cuando `done === false`
- Outlined "...": cuando `done === null` (sin datos de AUCO)

**Cambio 2 - Boton "Documento" (lineas 211-242):**

Se agrego un segundo boton de accion en el `dash` array, despues de "Detalles":
- Abre el PDF firmado en nueva pestana via `window.open(item.link, '_blank')`
- Se deshabilita (`disabled={!item.link}`) cuando no hay URL disponible
- Usa variante `outlined` para diferenciarse visualmente del boton principal

---

## 2. Descuento Automatico de Tokens

### Objetivo
Descontar 1 token del campo `bank` de la tabla `firms` cada vez que un usuario solicita una firma electronica o una validacion de antecedentes.

### SignController.php - Firma electronica

**Archivo:** `Back/app/Http/Controllers/SignController.php`
**Ubicacion:** Task `post`, despues de insertar los registros en `signs` (lineas 156-158)

```php
DB::table('firms')
    ->where('row', Auth::user()->firm->row)
    ->decrement('bank');
```

Se ejecuta una sola vez por solicitud de firma, independientemente del numero de firmantes.

### PastController.php - Validacion de antecedentes

**Archivo:** `Back/app/Http/Controllers/PastController.php`

**Ubicacion 1:** Validacion de representante legal (type 2), lineas 138-140
**Ubicacion 2:** Validacion de antecedentes penales (type 1/default), lineas 214-216

```php
DB::table('firms')
    ->where('row', Auth::user()->firm->row)
    ->decrement('bank');
```

Ambos se ejecutan despues de un `insertGetId` exitoso en la tabla `pasts` y solo si el API externo respondio correctamente.

### Consideraciones
- Se usa `decrement()` de Laravel que genera `UPDATE firms SET bank = bank - 1` a nivel SQL, evitando condiciones de carrera
- El descuento ocurre **despues** de confirmar la operacion exitosa con el API externo
- No hay validacion de saldo minimo (el campo `bank` puede llegar a negativo). **Recomendacion:** agregar validacion `bank > 0` antes de permitir la operacion

---

## 3. Modulo Administrativo de Tokens

### Objetivo
Pagina dedicada en `/tokens` para que el administrador visualice y agregue tokens a las empresas.

### Backend: FirmController.php

**Archivo:** `Back/app/Http/Controllers/FirmController.php`
**Ubicacion:** Nuevo case `bank` (lineas 223-252)

**Endpoint:** `POST /firms/bank/{hash}`
**Body:** `{ bank: number }` (cantidad de tokens a agregar)

```php
case 'bank':
    $validator = Validator::make($request->all(), [
        'bank' => 'required|integer|min:1'
    ]);
    if (empty($validator->fails())) {
        DB::table('firms')
            ->where('row', $item->row)
            ->increment('bank', intval($request->get('bank')));
        // Retorna { text, bank } con el nuevo saldo
    }
```

**Validaciones:**
- `bank` requerido, entero, minimo 1
- El `$item` ya viene validado por el flujo principal del controlador (busqueda por hash)

### Ruta: web.php

**Archivo:** `Back/routes/web.php` (linea 106)

Se agrego `bank` al listado de tasks permitidas para la ruta `/firms`:

```php
->where('task', 'load|pull|team|data|make|save|date|dump|bank|icon|drop|lock')
```

### Frontend: Archivos nuevos

**`Page/services/Banks.ts`** (nuevo)

Servicio con dos metodos:
- `load()`: Reutiliza `POST /firms/load` para obtener lista de empresas con paginacion
- `bank(item, amount)`: `POST /firms/bank/{hash}` para agregar tokens

**`Page/pages/Banks.tsx`** (nuevo)

Pagina con namespace `Banks.List` que muestra:

| Columna   | Campo  | Descripcion                     |
|-----------|--------|---------------------------------|
| Empresa   | `name` | Nombre y NIT/codigo de la empresa |
| Tokens    | `bank` | Saldo actual (verde si > 0, rojo si 0) |
| Acciones  | -      | Boton "Agregar Tokens"          |

**Flujo de uso:**
1. Admin navega a `/tokens`
2. Ve tabla con empresas y saldo de tokens
3. Clic en icono de accion -> abre dialogo
4. Dialogo muestra saldo actual e input numerico
5. Al confirmar, hace POST al backend
6. La tabla se recarga automaticamente

### Routing: Application.tsx

**Archivo:** `Page/Application.tsx`

- Import agregado: `import {Banks} from "./pages/Banks"` (linea 63)
- Ruta agregada (lineas 275-283):

```tsx
<Route path="/tokens">
    <Route
        element={
            <Lock name="Banks.List">
                <Banks.List />
            </Lock>
        }
        index />
</Route>
```

### Menu: environment.ts

**Archivo:** `Page/environment.ts` (lineas 212-216)

Entrada agregada en la seccion "Administracion" con `type: 1` (solo admin):

```typescript
{
    type: 1,
    name: 'Banks',
    path: '/tokens',
    text: 'Tokens',
    icon: ['M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0...']
}
```

---

## 4. Correcciones de Entorno Local

### UserController.php

**Archivo:** `Back/app/Http/Controllers/UserController.php`

Se envolvieron las llamadas a Bitrix24 y Google Drive en `try-catch` para evitar errores 500 en entorno local donde no hay credenciales configuradas para estos servicios.

### environment.ts (desarrollo)

**Archivo:** `Page/environment.ts` (lineas 1-3)

URLs apuntando a entorno local:
```typescript
export const HOST = 'http://localhost:8082';  // webpack-dev-server
export const BACK = 'http://shop.test';       // Laravel Valet
```

**Nota:** Antes del despliegue a produccion, revertir a:
```typescript
export const HOST = 'https://app.tallera.co';
export const BACK = 'https://shop.bontris.com';
```

---

## Inventario de Archivos Modificados

| Archivo | Accion | Descripcion |
|---------|--------|-------------|
| `Back/.env` | Modificado | Variables AUCO API |
| `Back/routes/web.php` | Modificado | Task `bank` en ruta firms |
| `Back/app/Http/Controllers/SignController.php` | Modificado | Status AUCO + decrement bank |
| `Back/app/Http/Controllers/PastController.php` | Modificado | Decrement bank (2 ubicaciones) |
| `Back/app/Http/Controllers/FirmController.php` | Modificado | Case `bank` para tokens |
| `Back/app/Http/Controllers/UserController.php` | Modificado | Try-catch Bitrix24/Google |
| `Page/environment.ts` | Modificado | URLs locales + menu Tokens |
| `Page/Application.tsx` | Modificado | Import Banks + ruta /tokens |
| `Page/pages/Signs.tsx` | Modificado | Columna Estado + boton Documento |
| `Page/pages/Banks.tsx` | **Nuevo** | Pagina de gestion de tokens |
| `Page/services/Banks.ts` | **Nuevo** | Servicio API para tokens |

---

## Recomendaciones para el Desarrollador

1. **Validacion de saldo:** Actualmente no se valida que `bank > 0` antes de permitir firmas o validaciones. Se recomienda agregar esta validacion en `SignController` y `PastController` para evitar saldos negativos.

2. **Revertir URLs:** `Page/environment.ts` tiene URLs de desarrollo local. Revertir antes de desplegar.

3. **Variables .env en produccion:** Verificar que las variables `API_AUCO_*` esten configuradas en el `.env` del servidor de produccion.

4. **Permisos del modulo Tokens:** El modulo esta protegido por `type: 1` en el menu y `<Lock name="Banks.List">` en el routing. Verificar que el componente `Lock` valide correctamente el acceso admin.

5. **Performance AUCO:** La consulta de estado se hace en tiempo real por cada `load`. Si el listado crece mucho, considerar cache o consulta asincrona para no ralentizar la carga de la tabla de firmas.
