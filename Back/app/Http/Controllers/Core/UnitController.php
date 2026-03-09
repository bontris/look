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

class UnitController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('units')
                           ->where('units.hide', 0)
                           ->where('units.hash', $item)
                           ->join('works', function ($join) {
                            $join->on('units.bind', 'works.row')
                                 ->where('works.bind', Auth::user()->bind);
                           })
                           ->select('units.*', DB::raw('ST_AsText(units.spot) AS spot'))
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'rank' => 'required|in:1,2,3,4,5',
                            'name' => 'required|max:32',
                            'mask' => 'nullable|max:64',
                            'bind' => 'required|integer',
                            'hook' => 'nullable|integer',
                            'skip' => 'nullable|integer',
                            'link' => 'nullable|integer',
                            'cost' => 'nullable|numeric',
                            'note' => 'nullable|max:1024',
                            'open' => 'nullable|date_format:Y-m-d',
                            'stop' => 'nullable|date_format:Y-m-d',
                            'date' => 'nullable|date_format:Y-m-d',
                            'post' => 'nullable|regex:/^[0-9]{6}$/',
                            'gate' => 'nullable|regex:/^[a-zA-Z0-9]{2,8}$/',
                            'code' => 'required|regex:/^[a-zA-Z0-9]{2,16}$/',
                            'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'back' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'spot' => 'nullable|array|min:2|max:2'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'rank.in' => 'El campo no es válido.',
                            'spot.min' => 'El campo no es válido.',
                            'spot.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'mask.max' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'back.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.regex' => 'El campo no es válido.',
                            'post.regex' => 'El campo no es válido.',
                            'gate.regex' => 'El campo no es válido.',
                            'tone.regex' => 'El campo no es válido.',
                            'spot.array' => 'El campo no es válido.',
                            'bind.integer' => 'El campo no es válido.',
                            'hook.integer' => 'El campo no es válido.',
                            'skip.integer' => 'El campo no es válido.',
                            'link.integer' => 'El campo no es válido.',
                            'code.required' => 'El campo es requerido.',
                            'rank.required' => 'El campo es requerido.',
                            'bind.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                            'back.mimetypes' => 'El campo debe ser una imágen válida.',
                            'open.date_format' => 'El campo no es válido.',
                            'stop.date_format' => 'El campo no es válido.',
                            'date.date_format' => 'El campo no es válido.'
                        ]);

			            if (empty($validator->fails())) {
                            if (($bind = DB::table('works')
                                           ->where('row', intval($request->get('bind', $item->bind)))
                                           ->where('hide', 0)
                                           ->where('once', 0)
                                           ->where('type', 1)
                                           ->where('bind', Auth::user()->bind)
                                           ->first())) {
                                if ((empty(($same = DB::table('units')
                                                      ->where('hide', 0)
                                                      ->where('bind', $bind->row)
                                                      ->where('code', trim($request->input('code', $item->code)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($same = DB::table('units')
                                                          ->where('hide', 0)
                                                          ->where('bind', $bind->row)
                                                          ->where('name', trim($request->input('name', $item->name)))
                                                          ->first())) || ($item->row == $same->row))) {
                                        if ((empty(($skip = intval($request->get('skip', $item->skip)))) || ($skip = DB::table('leads')
                                                                                                                       ->where('row', $skip)
                                                                                                                       ->where('hide', 0)
                                                                                                                       ->where('sort', 2)
                                                                                                                       ->where('bind', Auth::user()->bind)
                                                                                                                       ->first()))) {
                                            if ((empty(($link = intval($request->get('link', $item->link)))) || ($link = DB::table('leads')
                                                                                                                           ->where('row', $link)
                                                                                                                           ->where('hide', 0)
                                                                                                                           ->where('sort', 1)
                                                                                                                           ->where('bind', Auth::user()->bind)
                                                                                                                           ->first()))) {
                                                if ((empty(($hook = intval($request->get('hook', $item->hook)))) || ($hook = DB::table('hands')
                                                                                                                               ->where('row', $hook)
                                                                                                                               ->where('hide', 0)
                                                                                                                               ->where('bind', Auth::user()->bind)
                                                                                                                               ->first()))) {
                                                    if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                        if ((empty(($back = $request->file('back'))) || Image::make($back)->save(sprintf('%s/%s', storage_path('files'), ($back = md5(uniqid(rand(), true))))))) {
                                                            if (DB::table('units')->where('row', $item->row)->update([
                                                                'mark' => date('Y-m-d H:i:s'),
                                                                'icon' => $icon ?? $item->icon,
                                                                'back' => $back ?? $item->back,
                                                                'open' => $request->get('open', $item->open),
                                                                'stop' => $request->get('stop', $item->stop),
                                                                'date' => $request->get('date', $item->date),
                                                                'code' => trim($request->get('code', $item->code)),
                                                                'name' => trim($request->get('name', $item->name)),
                                                                'mask' => trim($request->get('mask', $item->mask)),
                                                                'path' => trim($request->get('path', $item->path)),
                                                                'gate' => trim($request->get('gate', $item->gate)),
                                                                'post' => trim($request->get('post', $item->post)),
                                                                'note' => trim($request->get('note', $item->note)),
                                                                'tone' => trim($request->get('tone', $item->tone)),
                                                                'lock' => intval($request->get('lock', $item->lock)),
                                                                'rank' => intval($request->get('rank', $item->rank)),
                                                                'bind' => intval($request->get('bind', $item->bind)),
                                                                'hook' => intval($request->get('hook', $item->hook)),
                                                                'skip' => intval($request->get('skip', $item->skip)),
                                                                'link' => intval($request->get('link', $item->link)),
                                                                'spot' => $request->has('spot') ? DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                                    return floatval($item);
                                                                }, $request->get('spot'))))) : $item->spot
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
                                                        'list' => ['hook' => 'La opción no es vealida.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                                    'list' => ['link' => 'La opción no es vealida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['skip' => 'La opción no es vealida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['name' => 'El nombre ya existe.']
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
                                    'list' => ['link' => 'La opción no es vealida.']
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
                                    if (DB::table('units')
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
                                if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('units')
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
                                    if (DB::table('units')
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
                                if ((empty(empty($item->back)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->back)) && DB::table('units')
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
                            if (DB::table('units')
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
			        	if (DB::table('units')
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
    					if (DB::table('units')
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
    				default:
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
                        'rank' => 'required|in:1,2,3,4,5',
                        'path' => 'nullable|max:32',
                        'mask' => 'nullable|max:64',
                        'name' => 'required|max:32',
                        'bind' => 'required|integer',
                        'hook' => 'nullable|integer',
                        'skip' => 'nullable|integer',
                        'link' => 'nullable|integer',
                        'note' => 'nullable|max:1024',
                        'open' => 'nullable|date_format:Y-m-d',
                        'stop' => 'nullable|date_format:Y-m-d',
                        'date' => 'nullable|date_format:Y-m-d',
                        'post' => 'nullable|regex:/^[0-9]{6}$/',
                        'gate' => 'nullable|regex:/^[a-zA-Z0-9]{2,8}$/',
                        'code' => 'nullable|regex:/^[a-zA-Z0-9]{2,16}$/',
                        'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                        'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                        'back' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                        'spot' => 'nullable|array|min:2|max:2'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'rank.in' => 'El campo no es válido.',
                        'spot.min' => 'El campo no es válido.',
                        'spot.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'path.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'mask.max' => 'El campo no es válido.',
                        'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'back.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'code.regex' => 'El campo no es válido.',
                        'post.regex' => 'El campo no es válido.',
                        'gate.regex' => 'El campo no es válido.',
                        'tone.regex' => 'El campo no es válido.',
                        'spot.array' => 'El campo no es válido.',
                        'cost.numeric' => 'El campo no es válido.',
                        'bind.integer' => 'El campo no es válido.',
                        'hook.integer' => 'El campo no es válido.',
                        'skip.integer' => 'El campo no es válido.',
                        'link.integer' => 'El campo no es válido.',
                        'rank.required' => 'El campo es requerido.',
                        'bind.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                        'back.mimetypes' => 'El campo debe ser una imágen válida.',
                        'open.date_format' => 'El campo no es válido.',
                        'stop.date_format' => 'El campo no es válido.',
                        'date.date_format' => 'El campo no es válido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (($bind = DB::table('works')
                                       ->where('row', intval($request->get('bind')))
                                       ->where('hide', 0)
                                       ->where('once', 0)
                                       ->where('type', 1)
                                       ->where('bind', Auth::user()->bind)
                                       ->first())) {
                            if (empty(DB::table('units')
                                        ->where('hide', 0)
                                        ->where('bind', $bind->row)
                                        ->where('code', ($code = trim($request->input('code', hexdec(uniqid())))))
                                        ->first())) {
                                if (empty(DB::table('units')
                                            ->where('hide', 0)
                                            ->where('bind', $bind->row)
                                            ->where('name', trim($request->input('name')))
                                            ->first())) {
                                    if ((empty(($skip = intval($request->get('skip')))) || ($skip = DB::table('leads')
                                                                                                      ->where('row', $skip)
                                                                                                      ->where('hide', 0)
                                                                                                      ->where('sort', 2)
                                                                                                      ->where('bind', Auth::user()->bind)
                                                                                                      ->first()))) {
                                        if ((empty(($link = intval($request->get('link')))) || ($link = DB::table('leads')
                                                                                                          ->where('row', $link)
                                                                                                          ->where('hide', 0)
                                                                                                          ->where('sort', 1)
                                                                                                          ->where('bind', Auth::user()->bind)
                                                                                                          ->first()))) {                       
                                            if ((empty(($hook = intval($request->get('hook')))) || ($hook = DB::table('hands')
                                                                                                              ->where('row', $hook)
                                                                                                              ->where('hide', 0)
                                                                                                              ->where('bind', Auth::user()->bind)
                                                                                                              ->first()))) {
                                                if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                    if ((empty(($back = $request->file('back'))) || Image::make($back)->save(sprintf('%s/%s', storage_path('files'), ($back = md5(uniqid(rand(), true))))))) {
                                                        if (($item = DB::table('units')->insertGetId([
                                                            'code' => $code,
                                                            'icon' => $icon,
                                                            'back' => $back,
                                                            'mask' => trim($request->get('mask')),
                                                            'path' => trim($request->get('path')),
                                                            'gate' => trim($request->get('gate')),
                                                            'post' => trim($request->get('post')),
                                                            'name' => trim($request->get('name')),
                                                            'note' => trim($request->get('note')),
                                                            'open' => $request->get('open', null),
                                                            'stop' => $request->get('stop', null),
                                                            'date' => $request->get('date', null),
                                                            'lock' => intval($request->get('lock')),
                                                            'bind' => intval($request->get('bind')),
                                                            'hook' => intval($request->get('hook')),
                                                            'skip' => intval($request->get('skip')),
                                                            'link' => intval($request->get('link')),
                                                            'rank' => intval($request->get('rank')),
                                                            'made' => ($made = date('Y-m-d H:i:s')),
                                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                                            'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                            'spot' => DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                                return floatval($item);
                                                            }, $request->get('spot', [])))))
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
                                                    'list' => ['hook' => 'La opción no es vealida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['link' => 'La opción no es vealida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['skip' => 'La opción no es vealida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['name' => 'El nombre ya existe.']
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
                                'list' => ['link' => 'La opción no es vealida.']
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
    				$query = DB::table('units')
                               ->where('units.hide', 0)
                               ->where('units.hide', 0)
                               ->join('works', function ($join) {
                                  $join->on('units.bind', 'works.row')
                                       ->where('works.bind', Auth::user()->bind);
                               })
                               ->leftJoin('steps', 'steps.row', 'units.gain')
                               ->leftJoin('leads', 'leads.row', 'units.skip')
                               ->leftJoin('hands', 'hands.row', 'units.hook');
                    
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
                                                $query->where(DB::raw('YEAR(units.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(units.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(units.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(units.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(units.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(units.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(units.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(units.made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'lock':
                                        $query->where(function ($query) use ($data) {
                                            foreach (explode(' ', trim($data)) as $item => $data) {
                                                if (empty($item)) {
                                                    $query->where('units.lock', intval($data));
                                                } else {
                                                    $query->orWhere('units.lock', intval($data));
                                                }
                                            }
                                        });
                                        break;
                                }
                            }
                        } else {
                            $query->where(function ($query) use ($find) {
                                $query->where('units.code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('units.mask', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('units.name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select(
                                                                            'units.*',
                                                                            'works.cost',
                                                                            'leads.kind',
                                                                            'leads.firm',
                                                                            DB::raw('works.name AS work'),
                                                                            DB::raw('steps.name AS step'),
                                                                            DB::raw('ST_AsText(units.spot) AS spot'),
                                                                            DB::raw('CONCAT(`leads`.`name`, " ", `leads`.`last`) AS `lead`'),
                                                                            DB::raw('CONCAT(`hands`.`name`, " ", `hands`.`last`) AS `hand`'),
                                                                            DB::raw(sprintf("CONVERT_TZ(units.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                                                          )
                                                                          ->orderBy('units.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'cost' => floatval($item->cost),
                            'lock' => intval($item->lock),
                            'rate' => intval($item->rate),
                            'rank' => intval($item->rank),
                            'kind' => intval($item->kind),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'work' => $item->work,
                            'step' => $item->step,
                            'firm' => $item->firm,
                            'lead' => $item->lead,
                            'hand' => $item->hand,
                            'icon' => $item->icon,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('units')
		                       ->where('units.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('units.bind', 'works.row')
                                     ->where('works.bind', Auth::user()->bind);
                               });
                    
                    if (($bind = intval($request->get('bind')))) {
                        $query->where('units.bind', $bind);
                    }

                    if (($hook = intval($request->get('hook')))) {
                        $query->where('units.hook', $hook);
                    }

                    if (($skip = intval($request->get('skip')))) {
                        $query->where('units.skip', $skip);
                    }

                    if (($link = intval($request->get('link')))) {
                        $query->where('units.link', $link);
                    }

			        return response()->json(['size' => ($size = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
			                                 'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $size))
			                                                              ->orderBy('units.made', 'asc')
                                                                          ->select('units.*', DB::raw('ST_AsText(`units`.`spot`) AS `spot`'))
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : [null, null],
                            'cost' => floatval($item->cost),
                            'lock' => intval($item->lock),
                            'gain' => intval($item->gain),
                            'rank' => intval($item->rank),
                            'bind' => intval($item->bind),
                            'hook' => intval($item->hook),
                            'skip' => intval($item->skip),
                            'link' => intval($item->link),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'path' => $item->path,
                            'post' => $item->post,
                            'gate' => $item->gate,
                            'note' => $item->note,
                            'icon' => $item->icon,
                            'tone' => $item->tone,
                            'mark' => $item->mark,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/units', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}