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

class ShopController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('shops')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->where('bind', Auth::user()->bind)
                           ->select('*', DB::raw('ST_AsText(spot) AS spot'))
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'type' => 'sometimes|in:1,2,3,4,5,6',
                            'name' => 'required|max:64',
                            'head' => 'nullable|integer',
                            'note' => 'nullable|max:256',
                            'flat' => 'nullable|max:8',
                            'zone' => 'nullable|max:32',
                            'town' => 'nullable|max:32',
                            'path' => 'nullable|max:32',
                            'mail' => 'nullable|email|max:64',
                            'coin' => 'required|regex:/^[A-Z]{3}$/',
                            'post' => 'nullable|regex:/^[0-9]{6}$/',
                            'page' => 'nullable|url:http,https|max:128',
                            'card' => 'sometimes|regex:/^[a-zA-Z0-9]{5,16}$/',
                            'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                            'time' => 'required|regex:/^[\+\-][0-9]{2}\:[0-9]{2}$/',
                            'work' => 'nullable|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/|max:16',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'back' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'spot' => 'nullable|array|min:2',
                            'spot.*' => 'nullable|numeric'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'spot.min' => 'El campo no es válido.',
                            'card.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'page.max' => 'El campo no es válido.',
                            'flat.max' => 'El campo no es válido.',
                            'path.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'head.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'page.url' => 'El campo no es válido.',
                            'card.regex' => 'El campo no es válido.',
                            'work.regex' => 'El campo no es válido.',
                            'coin.regex' => 'El campo no es válido.',
                            'time.regex' => 'El campo no es válido.',
                            'post.regex' => 'El campo no es válido.',
                            'tone.regex' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.',
                            'spot.array' => 'El campo no es válido.',
                            'head.integer' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'card.required' => 'El campo es requerido.',
                            'mail.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'coin.required' => 'El campo es requerido.',
                            'time.required' => 'El campo es requerido.',
                            'spot.*.numeric' => 'El campo no es válido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                            'back.mimetypes' => 'El campo debe ser una imágen válida.'
                        ]);

			            if (empty($validator->fails())) {
                            if ((empty(($same = DB::table('shops')
                                                  ->where('hide', 0)
                                                  ->where('bind', Auth::user()->bind)
                                                  ->where('card', trim($request->get('card', $item->card)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($head = intval($request->get('head', $item->head)))) || ($head = DB::table('hands')
                                                                                                               ->where('row', $head)
                                                                                                               ->where('hide', 0)
                                                                                                               ->where('bind', Auth::user()->bind)
                                                                                                               ->first()))) {
                                    if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                        if ((empty(($back = $request->file('back'))) || Image::make($back)->save(sprintf('%s/%s', storage_path('files'), ($back = md5(uniqid(rand(), true))))))) {
                                            if (DB::table('shops')->where('row', $item->row)->update([
                                                'mark' => date('Y-m-d H:i:s'),
                                                'icon' => $icon ?? $item->icon,
                                                'back' => $back ?? $item->back,
                                                'card' => trim($request->get('card', $item->card)),
                                                'name' => trim($request->get('name', $item->name)),
                                                'zone' => trim($request->get('zone', $item->zone)),
                                                'town' => trim($request->get('town', $item->town)),
                                                'path' => trim($request->get('path', $item->path)),
                                                'flat' => trim($request->get('flat', $item->flat)),
                                                'post' => trim($request->get('post', $item->post)),
                                                'work' => trim($request->get('work', $item->work)),
                                                'mail' => trim($request->get('mail', $item->mail)),
                                                'coin' => trim($request->get('coin', $item->coin)),
                                                'time' => trim($request->get('time', $item->time)),
                                                'page' => trim($request->get('page', $item->page)),
                                                'note' => trim($request->get('note', $item->note)),
                                                'tone' => trim($request->get('tone', $item->tone)),
                                                'lock' => intval($request->get('lock', $item->lock)),
                                                'type' => intval($request->get('type', $item->type)),
                                                'head' => intval($request->get('head', $item->head)),
                                                'spot' => (isset(($spot = $request->post('spot'))[0]) && isset($spot[1])) ? DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                    return floatval($item);
                                                }, $spot)))) : $item->spot
                                            ])) {
                                                if ((empty(empty($icon)) && empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->icon))))) {
                                                    Log::error(sprintf('Unable to delete the file: %s', $item->icon));
                                                }

                                                if ((empty(empty($back)) && empty(empty($item->back)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->back))))) {
                                                    Log::error(sprintf('Unable to delete the file: %s', $item->back));
                                                }
    
                                                return response()->json([
                                                    'icon' => $icon,
                                                    'back' => $back,
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
                                                'list' => ['back' => 'La imágen no pudo ser cargada con éxito.']
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Se presentó un error interno.',
                                            'list' => ['icon' => 'La imágen no pudo ser cargada con éxito.']
                                        ], 500);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['head' => 'La opción no es vealida.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['card' => 'El NIT ya existe.']
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
                            'file' => 'nullable|mimetypes:image/jpeg,image/png'
                        ], [
                            'file.mimetypes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('shops')
                                          ->where('row', $item->row)
                                          ->update(['icon' => $file, 'mark' => date('Y-m-d H:i:s')])) {
                                        if ((empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->icon))))) {
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
                                if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('shops')
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
                    case 'back':
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|mimetypes:image/jpeg,image/png'
                        ], [
                            'file.mimetypes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('shops')
                                            ->where('row', $item->row)
                                            ->update(['back' => $file, 'mark' => date('Y-m-d H:i:s')])) {
                                        if ((empty(empty($item->back)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->back))))) {
                                            Log::error(sprintf('Unable to delete the file: %s', $item->back));
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
                                if ((empty(empty($item->back)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->back)) && DB::table('shops')
                                                                                                                                     ->where('row', $item->row)
                                                                                                                                     ->update(['back' => null, 'mark' => date('Y-m-d H:i:s')]))) {
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
                    case 'move':
                        $validator = Validator::make($request->all(), [
                            'spot' => 'required|array|min:2',
                            'spot.*' => 'required|numeric',
                        ], [
                            'spot.array' => 'La posición no es válida.',
                            'spot.required' => 'El campo es requerido.',
                            'spot.*.numeric' => 'La posición no es válida.',
                            'spot.*.required' => 'El campo es requerido.'
                        ]);

                        if (empty($validator->fails())) {
                            if (DB::table('shops')
                                  ->where('row', $item->row)
                                  ->update(['spot' => DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                return floatval($item);
                            }, $request->post('spot'))))), 'mark' => date('Y-m-d H:i:s')])) {
                                return response()->json([
                                    'text' => 'La ubicación fue actualizada con éxito.'
                                ], 200);
                            } else {
                                return response()->json([
                                    'text' => 'La ubicación no pudo ser actualizada.'
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
			        case 'lock':
			        	if (DB::table('shops')
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
    					if (DB::table('shops')
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
    					$item = get_object_vars($item);

                        array_walk($item, function (&$item, $name) {
                            switch ($name) {
                                case 'spot':
                                    if (preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item, $item)) {
                                        $item = [floatval($item[1]), floatval($item[3])];
                                    }
                                    break;
                                case 'row':
                                    $item = intval($item);
                                    break;
                            }
                        });

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
                        'type' => 'required|in:1,2,3,4,5,6',
                        'name' => 'required|max:64',
                        'head' => 'nullable|integer',
                        'note' => 'nullable|max:256',
                        'zone' => 'nullable|max:32',
                        'town' => 'nullable|max:32',
                        'path' => 'nullable|max:32',
                        'flat' => 'nullable|max:8',
                        'mail' => 'nullable|email|max:64',
                        'coin' => 'required|regex:/^[A-Z]{3}$/',
                        'post' => 'nullable|regex:/^[0-9]{6}$/',
                        'page' => 'nullable|url:http,https|max:128',
                        'card' => 'required:regex:/^[a-zA-Z0-9]{5,16}$/',
                        'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                        'time' => 'required|regex:/^[\+\-][0-9]{2}\:[0-9]{2}$/',
                        'work' => 'nullable|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/|max:16',
                        'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                        'back' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                        'spot' => 'nullable|array|min:2',
                        'spot.*' => 'nullable|numeric'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'spot.min' => 'El campo no es válido.',
                        'card.max' => 'El campo no es válido.',
                        'work.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'zone.max' => 'El campo no es válido.',
                        'town.max' => 'El campo no es válido.',
                        'path.max' => 'El campo no es válido.',
                        'flat.max' => 'El campo no es válido.',
                        'page.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'head.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'page.url' => 'El campo no es válido.',
                        'card.regex' => 'El campo no es válido.',
                        'post.regex' => 'El campo no es válido.',
                        'work.regex' => 'El campo no es válido.',
                        'coin.regex' => 'El campo no es válido.',
                        'time.regex' => 'El campo no es válido.',
                        'tone.regex' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'spot.array' => 'El campo no es válido.',
                        'head.integer' => 'El campo no es válido.',
                        'type.required' => 'El campo es requerido.',
                        'card.required' => 'El campo es requerido.',
                        'mail.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'coin.required' => 'El campo es requerido.',
                        'time.required' => 'El campo es requerido.',
                        'spot.*.numeric' => 'El campo no es válido.',
                        'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                        'back.mimetypes' => 'El campo debe ser una imágen válida.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('shops')
                                    ->where('hide', 0)
                                    ->where('bind', Auth::user()->bind)
                                    ->where('code', ($code = hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('shops')
                                        ->where('hide', 0)
                                        ->where('bind', Auth::user()->bind)
                                        ->where('card', trim($request->get('card')))
                                        ->first())) {
                                if ((empty(($head = intval($request->get('head')))) || ($head = DB::table('hands')
                                                                                                  ->where('row', $head)
                                                                                                  ->where('hide', 0)
                                                                                                  ->where('bind', Auth::user()->bind)
                                                                                                  ->first()))) {
                                    if ((empty(($icon = $request->file('icon'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                        if ((empty(($back = $request->file('back'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($back = md5(uniqid(rand(), true))))))) {
                                            if (($item = DB::table('shops')->insertGetId([
                                                'code' => $code,
                                                'icon' => $icon,
                                                'back' => $back,
                                                'bind' => Auth::user()->bind,
                                                'card' => trim($request->get('card')),
                                                'work' => trim($request->get('work')),
                                                'page' => trim($request->get('page')),
                                                'mail' => trim($request->get('mail')),
                                                'coin' => trim($request->get('coin')),
                                                'time' => trim($request->get('time')),
                                                'zone' => trim($request->get('zone')),
                                                'town' => trim($request->get('town')),
                                                'path' => trim($request->get('path')),
                                                'flat' => trim($request->get('flat')),
                                                'post' => trim($request->get('post')),
                                                'name' => trim($request->get('name')),
                                                'note' => trim($request->get('note')),
                                                'time' => trim($request->get('time')),
                                                'lock' => intval($request->get('lock')),
                                                'type' => intval($request->get('type')),
                                                'head' => intval($request->get('head')),
                                                'made' => ($made = date('Y-m-d H:i:s')),
                                                'hash' => ($hash = md5(uniqid(rand(), true))),
                                                'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                'spot' => (isset(($spot = $request->post('spot'))[0]) && isset($spot[1])) ? DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                    return floatval($item);
                                                }, $spot)))) : null
                                            ]))) {
                                                return response()->json([
                                                    'made' => $made,
                                                    'item' => $item,
                                                    'code' => $code,
                                                    'hash' => $hash,
                                                    'icon' => $icon,
                                                    'back' => $back,
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
                                                'list' => ['back' => 'La imágen no pudo ser cargada con éxito.']
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Se presentó un error interno.',
                                            'list' => ['icon' => 'La imágen no pudo ser cargada con éxito.']
                                        ], 500);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['head' => 'La opción no es vealida.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['card' => 'El NIT ya existe.']
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
    				$query = DB::table('shops')
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
                                                                          ->select(
                                                                            '*',
                                                                            DB::raw('ST_AsText(spot) AS spot'),
                                                                            DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                                                          )
                                                                          ->orderBy('made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'coin' => $item->coin,
                            'time' => $item->time,
                            'page' => $item->page,
                            'name' => $item->name,
                            'icon' => $item->icon,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('shops')
		                       ->where('hide', 0)
                               ->where('bind', Auth::user()->bind);

			        return response()->json(['size' => ($size = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
			                                 'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $size))
			                                                              ->orderBy('made', 'asc')
                                                                          ->select('*', DB::raw('ST_AsText(spot) AS spot'))
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'coin' => $item->coin,
                            'time' => $item->time,
                            'page' => $item->page,
                            'name' => $item->name,
                            'icon' => $item->icon,
                            'tone' => $item->tone,
                            'mark' => $item->mark,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/shops', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}