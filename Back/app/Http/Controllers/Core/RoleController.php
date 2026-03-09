<?php

namespace App\Http\Controllers\Core;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('steps')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->where('bind', Auth::user()->bind)
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
                            'join' => 'required|integer',
                            'time' => 'required|integer',
                            'cost' => 'nullable|numeric',
                            'mask' => 'nullable|max:64',
                            'note' => 'nullable|max:256',
                            'coin' => 'nullable|regex:/^[A-Z]{3}$/',
                            'tone' => 'nullable|regex:/^(?:[0-9A-F]{3}){1,2}$/i',
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
                            'mask.max' => 'El campo no es válido.',
                            'coin.regex' => 'El campo no es válido.',
                            'unit.regex' => 'El campo no es válido.',
                            'tone.regex' => 'El campo no es válido.',
                            'size.integer' => 'El campo no es válido.',
                            'rate.integer' => 'El campo no es válido.',
                            'time.integer' => 'El campo no es válido.',
                            'join.integer' => 'El campo no es válido.',
                            'cost.numeric' => 'El campo no es válido.',
                            'sort.required' => 'El campo es requerido.',
                            'join.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'size.required_if' => 'El campo es requerido.',
                            'unit.required_if' => 'El campo es requerido.'
                        ]);

			            if (empty($validator->fails())) {
                            if (($join = DB::table('works')
                                           ->where('row', intval($request->get('join', $item->join)))
                                           ->where('hide', 0)
                                           ->where('type', 1)
                                           ->where('bind', Auth::user()->bind)
                                           ->first())) {
                                if ((empty(($same = DB::table('steps')
                                                      ->where('hide', 0)
                                                      ->where('join', $join->row)
                                                      ->where('bind', Auth::user()->bind)
                                                      ->where('name', trim($request->input('name', $item->name)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if (empty(((DB::table('steps')
                                                  ->where('hide', 0)
                                                  ->where('join', $join->row)
                                                  ->where('row', '<>', $item->row)
                                                  ->where('bind', Auth::user()->bind)
                                                  ->sum('rate') + intval($request->get('rate', $item->rate)) > 100)))) {
                                        if (DB::table('steps')->where('row', $item->row)->update([
                                            'mark' => date('Y-m-d H:i:s'),
                                            'code' => trim($request->get('code', $item->code)),
                                            'mask' => trim($request->get('mask', $item->mask)),
                                            'coin' => trim($request->get('coin', $item->coin)),
                                            'unit' => trim($request->get('unit', $item->unit)),
                                            'name' => trim($request->get('name', $item->name)),
                                            'note' => trim($request->get('note', $item->note)),
                                            'tone' => trim($request->get('tone', $item->tone)),
                                            'lock' => intval($request->get('lock', $item->lock)),
                                            'sort' => intval($request->get('sort', $item->sort)),
                                            'mode' => intval($request->get('mode', $item->mode)),
                                            'join' => intval($request->get('join', $item->join)),
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
                                            'list' => ['rate' => 'Se supera el 100% del avance del proyecto.']
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
                                    'list' => ['join' => 'La opción no es vealida.']
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
                        'sort' => 'required|in:1,2',
                        'mode' => 'nullable|in:0,1',
                        'size' => 'required_if:mode,0|nullable|integer|min:0',
                        'rate' => 'required|integer|min:0|max:100',
                        'name' => 'required|max:64',
                        'join' => 'required|integer',
                        'time' => 'required|integer',
                        'cost' => 'nullable|numeric',
                        'mask' => 'nullable|max:64',
                        'note' => 'nullable|max:256',
                        'coin' => 'nullable|regex:/^[A-Z]{3}$/',
                        'tone' => 'nullable|regex:/^(?:[0-9a-fA-F]{3}){1,2}$/',
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
                        'mask.max' => 'El campo no es válido.',
                        'coin.regex' => 'El campo no es válido.',
                        'unit.regex' => 'El campo no es válido.',
                        'tone.regex' => 'El campo no es válido.',
                        'size.integer' => 'El campo no es válido.',
                        'rate.integer' => 'El campo no es válido.',
                        'time.integer' => 'El campo no es válido.',
                        'join.integer' => 'El campo no es válido.',
                        'cost.numeric' => 'El campo no es válido.',
                        'sort.required' => 'El campo es requerido.',
                        'join.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'size.required_if' => 'El campo es requerido.',
                        'unit.required_if' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (($join = DB::table('works')
                                       ->where('hide', 0)
                                       ->where('type', 1)
                                       ->where('bind', Auth::user()->bind)
                                       ->where('row', intval($request->get('join')))
                                       ->first())) {
                            if (empty(DB::table('steps')
                                        ->where('hide', 0)
                                        ->where('bind', Auth::user()->bind)
                                        ->where('code', ($code = hexdec(uniqid())))
                                        ->first())) {
                                if (empty(DB::table('steps')
                                            ->where('hide', 0)
                                            ->where('join', $join->row)
                                            ->where('bind', Auth::user()->bind)
                                            ->where('name', trim($request->input('name')))
                                            ->first())) {
                                    if (empty(((DB::table('steps')
                                                  ->where('hide', 0)
                                                  ->where('join', $join->row)
                                                  ->where('bind', Auth::user()->bind)
                                                  ->sum('rate') + intval($request->get('rate')) > 100)))) {
                                        if (($item = DB::table('steps')->insertGetId([
                                            'code' => $code,
                                            'bind' => Auth::user()->bind,
                                            'mask' => trim($request->get('mask')),
                                            'unit' => trim($request->get('unit')),
                                            'coin' => trim($request->get('coin')),
                                            'name' => trim($request->get('name')),
                                            'note' => trim($request->get('note')),
                                            'lock' => intval($request->get('lock')),
                                            'sort' => intval($request->get('sort')),
                                            'mode' => intval($request->get('mode')),
                                            'size' => intval($request->get('size')),
                                            'time' => intval($request->get('time')),
                                            'rate' => intval($request->get('rate')),
                                            'join' => intval($request->get('join')),
                                            'made' => ($made = date('Y-m-d H:i:s')),
                                            'cost' => floatval($request->get('cost')),
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
                                            'list' => ['rate' => 'Se supera el 100% del avance del proyecto.']
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
                                'list' => ['join' => 'La opción no es vealida.']
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
    				$query = DB::table('roles')
                               ->where('roles.hide', 0)
                               ->where('roles.bind', Auth::user()->bind);
                    
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
                                                                          ->select('roles.*', DB::raw(sprintf("CONVERT_TZ(roles.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('roles.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'lock' => intval($item->lock),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('roles')
		                       ->where('hide', 0)
                               ->where('bind', Auth::user()->bind);

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
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'note' => $item->note,
                            'tone' => $item->tone
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/roles', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}