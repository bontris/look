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

class StepController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('steps')
                           ->where('steps.hide', 0)
                           ->where('steps.hash', $item)
                           ->join('works', function ($join) {
                            $join->on('steps.bind', 'works.row')
                                 ->where('works.bind', Auth::user()->bind);
                           })
                           ->select('steps.*')
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'pass' => 'nullable|in:0,1',
                            'spot' => 'nullable|in:0,1',
                            'sort' => 'required|in:1,2',
                            'mode' => 'nullable|in:1,2',
                            'size' => 'required_if:mode,1|nullable|numeric|min:0',
                            'rate' => 'required|integer|min:0|max:100',
                            'name' => 'required|max:64',
                            'bind' => 'required|integer',
                            'term' => 'required|integer',
                            'lead' => 'nullable|integer',
                            'back' => 'nullable|integer',
                            'unit' => 'required_if:mode,1|nullable|integer|min:1',
                            'cost' => 'nullable|numeric',
                            'bond' => 'nullable|numeric',
                            'mask' => 'nullable|max:64',
                            'note' => 'nullable|max:256',
                            'tone' => 'nullable|regex:/^(?:[0-9A-F]{3}){1,2}$/i'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'pass.in' => 'El campo no es válido.',
                            'pass.in' => 'El campo no es válido.',
                            'sort.in' => 'El campo no es válido.',
                            'mode.in' => 'El campo no es válido.',
                            'size.min' => 'El campo no es válido.',
                            'rate.min' => 'El campo no es válido.',
                            'unit.min' => 'El campo no es válido.',
                            'rate.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'mask.max' => 'El campo no es válido.',
                            'unit.regex' => 'El campo no es válido.',
                            'tone.regex' => 'El campo no es válido.',
                            'rate.integer' => 'El campo no es válido.',
                            'term.integer' => 'El campo no es válido.',
                            'lead.integer' => 'El campo no es válido.',
                            'bind.integer' => 'El campo no es válido.',
                            'back.integer' => 'El campo no es válido.',
                            'unit.integer' => 'El campo no es válido.',
                            'size.numeric' => 'El campo no es válido.',
                            'cost.numeric' => 'El campo no es válido.',
                            'bond.numeric' => 'El campo no es válido.',
                            'sort.required' => 'El campo es requerido.',
                            'bind.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'rate.required' => 'El campo es requerido.',
                            'size.required_if' => 'El campo es requerido.',
                            'unit.required_if' => 'El campo es requerido.'
                        ]);

			            if (empty($validator->fails())) {
                            if (($bind = DB::table('works')
                                           ->where('row', intval($request->get('bind', $item->bind)))
                                           ->where('hide', 0)
                                           ->where('type', 1)
                                           ->where('bind', Auth::user()->bind)
                                           ->first())) {
                                if ((empty(($same = DB::table('steps')
                                                      ->where('hide', 0)
                                                      ->where('bind', $bind->row)
                                                      ->where('name', trim($request->input('name', $item->name)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if (empty(((DB::table('steps')
                                                  ->where('hide', 0)
                                                  ->where('bind', $bind->row)
                                                  ->where('row', '<>', $item->row)
                                                  ->sum('rate') + intval($request->get('rate', $item->rate)) > 100)))) {
                                        if ((empty(intval($request->get('back', $item->back))) || (($back = DB::table('steps')
                                                                                                              ->where('hide', 0)
                                                                                                              ->where('bind', $bind->row)
                                                                                                              ->where('row', intval($request->get('back', $item->back)))
                                                                                                              ->first()) && empty(($item->row == $back->row))))) {
                                            if ((empty(($unit = intval($request->get('unit', $item->unit)))) || ($unit = DB::table('chips')
                                                                                                                           ->where('row', $unit)
                                                                                                                           ->where('hide', 0)
                                                                                                                           ->where('type', 7)
                                                                                                                           ->first()))) {
                                                if (is_array(($disk = json_decode(trim($request->get('disk', $item->disk)), true)))) {
                                                    if (is_array(($disk = (function ($next, $list, $data, &$fail) {
                                                        foreach ($data as $item) {
                                                            if (isset($item['name'])) {
                                                                if (empty(($validator = Validator::make($item ?? [], [
                                                                    'type' => 'required|in:1,2,3,4,5,6,7',
                                                                    'bind' => 'required|in:0,1',
                                                                    'name' => 'required|max:32'
                                                                ]))->fails())) {
                                                                    if (empty(array_filter($list, function ($some) use ($item) {
                                                                        return empty(strcasecmp($some['name'], $item['name']));
                                                                    }))) {
                                                                        array_push($list, $item);
                                                                    } else {
                                                                        return false;
                                                                    }
                                                                } else {
                                                                    return false;
                                                                }
                                                            }
                                                        }

                                                        return $list;
                                                    })(0, [], $disk, $fail)))) {
                                                        if (DB::table('steps')->where('row', $item->row)->update([
                                                            'disk' => json_encode($disk),
                                                            'mark' => date('Y-m-d H:i:s'),
                                                            'code' => trim($request->get('code', $item->code)),
                                                            'mask' => trim($request->get('mask', $item->mask)),
                                                            'name' => trim($request->get('name', $item->name)),
                                                            'note' => trim($request->get('note', $item->note)),
                                                            'tone' => trim($request->get('tone', $item->tone)),
                                                            'lock' => intval($request->get('lock', $item->lock)),
                                                            'pass' => intval($request->get('pass', $item->pass)),
                                                            'spot' => intval($request->get('spot', $item->spot)),
                                                            'sort' => intval($request->get('sort', $item->sort)),
                                                            'mode' => intval($request->get('mode', $item->mode)),
                                                            'bind' => intval($request->get('bind', $item->bind)),
                                                            'back' => intval($request->get('back', $item->back)),
                                                            'unit' => intval($request->get('unit', $item->unit)),
                                                            'term' => intval($request->get('term', $item->term)),
                                                            'lead' => intval($request->get('lead', $item->lead)),
                                                            'rate' => intval($request->get('rate', $item->rate)),
                                                            'size' => floatval($request->get('size', $item->size)),
                                                            'cost' => floatval($request->get('cost', $item->cost)),
                                                            'bond' => floatval($request->get('bond', $item->cost))
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
                                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                                            'list' => ['disk' => 'Los datos no son válidos.']
                                                        ], 400);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                                        'list' => ['disk' => 'Los datos no son válidos.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                                    'list' => ['unit' => 'La opción no es válida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                'list' => ['back' => 'La opción no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'list' => ['rate' => 'Se supera el 100% del avance del proyecto.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                        'list' => ['name' => 'El nombre ya existe.']
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
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
			        case 'lock':
			        	if (DB::table('steps')
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
    					if (DB::table('steps')
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
                        array_walk($item, function (&$item, $name) {
                            switch ($name) {
                                case 'disk':
                                    $item = empty(($item = json_decode($item, true))) ? [['name' => null, 'type' => null, 'bind' => null]] : $item;
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
                        'pass' => 'nullable|in:0,1',
                        'spot' => 'nullable|in:0,1',
                        'sort' => 'required|in:1,2',
                        'mode' => 'nullable|in:1,2',
                        'size' => 'required_if:mode,1|nullable|numeric|min:0',
                        'rate' => 'required|integer|min:0|max:100',
                        'name' => 'required|max:64',
                        'bind' => 'required|integer',
                        'term' => 'required|integer',
                        'lead' => 'nullable|integer',
                        'back' => 'nullable|integer',
                        'unit' => 'required_if:mode,1|nullable|integer|min:1',
                        'cost' => 'nullable|numeric',
                        'bond' => 'nullable|numeric',
                        'mask' => 'nullable|max:64',
                        'note' => 'nullable|max:256',
                        'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/'
                        
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'pass.in' => 'El campo no es válido.',
                        'spot.in' => 'El campo no es válido.',
                        'sort.in' => 'El campo no es válido.',
                        'mode.in' => 'El campo no es válido.',
                        'size.min' => 'El campo no es válido.',
                        'rate.min' => 'El campo no es válido.',
                        'unit.min' => 'El campo no es válido.',
                        'rate.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'mask.max' => 'El campo no es válido.',
                        'tone.regex' => 'El campo no es válido.',
                        'rate.integer' => 'El campo no es válido.',
                        'term.integer' => 'El campo no es válido.',
                        'lead.integer' => 'El campo no es válido.',
                        'bind.integer' => 'El campo no es válido.',
                        'back.integer' => 'El campo no es válido.',
                        'unit.integer' => 'El campo no es válido.',
                        'size.numeric' => 'El campo no es válido.',
                        'cost.numeric' => 'El campo no es válido.',
                        'bond.numeric' => 'El campo no es válido.',
                        'sort.required' => 'El campo es requerido.',
                        'bind.required' => 'El campo es requerido.',
                        'term.required' => 'El campo es requerido.',
                        'rate.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'size.required_if' => 'El campo es requerido.',
                        'unit.required_if' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (($bind = DB::table('works')
                                       ->where('hide', 0)
                                       ->where('type', 1)
                                       ->where('bind', Auth::user()->bind)
                                       ->where('row', intval($request->get('bind')))
                                       ->first())) {
                            if (empty(DB::table('steps')
                                        ->where('hide', 0)
                                        ->where('bind', $bind->row)
                                        ->where('code', ($code = $request->input('code') ?? hexdec(uniqid())))
                                        ->first())) {
                                if (empty(DB::table('steps')
                                            ->where('hide', 0)
                                            ->where('bind', $bind->row)
                                            ->where('name', trim($request->input('name')))
                                            ->first())) {
                                    if (empty(((DB::table('steps')
                                                  ->where('hide', 0)
                                                  ->where('bind', $bind->row)
                                                  ->sum('rate') + intval($request->get('rate')) > 100)))) {
                                        if ((empty(intval($request->input('back'))) || ($back = DB::table('steps')
                                                                                                  ->where('hide', 0)
                                                                                                  ->where('bind', $bind->row)
                                                                                                  ->where('row', intval($request->input('back')))
                                                                                                  ->first()))) {
                                            if ((empty(($unit = intval($request->get('unit')))) || ($unit = DB::table('chips')
                                                                                                              ->where('hide', 0)
                                                                                                              ->where('type', 7)
                                                                                                              ->where('row', $unit)
                                                                                                              ->first()))) {
                                                if (is_array(($disk = json_decode(trim($request->get('disk')), true)))) {
                                                    if (is_array(($disk = (function ($next, $list, $data, &$fail) {
                                                        foreach ($data as $item) {
                                                            if (isset($item['name'])) {
                                                                if (empty(($validator = Validator::make($item ?? [], [
                                                                    'type' => 'required|in:1,2,3,4,5,6,7',
                                                                    'bind' => 'required|in:0,1',
                                                                    'name' => 'required|max:32'
                                                                ]))->fails())) {
                                                                    if (empty(array_filter($list, function ($some) use ($item) {
                                                                        return empty(strcasecmp($some['name'], $item['name']));
                                                                    }))) {
                                                                        array_push($list, $item);
                                                                    } else {
                                                                        return false;
                                                                    }
                                                                } else {
                                                                    return false;
                                                                }
                                                            }
                                                        }

                                                        return $list;
                                                    })(0, [], $disk, $fail)))) {
                                                        if (($item = DB::table('steps')->insertGetId([
                                                            'code' => $code,
                                                            'disk' => json_encode($disk),
                                                            'mask' => trim($request->get('mask')),
                                                            'name' => trim($request->get('name')),
                                                            'note' => trim($request->get('note')),
                                                            'lock' => intval($request->get('lock')),
                                                            'pass' => intval($request->get('pass')),
                                                            'spot' => intval($request->get('spot')),
                                                            'sort' => intval($request->get('sort')),
                                                            'mode' => intval($request->get('mode')),
                                                            'term' => intval($request->get('term')),
                                                            'lead' => intval($request->get('lead')),
                                                            'rate' => intval($request->get('rate')),
                                                            'bind' => intval($request->get('bind')),
                                                            'back' => intval($request->get('back')),
                                                            'unit' => intval($request->get('unit')),
                                                            'made' => ($made = date('Y-m-d H:i:s')),
                                                            'size' => floatval($request->get('size')),
                                                            'cost' => floatval($request->get('cost')),
                                                            'bond' => floatval($request->get('bond')),
                                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                                            'tone' => ($tone = $request->input('tone') ?? ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
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
                                                            'list' => ['disk' => 'Los datos no son válidos.']
                                                        ], 400);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                                        'list' => ['disk' => 'Los datos no son válidos.']
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'Uno o más campos del formulario no son correctos.',
                                                    'list' => ['unit' => 'La opción no es válida.']
                                                ], 400);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                'list' => ['back' => 'La opción no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'list' => ['rate' => 'Se supera el 100% del avance del proyecto.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                        'list' => ['name' => 'El nombre ya existe.']
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
    			case 'load':
    				$query = DB::table('steps')
                               ->where('steps.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('steps.bind', 'works.row')
                                     ->where('works.bind', Auth::user()->bind);
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
                                                $query->where(DB::raw('YEAR(steps.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(steps.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(steps.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(steps.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(steps.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(steps.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(steps.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(steps.made))'), date('Y-m-d', strtotime($data[0])));
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
                                $query->where('steps.code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('steps.mask', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('steps.name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select(
                                                                            'steps.*',
                                                                            DB::raw('works.name AS `work`'),
                                                                            DB::raw(sprintf("CONVERT_TZ(steps.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                                                          )
                                                                          ->orderBy('steps.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'cost' => floatval($item->cost),
                            'bond' => floatval($item->bond),
                            'size' => floatval($item->size),
                            'lock' => intval($item->lock),
                            'sort' => intval($item->sort),
                            'term' => intval($item->term),
                            'lead' => intval($item->lead),
                            'rate' => intval($item->rate),
                            'pass' => intval($item->pass),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'unit' => $item->unit,
                            'work' => $item->work,
                            'name' => $item->name,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('steps')
		                       ->where('steps.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('steps.bind', 'works.row')
                                     ->where('works.bind', Auth::user()->bind);
                               });

                    if (($bind = intval($request->get('bind')))) {
                        $query->where('steps.bind', $bind);
                    }

			        return response()->json(['size' => ($size = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
			                                 'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $size))
			                                                              ->orderBy('steps.made', 'asc')
                                                                          ->select('steps.*')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'data' => json_decode($item->data),
                            'disk' => json_decode($item->disk),
                            'cost' => floatval($item->cost),
                            'bond' => floatval($item->bond),
                            'size' => floatval($item->size),
                            'lock' => intval($item->lock),
                            'sort' => intval($item->sort),
                            'mode' => intval($item->mode),
                            'term' => intval($item->term),
                            'lead' => intval($item->lead),
                            'rate' => intval($item->rate),
                            'bind' => intval($item->bind),
                            'back' => intval($item->back),
                            'spot' => intval($item->spot),
                            'unit' => intval($item->unit),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'note' => $item->note,
                            'tone' => $item->tone,
                            'mark' => $item->mark,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/steps', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}