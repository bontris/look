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

class GainController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('gains')
                           ->where('gains.hide', 0)
                           ->where('gains.hash', $item)
                           ->join('works', function ($join) {
                            $join->on('gains.bind', 'works.row')
                                 ->where('works.bind', Auth::user()->bind);
                           })
                           ->select('gains.*')
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'sort' => 'required|in:1,2',
                            'mode' => 'nullable|in:0,1',
                            'size' => 'required_if:mode,0|nullable|integer|min:0',
                            'rate' => 'required|integer|min:0|max:100',
                            'name' => 'required|max:64',
                            'link' => 'required|integer',
                            'time' => 'required|integer',
                            'cost' => 'nullable|numeric',
                            'note' => 'nullable|max:256',
                            'coin' => 'nullable|regex:/^[A-Z]{3}$/',
                            'code' => 'nullable|regex:/^[0-9A-Z]{2,16}$/i',
                            'unit' => 'required_if:mode,0|nullable|regex:/^[A-Z]{1,2}[2-3]?(\\/[A-Z]{1,2}[2-3]?)?$/i'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'sort.in' => 'El campo no es válido.',
                            'mode.in' => 'El campo no es válido.',
                            'size.min' => 'El campo no es válido.',
                            'rate.min' => 'El campo no es válido.',
                            'rate.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'code.regex' => 'El campo no es válido.',
                            'coin.regex' => 'El campo no es válido.',
                            'unit.regex' => 'El campo no es válido.',
                            'size.integer' => 'El campo no es válido.',
                            'rate.integer' => 'El campo no es válido.',
                            'time.integer' => 'El campo no es válido.',
                            'link.integer' => 'El campo no es válido.',
                            'cost.numeric' => 'El campo no es válido.',
                            'sort.required' => 'El campo es requerido.',
                            'link.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'size.required_if' => 'El campo es requerido.',
                            'unit.required_if' => 'El campo es requerido.'
                        ]);

			            if (empty($validator->fails())) {
                            if (($link = DB::table('works')
                                           ->where('row', intval($request->get('link', $item->link)))
                                           ->where('hide', 0)
                                           ->where('type', 1)
                                           ->where('bind', Auth::user()->bind)
                                           ->first())) {
                                if ((empty(($same = DB::table('steps')
                                                      ->where('hide', 0)
                                                      ->where('bind', Auth::user()->bind)
                                                      ->where('code', trim($request->input('code', $item->code)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($same = DB::table('steps')
                                                          ->where('hide', 0)
                                                          ->where('bind', Auth::user()->bind)
                                                          ->where('name', trim($request->input('name', $item->name)))
                                                          ->first())) || ($item->row == $same->row))) {
                                        if ((true)) {
                                            if (DB::table('gains')->where('row', $item->row)->update([
                                                'mark' => date('Y-m-d H:i:s'),
                                                'code' => trim($request->get('code', $item->code)),
                                                'coin' => trim($request->get('coin', $item->coin)),
                                                'unit' => trim($request->get('unit', $item->unit)),
                                                'name' => trim($request->get('name', $item->name)),
                                                'note' => trim($request->get('note', $item->note)),
                                                'lock' => intval($request->get('lock', $item->lock)),
                                                'sort' => intval($request->get('sort', $item->sort)),
                                                'mode' => intval($request->get('mode', $item->mode)),
                                                'link' => intval($request->get('link', $item->link)),
                                                'time' => intval($request->get('time', $item->time)),
                                                'size' => intval($request->get('size', $item->size)),
                                                'rate' => intval($request->get('rate', $item->rate)),
                                                'cost' => floatval($request->get('cost', $item->cost))
                                            ])) {
                                                return response()->json([
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
                                                'list' => ['rate' => 'La opción no es válida.']
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
                    case 'pass':
                        $validator = Validator::make($request->all(), [
                            'pass' => 'required|in:1,2',
                            'more' => 'nullable|max:1024'
                        ], [
                            'pass.in' => 'El campo no es válido.',
                            'more.max' => 'El campo no es válido.',
                            'pass.required' => 'El campo es requerido.'
                        ]);

                        if (empty($validator->fails())) {
                            if ((intval($request->get('pass')) == 1)) {
                                if (($work = DB::table('works')
                                               ->where('works.row', $item->bind)
                                               ->first())) {
                                    if (($step = DB::table('steps')
                                                    ->where('hide', 0)
                                                    ->where('row', $item->next)
                                                    ->first())) {
                                        if ((boolval($work->once) || ($unit = DB::table('units')
                                                                                ->where('units.row', $item->pick)
                                                                                ->first()))) {
                                            if (((($load = DB::table('gains')
                                                             ->where('hide', 0)
                                                             ->where('pass', 1)
                                                             ->where('bind', $item->bind)
                                                             ->where('pick', $item->pick)
                                                             ->where('next', $item->next)
                                                             ->sum('load') + floatval($item->load))) <= ((intval($step->mode) == 1) ? floatval($step->size) : 100))) {
                                                if ((empty(intval($step->back)) || ((isset($unit) ? $unit->gain : $work->gain) == $step->row) || ((isset($unit) ? $unit->gain : $work->gain) == $step->back))) {
                                                    if (DB::table('gains')
                                                          ->where('row', $item->row)
                                                          ->update(['pass' => 1, 'chop' => date('Y-m-d'), 'more' => ($more = trim($request->get('more')))])) {
                                                        if ((isset($unit) ? DB::table('units')
                                                                              ->where('row', $unit->row)
                                                                              ->update([
                                                            'gain' => $step->row,
                                                            'mark' => date('Y-m-d H:i:s'),
                                                            'rate' => min((((intval($step->mode) == 1) ? floatval($step->size) : 100) == $load) ? ($unit->rate + $step->rate) : $unit->rate, 100)
                                                        ]) : DB::table('works')
                                                               ->where('row', $work->row)
                                                               ->update([
                                                            'gain' => $step->row,
                                                            'mark' => date('Y-m-d H:i:s'),
                                                            'rate' => min((((intval($step->mode) == 1) ? floatval($step->size) : 100) == $load) ? ($work->rate + $step->rate) : $work->rate, 100)
                                                        ]))) {
                                                            if ((empty((((intval($step->mode) == 1) ? floatval($step->size) : 100) == $load)) || empty(($time = DB::table('times')
                                                                                                                                                                  ->where('hide', 0)
                                                                                                                                                                  ->where('next', $item->next)
                                                                                                                                                                  ->where('bind', $item->bind)
                                                                                                                                                                  ->where('pick', $item->pick)
                                                                                                                                                                  ->first())) || DB::table('times')
                                                                                                                                                                                 ->where('row', $time->row)
                                                                                                                                                                                 ->update([
                                                                                                                                                                                    'date' => $item->date,
                                                                                                                                                                                    'mark' => date('Y-m-d H:i:s')
                                                                                                                                                                                ]))) {
                                                                return response()->json([
                                                                    'text' => 'El avance fue actualizado correctamente.'
                                                                ], 200);
                                                            } else {
                                                                return response()->json([
                                                                    'text' => 'No se pudo actualizar el avance en la unidad correctamente.'
                                                                ], 500);
                                                            }
                                                        } else {
                                                            return response()->json([
                                                                'text' => 'No se pudo actualizar el avance en la unidad correctamente.'
                                                            ], 500);
                                                        }
                                                    } else {
                                                        return response()->json([
                                                            'text' => 'El avance no pudo ser actualizado correctamente.'
                                                        ], 500);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'La fase del avance no sigue el orden correcto.'
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'El avance supera el valor máximo de la fase.'
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'No se pudo encontrar la unidad relacionada.'
                                            ], 404);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'No se pudo encontrar la fase relacionada.'
                                        ], 404);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'No se pudo encontrar el proyecto relacionado.'
                                    ], 404);
                                }
                            } else {
                                if (DB::table('gains')
                                      ->where('row', $item->row)
                                      ->update([
                                        'pass' => 2,
                                        'chop' => date('Y-m-d'),
                                        'more' => ($more = trim($request->get('more')))
                                      ])) {
                                    return response()->json([
                                        'text' => 'El registro fue actualizado correctamente.'
                                    ], 200);
                                } else {
                                    return response()->json([
                                        'text' => 'El registro no pudo ser actualizado correctamente.'
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
			        	if (DB::table('gains')
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
    					if (DB::table('gains')
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
                    case 'data':
                        if (($data = DB::table('gains')
                                       ->where('gains.row', $item->row)
                                       ->join('works', 'works.row', 'gains.bind')
                                       ->leftJoin('users', 'users.id', 'gains.skip')
                                       ->leftJoin('units', 'units.row', 'gains.pick')
                                       ->leftJoin('steps', 'steps.row', 'gains.next')
                                       ->leftJoin('chips', 'chips.row', 'steps.unit')
                                       ->leftJoin('chips AS towns', 'towns.row', 'works.town')
                                       ->leftJoin('chips AS zones', 'zones.row', 'towns.link')
                                       ->select(
                                        'gains.*',
                                        'steps.mode',
                                        'steps.rate',
                                        'steps.size',
                                        'works.cost',
                                        'units.code',
                                        'units.name',
                                        DB::raw('works.name AS `work`'),
                                        DB::raw('steps.name AS `step`'),
                                        DB::raw('chips.code AS `unit`'),
                                        DB::raw('towns.name AS `town`'),
                                        DB::raw('zones.name AS `zone`'),
                                        DB::raw('ST_AsText(gains.spot) AS `spot`'),
                                        DB::raw('CONCAT(`users`.`name`, " ", `users`.`last`) AS skip'),
                                        DB::raw(sprintf("CONVERT_TZ(gains.mark, '%s', '%s') AS `mark`", date_default_timezone_get(), env('APP_TIME', '-05:00'))),
                                        DB::raw(sprintf("CONVERT_TZ(gains.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                       )->first())) {
                            return response()->json([
                                'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $data->spot, $data->spot) ? [floatval($data->spot[1]), floatval($data->spot[3])] : null,
                                'disk' => json_decode($data->disk),
                                'cost' => floatval($data->cost),
                                'load' => floatval($data->load),
                                'lock' => intval($data->lock),
                                'rate' => intval($data->rate),
                                'pass' => intval($data->pass),
                                'mode' => intval($data->mode),
                                'size' => intval($data->size),
                                'item' => intval($data->row),
                                'hash' => $data->hash,
                                'code' => $data->code,
                                'name' => $data->name,
                                'work' => $data->work,
                                'town' => $data->town,
                                'zone' => $data->zone,
                                'unit' => $data->unit,
                                'step' => $data->step,
                                'skip' => $data->skip,
                                'note' => $data->note,
                                'more' => $data->more,
                                'date' => $data->date,
                                'mark' => $data->mark,
                                'made' => $data->made
                            ], 200);
                        } else {
                            return response()->json([
                                'text' => 'No se pudo obtener los detalles del registro.'
                            ], 404);
                        }
    				default:
                        array_walk($item, function (&$item, $name) {
                            switch ($name) {
                                case 'disk':
                                    $item = json_decode($item, true);
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
                        'bind' => 'required|integer',
                        'pick' => 'nullable|integer',
                        'next' => 'required|integer',
                        'note' => 'nullable|max:1024',
                        'load' => 'required|numeric|min:0',
                        'spot' => 'nullable|array|min:2|max:2',
                        'date' => 'required|date_format:Y-m-d',
                        'code' => 'nullable|regex:/^[a-zA-Z0-9]{2,16}$/'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'spot.min' => 'El campo no es válido.',
                        'spot.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'code.regex' => 'El campo no es válido.',
                        'spot.array' => 'El campo no es válido.',
                        'bind.integer' => 'El campo no es válido.',
                        'pick.integer' => 'El campo no es válido.',
                        'next.integer' => 'El campo no es válido.',
                        'load.numeric' => 'El campo no es válido.',
                        'next.required' => 'El campo es requerido.',
                        'bind.required' => 'El campo es requerido.',
                        'load.required' => 'El campo es requerido.',
                        'date.required' => 'El campo es requerido.',
                        'date.date_format' => 'El campo no es válido.',
                    ]);

		            if (empty($validator->fails())) {
		            	if (($bind = DB::table('works')
                                       ->where('works.hide', 0)
                                       ->where('works.type', 1)
                                       ->where('works.bind', Auth::user()->bind)
                                       ->where('works.row', intval($request->get('bind')))
                                       ->leftJoin('steps', 'steps.row', 'works.gain')
                                       ->select('works.row', 'works.once', 'works.rate', 'works.gain', 'steps.mode', 'steps.size')
                                       ->first())) {
                            if (($next = DB::table('steps')
                                          ->where('hide', 0)
                                          ->where('bind', $bind->row)
                                          ->where('row', intval($request->get('next')))
                                          ->first())) {
                                if ((boolval($bind->once) || ($pick = DB::table('units')
                                                                        ->where('units.hide', 0)
                                                                        ->where('units.bind', $bind->row)
                                                                        ->where('units.row', intval($request->get('pick')))
                                                                        ->leftJoin('steps', 'steps.row', 'units.gain')
                                                                        ->select('units.row', 'units.rate', 'units.gain', 'steps.mode', 'steps.size')
                                                                        ->first()))) {
                                    if (((($load = DB::table('gains')
                                                     ->where('hide', 0)
                                                     ->where(function ($query) {
                                                        $query->where('pass', 0)
                                                              ->orWhere('pass', 1);
                                                     })
                                                     ->where('next', intval($request->get('next')))
                                                     ->where('bind', intval($request->get('bind')))
                                                     ->where('pick', intval($request->get('pick')))
                                                     ->sum('load') + floatval($request->get('load')))) <= ((intval($next->mode) == 1) ? floatval($next->size) : 100))) {
                                        if (empty(DB::table('gains')
                                                    ->where('hide', 0)
                                                    ->where('bind', $bind->row)
                                                    ->where('code', ($code = trim($request->input('code') ?? hexdec(uniqid()))))
                                                    ->first())) {
                                            if (is_array(($disk = (function ($list, $file, $data) {
                                                foreach ($data as $next => $item) {
                                                    if (isset($file[$next])) {
                                                        if (empty(($validator = Validator::make(['file' => $file[$next]], [
                                                            'file' => sprintf('mimetypes:%s|max:%d', [
                                                                1 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf',
                                                                2 => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
                                                                3 => 'application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint',
                                                                4 => 'drawing/x-dwf,image/x-dwg,image/x-dxf',
                                                                5 => 'image/jpeg,image/png',
                                                                6 => 'video/mpeg,video/mp4',
                                                                7 => 'audio/mpeg'][$item['type']
                                                            ], [
                                                                1 => 6144,
                                                                2 => 2048,
                                                                3 => 8192,
                                                                4 => 16384,
                                                                5 => 8192,
                                                                6 => 32768,
                                                                7 => 6144
                                                            ][$item['type']])
                                                            ], [
                                                                'file.max' => 'La imágen no puede pesar más de 5 Mb.',
                                                                'file.required' => 'El campo es requerido.',
                                                                'file.mimetypes' => 'El campo debe ser una imágen válida.'
                                                            ]))->fails())) {
                                                            if (($file[$next]->move(storage_path('files'), ($hash = md5(uniqid(rand(), true)))) && DB::table('files')
                                                                                                                                                     ->insertGetId([
                                                                                                                                                        'hash' => $hash,
                                                                                                                                                        'skip' => Auth::user()->id,
                                                                                                                                                        'bind' => Auth::user()->bind,
                                                                                                                                                        'made' => date('Y-m-d H:i:s'),
                                                                                                                                                        'size' => 0,//$request->file('file')->getSize(),
                                                                                                                                                        'type' => $file[$next]->getClientMimeType(),
                                                                                                                                                        'name' => $file[$next]->getClientOriginalName()]))) {
                                                                array_push($list, [
                                                                    'name' => $file[$next]->getClientOriginalName(),
                                                                    'type' => $file[$next]->getClientMimeType(),
                                                                    'item' => $item['name'],
                                                                    'hash' => $hash
                                                                ]);
                                                            } else {
                                                                return false;
                                                            }
                                                        } else {
                                                            return false;
                                                        }
                                                    } else {
                                                        if (boolval($item['bind'])) {
                                                            return false;
                                                        }
                                                    }
                                                }

                                                return $list;
                                                
                                            })([], $request->file('disk', []), json_decode($next->disk, true) ?? [])))) {
                                                if (($item = DB::table('gains')->insertGetId([
                                                    'code' => $code,
                                                    'skip' => Auth::user()->id,
                                                    'disk' => json_encode($disk),
                                                    'date' => trim($request->get('date')),
                                                    'note' => trim($request->get('note')),
                                                    'bind' => intval($request->get('bind')),
                                                    'pick' => intval($request->get('pick')),
                                                    'next' => intval($request->get('next')),
                                                    'lock' => intval($request->get('lock')),
                                                    'load' => floatval($request->get('load')),
                                                    'made' => ($made = date('Y-m-d H:i:s')),
                                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                                    'spot' => DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                        return floatval($item);
                                                    }, $request->get('spot', [])))))
                                                ]))) {
                                                    return response()->json([
                                                        'text' => 'El registro fue guardado con éxito.',
                                                        'made' => $made,
                                                        'item' => $item,
                                                        'code' => $code,
                                                        'hash' => $hash
                                                    ], 200);
                                                } else {
                                                    return response()->json([
                                                        'text' => 'El registro no pudo ser guardado.'
                                                    ], 500);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Se presentó un error interno.',
                                                    'list' => ['file' => 'Uno o más archivos no son válidos o no pudieron ser cargados correctamente.']
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
                                            'list' => ['load' => 'El valor especificado supera el máximo de la fase.','data'=>$load]
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['unit' => 'La opción no es válida.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['step' => 'La opción no es válida.']
                                ], 400);
                            }
		            	} else {
                            return response()->json([
                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                'list' => ['work' => 'La opción no es válida.']
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
    				$query = DB::table('gains')
                               ->where('gains.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('gains.bind', 'works.row')
                                     ->where('works.bind', Auth::user()->bind);
                               })
                               ->leftJoin('units', 'units.row', 'gains.pick')
                               ->leftJoin('steps', 'steps.row', 'gains.next')
                               ->leftJoin('chips', 'chips.row', 'steps.unit')
                               ->leftJoin('users', 'users.id', 'gains.skip')
                               ->leftJoin('chips AS towns', 'towns.row', 'works.town')
                               ->leftJoin('chips AS zones', 'zones.row', 'towns.link');
                    
                    if (($seek = trim($request->get('seek')))) {
                        $match = [];

                        $rules = [];

                        $names = [];

                        $texts = [];

                        $dates = [];

                        $items = [];

                        $codes = [];
                        
                        foreach (array_slice(explode(',', $seek), 0, 20) as $part) {
                            if (($part = trim($part))) {
                                if (preg_match('%^(?P<name>\w+):(\s+)?(?P<sign>[!<=>])?(?P<data>.+)$%', $part, $data)) {
                                    if (($part = trim($data['data']))) {
                                      $rules[strtolower($data['name'])] = ['sign' => $data['sign'], 'data' => $part];
                                    }
                                } else {
                                    if (preg_match('/^[0-9]{1,2}\\/[0-9]{1,2}\\/[0-9]{4}$/', $part)) {
                                      $dates[] = date('Y-m-d', strtotime(strtr($part, '/', '-')));
                                    } else {
                                        if (preg_match('/^@\w+$/i', $part)) {
                                            $names[] = strtolower(substr($part, 1));
                                        } else {
                                            if (preg_match('/^#(?P<item>\d+)$/', $part, $data)) {
                                              $items[] = intval($data['item']);
                                            } else {
                                                if (preg_match('/^\{(?P<code>[\w]{32})\}$/', $part, $data)) {
                                                    $codes[] = $data['code'];
                                                } else {
                                                    $texts[] = $part;
                                                }
                                            }
                                        }
                                    }
                                }
                            } 
                        }

                        foreach ($rules as $name => $item) {
                            switch ($name) {
                                case 'date':
                                    switch (($data = strtoupper($item['data']))) {
                                        case 'YR':
                                            $query->where(DB::raw('YEAR(gains.made)'), date('Y', time()));
                                            break;
                                        case 'MH':
                                            $query->where(DB::raw('MONTH(gains.made))'), date('m', time()));
                                            break;
                                        case 'WK':
                                            $query->whereBetween(DB::raw('DATE(gains.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                            break;
                                        case 'DY':
                                            $query->where(DB::raw('DAY(gains.made))'), date('d', time()));
                                            break;
                                        case 'NW':
                                            $query->where(function ($query) {
                                                $query->where(DB::raw('DAY(gains.made))'), date('d', time()))
                                                        ->where(DB::raw('HOUR(gains.made))'), date('H', time()));
                                            });
                                            break;
                                        default:
                                            if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                $query->whereBetween(DB::raw('DATE(gains.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                            } else {
                                                $query->where(DB::raw('DATE(gains.made))'), date('Y-m-d', strtotime($data[0])));
                                            }
                                    }
                                    break;
                                case 'pass':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('gains.pass', intval($data));
                                            } else {
                                                $query->orWhere('gains.pass', intval($data));
                                            }
                                        }
                                    });
                                    break;
                                case 'lock':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('gains.lock', intval($data));
                                            } else {
                                                $query->orWhere('gains.lock', intval($data));
                                            }
                                        }
                                    });
                                    break;
                                case 'work':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('works.row', intval($data));
                                            } else {
                                                $query->orWhere('works.row', intval($data));
                                            }
                                        }
                                    });
                                    break;
                                case 'unit':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('units.row', intval($data));
                                            } else {
                                                $query->orWhere('units.row', intval($data));
                                            }
                                        }
                                    });
                                    break;
                                case 'step':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('steps.row', intval($data));
                                            } else {
                                                $query->orWhere('steps.row', intval($data));
                                            }
                                        }
                                    });
                                    break;
                            }
                        }

                        if (count($items)) {
                            $query->where(function ($query) use ($items) {
                                foreach ($items as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('gains.row', $data);
                                    } else {
                                        $query->orWhere('gains.row', $data);
                                    }
                                }
                            });
                        }

                        if (count($codes)) {
                            $query->where(function ($query) use ($codes) {
                                foreach ($codes as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('gains.hash', $data);
                                    } else {
                                        $query->orWhere('gains.hash', $data);
                                    }
                                }
                            });
                        }
    
                        if (count($dates)) {
                            $query->where(function ($query) use ($dates) {
                                foreach ($dates as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('gains.date', $data);
                                    } else {
                                        $query->orWhere('gains.date', $data);
                                    }
                                }
                            });
                        }
    
                        if (count($texts)) {
                            $query->where(function ($query) use ($texts) {
                                foreach ($texts as $item => $data) {
                                    if (empty($item)) {
                                        $query->where(function ($query) use ($data) {
                                            $query->where('gains.code', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('units.code', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('units.name', 'like', sprintf('%%%s%%', $data));
                                        });
                                    } else {
                                        $query->orWhere(function ($query) use ($data) {
                                            $query->where('gains.code', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('units.code', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('units.name', 'like', sprintf('%%%s%%', $data));;
                                        });
                                    }
                                }
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select(
                                                                            'gains.*',
                                                                            'steps.mode',
                                                                            'steps.rate',
                                                                            'steps.size',
                                                                            'works.cost',
                                                                            'units.code',
                                                                            'units.name',
                                                                            DB::raw('works.name AS `work`'),
                                                                            DB::raw('steps.name AS `step`'),
                                                                            DB::raw('chips.code AS `unit`'),
                                                                            DB::raw('towns.name AS `town`'),
                                                                            DB::raw('zones.name AS `zone`'),
                                                                            DB::raw('ST_AsText(gains.spot) AS `spot`'),
                                                                            DB::raw('CONCAT(`users`.`name`, " ", `users`.`last`) AS skip'),
                                                                            DB::raw(sprintf("CONVERT_TZ(gains.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                                                          )
                                                                          ->orderBy('gains.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'cost' => floatval($item->cost),
                            'load' => floatval($item->load),
                            'lock' => intval($item->lock),
                            'rate' => intval($item->rate),
                            'pass' => intval($item->pass),
                            'mode' => intval($item->mode),
                            'size' => intval($item->size),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'work' => $item->work,
                            'town' => $item->town,
                            'zone' => $item->zone,
                            'unit' => $item->unit,
                            'step' => $item->step,
                            'skip' => $item->skip,
                            'date' => $item->date,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('gains')
		                       ->where('gains.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('gains.bind', 'works.row')
                                     ->where('works.bind', Auth::user()->bind);
                               });

                    if (($bind = intval($request->get('bind')))) {
                        $query->where('bind', $bind);
                    }

			        return response()->json(['size' => ($size = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
			                                 'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $size))
                                                                          ->select('gains.*', DB::raw('ST_AsText(gains.spot) AS spot'))
			                                                              ->orderBy('gains.made', 'asc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : [null, null],
                            'disk' => json_decode($item->disk),
                            'bond' => floatval($item->bond),
                            'load' => floatval($item->load),
                            'lock' => intval($item->lock),
                            'pass' => intval($item->pass),
                            'bind' => intval($item->bind),
                            'pick' => intval($item->pick),
                            'next' => intval($item->next),
                            'skip' => intval($item->skip),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'note' => $item->note,
                            'more' => $item->more,
                            'date' => $item->date,
                            'mark' => $item->mark,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/gains', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}