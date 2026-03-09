@extends('core')

@section('page')
    <div class="col-xxl-12 col-12">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner" v-if="same(view, 1)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Diagnóstico</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th style="text-align: left; width: 80px">No</th>
                                        <th style="text-align: left">Pregunta</th>
                                        <th style="text-align: left; width: 80px">Acción</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body" v-if="Boolean(data?.list?.length)">
                                    <tr>
                                        <td style="text-align: left">
                                           1.1
                                        </td>
                                        <td style="text-align: left">
                                            Estatutos compilados y actualizados (incluyendo cualquier modificación efectuada al documento de constitución).
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S1" v-model="form[1]" value="1">
                                            <label for="S1">Sí</label><br>
                                            <input type="radio" id="N1" v-model="form[1]" value="0">
                                            <label for="N1">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.2
                                        </td>
                                        <td style="text-align: left">
                                            Copia del Libro de Registro de Accionistas registrado ante la Cámara de Comercio y de los títulos de acciones existentes.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S2" v-model="form[2]" value="1">
                                            <label for="S2">Sí</label><br>
                                            <input type="radio" id="N2" v-model="form[2]" value="0">
                                            <label for="N2">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.3
                                        </td>
                                        <td style="text-align: left">
                                            Copia del Libro de Actas de Asamblea registrados ante la Cámara de Comercio.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S3" v-model="form[3]" value="1">
                                            <label for="S3">Sí</label><br>
                                            <input type="radio" id="N3" v-model="form[3]" value="0">
                                            <label for="N3">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.4
                                        </td>
                                        <td style="text-align: left">
                                            Copia de cualquier derecho, garantía u opción de compra u otros derechos para adquirir acciones o confirmación de que no existen.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S4" v-model="form[4]" value="1">
                                            <label for="S4">Sí</label><br>
                                            <input type="radio" id="N4" v-model="form[4]" value="0">
                                            <label for="N4">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.5
                                        </td>
                                        <td style="text-align: left">
                                            Copia de cualquier acuerdo de compra de acciones, o confirmación de que no existen.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S5" v-model="form[5]" value="1">
                                            <label for="S5">Sí</label><br>
                                            <input type="radio" id="N5" v-model="form[5]" value="0">
                                            <label for="N5">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.6
                                        </td>
                                        <td style="text-align: left">
                                            Copia de todos los poderes otorgados por la Compañía.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S6" v-model="form[6]" value="1">
                                            <label for="S6">Sí</label><br>
                                            <input type="radio" id="N6" v-model="form[6]" value="0">
                                            <label for="N6">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.7
                                        </td>
                                        <td style="text-align: left">
                                            Información sobre los préstamos concedidos a los accionistas o por los mismos y copia de la documentación correspondiente.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S7" v-model="form[7]" value="1">
                                            <label for="S7">Sí</label><br>
                                            <input type="radio" id="N7" v-model="form[7]" value="0">
                                            <label for="N7">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.8
                                        </td>
                                        <td style="text-align: left">
                                            Copia de los Estados financieros de la Compañía de los últimos cinco (5) años y copia de cualquier dictamen del revisor fiscal o contador público.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S8" v-model="form[8]" value="1">
                                            <label for="S8">Sí</label><br>
                                            <input type="radio" id="N8" v-model="form[8]" value="0">
                                            <label for="N8">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.9
                                        </td>
                                        <td style="text-align: left">
                                            Copias de todas las comunicaciones con la Superintendencia de Sociedades.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S9" v-model="form[9]" value="1">
                                            <label for="S9">Sí</label><br>
                                            <input type="radio" id="N9" v-model="form[9]" value="0">
                                            <label for="N9">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           1.10
                                        </td>
                                        <td style="text-align: left">
                                            Copia de los recibos/pruebas de depósito de los estados financieros en la Cámara de Comercio de los últimos cinco (5) años (Artículo 41 de la Ley 222 de 1995).
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S10" v-model="form[10]" value="1">
                                            <label for="S10">Sí</label><br>
                                            <input type="radio" id="N10" v-model="form[10]" value="0">
                                            <label for="N10">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           2.1
                                        </td>
                                        <td style="text-align: left">
                                            Copia de todas las declaraciones de cambio por endeudamiento, importación, exportación, adquisición, venta, u otros conceptos presentados ante el Banco de la República de Colombia.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S11" v-model="form[11]" value="1">
                                            <label for="S11">Sí</label><br>
                                            <input type="radio" id="N11" v-model="form[11]" value="0">
                                            <label for="N11">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           2.2
                                        </td>
                                        <td style="text-align: left">
                                            Copia de todas las declaraciones de divisas por inversión extranjera directa, endeudamiento u otros conceptos presentados ante el Banco de la República y la información de las cuentas de compensación. Confirmación en caso de no existir endeudamiento externo o inversión extranjera directa o cuentas de compensación.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S12" v-model="form[12]" value="1">
                                            <label for="S12">Sí</label><br>
                                            <input type="radio" id="N12" v-model="form[12]" value="0">
                                            <label for="N12">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           2.3
                                        </td>
                                        <td style="text-align: left">
                                            Copia de cualquier comunicación recibida del Banco de la República de Colombia y las respuestas enviadas por la Compañía al Banco de la República de Colombia.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S13" v-model="form[13]" value="1">
                                            <label for="S13">Sí</label><br>
                                            <input type="radio" id="N13" v-model="form[13]" value="0">
                                            <label for="N13">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.1
                                        </td>
                                        <td style="text-align: left">
                                            Lista de todos los empleados (nacionales o extranjeros), incluyendo la fecha del contrato, tipo de contrato, duración (término fijo o indefinido), monto del salario y tipo de salario, régimen de cesantías si aplica, vacaciones pendientes, licencias (ejemplo, permiso de maternidad, incapacidad, permiso sindical etc.) de la Compañía en los tres (3) últimos años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S14" v-model="form[14]" value="1">
                                            <label for="S14">Sí</label><br>
                                            <input type="radio" id="N14" v-model="form[14]" value="0">
                                            <label for="N14">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.2
                                        </td>
                                        <td style="text-align: left">
                                            Lista de personas contratadas mediante contrato de aprendizaje, incluyendo la fecha del contrato y la Resolución del SENA en los últimos tres (3) años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S15" v-model="form[15]" value="1">
                                            <label for="S15">Sí</label><br>
                                            <input type="radio" id="N15" v-model="form[15]" value="0">
                                            <label for="N15">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.3
                                        </td>
                                        <td style="text-align: left">
                                            Copia de los contratos de trabajo de los empleados de la Compañía, incluyendo modificaciones y anexos.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S16" v-model="form[16]" value="1">
                                            <label for="S16">Sí</label><br>
                                            <input type="radio" id="N16" v-model="form[16]" value="0">
                                            <label for="N16">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.4
                                        </td>
                                        <td style="text-align: left">
                                            Listado del personal contratado a través de outsourcing, servicios temporales, cooperativas de trabajo asociado, etc., señalando antigüedad (incluyendo contrataciones previas si no ha habido interrupción) y copia de los Contratos tanto de la Compañía con la empresa outsourcing o temporal, así como los formatos de los contratos usados por ésta con los trabajadores en los tres (3) últimos años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S17" v-model="form[17]" value="1">
                                            <label for="S17">Sí</label><br>
                                            <input type="radio" id="N17" v-model="form[17]" value="0">
                                            <label for="N17">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.5
                                        </td>
                                        <td style="text-align: left">
                                            Listado de contratistas de la Compañía en los tres (3) últimos años y los servicios que prestan, y confirmación de si dichos contratistas fueron afiliados al sistema de seguridad social o al sistema de riesgos laborales.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.6
                                        </td>
                                        <td style="text-align: left">
                                            Lista de préstamos a los empleados de la Compañía y comprobantes de las garantías y autorizaciones de descuento de los salarios y beneficios sociales de los empleados en los tres (3) últimos años.
                                            <br>
                                            Copia de las autorizaciones de descuento en los casos en los que la Compañía descuenta los salarios de los empleados (por ejemplo, nóminas).
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S19" v-model="form[19]" value="1">
                                            <label for="S19">Sí</label><br>
                                            <input type="radio" id="N19" v-model="form[19]" value="0">
                                            <label for="N19">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.7
                                        </td>
                                        <td style="text-align: left">
                                            De ser aplicable, lista de las demandas laborales que la Compañía tiene en su contra. Acceso a los expedientes pertinentes (incluyendo cualquier solicitud o proceso iniciado por la Unidad de Gestión Pensional y Parafiscal - UGPP y el Ministerio de Trabajo).
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S20" v-model="form[20]" value="1">
                                            <label for="S20">Sí</label><br>
                                            <input type="radio" id="N20" v-model="form[20]" value="0">
                                            <label for="N20">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.8
                                        </td>
                                        <td style="text-align: left">
                                            Copia de la última nómina pagada por la Compañía. Información relativa a la remuneración variable (comisiones, dietas, viáticos, bonos, primas, asignaciones etc.) y si dichos pagos se consideran parte constitutiva del salario del empleado. Descripción de cualquier plan de acciones u opciones sobre acciones aplicable a los empleados.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S20" v-model="form[20]" value="1">
                                            <label for="S20">Sí</label><br>
                                            <input type="radio" id="N20" v-model="form[20]" value="0">
                                            <label for="N20">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.9
                                        </td>
                                        <td style="text-align: left">
                                            Evidencia de pago de beneficios sociales obligatorios (prestaciones sociales cesantía, interés y prima de servicios), subsidio de transporte, etc. Información relacionada con el trabajo suplementario u horas extras e indicación sobre la forma en que la Compañía compensa por ese trabajo del total de número de trabajadores en los tres (3) últimos años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.10
                                        </td>
                                        <td style="text-align: left">
                                            Copia del PILA (planillas de liquidación de aportes) del total de número de trabajadores en los tres (3) últimos años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.11
                                        </td>
                                        <td style="text-align: left">
                                            Confirmar si la Compañía tiene vigente el Programa de Salud Ocupacional (ahora llamado el Sistema de Gestión de Seguridad y Salud en el Trabajo) y el Panorama de Factores de Riesgo. Confirmar si están firmados por el Representante Legal, el responsable de seguridad y salud en el trabajo y el asesor de la ARL. Proporcionar el estado actual del proceso.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           3.12
                                        </td>
                                        <td style="text-align: left">
                                            Registros de accidentes de trabajo y enfermedades profesionales de trabajadores y contratistas de la Compañía en los últimos tres (3) años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.1
                                        </td>
                                        <td style="text-align: left">
                                            Información sobre todos los derechos de propiedad intelectual utilizados y/o registrados por la Compañía en Colombia o en el exterior (derechos de propiedad intelectual incluyen patentes, modelos de utilidad, marcas comerciales, nombres comerciales y comerciales, nombres de dominio y URL, diseños, derechos de autor y derechos relacionados, software) y confirme si las marcas registradas se están utilizando y si han sido autorizados a terceros.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.2
                                        </td>
                                        <td style="text-align: left">
                                            Información relacionada con disputas, quejas u objeciones relacionadas con los derechos de propiedad intelectual.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.3
                                        </td>
                                        <td style="text-align: left">
                                            Información relacionada con acuerdos, licencias y sublicencias de derechos de propiedad intelectual.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.4
                                        </td>
                                        <td style="text-align: left">
                                            Copias de los formatos a ser suscritos por empleados, consultores o terceros, cuando se contrata trabajo por encargo, contratos laborales, cláusulas de no competencia o confidencialidad utilizados por la Compañía.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.5
                                        </td>
                                        <td style="text-align: left">
                                            Listado de cualquier otra Propiedad Intelectual que sea relevante para la Compañía. (Patentes, modelos de utilidad, diseños industriales, marcas, logos o diseños que la Compañía utilice así no estén registrados ante la Autoridad.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.6
                                        </td>
                                        <td style="text-align: left">
                                            Copias de cualesquiera contratos o acuerdos con terceros que regule o restrinja el uso de cualquier propiedad intelectual que pertenezca a terceros y que sea utilizada por la Compañía.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.7
                                        </td>
                                        <td style="text-align: left">
                                            Copias de Políticas internas en relación con información confidencial, secretos industriales, know-how y otra información propietaria o de acceso de la Compañía.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.8
                                        </td>
                                        <td style="text-align: left">
                                            Una descripción de la(s) plataforma(s) y software(s) que estén siendo utilizadas por la Compañía, así como una explicación respecto de si las mismas han sido desarrolladas internamente por la Compañía y/o si son contratadas de terceras personas, adjuntando copia de los mencionados contratos de licencia.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           4.9
                                        </td>
                                        <td style="text-align: left">
                                            Listado de los sistemas contratados que involucren servicios alojados en la nube, copia de los mencionados contratos.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           5.1
                                        </td>
                                        <td style="text-align: left">
                                            Lista de los principales acuerdos vigentes celebrados por la Compañía, incluidos los acuerdos verbales, que indiquen su propósito, alcance, duración y principales obligaciones. Específicamente, aquellos ejecutados fuera del curso ordinario de los negocios de la Compañía, indicando el monto (si corresponde) e incluyendo una breve descripción.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           5.2
                                        </td>
                                        <td style="text-align: left">
                                            Copia de los acuerdos mencionados anteriormente, incluidos los acuerdos celebrados con proveedores, clientes, cualquier entidad gubernamental o gubernamental, acuerdos de colaboración o joint venture, acuerdos de fideicomiso, acuerdos o acuerdos de confidencialidad y de no competencia que limiten las actividades en un mercado o área geográfica específica.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           5.3
                                        </td>
                                        <td style="text-align: left">
                                            Lista y copia de los contratos celebrados con partes vinculadas.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           5.4
                                        </td>
                                        <td style="text-align: left">
                                            Favor confirmar si existen reclamaciones contractuales por parte de clientes (especialmente distribuidores) y, de ser así, proporcionar la información y documentación pertinente.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           6.1
                                        </td>
                                        <td style="text-align: left">
                                            Litigios, arbitrajes, reclamos, demandas, investigaciones o procesos administrativos existentes, pendientes de los cuales la Compañía o cualquiera de sus empleados es parte debido a hechos relacionados con el desempeño de sus funciones, junto con una indicación de la cantidad y probabilidad de éxito para el demandante.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.1
                                        </td>
                                        <td style="text-align: left">
                                            Descripción de la estructura de manejo de la privacidad y datos de la Compañía, y suministro de los documentos correspondientes.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.2
                                        </td>
                                        <td style="text-align: left">
                                            Copia de todas las autorizaciones, avisos y documentos relativos a uso y manejo de datos personales en la Compañía.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.3
                                        </td>
                                        <td style="text-align: left">
                                            Copia de todos los contratos de transferencia o transmisión de datos personales celebrados con terceros.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.4
                                        </td>
                                        <td style="text-align: left">
                                            Copia de la Política de Privacidad adoptada por la Compañía y los Manuales Internos relacionados con el manejo de datos personales.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.5
                                        </td>
                                        <td style="text-align: left">
                                            Confirmación de si la Compañía implementa mecanismos de videovigilancia en sus instalaciones. En caso afirmativo, favor compartir una copia de los avisos de videovigilancia.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.6
                                        </td>
                                        <td style="text-align: left">
                                            Confirmación de si la Compañía ha designado un oficial de protección de datos (DPO) que atienda quejas y/o reclamos de la operación en Colombia y copia del documento de nombramiento.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.7
                                        </td>
                                        <td style="text-align: left">
                                            Copia de los manuales y políticas internas en materia de seguridad de la información donde se establezcan las medidas técnicas, administrativas y humanas que han sido implementados por la compañía para salvaguardar la información, o copia de certificaciones de estándares internacionales de seguridad de la información (i.e. ISO 270001).
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.8
                                        </td>
                                        <td style="text-align: left">
                                            Confirmación que la Compañía no cuente con ningún proceso ante la delegatura de protección de datos personales de la Superintendencia de Industria y Comercio, y que no han sufrido incidentes de seguridad donde la disponibilidad, integridad y/o confidencialidad de la información personal se haya visto afectada en los últimos cinco (5) años.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left">
                                           7.9
                                        </td>
                                        <td style="text-align: left">
                                            Confirmación que la compañía tiene activos totales superiores a 100.000 UVT y, en caso afirmativo, que ya ha realizado el registro de la base de datos ante el Registro Nacional de Bases de Datos (RNBD) de la SIC.
                                            <div style="color: red" v-if="fail?.list">@{{fail?.list?.type}}</div>
                                        </td>
                                        <td style="text-align: left">
                                            <input type="radio" id="S18" v-model="form[18]" value="1">
                                            <label for="S18">Sí</label><br>
                                            <input type="radio" id="N18" v-model="form[128]" value="0">
                                            <label for="N18">No</label><br>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                        <div class="nftmax__item-button--group">
                            <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius nftmax-item__btn" data-bs-toggle="modal"  data-bs-target="#save">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nftmax-dsinner" v-if="same(view, 2)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">@{{pick?.code}}</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th style="text-align: left; width: 260px">Campo</th>
                                        <th style="text-align: left">Valor</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Documento
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.code ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Asunto
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.hint ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Mensaje
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.note ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Nombre
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.name ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; vertical-align: top">
                                            Correo
                                        </td>
                                        <td style="text-align: left">
                                            @{{pick?.mail ?? 'Ninguno'}}
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                    <div class="nftmax__item-button--group">
                        <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#exit">Cerrar</button>
                    </div>
                </div>
            </div>
            <!-- End Dashboard Inner -->

            <div class="nftmax-preview__modal modal" id="post" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Solicitar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close" v-if="wait">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/timer.png" alt="#"></div>
								<h2 class="nftmax-preview__close-title">Enviando información...</h2>
                            </div>
                            <div class="nftmax-preview__close" v-else>
                                <div style="width: 100%; overflow: hidden">
                                    <div style="margin: 0px 0px 16px 0px">Por favor ingrese los datos para realizar la solicitud.</div>
                                    <div style="color: red; margin: 8px 0px" v-if="fail?.text">@{{fail?.text}}</div>
                                    <div class="nftmax__item-form--group">
                                        <input class="nftmax__item-input" style="width: 100%" type="text" placeholder="Nombre" v-model="form.name" required>
                                        <div style="color: red" v-if="fail?.list?.name">@{{fail?.list?.name}}</div>
                                    </div>
                                    <div class="nftmax__item-form--group">
                                        <input class="nftmax__item-input" style="width: 100%" type="text" placeholder="Correo" v-model="form.mail" required>
                                        <div style="color: red" v-if="fail?.list?.mail">@{{fail?.list?.mail}}</div>
                                    </div>
                                    <div class="nftmax__item-form--group">
                                        <input class="nftmax__item-input" style="width: 100%" type="text" placeholder="Asunto" v-model="form.hint" required>
                                        <div style="color: red" v-if="fail?.list?.hint">@{{fail?.list?.hint}}</div>
                                    </div>
                                    <div class="nftmax__item-form--group">
                                        <textarea class="nftmax__item-input nftmax__item-textarea" style="width: 100%; resize: none" placeholder="Mensaje" v-model="form.note" required></textarea>
                                        <div style="color: red" v-if="fail?.list?.note">@{{fail?.list?.note}}</div>
                                    </div>
                                    <div class="nftmax__file-upload">
                                        <div class="upload-files">
                                            <div class="body" id="drop">
                                                <img class="nftmax__file-upload--img" src="/assets/img/upload.png" alt="">
                                                <p class="pointer-none nftmax__file-text">
                                                    <b>Arrastre el documento aquí.</b>
                                                </p>
                                                <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered mt-4 bg radius">
                                                    <label for="load">Buscar documento</label>
                                                    <input
                                                        id="load"
                                                        type="file"
                                                        @change="(form.file = $event.target.files.item(0))"
                                                        accept="application/pdf" />
                                                </button>
                                                <p class="pt-2" v-if="form.file">
                                                    <b>@{{form.file?.name}}</b>
                                                </p>
                                                <p class="pt-2 mx-4">Sólo se admiten documentos en formato PDF.</p>
                                                <div style="color: red" v-if="fail?.list?.file">@{{fail?.list?.file}}</div>
                                            </div>
                                            <div class="nftmax__file-updated">
                                                <div class="divider">
                                                    <span><a>FILES</a></span>
                                                </div>
                                                <div class="list-files"></div>
                                                <button class="importar">UPDATE FILES</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="post(form)" :disabled="wait">
                                        Solicitar
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Cancelar</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="quit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere salir del formulario?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius"  data-bs-dismiss="modal" @click="(view = 1)">
                                        Sí, salir
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="exit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere salir del reporte?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius"  data-bs-dismiss="modal" @click="(view = 1)">
                                        Sí, salir
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="save" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Desea guardar el diagnóstico?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="save(form, pick)" :disabled="wait">
                                        @{{(wait ? 'Guardando' : 'Sí, guardar')}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="drop" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Desea eliminar el reporte de antecedentes?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="drop(pick)" :disabled="wait">
                                        @{{(wait ? 'Eliminando...' : 'Sí, eliminar')}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		wait: false,
			  		menu: false,
                    quit: false,
			  		snap: null,
			  		pick: null,
			  		sync: null,
                    seek: [],
                    list: [],
					view: 1,
                    tone: {
                        5: '#E53935',
                        4: '#E65100',
                        3: '#FF8F00',
                        2: '#FDD835',
                        1: '#FFFFFF'
                    },
			  		data: {
                        size: 0,
                        page: 0,
                        take: 0,
                        list: []
                    },
					pile: {
						role: {
							wait: false,
							text: null,
							list: []
						}
					},
			  		show: {
			  			form: false,
			  			view: false,
			  			mail: false,
			  			crop: false,
			  			pass: false,
			  			wipe: false,
			  			lock: false,
			  			drop: false
			  		},
			  		fail: {
			  			text: null,
			  		    list: {}
			  		},
			  		sort: {
			  			text: null,
			  			type: null
			  		},
					bulk: {
						show: false,
						list: [],
						data: {
							note: null
						}
					},
			  		form: {}
			    },
			    watch: {
					'pile.role.text': (text) => {
						if (self.pile.role.time) {
							clearTimeout(self.pile.role.time);
						}

						
					},
			    	sort: {
			    		handler: function (sort) {
			    		   	if (self.time) {
				    			clearTimeout(self.time);
				    		}

				    		self.time = setTimeout(function () {
				    			self.load(self.take, null, sort.text, sort.type);
				    		}, 500);
					    },
					    deep: true
			    	}
			    },
			    methods: {
			    	load: function (take, page, text, done) {
			    		axios.get("{{route('signs', ['type' => 'documentos', 'task' => 'load'])}}?take=" + (take ?? '') + '&page=' + (page ?? '') +
			    			                                                                                                   '&find=' + (text ?? ''), {})
				             .then(function (data) {
				            self.data.list = data.data.data || [];

				            self.data.size = data.data.size || 0;

				            self.data.page = data.data.page || 0;

				            self.data.take = data.data.take || 0;

				            self.wait = false;

				            if (done) {
				            	done(false);
				            }
				        }).catch(function (fail) {
				            self.wait = false;

				            if (done) {
				            	done(true);
				            }
				        });

				        this.wait = true;
			    	},
			    	open: function (task, item, data) {
			    		self.note = {show: false,
	                                 type: null,
	                                 text: null};

			    		self.fail = {text: null,
			    			         list: {}};

			    		if (item) {
			    			switch (task) {
			    				case 'data':
							        self.pick = item;

                                    self.view = 2;
			    					break;
                                case 'drop':
                                    $('#drop').modal('show');

                                    self.pick = item;
                                    break;
			    			}
			    		} else {
			    			switch (task) {
                                case 'post':
                                    self.form = {
                                        name: '',
                                        mail: '',
                                        hint: '',
                                        note: '',
                                        file: ''
                                    };

                                    $('#post').modal('show');
                                    break;
			    			}
			    		}
			    	},
			    	save: function (form) {
                        var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};
                        
                        Object.entries(form).forEach((item) => {
                            data.append(`data[${item[0]}]`, item[1]);
                        })
                        $('#save').modal('hide');
                        axios.post("{{route('test')}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
                            self.wait = false;
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response?.data?.text) {
                                self.fail = {text: fail.response.data.text,
                                             list: fail.response.data.list ?? {}};
                            } else {
                                self.fail.text = 'Se presentó un error inesperado.';
                            }
                        });

				    	self.wait = true;
			    	},
                    drop: function (item) {
			    		axios.get(`{{route('signs', ['type' => 'documentos', 'task' => 'drop'])}}/${item.hash}`, {})
				             .then(function (data) {
                            $('#drop').modal('hide');
                            self.wait = false;

                            self.show.drop = false;

                            self.data.size = self.data.size - 1;

                            self.data.list.splice(self.data.list.indexOf(item), 1);
				        }).catch(function (fail) {
				            self.wait = false;

				            if (fail.response?.data?.text) {
                                self.fail = {text: fail.response.data.text,
                                             list: fail.response.data.list ?? {}};
                            } else {
                                self.fail.text = 'Se presentó un error inesperado.';
                            }
				        });

				        self.wait = true;
			    	}
			    },
			    mounted: function () {
			    	setTimeout(function () {
                        self.load(0, 0, null, (fail) => {
                            self.done = true;
                        });
	             	}, 300);
			    }
			})
  		});
  	</script>
@stop