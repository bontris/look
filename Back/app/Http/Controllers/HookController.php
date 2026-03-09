<?php

namespace App\Http\Controllers;

use Auth;

use Validator;

use Illuminate\Http\Request;

use Orhanerday\OpenAi\OpenAi;

use Illuminate\Support\Facades\DB;

class HookController extends Controller
{
    public function main (Request $request, $firm, $type) {
        switch (strtolower($firm)) {
            case 'bontris':
                switch (strtolower($type)) {
                    case 'chat':
                        $open = new OpenAi(getenv('API_OPENAI'));

                        //$text = 'Hola {{Nombre completo}}, tienes {{Edad}}, eres de {{Origen}} y eres una persona {{¿Qué tipo de zonas prefieres?}}.';

                        $data = json_decode($request->getContent(), true);

                        /*$data = array_reduce(json_decode($request->getContent(), true), function ($hash, $item) {
                            if (isset($item['label'])) {
                                if (isset($item['value'])) {
                                    $hash[sprintf('{{%s}}', $item['label'])] = $item['value'];
                                }
                            }

                            return $hash;
                        }, []);*/

                        //print_r(strtr($text, $data));

                        //exit();
                
                        $chat = $open->chat([
                            'model' => 'gpt-4o',
                            'messages' => [
                                [
                                    'role' => 'system',
                                    'content' => $data['text']
                                ],
                                [
                                    'role' => 'user',
                                    'content' => sprintf("```json\n%s\n```", json_encode($data['form']))
                                ]
                            ],
                            'temperature' => 1.0,
                            'max_tokens' => 64000,
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
                        break;
                }
                break;
        }

        abort(503);
    }
}