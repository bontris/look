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

class HandController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('hands')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'test' => 'nullable|in:0,1',
                            'type' => 'sometimes|required|in:1,2,3',
                            'code' => 'nullable|max:16',
                            'card' => 'nullable|max:16',
                            'name' => 'sometimes|required|max:64',
                            'base' => 'sometimes|required|numeric',
                            'note' => 'nullable|max:256',
                            'work' => 'nullable|max:16',
                            'book' => 'nullable|url:https|max:128',
                            'mail' => 'sometimes|required|email|max:64',
                            'page' => 'nullable|max:128',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'pass' => 'nullable|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[0-9a-zA-Z@$!%*?&]{6,12}$/',
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.max' => 'El campo no es válido.',
                            'card.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'page.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'book.max' => 'El campo no es válido.',
                            'book.url' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.',
                            'pass.regex' => 'La contraseña debe tener minimo 6 y maximo 12 caracteres, al menos una letra mayúscula, una letra minuzcula, un numero y un caracter especial.',
                            'type.required' => 'El campo es requerido.',
                            'card.required' => 'El campo es requerido.',
                            'mail.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                        ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('hands')
                                                  ->where('hide', 0)
                                                  ->where('card', trim($request->get('card', $item->card)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('hands')
                                                        ->where('hide', 0)
                                                        ->where('work', trim($request->get('work', $item->work)))
                                                        ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($same = DB::table('hands')
                                                            ->where('hide', 0)
                                                            ->where('mail', trim($request->get('mail', $item->mail)))
                                                            ->first())) || ($item->row == $same->row))) {
                                        if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                            if (DB::table('hands')->where('row', $item->row)->update([
                                                'mark' => date('Y-m-d H:i:s'),
                                                'icon' => $icon ?? $item->icon,
                                                'card' => trim($request->get('card', $item->card)),
                                                'work' => trim($request->get('work', $item->work)),
                                                'mail' => trim($request->get('mail', $item->mail)),
                                                'book' => trim($request->get('book', $item->book)),
                                                'last' => trim($request->get('last', $item->last)),
                                                'name' => trim($request->get('name', $item->name)),
                                                'note' => trim($request->get('note', $item->note)),
                                                'link' => intval($request->get('link', $item->link)),
                                                'lock' => intval($request->get('lock', $item->lock)),
                                                'type' => intval($request->get('type', $item->type))
                                            ])) {
                                                if ((($type = intval($request->get('type'))))) {
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
                                                    'text' => 'El registro fue actualizado con éxito.'
                                                ], 200);
                                            } else {
                                                return response()->json([
                                                    'text' => 'El registro no pudo ser actualizado.'
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
                                    'list' => ['card' => 'El documento ya existe.']
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
                                    if (DB::table('hands')
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
                                if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('firms')
                                                                                                                                     ->where('row', $item->row)
                                                                                                                                     ->update(['icon' => null]))) {
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
			        	if (DB::table('hands')
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
    					if (DB::table('hands')
			                  ->where('row', $item->row)
			                  ->update(['hide' => 1, 'wipe' => date('Y-m-d H:i:s')])) {
                            if (DB::table('users')
                                  ->where('link', $item->row)
                                  ->where(function ($query) {
                                    $query->where('type', 1)
                                          ->orWhere('type', 2)
                                          ->orWhere('type', 3);
                                  })->update(['hide' => 1, 'deletion' => date('Y-m-d H:i:s')])) {
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
                        'type' => 'nullable|in:1,2,3',
                        'link' => 'nullable|integer',
                        'code' => 'nullable|max:16',
                        'card' => 'required|max:16',
                        'last' => 'required|max:32',
                        'name' => 'required|max:32',
                        'note' => 'nullable|max:512',
                        'work' => 'nullable|max:16',
                        'mail' => 'required|email|max:64',
                        'book' => 'nullable|url:https|max:128',
                        'nick' => 'nullable|regex:/^\w+(.\w+)*$/i|max:32',
                        'pass' => 'nullable|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[0-9a-zA-Z@$!%*?&]{6,12}$/',
                        'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'code.max' => 'El campo no es válido.',
                        'card.max' => 'El campo no es válido.',
                        'work.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'nick.max' => 'El campo no es válido.',
                        'last.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'book.max' => 'El campo no es válido.',
                        'book.url' => 'El campo no es válido.',
                        'nick.regex' => 'El campo no es válido.',
                        'pass.regex' => 'La contraseña debe tener minimo 6 y maximo 12 caracteres, al menos una letra mayúscula, una letra minuzcula, un numero y un caracter especial.',
                        'mail.email' => 'El campo no es válido.',
                        'link.integer' => 'El campo no es válido.',
                        'card.required' => 'El campo es requerido.',
                        'mail.required' => 'El campo es requerido.',
                        'last.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('hands')
                                    ->where('hide', 0)
                                    ->where('code', ($code = hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('hands')
                                        ->where('hide', 0)
                                        ->where('card', trim($request->get('card')))
                                        ->first())) {
                                if (empty(DB::table('hands')
                                            ->where('hide', 0)
                                            ->where('work', trim($request->get('work')))
                                            ->first())) {
                                    if (empty(DB::table('hands')
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
                                                if ((empty(trim($request->get('nick'))) || empty(DB::table('users')
                                                                                                   ->where('hide', 0)
                                                                                                   ->where('nick', trim($request->get('nick')))
                                                                                                   ->first()))) {
                                                    if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                        if (($link = intval((function ($pick, $name, $last, $mail, $work, $note, &$fail) {
                                                            $client = new Client();
            
                                                            if (boolval($pick)) {
                                                                $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/mq4gp6t9m57evrv3/user.get.json', [
                                                                    RequestOptions::QUERY => [
                                                                        'id' => $pick
                                                                    ]
                                                                ]);
                                            
                                                                if (($response->getStatusCode() == 200)) {
                                                                    if (($data = json_decode($response->getBody(), true))) {
                                                                        return isset($data['result'][0]['ID']) ? intval($data['result'][0]['ID']) : 0;
                                                                    }
                                                                }
                                                            } else {
                                                                $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/ghhifu76h9zgjjsq/crm.company.add.json', [
                                                                    'form_params' => [
                                                                        'fields' => [
                                                                            'TITLE' => sprintf('%s %s', $name, $last),
                                                                            'EMAIL' => [['VALUE' => $mail, 'VALUE_TYPE' => 'WORK']],
                                                                            'PHONE' => [['VALUE' => $work, 'VALUE_TYPE' => 'WORK']],
                                                                            'COMMENTS' => $note,
                                                                            'COMPANY_TYPE' => 'CUSTOMER'
                                                                        ]
                                                                    ]
                                                                ]);
            
                                                                if (($response->getStatusCode() == 200)) {
                                                                    if (($data = json_decode($response->getBody(), true))) {
                                                                        return isset($data['result']) ? intval($data['result']) : 0;
                                                                    }
                                                                }
                                                            }
                                                        })(intval($request->get('link')), trim($request->get('name')), trim($request->get('last')), trim($request->get('mail')), trim($request->get('work')), trim($request->get('note')), $fail)))) {
                                                            try {
                                                                DB::beginTransaction();

                                                                if (($item = DB::table('hands')->insertGetId([
                                                                    'code' => $code,
                                                                    'icon' => $icon,
                                                                    'link' => $link,
                                                                    'made' => date('Y-m-d H:i:s'),
                                                                    'card' => trim($request->get('card')),
                                                                    'work' => trim($request->get('work')),
                                                                    'mail' => trim($request->get('mail')),
                                                                    'book' => trim($request->get('book')),
                                                                    'last' => trim($request->get('last')),
                                                                    'name' => trim($request->get('name')),
                                                                    'note' => trim($request->get('note')),
                                                                    'lock' => intval($request->get('lock')),
                                                                    'type' => intval($request->get('type')),
                                                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                    'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                                                ]))) {
                                                                    if (($skip = DB::table('users')->insertGetId([
                                                                        'test' => 0,
                                                                        'lock' => 0,
                                                                        'pass' => null,
                                                                        'face' => $icon,
                                                                        'tone' => $tone,
                                                                        'hash' => $hash,
                                                                        'code' => $code,
                                                                        'link' => $item,
                                                                        'bind' => Auth::user()->firm->row,
                                                                        'nick' => trim($request->get('nick')),
                                                                        'last' => trim($request->get('last')),
                                                                        'name' => trim($request->get('name')),
                                                                        'mail' => trim($request->get('mail')),
                                                                        'work' => trim($request->get('work')),
                                                                        'note' => trim($request->get('note')),
                                                                        'type' => intval($request->get('type')),
                                                                        'pass' => ($pass = trim($request->get('pass'))) ? Hash::make($pass) : null,
                                                                        'creation' => date('Y-m-d H:i:s')
                                                                    ]))) {
                                                                        if ((empty(empty(($pass))) || DB::table('codes')->insert([
                                                                            'type' => 1,
                                                                            'item' => $skip,
                                                                            'date' => date('Y-m-d H:i:s'),
                                                                            'hash' => ($seek = md5(uniqid(rand(), true))),
                                                                            'pass' =>  Hash::make(($lock = Str::random(6)))
                                                                        ]))) {
                                                                            if (empty($pass)) {
                                                                                Mail::send('mail.sign', ['mail' => trim($request->get('mail')), 'name' => trim($request->get('name')), 'last' => trim($request->get('last')), 'seek' => $seek, 'lock' => $lock], function ($message) use ($request) {
                                                                                    $message->to(trim($request->get('mail')), sprintf('%s %s', trim($request->get('name')), trim($request->get('last'))))
                                                                                            ->cc(env('APP_MAIL'), env('APP_NAME'))
                                                                                            ->from(env('APP_MAIL'), env('APP_NAME'))
                                                                                            ->subject('Activa tu cuenta');
                                                                                });
                                                                            }

                                                                            DB::commit();
                
                                                                            return response()->json([
                                                                                'item' => $item,
                                                                                'link' => $link,
                                                                                'code' => $code,
                                                                                'hash' => $hash,
                                                                                'icon' => $icon,
                                                                                'tone' => $tone,
                                                                                'text' => 'El registro fue guardado con éxito.'
                                                                            ], 200);
                                                                        } else {
                                                                            return response()->json([
                                                                                'text' => 'El registro no pudo ser guardado correctamente.'
                                                                            ], 500);
                                                                        }
                                                                    } else {
                                                                        return response()->json([
                                                                            'text' => 'El registro no pudo ser guardado.'
                                                                        ], 500);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'text' => 'El registro no pudo ser guardado.'
                                                                    ], 500);
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
                                                        'list' => ['nick' => 'El usuario ya existe.']
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
                                    'list' => ['card' => 'El documento ya existe.']
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
    				$query = DB::table('hands')
                               ->where('hide', 0);
                    
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
                                                $query->where(DB::raw('YEAR(made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'lock':
                                        $query->where(function ($query) use ($data) {
                                            foreach (explode(' ', trim($data)) as $item => $data) {
                                                if (empty($item)) {
                                                    $query->where('lock', intval($data));
                                                } else {
                                                    $query->orWhere('lock', intval($data));
                                                }
                                            }
                                        });
                                        break;
                                }
                            }
                        } else {
                            $query->where(function ($query) use ($find) {
                                $query->where('code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('card', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('made', 'desc')
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
                            'tone' => $item->tone
                        ]);

                        return $list;
                    }, [])]);
                case 'data':
                    $client = new Client();

                    $base = [];

                    $next = 0;

                    do {
                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/i668hxjt9d3qn08b/user.search.json', [
                            RequestOptions::QUERY => [
                                'order' => ['NAME' => 'asc', 'LAST_NAME' => 'asc'],
                                'fields' => ['USER_TYPE' => 'employee'],
                                'start' => $next,
                                'limit' => 50
                            ]
                        ]);
    
                        if (($done = ($response->getStatusCode() == 200))) {
                            if (($data = json_decode($response->getBody(), true))) {
                                $base = array_merge($base, array_reduce($data['result'], function ($list, $item) {
                                    array_push($list, [
                                        'item' => $item['ID'],
                                        'name' => $item['NAME'],
                                        'last' => $item['LAST_NAME'],
                                        'icon' => isset($item['PERSONAL_PHOTO']) ? $item['PERSONAL_PHOTO'] : null
                                    ]);
        
                                    return $list;
                                }, []));
                            }
                        }
                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));

                    return response()->json(['base' => $base]);
			    case 'pull':
		            $query = DB::table('firms')
		                       ->where('hide', 0);

			        return response()->json(['high' => ($high = $query->count()),
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
                            'base' => intval($item->base),
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