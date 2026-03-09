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

class DonationController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('donations')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->select('*', DB::raw('ST_AsText(spot) AS spot'))
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'once' => 'nullable|in:0,1',
                            'type' => 'required|in:1,2',
                            'name' => 'required|max:64',
                            'coin' => 'nullable|integer',
                            'land' => 'nullable|integer',
                            'zone' => 'nullable|integer',
                            'town' => 'nullable|integer',
                            'path' => 'nullable|max:32',
                            'head' => 'nullable|integer',
                            'link' => 'nullable|integer',
                            'amount' => 'nullable|numeric',
                            'note' => 'nullable|max:1024',
                            'mail' => 'nullable|email|max:64',
                            'date' => 'nullable|date_format:Y-m-d',
                            'open' => 'nullable|date_format:Y-m-d',
                            'stop' => 'nullable|date_format:Y-m-d',
                            'post' => 'nullable|regex:/^[0-9]{6}$/',
                            'location' => 'nullable|array|min:2|max:2',
                            'location.*' => 'nullable|numeric'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'once.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'rank.in' => 'El campo no es válido.',
                            'spot.min' => 'El campo no es válido.',
                            'spot.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'path.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'icon.back' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.regex' => 'El campo no es válido.',
                            'post.regex' => 'El campo no es válido.',
                            'tone.regex' => 'El campo no es válido.',
                            'spot.array' => 'El campo no es válido.',
                            'cost.numeric' => 'El campo no es válido.',
                            'link.integer' => 'El campo no es válido.',
                            'hook.integer' => 'El campo no es válido.',
                            'coin.integer' => 'El campo no es válido.',
                            'land.integer' => 'El campo no es válido.',
                            'zone.integer' => 'El campo no es válido.',
                            'town.integer' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'rank.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'code.required' => 'El campo es requerido.',
                            'spot.*.numeric' => 'El campo no es válido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                            'back.mimetypes' => 'El campo debe ser una imágen válida.',
                            'date.date_format' => 'El campo no es válido.',
                            'open.date_format' => 'El campo no es válido.',
                            'stop.date_format' => 'El campo no es válido.'
                        ]);

			            if (empty($validator->fails())) {
                            if ((empty(($same = DB::table('works')
                                                  ->where('hide', 0)
                                                  ->where('bind', Auth::user()->bind)
                                                  ->where('code', trim($request->input('code', $item->code)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($link = intval($request->get('link', $item->link)))) || ($link = DB::table('leads')
                                                                                                               ->where('row', $link)
                                                                                                               ->where('hide', 0)
                                                                                                               ->where('bind', Auth::user()->bind)
                                                                                                               ->first()))) {
                                    if ((empty(($hook = intval($request->get('hook', $item->hook)))) || ($hook = DB::table('hands')
                                                                                                                   ->where('row', $hook)
                                                                                                                   ->where('hide', 0)
                                                                                                                   ->where('bind', Auth::user()->bind)
                                                                                                                   ->first()))) {
                                        if ((empty(($coin = intval($request->get('coin', $item->coin)))) || ($coin = DB::table('chips')
                                                                                                                       ->where('row', $coin)
                                                                                                                       ->where('hide', 0)
                                                                                                                       ->where('type', 6)
                                                                                                                       ->first()))) {
                                            if ((empty(($land = intval($request->get('land', $item->land)))) || ($land = DB::table('chips')
                                                                                                                           ->where('row', $land)
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
                                                        if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                            if ((empty(($back = $request->file('back'))) || Image::make($back)->save(sprintf('%s/%s', storage_path('files'), ($back = md5(uniqid(rand(), true))))))) {
                                                                if (DB::table('works')->where('row', $item->row)->update([
                                                                    'mark' => date('Y-m-d H:i:s'),
                                                                    'icon' => $icon ?? $item->icon,
                                                                    'back' => $back ?? $item->back,
                                                                    'date' => $request->get('date', $item->date),
                                                                    'open' => $request->get('open', $item->open),
                                                                    'stop' => $request->get('stop', $item->stop),
                                                                    'code' => trim($request->get('code', $item->code)),
                                                                    'path' => trim($request->get('path', $item->path)),
                                                                    'post' => trim($request->get('post', $item->post)),
                                                                    'name' => trim($request->get('name', $item->name)),
                                                                    'note' => trim($request->get('note', $item->note)),
                                                                    'tone' => trim($request->get('tone', $item->tone)),
                                                                    'coin' => intval($request->get('coin', $item->coin)),
                                                                    'land' => intval($request->get('land', $item->land)),
                                                                    'zone' => intval($request->get('zone', $item->zone)),
                                                                    'town' => intval($request->get('town', $item->town)),
                                                                    'lock' => intval($request->get('lock', $item->lock)),
                                                                    'once' => intval($request->get('once', $item->once)),
                                                                    'type' => intval($request->get('type', $item->type)),
                                                                    'rank' => intval($request->get('rank', $item->rank)),
                                                                    'link' => intval($request->get('link', $item->link)),
                                                                    'hook' => intval($request->get('hook', $item->hook)),
                                                                    'cost' => floatval($request->get('cost', $item->cost)),
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
                                                            'list' => ['town' => 'La opción no es válida.']
                                                        ], 400);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                                        'list' => ['zone' => 'La opción no es válida.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                                    'list' => ['land' => 'La opción no es válida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['coin' => 'La opción no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['link' => 'La opción no es válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['link' => 'La opción no es válida.']
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
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
                    case 'move':
                        $validator = Validator::make($request->all(), [
                            'spot' => 'required|array|min:2|max:2'
                        ], [
                            'spot.min' => 'La posición no es válida.',
                            'spot.max' => 'La posición no es válida.',
                            'spot.array' => 'La posición no es válida.',
                            'spot.required' => 'El campo es requerido.'
                        ]);

                        if (empty($validator->fails())) {
                            if (DB::table('donations')
                                  ->where('row', $item->row)
                                  ->update(['spot' => DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($part) {
                                return floatval($part);
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
			        	if (DB::table('donations')
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
    					if (DB::table('donations')
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
                        'echo' => 'nullable|in:0,1',
                        'done' => 'nullable|in:0,1,2',
                        'type' => 'required|in:1,2',
                        'name' => 'required|max:64',
                        'town' => 'required|max:64',
                        'load' => 'required|numeric',
                        'date' => 'required|in:0,1,2,3,4,5,6,7',
                        'mail' => 'nullable|email|max:64',
                        'code' => 'nullable|regex:/^[a-zA-Z0-9]{2,16}$/',
                        'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
                        'spot' => 'nullable|array|min:2|max:2',
                        'phone' => 'nullable|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/',
                        'mobile' => 'nullable|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/',
                        'address' => 'required|max:32',
                        'district' => 'nullable|max:64',
                        'indication' => 'nullable|max:128'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'echo.in' => 'El campo no es válido.',
                        'done.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'spot.min' => 'El campo no es válido.',
                        'spot.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'town.max' => 'El campo no es válido.',
                        'address.max' => 'El campo no es válido.',
                        'district.max' => 'El campo no es válido.',
                        'indication.max' => 'El campo no es válido.',
                        'code.email' => 'El campo no es válido.',
                        'code.regex' => 'El campo no es válido.',
                        'tone.regex' => 'El campo no es válido.',
                        'phone.regex' => 'El campo no es válido.',
                        'mobile.regex' => 'El campo no es válido.',
                        'spot.array' => 'El campo no es válido.',
                        'load.numeric' => 'El campo no es válido.',
                        'type.required' => 'El campo es requerido.',
                        'date.required' => 'El campo es requerido.',
                        'load.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'town.required' => 'El campo es requerido.',
                        'address.required' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('donations')
                                    ->where('hide', 0)
                                    ->where('code', ($code = $request->input('code') ?? hexdec(uniqid())))
                                    ->first())) {
                            if (($item = DB::table('donations')->insertGetId([
                                'code' => $code,
                                'mail' => trim($request->get('mail')),
                                'name' => trim($request->get('name')),
                                'town' => trim($request->get('town')),
                                'phone' => trim($request->get('phone')),
                                'mobile' => trim($request->get('mobile')),
                                'address' => trim($request->get('address')),
                                'district' => trim($request->get('district')),
                                'indication' => trim($request->get('indication')),
                                'type' => intval($request->get('type')),
                                'date' => intval($request->get('date')),
                                'echo' => intval($request->get('echo')),
                                'done' => intval($request->get('done')),
                                'lock' => intval($request->get('lock')),
                                'made' => ($made = date('Y-m-d H:i:s')),
                                'load' => floatval($request->get('load')),
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
    				$query = DB::table('donations')
                               ->where('hide', 0);
                    
                    if (($seek = trim($request->get('seek')))) {
                        foreach (array_slice(explode(',', $seek), 0, 20) as $part) {
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
                            $query->where(function ($query) use ($seek) {
                                $query->where('code', 'like', sprintf('%%%s%%', $seek))
                                      ->orWhere('code', 'like', sprintf('%%%s%%', $seek))
                                      ->orWhere('name', 'like', sprintf('%%%s%%', $seek))
                                      ->orWhere('town', 'like', sprintf('%%%s%%', $seek))
                                      ->orWhere('address', 'like', sprintf('%%%s%%', $seek));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('*', DB::raw('ST_AsText(spot) AS spot'), DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'address' => $item->address,
                            'mobile' => $item->mobile,
                            'phone' => $item->phone,
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'load' => floatval($item->load),
                            'done' => intval($item->done),
                            'echo' => intval($item->echo),
                            'date' => intval($item->date),
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'town' => $item->town,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('donations')
		                       ->where('hide', 0);

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
                            'address' => $item->address,
                            'mobile' => $item->mobile,
                            'phone' => $item->phone,
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : [null, null],
                            'load' => floatval($item->load),
                            'lock' => intval($item->lock),
                            'done' => intval($item->done),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'town' => $item->town,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/donations', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}