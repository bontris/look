<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class LeadController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('leads')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'type' => 'required|in:4,5',
                            'bind' => 'required|numeric',
                            'link' => 'nullable|numeric',
                            'code' => 'nullable|max:16',
                            'card' => 'required|max:16',
                            'last' => 'required|max:32',
                            'name' => 'required|max:32',
                            'note' => 'nullable|max:512',
                            'work' => 'nullable|max:16',
                            'mail' => 'required|email|max:64',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'pass' => 'nullable|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[0-9a-zA-Z@$!%*?&]{6,12}$/',
                        ], [
                            'lock.in' => 'La opción no es válida.',
                            'type.in' => 'La opción no es válida.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.max' => 'El campo no es válido.',
                            'card.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'last.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'pass.regex' => 'La contraseña debe tener minimo 6 y maximo 12 caracteres, al menos una letra mayúscula, una letra minuzcula, un numero y un caracter especial.',
                            'mail.email' => 'El campo no es válido.',
                            'bind.numeric' => 'El campo no es válido.',
                            'link.numeric' => 'El campo no es válido.',
                            'bind.required' => 'El campo es requerido.',
                            'mail.required' => 'El campo es requerido.',
                            'last.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                        ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('leads')
                                                  ->where('hide', 0)
                                                  ->where('card', trim($request->get('card')))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('leads')
                                                      ->where('hide', 0)
                                                      ->where('work', trim($request->get('work')))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($same = DB::table('leads')
                                                          ->where('hide', 0)
                                                          ->where('mail', trim($request->get('mail')))
                                                          ->first())) || ($item->row == $same->row))) {
                                        if (($bind = DB::table('firms')
                                                       ->where('hide', 0)
                                                       ->where('row', intval($request->get('bind')))
                                                       ->first())) {
                                            if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                if (($link = intval((function ($pick, $name, $last, $mail, $work, $note, &$fail) use ($bind) {
                                                    $client = new Client();

                                                    if (intval($bind->link)) {
                                                        if (boolval($pick)) {
                                                            $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/su1llgi310o5vp59/crm.contact.get.json', [
                                                                RequestOptions::QUERY => [
                                                                    'id' => $pick
                                                                ]
                                                            ]);
                                        
                                                            if (($response->getStatusCode() == 200)) {
                                                                if (($data = json_decode($response->getBody(), true))) {
                                                                    if (($data['result']['COMPANY_ID'] == $bind->link)) {
                                                                        return $data['result']['ID'];
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/md0968j4v4x3cy5y/crm.contact.add.json', [
                                                                'form_params' => [
                                                                    'fields' => [
                                                                        'NAME' => $name,
                                                                        'EMAIL' => [['VALUE' => $mail, 'VALUE_TYPE' => 'WORK']],
                                                                        'PHONE' => [['VALUE' => $work, 'VALUE_TYPE' => 'WORK']],
                                                                        'TYPE_ID' => 'CLIENT',
                                                                        'LAST_NAME' => $last,
                                                                        'COMMENTS' => $note
                                                                    ]
                                                                ]
                                                            ]);
        
                                                            if (($response->getStatusCode() == 200)) {
                                                                if (($data = json_decode($response->getBody(), true))) {
                                                                    $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/ffork65fduhppg1p/crm.contact.company.add.json', [
                                                                        'form_params' => [
                                                                            'id' => $data['result'],
                                                                            'fields' => [
                                                                                'COMPANY_ID' => $bind->link
                                                                            ]
                                                                        ]
                                                                    ]);
    
                                                                    if (($response->getStatusCode() == 200)) {
                                                                        return $data['result'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                })(intval($request->get('link')), trim($request->get('name')), trim($request->get('last')), trim($request->get('mail')), trim($request->get('work')), trim($request->get('note')), $fail)))) {
                                                    if (DB::table('leads')->where('row', $item->row)->update([
                                                        'link' => $link,
                                                        'mark' => date('Y-m-d H:i:s'),
                                                        'icon' => $icon ?? $item->icon,
                                                        'card' => trim($request->get('card', $item->card)),
                                                        'work' => trim($request->get('work', $item->work)),
                                                        'mail' => trim($request->get('mail', $item->mail)),
                                                        'last' => trim($request->get('last', $item->last)),
                                                        'name' => trim($request->get('name', $item->name)),
                                                        'note' => trim($request->get('note', $item->note)),
                                                        'bind' => intval($request->get('bind', $item->bind)),
                                                        'lock' => intval($request->get('lock', $item->lock)),
                                                        'type' => intval($request->get('type', $item->type))
                                                    ])) {
                                                        if (($type = intval($request->get('type')))) {
                                                            if (empty(DB::table('users')->where('link', $item->row)->update([
                                                                'type' => $type,
                                                                'modification' => date('Y-m-d H:i:s')
                                                            ]))) {
                                                                return response()->json([
                                                                    'text' => 'No se pudo actualizar el tipo.'
                                                                ], 500);
                                                            }
                                                        }

                                                        if (($pass = trim($request->get('pass')))) {
                                                            if (empty(DB::table('users')->where('link', $item->row)->update([
                                                                'pass' => Hash::make($pass),
                                                                'modification' => date('Y-m-d H:i:s')
                                                            ]))) {
                                                                return response()->json([
                                                                    'text' => 'No se pudo actualizar la contraseña.'
                                                                ], 500);
                                                            }
                                                        }

                                                        if ((empty(empty($icon)) && empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->icon))))) {
                                                            Log::error(sprintf('Unable to delete the file: %s', $item->icon));
                                                        } 
    
                                                        return response()->json([
                                                            'icon' => $icon,
                                                            'bind' => $bind,
                                                            'text' => 'El registro fue actualizado con éxito.'
                                                        ], 200);
                                                    } else {
                                                        return response()->json([
                                                            'text' => 'El registro no pudo ser actualizado.'
                                                        ], 500);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                                        'list' => ['link' => 'El contácto no pudo ser vinculado.']
                                                    ], 500);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'La imágen no pudo ser cargada con éxito.'
                                                ], 500);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['firm' => 'La compañía no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['mail' => 'El correo electrónico ya existe.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['work' => 'El número de teléfono ya existe.']
                                    ], 400);
                                }
				            } else {
				            	return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['card' => 'El número documento ya existe.']
                                ], 400);
				            }
			            } else {
			            	return response()->json([
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
                    case 'icon':
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                        ], [
                            'file.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'file.mimetypes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('leads')
                                          ->where('row', $item->row)
                                          ->update(['icon' => $file, 'mark' => date('Y-m-d H:i:s')])) {
                                        if ((empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->face))))) {
                                            Log::error(sprintf('Unable to delete the file: %s', $item->icon));
                                        }

                                        return response()->json([
                                            'file' => $file,
                                            'text' => 'La imágen fue actualizada con éxito.'
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'text' => 'La imágen no pudo ser actualizada.'
                                        ], 500);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'La imágen no pudo ser cargada.'
                                    ], 500);
                                }
                            } else {
                                if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('leads')
                                                                                                                                     ->where('row', $item->row)
                                                                                                                                     ->update(['icon' => null, 'mark' => date('Y-m-d H:i:s')]))) {
                                    return response()->json([
                                        'text' => 'La imágen fue eliminada con éxito.'
                                    ], 200);
                                } else {
                                    return response()->json([
                                        'text' => 'La imágen no pudo ser eliminada.'
                                    ], 500);
                                }
                            }
                        } else {
                            return response()->json([
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
                        }
			        case 'lock':
			        	if (DB::table('leads')
			                  ->where('row', $item->row)
			                  ->update(['lock' => boolval($request->get('lock')), 'mark' => date('Y-m-d H:i:s')])) {
			              	return response()->json([
                                'text' => 'El registro fue actualizado con éxito.'
                            ], 200);
			            } else {
                            return response()->json([
								'text' => 'El registro no pudo ser actualizado.'
							], 500);
			            }
    				case 'drop':
    					if (DB::table('leads')
			                  ->where('row', $item->row)
			                  ->update(['hide' => 1, 'wipe' => date('Y-m-d H:i:s')])) {
                            if (DB::table('users')
                                  ->where('type', 4)
                                  ->where('link', $item->row)
                                  ->update(['hide' => 1, 'deletion' => date('Y-m-d H:i:s')])) {
                                return response()->json([
                                    'text' => 'El registro fue eliminado con éxito.'
                                ], 200);
                            } else {
                                return response()->json([
                                  'text' => 'El registro no pudo ser eliminado.'
                                ], 500);
                            }
			            } else {
			              	return response()->json([
                                'text' => 'El registro no pudo ser eliminado.'
                            ], 500);
			            }
    				case 'load':
    					return response()->json($item, 200);
    			}
    		} else {
    			return response()->json([
                    'text' => 'El registro no fue encontrado.'
                ], 404);
    		}
    	} else {
    		switch (strtolower($task)) {
    			case 'make':
    				$validator = Validator::make($request->all(), [
                        'lock' => 'nullable|in:0,1',
                        'test' => 'nullable|in:0,1',
                        'type' => 'required|in:4,5',
                        'bind' => 'required|numeric',
                        'link' => 'nullable|numeric',
                        'code' => 'nullable|max:16',
                        'card' => 'required|max:16',
                        'last' => 'required|max:32',
                        'name' => 'required|max:32',
                        'note' => 'nullable|max:512',
                        'work' => 'nullable|max:16',
                        'mail' => 'required|email|max:64',
                        'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                    ], [
                        'lock.in' => 'La opción no es válida.',
                        'type.in' => 'La opción no es válida.',
                        'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'code.max' => 'El campo no es válido.',
                        'card.max' => 'El campo no es válido.',
                        'work.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'last.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'bind.numeric' => 'El campo no es válido.',
                        'link.numeric' => 'El campo no es válido.',
                        'bind.required' => 'El campo es requerido.',
                        'mail.required' => 'El campo es requerido.',
                        'last.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('leads')
                                    ->where('hide', 0)
                                    ->where('code', ($code = hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('leads')
                                        ->where('hide', 0)
                                        ->where('card', trim($request->get('card')))
                                        ->first())) {
                                if (empty(DB::table('leads')
                                            ->where('hide', 0)
                                            ->where('work', trim($request->get('work')))
                                            ->first())) {
                                    if (empty(DB::table('leads')
                                                ->where('hide', 0)
                                                ->where('mail', trim($request->get('mail')))
                                                ->first())) {
                                        if (empty(DB::table('users')
                                                    ->where('hide', 0)
                                                    ->where('work', trim($request->get('work')))
                                                    ->first())) {
                                            if (empty(DB::table('users')
                                                        ->where('hide', 0)
                                                        ->where('mail', trim($request->get('mail')))
                                                        ->first())) {
                                                if (($bind = DB::table('firms')
                                                               ->where('hide', 0)
                                                               ->where('row', intval($request->get('bind')))
                                                               ->first())) {
                                                    if ((empty(($icon = $request->file('icon'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                        if (($link = intval((function ($pick, $name, $last, $mail, $work, $note, &$fail) use ($bind) {
                                                            $client = new Client();

                                                            if (intval($bind->link)) {
                                                                if (boolval($pick)) {
                                                                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/su1llgi310o5vp59/crm.contact.get.json', [
                                                                        RequestOptions::QUERY => [
                                                                            'id' => $pick
                                                                        ]
                                                                    ]);
                                                
                                                                    if (($response->getStatusCode() == 200)) {
                                                                        if (($data = json_decode($response->getBody(), true))) {
                                                                            if (($data['result']['COMPANY_ID'] == $bind->link)) {
                                                                                return $data['result']['ID'];
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/md0968j4v4x3cy5y/crm.contact.add.json', [
                                                                        'form_params' => [
                                                                            'fields' => [
                                                                                'NAME' => $name,
                                                                                'EMAIL' => [['VALUE' => $mail, 'VALUE_TYPE' => 'WORK']],
                                                                                'PHONE' => [['VALUE' => $work, 'VALUE_TYPE' => 'WORK']],
                                                                                'TYPE_ID' => 'CLIENT',
                                                                                'LAST_NAME' => $last,
                                                                                'COMMENTS' => $note
                                                                            ]
                                                                        ]
                                                                    ]);
                                                                    
                                                                    if (($response->getStatusCode() == 200)) {
                                                                        if (($data = json_decode($response->getBody(), true))) {
                                                                            $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/ffork65fduhppg1p/crm.contact.company.add.json', [
                                                                                'form_params' => [
                                                                                    'id' => $data['result'],
                                                                                    'fields' => [
                                                                                        'COMPANY_ID' => $bind->link
                                                                                    ]
                                                                                ]
                                                                            ]);
                                                                            
                                                                            if (($response->getStatusCode() == 200)) {
                                                                                return $data['result'];
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        })(intval($request->get('link')), trim($request->get('name')), trim($request->get('last')), trim($request->get('mail')), trim($request->get('work')), trim($request->get('note')), $fail)))) {
                                                            if (($item = DB::table('leads')->insertGetId([
                                                                'code' => $code,
                                                                'icon' => $icon,
                                                                'link' => $link,
                                                                'bind' => $bind->row,
                                                                'made' => date('Y-m-d H:i:s'),
                                                                'card' => trim($request->get('card')),
                                                                'work' => trim($request->get('work')),
                                                                'mail' => trim($request->get('mail')),
                                                                'last' => trim($request->get('last')),
                                                                'name' => trim($request->get('name')),
                                                                'note' => trim($request->get('note')),
                                                                'lock' => intval($request->get('lock')),
                                                                'type' => intval($request->get('type')),
                                                                'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                                            ]))) {
                                                                try {
                                                                    DB::beginTransaction();

                                                                    if (($skip = DB::table('users')->insertGetId([
                                                                        'test' => 0,
                                                                        'lock' => 0,
                                                                        'type' => 4,
                                                                        'pass' => Hash::make(($pass = Str::random(8))),
                                                                        'face' => $icon,
                                                                        'tone' => $tone,
                                                                        'hash' => $hash,
                                                                        'code' => $code,
                                                                        'link' => $item,
                                                                        'bind' => $bind->row,
                                                                        'last' => trim($request->get('last')),
                                                                        'name' => trim($request->get('name')),
                                                                        'mail' => trim($request->get('mail')),
                                                                        'work' => trim($request->get('work')),
                                                                        'note' => trim($request->get('note')),
                                                                        'creation' => date('Y-m-d H:i:s')
                                                                    ]))) {
                                                                        if (DB::table('codes')->insert([
                                                                            'type' => 1,
                                                                            'item' => $skip,
                                                                            'pass' =>  $pass,
                                                                            'date' => date('Y-m-d H:i:s'),
                                                                            'hash' => ($seek = md5(uniqid(rand(), true)))
                                                                        ])) {
                                                                            DB::commit();

                                                                            Mail::send('mail.sign', ['mail' => trim($request->get('mail')), 'name' => trim($request->get('name')), 'last' => trim($request->get('last')), 'seek' => $seek, 'pass' => $pass], function ($message) use ($request) {
                                                                                $message->to(trim($request->get('mail')), sprintf('%s %s', trim($request->get('name')), trim($request->get('last'))))
                                                                                        ->cc(env('APP_MAIL'), env('APP_NAME'))
                                                                                        ->from(env('APP_MAIL'), env('APP_NAME'))
                                                                                        ->subject(sprintf('Bienvenido a %s', env('APP_NAME')));
                                                                            });

                                                                            return response()->json([
                                                                                'item' => $item,
                                                                                'bind' => $bind,
                                                                                'link' => $link,
                                                                                'code' => $code,
                                                                                'hash' => $hash,
                                                                                'icon' => $icon,
                                                                                'tone' => $tone,
                                                                                'text' => 'El registro fue guardado con éxito.'
                                                                            ], 200);
                                                                        } else {
                                                                            return response()->json([
                                                                                'text' => 'El registro no pudo ser guardado correctamente 3.'
                                                                            ], 500);
                                                                        }
                                                                    }
                                                                } catch (\Exception $exception) {
                                                                    DB::rollBack();
                                
                                                                    Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));
                                
                                                                    return response()->json([
                                                                        'text' => 'El registro no pudo ser creado.'
                                                                    ], 500);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'text' => 'El registro no pudo ser guardado.'
                                                                ], 500);
                                                            }
                                                        } else {
                                                            return response()->json([
                                                                'text' => $fail ?? 'El registro no pudo ser guardado.'
                                                            ], 500);
                                                        }
                                                    } else {
                                                        return response()->json([
                                                            'text' => 'La imágen no pudo ser cargada con éxito.'
                                                        ], 500);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                                        'list' => ['bind' => 'La compañía no es válida.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                                    'list' => ['mail' => 'El correo ya existe.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['work' => 'El teléfono ya existe.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['mail' => 'El correo ya existe.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['work' => 'El teléfono ya existe.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['card' => 'El número documento ya existe.']
                                ], 400);
                            }
		            	} else {
                            return response()->json([
                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                'list' => ['code' => 'El código ya existe.']
                            ], 400);
		            	}
		            } else {
		            	return response()->json([
                            'text' => 'Uno o mas campos del formulario no son correctos.',
                            'list' => array_map(function ($item) {
                                return current($item);
                            }, $validator->errors()->toArray())
                        ], 400);
		            }
    			case 'load':
    				$query = DB::table('leads')
                                ->where('leads.hide', 0)
                                ->join('firms', function ($join) {
                                    $join->on('leads.bind', 'firms.row');
                                });
                    
                    if (($find = trim($request->get('find')))) {
                        foreach (array_slice(explode(',', $find), 0, 20) as $part) {
                            if (($part = trim($part))) {
                                if (preg_match('%^(?P<name>\w+):(\s+)?(\s+)?(?P<data>.+)$%', $part, $part)) {
                                    if (strlen(trim($part['data']))) {
                                        $list[strtolower(trim($part['name']))] = trim($part['data']);
                                    }
                                }
                            } 
                        }

                        if (isset($list)) {
                            foreach ($list as $name => $data) {
                                switch ($name) {
                                    case 'date':
                                        switch (($data = strtoupper($data))) {
                                            case 'YR':
                                                $query->where(DB::raw('YEAR(leads.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(leads.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(leads.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(leads.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(leads.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(leads.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(leads.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(leads.made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'lock':
                                        $query->where(function ($query) use ($data) {
                                            foreach (explode(' ', trim($data)) as $item => $data) {
                                                if (empty($item)) {
                                                    $query->where('leads.lock', intval($data));
                                                } else {
                                                    $query->orWhere('leads.lock', intval($data));
                                                }
                                            }
                                        });
                                        break;
                                }
                            }
                        } else {
                            $query->where(function ($query) use ($find) {
                                $query->where('leads.code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('leads.card', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('leads.last', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('leads.name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('leads.*', 'firms.name AS firm', DB::raw(sprintf("CONVERT_TZ(leads.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('leads.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'link' => intval($item->link),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'name' => $item->name,
                            'last' => $item->last,
                            'icon' => $item->icon,
                            'tone' => $item->tone,
                            'firm' => $item->firm
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('firms')
		                       ->where('hide', 0);

			        return response()->json(['size' => ($high = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
			                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $high))
			                                                              ->orderBy('made', 'asc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'plan' => intval($item->plan),
                            'link' => intval($item->link),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'page' => $item->page,
                            'name' => $item->name,
                            'icon' => $item->icon,
                            'tone' => $item->tone
                        ]);

			            return $list;
			        }, [])]);
    		}
    	}
    }
}