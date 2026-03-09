<?php

namespace App\Http\Controllers\Core;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class HandController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('hands')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->where('bind', Auth::user()->bind)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'mode' => 'required|in:1,2',
                            'live' => 'required|in:1,2,3',
                            'sort' => 'required|in:1,2,3,4,5',
                            'type' => 'required|in:1,2,3,4,5',
                            'rank' => 'nullable|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14',
                            'last' => 'required|max:32',
                            'name' => 'required|max:32',
                            'role' => 'nullable|max:32',
                            'firm' => 'nullable|max:64',
                            'note' => 'nullable|max:256',
                            'path' => 'nullable|max:32',
                            'gate' => 'nullable|max:8',
                            'link' => 'nullable|integer',
                            'hook' => 'nullable|integer',
                            'spot' => 'nullable|integer',
                            'stay' => 'nullable|integer',
                            'zone' => 'nullable|integer',
                            'town' => 'nullable|integer',
                            'born' => 'nullable|integer',
                            'mail' => 'nullable|email|max:64',
                            'post' => 'nullable|regex:/^[0-9]{6}$/',
                            'code' => 'required|regex:/^[a-zA-Z0-9]{2,16}$/',
                            'card' => 'required|regex:/^[a-zA-Z0-9]{4,16}$/',
                            'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                            'work' => 'nullable|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/',
                            'date' => 'nullable|date_format:Y-m-d',
                            'open' => 'nullable|date_format:Y-m-d',
                            'exit' => 'nullable|date_format:Y-m-d',
                            'face' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                        ], [
                            'sort.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'rank.in' => 'El campo no es válido.',
                            'mode.in' => 'El campo no es válido.',
                            'live.in' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'path.max' => 'El campo no es válido.',
                            'gate.max' => 'El campo no es válido.',
                            'last.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'role.max' => 'El campo no es válido.',
                            'firm.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'face.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.regex' => 'El campo no es válido.',
                            'card.regex' => 'El campo no es válido.',
                            'post.regex' => 'El campo no es válido.',
                            'work.regex' => 'El campo no es válido.',
                            'tone.regex' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.',
                            'link.integer' => 'El campo no es válido.',
                            'hook.integer' => 'El campo no es válido.',
                            'stay.integer' => 'El campo no es válido.',
                            'zone.integer' => 'El campo no es válido.',
                            'town.integer' => 'El campo no es válido.',
                            'born.integer' => 'El campo no es válido.',
                            'spot.integer' => 'El campo no es válido.',
                            'sort.required' => 'El campo es requerido.',
                            'type.required' => 'El campo es requerido.',
                            'mode.required' => 'El campo es requerido.',
                            'live.required' => 'El campo es requerido.',
                            'code.required' => 'El campo es requerido.',
                            'card.required' => 'El campo es requerido.',
                            'last.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'face.mimetypes' => 'El campo debe ser una imágen válida.',
                            'date.date_format' => 'El campo no es válido.',
                            'open.date_format' => 'El campo no es válido.',
                            'exit.date_format' => 'El campo no es válido.'
                        ]);

			            if (empty($validator->fails())) {
                            if ((empty(($same = DB::table('hands')
                                                  ->where('hide', 0)
                                                  ->where('bind', Auth::user()->bind)
                                                  ->where('code', trim($request->get('code', $item->code)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('hands')
                                                      ->where('hide', 0)
                                                      ->where('bind', Auth::user()->bind)
                                                      ->where('card', trim($request->get('card', $item->card)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($link = intval($request->get('link', $item->link)))) || ($link = DB::table('users')
                                                                                                                   ->where('id', $link)
                                                                                                                   ->where('hide', 0)
                                                                                                                   ->where('type', 2)
                                                                                                                   ->where('bind', Auth::user()->bind)
                                                                                                                   ->first()))) {
                                        if ((empty(($hook = intval($request->get('hook', $item->hook)))) || ($hook = DB::table('hands')
                                                                                                                       ->where('row', $hook)
                                                                                                                       ->where('hide', 0)
                                                                                                                       ->where('bind', Auth::user()->bind)
                                                                                                                       ->first()))) {
                                            if ((empty(($spot = intval($request->get('spot', $item->spot)))) || ($spot = DB::table('shops')
                                                                                                                           ->where('row', $spot)
                                                                                                                           ->where('hide', 0)
                                                                                                                           ->where('bind', Auth::user()->bind)
                                                                                                                           ->first()))) {
                                                if ((empty(($stay = intval($request->get('stay', $item->stay)))) || ($stay = DB::table('chips')
                                                                                                                               ->where('row', $stay)
                                                                                                                               ->where('hide', 0)
                                                                                                                               ->where('type', 2)
                                                                                                                               ->first()))) {
                                                    if ((empty(($zone = intval($request->get('zone', $item->zone)))) || ($zone = DB::table('chips')
                                                                                                                                   ->where('row', $zone)
                                                                                                                                   ->where('hide', 0)
                                                                                                                                   ->where('type', 3)
                                                                                                                                   ->first()))) {
                                                        if ((empty(($town = intval($request->get('town', $item->town)))) || ($town = DB::table('chips')
                                                                                                                                       ->where('row', $town)
                                                                                                                                       ->where('hide', 0)
                                                                                                                                       ->where('type', 4)
                                                                                                                                       ->first()))) {
                                                            if ((empty(($born = intval($request->get('born', $item->born)))) || ($born = DB::table('chips')
                                                                                                                                           ->where('row', $born)
                                                                                                                                           ->where('hide', 0)
                                                                                                                                           ->where('type', 2)
                                                                                                                                           ->first()))) {
                                                                if ((empty(($face = $request->file('face'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($face = md5(uniqid(rand(), true))))))) {
                                                                    if (DB::table('hands')->where('row', $item->row)->update([
                                                                        'mark' => date('Y-m-d H:i:s'),
                                                                        'face' => $face ?? $item->face,
                                                                        'code' => trim($request->get('code', $item->code)),
                                                                        'card' => trim($request->get('card', $item->card)),
                                                                        'last' => trim($request->get('last', $item->last)),
                                                                        'name' => trim($request->get('name', $item->name)),
                                                                        'path' => trim($request->get('path', $item->path)),
                                                                        'gate' => trim($request->get('gate', $item->gate)),
                                                                        'post' => trim($request->get('post', $item->post)),
                                                                        'work' => trim($request->get('work', $item->work)),
                                                                        'mail' => trim($request->get('mail', $item->mail)),
                                                                        'role' => trim($request->get('role', $item->role)),
                                                                        'area' => trim($request->get('area', $item->area)),
                                                                        'note' => trim($request->get('note', $item->note)),
                                                                        'tone' => trim($request->get('tone', $item->tone)),
                                                                        'sort' => intval($request->get('sort', $item->sort)),
                                                                        'type' => intval($request->get('type', $item->type)),
                                                                        'rank' => intval($request->get('rank', $item->rank)),
                                                                        'mode' => intval($request->get('mode', $item->mode)),
                                                                        'live' => intval($request->get('live', $item->live)),
                                                                        'link' => intval($request->get('link', $item->link)),
                                                                        'hook' => intval($request->get('hook', $item->hook)),
                                                                        'stay' => intval($request->get('stay', $item->stay)),
                                                                        'zone' => intval($request->get('zone', $item->zone)),
                                                                        'town' => intval($request->get('town', $item->town)),
                                                                        'born' => intval($request->get('born', $item->born)),
                                                                        'spot' => intval($request->get('spot', $item->spot)),
                                                                        'date' => ($date = trim($request->get('date', $item->date))) ? $date : null,
                                                                        'open' => ($open = trim($request->get('open', $item->open))) ? $open : null,
                                                                        'exit' => ($exit = trim($request->get('exit', $item->exit))) ? $exit : null
                                                                    ])) {
                                                                        if ((empty(empty($face)) && empty(empty($item->face)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->face))))) {
                                                                            Log::error(sprintf('Unable to delete the file: %s', $item->face));
                                                                        } 

                                                                        return response()->json([
                                                                            'face' => $face,
                                                                            'text' => 'El registro fue actualizado con éxito.'
                                                                        ], 200);
                                                                    } else {
                                                                        return response()->json([
                                                                            'text' => 'El registro no pudo ser actualizado.'
                                                                        ], 500);
                                                                    }
                                                                } else {
                                                                    return response()->json([
                                                                        'text' => 'Se presentó un error interno.',
                                                                        'list' => ['face' => 'La imágen no pudo ser cargada con éxito.']
                                                                    ], 500);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                                                    'list' => ['born' => 'La opción no es válida.']
                                                                ], 400);
                                                            }
                                                        } else {
                                                            return response()->json([
                                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                                'list' => ['town' => 'La opción no es válida.']
                                                            ], 400);
                                                        }
                                                    } else {
                                                        return response()->json([
                                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                                            'list' => ['zone' => 'La opción no es válida.']
                                                        ], 400);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                                        'list' => ['stay' => 'La opción no es válida.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                                    'list' => ['spot' => 'La opción no es válida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                'list' => ['hook' => 'La opción no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'list' => ['link' => 'La opción no es válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                        'list' => ['card' => 'El documento ya existe.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                    'list' => ['code' => 'El código ya existe.']
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
                    case 'face':
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|mimetypes:image/jpeg,image/png'
                        ], [
                            'file.mimetypes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('hands')
                                          ->where('row', $item->row)
                                          ->update(['face' => $file, 'mark' => date('Y-m-d H:i:s')])) {
                                        if ((empty(empty($item->face)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->face))))) {
                                            Log::error(sprintf('Unable to delete the file: %s', $item->face));
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
                                if ((empty(empty($item->face)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->face)) && DB::table('hands')
                                                                                                                                     ->where('row', $item->row)
                                                                                                                                     ->update(['face' => null, 'mark' => date('Y-m-d H:i:s')]))) {
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
			                  ->update(['lock' => boolval($request->get('flag')), 'mark' => date('Y-m-d H:i:s')])) {
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
			              	return response()->json([
                                'text' => 'El registro fue eliminado con éxito.'
                            ], 200);
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
                        'mode' => 'required|in:1,2',
                        'live' => 'required|in:1,2,3',
                        'sort' => 'required|in:1,2,3,4,5',
                        'type' => 'required|in:1,2,3,4,5',
                        'rank' => 'nullable|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14',
                        'last' => 'required|max:32',
                        'name' => 'required|max:32',
                        'role' => 'nullable|max:32',
                        'area' => 'nullable|max:32',
                        'note' => 'nullable|max:256',
                        'path' => 'nullable|max:32',
                        'gate' => 'nullable|max:8',
                        'link' => 'nullable|integer',
                        'hook' => 'nullable|integer',
                        'spot' => 'nullable|integer',
                        'mail' => 'nullable|email|max:64',
                        'stay' => 'nullable|integer',
                        'zone' => 'nullable|integer',
                        'town' => 'nullable|integer',
                        'born' => 'nullable|integer',
                        'post' => 'nullable|regex:/^[0-9]{6}$/',
                        'code' => 'nullable|regex:/^[a-zA-Z0-9]{2,16}$/',
                        'card' => 'required|regex:/^[a-zA-Z0-9]{4,16}$/',
                        'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                        'work' => 'nullable|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/',
                        'date' => 'nullable|date_format:Y-m-d',
                        'open' => 'nullable|date_format:Y-m-d',
                        'exit' => 'nullable|date_format:Y-m-d',
                        'face' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'test.in' => 'El campo no es válido.',
                        'live.in' => 'El campo no es válido.',
                        'mode.in' => 'El campo no es válido.',
                        'rank.in' => 'El campo no es válido.',
                        'sort.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'zone.max' => 'El campo no es válido.',
                        'town.max' => 'El campo no es válido.',
                        'path.max' => 'El campo no es válido.',
                        'gate.max' => 'El campo no es válido.',
                        'last.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'role.max' => 'El campo no es válido.',
                        'area.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'face.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'code.regex' => 'El campo no es válido.',
                        'card.regex' => 'El campo no es válido.',
                        'post.regex' => 'El campo no es válido.',
                        'work.regex' => 'El campo no es válido.',
                        'tone.regex' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'link.integer' => 'El campo no es válido.',
                        'hook.integer' => 'El campo no es válido.',
                        'stay.integer' => 'El campo no es válido.',
                        'zone.integer' => 'El campo no es válido.',
                        'town.integer' => 'El campo no es válido.',
                        'born.integer' => 'El campo no es válido.',
                        'spot.integer' => 'El campo no es válido.',
                        'sort.required' => 'El campo es requerido.',
                        'type.required' => 'El campo es requerido.',
                        'mode.required' => 'El campo es requerido.',
                        'card.required' => 'El campo es requerido.',
                        'last.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'face.mimetypes' => 'El campo debe ser una imágen válida.',
                        'date.date_format' => 'El campo no es válido.',
                        'open.date_format' => 'El campo no es válido.',
                        'exit.date_format' => 'El campo no es válido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('hands')
                                    ->where('hide', 0)
                                    ->where('bind', Auth::user()->bind)
                                    ->where('code', ($code = $request->get('code') ?? hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('hands')
                                        ->where('hide', 0)
                                        ->where('bind', Auth::user()->bind)
                                        ->where('card', trim($request->get('card')))
                                        ->first())) {
                                if ((empty(($link = intval($request->get('link')))) || ($link = DB::table('users')
                                                                                                  ->where('id', $link)
                                                                                                  ->where('hide', 0)
                                                                                                  ->where('type', 2)
                                                                                                  ->where('bind', Auth::user()->bind)
                                                                                                   ->first()))) {
                                    if ((empty(($hook = intval($request->get('hook')))) || ($hook = DB::table('hands')
                                                                                                      ->where('row', $hook)
                                                                                                      ->where('hide', 0)
                                                                                                      ->where('bind', Auth::user()->bind)
                                                                                                      ->first()))) {
                                        if ((empty(($spot = intval($request->get('spot')))) || ($unit = DB::table('shops')
                                                                                                          ->where('row', $spot)
                                                                                                          ->where('hide', 0)
                                                                                                          ->where('bind', Auth::user()->bind)
                                                                                                          ->first()))) {
                                            if ((empty(($stay = intval($request->get('stay')))) || ($stay = DB::table('chips')
                                                                                                              ->where('hide', 0)
                                                                                                              ->where('type', 2)
                                                                                                              ->where('row', $stay)
                                                                                                              ->first()))) {
                                                if ((empty(($zone = intval($request->get('zone')))) || ($zone = DB::table('chips')
                                                                                                                  ->where('hide', 0)
                                                                                                                  ->where('type', 3)
                                                                                                                  ->where('row', $zone)
                                                                                                                  ->first()))) {
                                                    if ((empty(($town = intval($request->get('town')))) || ($town = DB::table('chips')
                                                                                                                      ->where('hide', 0)
                                                                                                                      ->where('type', 4)
                                                                                                                      ->where('row', $town)
                                                                                                                      ->first()))) {
                                                        if ((empty(($born = intval($request->get('born')))) || ($born = DB::table('chips')
                                                                                                                          ->where('hide', 0)
                                                                                                                          ->where('type', 2)
                                                                                                                          ->where('row', $born)
                                                                                                                          ->first()))) {
                                                            if ((empty(($face = $request->file('face'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($face = md5(uniqid(rand(), true))))))) {
                                                                if (($item = DB::table('hands')->insertGetId([
                                                                    'code' => $code,
                                                                    'face' => $face,
                                                                    'bind' => Auth::user()->bind,
                                                                    'card' => trim($request->get('card')),
                                                                    'work' => trim($request->get('work')),
                                                                    'mail' => trim($request->get('mail')),
                                                                    'path' => trim($request->get('path')),
                                                                    'post' => trim($request->get('post')),
                                                                    'gate' => trim($request->get('gate')),
                                                                    'last' => trim($request->get('last')),
                                                                    'name' => trim($request->get('name')),
                                                                    'role' => trim($request->get('role')),
                                                                    'area' => trim($request->get('area')),
                                                                    'note' => trim($request->get('note')),
                                                                    'link' => intval($request->get('link')),
                                                                    'hook' => intval($request->get('hook')),
                                                                    'spot' => intval($request->get('spot')),
                                                                    'lock' => intval($request->get('lock')),
                                                                    'sort' => intval($request->get('sort')),
                                                                    'rank' => intval($request->get('rank')),
                                                                    'type' => intval($request->get('type')),
                                                                    'live' => intval($request->get('live')),
                                                                    'mode' => intval($request->get('mode')),
                                                                    'stay' => intval($request->get('stay')),
                                                                    'zone' => intval($request->get('zone')),
                                                                    'town' => intval($request->get('town')),
                                                                    'born' => intval($request->get('born')),
                                                                    'made' => ($made = date('Y-m-d H:i:s')),
                                                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                    'date' => ($date = trim($request->get('date'))) ? $date : null,
                                                                    'open' => ($open = trim($request->get('open'))) ? $open : null,
                                                                    'exit' => ($exit = trim($request->get('exit'))) ? $exit : null,
                                                                    'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                                                ]))) {
                                                                    return response()->json([
                                                                        'made' => $made,
                                                                        'item' => $item,
                                                                        'code' => $code,
                                                                        'hash' => $hash,
                                                                        'face' => $face,
                                                                        'tone' => $tone,
                                                                        'text' => 'El registro fue guardado con éxito.'
                                                                    ], 200);
                                                                } else {
                                                                    return response()->json([
                                                                        'text' => 'El registro no pudo ser guardado.'
                                                                    ], 500);
                                                                }
                                                            } else {
                                                                return response()->json([
                                                                    'text' => 'Se presentó un error interno.',
                                                                    'list' => ['face' => 'La imágen no pudo ser cargada con éxito.']
                                                                ], 500);
                                                            }
                                                        } else {
                                                            return response()->json([
                                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                                'list' => ['born' => 'La opción no es válida.']
                                                            ], 400);
                                                        }
                                                    } else {
                                                        return response()->json([
                                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                                            'list' => ['town' => 'La opción no es válida.']
                                                        ], 400);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                                        'list' => ['zone' => 'La opción no es válida.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                                    'list' => ['stay' => 'La opción no es válida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                'list' => ['spot' => 'La opción no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'list' => ['hook' => 'La opción no es válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                        'list' => ['link' => 'La opción no es válida.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                    'list' => ['card' => 'El número de documento ya existe.']
                                ], 400);
                            }
		            	} else {
                            return response()->json([
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => ['code' => 'El código ya existe.']
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
    			case 'load':
    				$query = DB::table('hands')
                               ->where('hide', 0)
                               ->where('bind', Auth::user()->bind);
                    
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
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'sort' => intval($item->sort),
                            'mode' => intval($item->mode),
                            'spot' => intval($item->spot),
                            'hook' => intval($item->hook),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'role' => $item->role,
                            'area' => $item->area,
                            'last' => $item->last,
                            'name' => $item->name,
                            'face' => $item->face,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('hands')
		                       ->where('hide', 0)
                               ->where('bind', Auth::user()->bind);
                    
                    if (($type = intval($request->get('type')))) {
                        $query->where('type', $type);
                    }

                    if (($sort = intval($request->get('sort')))) {
                        $query->where('sort', $sort);
                    }

                    if (($hook = intval($request->get('hook')))) {
                        $query->where('hook', $hook);
                    }

                    if (($spot = intval($request->get('spot')))) {
                        $query->where('spot', $spot);
                    }

                    if (($stay = intval($request->get('stay')))) {
                        $query->where('stay', $stay);
                    }

                    if (($stay = intval($request->get('zone')))) {
                        $query->where('zone', $zone);
                    }

                    if (($town = intval($request->get('town')))) {
                        $query->where('town', $town);
                    }

                    if (($born = intval($request->get('born')))) {
                        $query->where('born', $born);
                    }

			        return response()->json(['size' => ($size = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
			                                 'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $size))
			                                                              ->orderBy('made', 'asc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'live' => intval($item->live),
                            'rank' => intval($item->rank),
                            'sort' => intval($item->sort),
                            'mode' => intval($item->mode),
                            'spot' => intval($item->spot),
                            'hook' => intval($item->hook),
                            'link' => intval($item->link),
                            'time' => intval($item->time),
                            'text' => intval($item->text),
                            'stay' => intval($item->stay),
                            'zone' => intval($item->zone),
                            'town' => intval($item->town),
                            'born' => intval($item->born),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'mail' => $item->mail,
                            'work' => $item->work,
                            'path' => $item->path,
                            'post' => $item->post,
                            'gate' => $item->gate,
                            'role' => $item->role,
                            'area' => $item->area,
                            'last' => $item->last,
                            'name' => $item->name,
                            'date' => $item->date,
                            'open' => $item->open,
                            'exit' => $item->exit,
                            'note' => $item->note,
                            'face' => $item->face,
                            'tone' => $item->tone,
                            'mark' => $item->mark,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/hands', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}