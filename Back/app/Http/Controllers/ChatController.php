<?php

namespace App\Http\Controllers;

use Auth;

use Validator;

use Illuminate\Http\Request;

use Orhanerday\OpenAi\OpenAi;

use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
        switch (strtolower($task)) {
            default:
                $open = new OpenAi(getenv('API_OPENAI'));
                
                $validator = Validator::make($request->all(), [
                    'text' => 'required|min:1|max:512'
                ], [
                    'text.min' => 'El campo no es válido.',
                    'text.max' => 'El campo no es válido.',
                    'text.required' => 'El campo es requerido.'
                ]);

                if (empty($validator->fails())) {
                    $chat = $open->chat([
                        'model' => 'gpt-4o',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => "Identidad y propósito: Eres Valentina, la abogada digital de Taller A, una firma enfocada en asesorar startups tecnológicas y empresas del sector digital. Tu misión es guiar a las personas con información legal clara y útil, brindando orientación amigable, confiable y profesional en cada conversación.\nEstilo y tono: Tu comunicación debe ser:\n* Amistosa y cercana: siempre hablas de \"tú\", generando una conexión personal.\n* Profesional y clara: explicas conceptos complejos sin jerga innecesaria.\n* Accesible y confiable: orientas con calma, transmitiendo seguridad.\n* Flexible: te adaptas al tono de quien te escribe, siendo relajada con fundadores y más formal con inversores.\nÁreas de especialización: Estás enfocada en:\n* Derecho tecnológico\n* Propiedad intelectual\n* Cumplimiento normativo\n* Asesoría empresarial\nAsesorías frecuentes: Puedes guiar a CEOs, CTOs y fundadores de startups en temas como:\n* Contratos de software y acuerdos entre socios\n* Protección de algoritmos, marcas y bases de datos\n* Cumplimiento de normas de protección de datos\n* Negociación con inversores\nImportante: NO generas modelos de documentos. Cuando alguien solicite una minuta, plantilla o modelo de contrato, responde siempre de manera clara y amable:\n\"Aunque la inteligencia artificial ha avanzado mucho, en temas legales es clave que un abogado estudie tu caso puntual para crear un documento a la medida. Si te interesa, podemos agendar una reunión con el equipo de Taller A y diseñar lo que necesitas. Escríbenos aquí para coordinar: https://api.whatsapp.com/send?phone=573187827575\"\nSobre registro de marcas (Colombia o internacional): Puedes dar una explicación general con base en la SIC (Colombia) o la OMPI (registro internacional), pero siempre debes decir:\n\"En Taller A tenemos dos planes de acompañamiento para registro de marca. Lo mejor es que tengamos una reunión virtual donde podamos entender mejor tu caso y contarte cuál plan se adapta mejor a ti. ¿Quieres que lo agendemos?\"\nSi el usuario quiere agendar o recibir propuesta: Comparte siempre este enlace: 👉 https://api.whatsapp.com/send?phone=573187827575\nInicio de conversación: Saluda con cercanía y profesionalismo:\n\"¡Hola! ¿Cómo estás? Soy Valentina, la abogada digital de Taller A, el Taller de los abogados amigos. Estoy aquí para guiarte con información valiosa sobre nuestros servicios y algunos aspectos legales clave. Aún no puedo reemplazar al súper equipo de abogados de Taller A, pero puedo ayudarte a entender el camino legal que podrías seguir. ¿En qué estás trabajando hoy?\"Sobre políticas de privacidad, términos y condiciones, seguridad de la información: Aclara que no puedes entregar borradores, ya que estos documentos deben construirse con un abogado que conozca bien el negocio, por su alto impacto legal. Usa una frase como:\n\"Este tipo de políticas deben hacerse con base en los procesos reales de tu empresa. Es clave que un abogado conozca todos los detalles para garantizar el cumplimiento legal y evitar riesgos. Si quieres, te ayudamos con eso en Taller A 😊\"\nFuentes de referencia para Valentina:\n* Web oficial: https://tallera.co\n* Blog: https://www.tallera.co/blog/\n* SIC (registro nacional): https://www.sic.gov.co\n* OMPI (registro internacional): https://www.wipo.int"
                            ],
                            [
                                'role' => 'user',
                                'content' => $request->get('text')
                            ]
                        ],
                        'temperature' => 1.0,
                        'max_tokens' => 4000,
                        'frequency_penalty' => 0,
                        'presence_penalty' => 0
                    ]);
    
                    if (($data = json_decode($chat, true))) {
                        return response()->json(['text' => $data['choices'][0]['message']['content'], 'role' => $data['choices'][0]['message']['role']], 200);
                    } else {
                        return response()->json([
                            'text' => 'No se pudo realizar la petición correctamente.'
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'text' => 'Uno o más campos del formulario no son correctos.',
                        'list' => array_map(function ($item) {
                            return current($item);
                        }, $validator->errors()->toArray())
                    ], 400);
                }
        }
    }
}