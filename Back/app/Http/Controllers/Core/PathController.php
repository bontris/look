<?php

namespace App\Http\Controllers\Core;

use Auth;

use User;

use Validator;

use App\Models\Stop;

use App\Models\Path;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class PathController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = Path::with('list.gift')
                             ->where('hide', 0)
                             ->where('hash', $item)
                             ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'name' => 'required|max:64',
                            'pick' => 'nullable|integer',
                            'note' => 'nullable|max:256',
                            'list' => 'required|array|min:1',
                            'code' => 'sometimes|required|regex:/^[a-zA-Z0-9]{2,16}$/',
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'list.min' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'code.regex' => 'El campo no es válido.',
                            'list.array' => 'El campo no es válido.',
                            'pick.integer' => 'El campo no es válido.',
                            'code.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'list.required' => 'El campo es requerido.'
                        ]);

			            if (empty($validator->fails())) {
                            if ((empty(($same = DB::table('paths')
                                                  ->where('hide', 0)
                                                  ->where('code', trim($request->input('code', $item->code)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('paths')
                                                      ->where('hide', 0)
                                                      ->where('name', trim($request->input('name', $item->name)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(intval($request->input('pick', $item->pick))) || ($pick = DB::table('users')
                                                                                                           ->where('hide', 0)
                                                                                                           ->where('type', 3)
                                                                                                           ->where('id', intval($request->input('pick', $item->pick)))
                                                                                                           ->first()))) {
                                        if (count(($list = array_reduce($request->get('list'), function ($list, $hash) {
                                            if ((($pick = DB::table('donations')
                                                            ->where('hide', 0)
                                                            ->where('hash', $hash)
                                                            ->first()) && ((empty(intval($pick->hide)) && empty(intval($pick->lock)))))) {
                                                array_push($list, $pick->row);
                                            }
    
                                            return $list;
                                        }, [])))) {
                                            try {
                                                DB::beginTransaction();
                                                
                                                if (DB::table('paths')->where('row', $item->row)->update([
                                                    'mark' => ($mark = date('Y-m-d H:i:s')),
                                                    'code' => trim($request->get('code', $item->code)),
                                                    'name' => trim($request->get('name', $item->name)),
                                                    'note' => trim($request->get('note', $item->note)),
                                                    'pick' => intval($request->get('pick', $item->pick)),
                                                    'pass' => (empty(intval($item->pass)) ? isset($pick) : $item->pass)
                                                ])) {
                                                    foreach (array_reduce($item->list->toArray(), function ($data, $stop) use ($list) {
                                                        $skip = false;

                                                        foreach ($list as $pick) {
                                                            if (($stop['pick'] == $pick)) {
                                                                $skip = true;

                                                                break;
                                                            }
                                                        }

                                                        if (empty($skip)) {
                                                            array_push($data, $stop['row']);
                                                        }
                                                        
                                                        return $data;
                                                    }, []) as $drop) {
                                                        DB::table('stops')
                                                          ->where('row', $drop)
                                                          ->delete();
                                                    }

                                                    foreach (array_reduce($list, function ($data, $pick) use ($item) {
                                                        $skip = false;

                                                        foreach ($item->list as $stop) {
                                                            if (($stop->pick == $pick)) {
                                                                $skip = true;

                                                                break;
                                                            }
                                                        }

                                                        if (empty($skip)) {
                                                            array_push($data, $pick);
                                                        }
                                                        
                                                        return $data;
                                                    }, []) as $push) {
                                                        DB::table('stops')->insert([
                                                            'pick' => $push,
                                                            'bind' => $item->row,
                                                            'code' => hexdec(uniqid()),
                                                            'made' => date('Y-m-d H:i:s'),
                                                            'hash' => md5(uniqid(rand(), true))
                                                        ]);
                                                    }

                                                    DB::commit();

                                                    return response()->json([
                                                        'text' => 'El registro fue actualizado con éxito.',
                                                        'mark' => $mark
                                                    ], 200);
                                                } else {
                                                    return response()->json([
                                                        'text' => 'El registro no pudo ser actualizado.'
                                                    ], 500);
                                                }
                                            } catch (\Exception $exception) {
                                                DB::rollBack();
    
                                                Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));
    
                                                return response()->json([
                                                    'text' => 'El registro no pudo ser actualizado.','fail'=>$exception->getMessage()
                                                ], 500);
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'Uno o más campos del formulario no son correctos.',
                                                'list' => ['line' => 'La opción no es válida.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'list' => ['pick' => 'La opción no es válida.']
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
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
                    case 'pass':
                        $validator = Validator::make($request->all(), [
                            'pass' => 'required|in:1,2',
                            'note' => 'nullable|max:256'
                        ], [
                            'pass.in' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'done.required' => 'El campo es requerido.'
                        ]);

                        if (empty($validator->fails())) {
                            if (((intval($item->pass) == 1) || (intval($item->pass) == 3))) {
                                if (DB::table('paths')
                                    ->where('row', $item->row)
                                    ->update([
                                    'mark' => date('Y-m-d H:i:s'),
                                    'note' => trim($request->get('note')),
                                    'pass' => ($pass = intval($request->get('pass')) + 1)
                                ])) {
                                    return response()->json([
                                        'text' => 'El registro fue actualizado correctamente.',
                                        'pass' => $pass
                                    ], 200);
                                } else {
                                    return response()->json([
                                        'text' => 'No se pudo actualizar el registro correctamente.'
                                    ], 500);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'El registro no está en un estado válido.'
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
                        'name' => 'required|max:64',
                        'pick' => 'nullable|integer',
                        'note' => 'nullable|max:256',
                        'list' => 'required|array|min:1'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'list.min' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'list.array' => 'El campo no es válido.',
                        'pick.integer' => 'El campo no es válido.',
                        'name.required' => 'El campo es requerido.',
                        'list.required' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
                        if (empty(DB::table('paths')
                                    ->where('hide', 0)
                                    ->where('code', ($code = $request->input('code') ?? hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('paths')
                                        ->where('hide', 0)
                                        ->where('name', trim($request->input('name')))
                                        ->first())) {
                                if ((empty(intval($request->input('pick'))) || ($pick = DB::table('users')
                                                                                          ->where('hide', 0)
                                                                                          ->where('type', 3)
                                                                                          ->where('id', intval($request->input('pick')))
                                                                                          ->first()))) {
                                    if (count(($list = array_reduce($request->get('list'), function ($list, $hash) {
                                        if ((($pick = DB::table('donations')
                                                        ->where('hide', 0)
                                                        ->where('hash', $hash)
                                                        ->first()) && ((empty(intval($pick->hide)) && empty(intval($pick->lock)))))) {
                                            array_push($list, $pick->row);
                                        }

                                        return $list;
                                    }, [])))) {
                                        try {
                                            DB::beginTransaction();

                                            if (($item = DB::table('paths')->insertGetId([
                                                'code' => $code,
                                                'pass' => isset($pick),
                                                'name' => trim($request->get('name')),
                                                'note' => trim($request->get('note')),
                                                'lock' => intval($request->get('lock')),
                                                'pass' => intval($request->get('pass')),
                                                'pick' => intval($request->get('pick')),
                                                'made' => ($made = date('Y-m-d H:i:s')),
                                                'hash' => ($hash = md5(uniqid(rand(), true))),
                                                'tone' => ($tone = $request->input('tone') ?? ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                            ]))) {
                                                foreach ($list as $gift) {
                                                    DB::table('stops')
                                                      ->insert(['bind' => $item,
                                                                'pick' => $gift,
                                                                'code' => hexdec(uniqid()),
                                                                'made' => date('Y-m-d H:i:s'),
                                                                'hash' => md5(uniqid(rand(), true))]);
                                                }
    
                                                DB::commit();
    
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
                                        } catch (\Exception $exception) {
                                            DB::rollBack();

                                            Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));

                                            return response()->json([
                                                'text' => 'El registro no pudo ser guardado.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'list' => ['line' => 'La opción no es válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o más campos del formulario no son correctos.',
                                        'list' => ['pick' => 'La opción no es válida.']
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
                            'list' => array_map(function ($item) {
                                return current($item);
                            }, $validator->errors()->toArray())
                        ], 400);
		            }
    			case 'load':
    				$query = DB::table('paths')
                               ->where('paths.hide', 0)
                               ->leftJoin('users', 'users.id', 'paths.pick');
                    
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
                                                $query->where(DB::raw('YEAR(paths.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(paths.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(paths.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(paths.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(paths.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(paths.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(paths.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(paths.made))'), date('Y-m-d', strtotime($data[0])));
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
                                $query->where('paths.code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('paths.name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select(
                                                                            'paths.*',
                                                                            DB::raw('CONCAT(`users`.`name`, " ", `users`.`last`) AS pick'),
                                                                            DB::raw('(SELECT COUNT(*) FROM `stops` WHERE `stops`.`hide` = 0 AND `stops`.`bind` = `paths`.`row`) AS `size`'),
                                                                            DB::raw(sprintf("CONVERT_TZ(paths.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))),
                                                                            DB::raw('(SELECT COUNT(*) FROM `stops` WHERE `stops`.`hide` = 0 AND NOT `stops`.`done` = 0 AND `stops`.`bind` = `paths`.`row`) AS `done`'),
                                                                            DB::raw('(SELECT SUM(`donations`.`load`) FROM `stops` JOIN `donations` ON `donations`.`row` = `stops`.`pick` AND `donations`.`type` = 1 WHERE `stops`.`hide` = 0 AND `stops`.`bind` = `paths`.`row`) AS `rate`'),
                                                                            DB::raw('(SELECT SUM(`donations`.`load`) FROM `stops` JOIN `donations` ON `donations`.`row` = `stops`.`pick` AND `donations`.`type` = 2 WHERE `stops`.`hide` = 0 AND `stops`.`bind` = `paths`.`row`) AS `load`')
                                                                          )
                                                                          ->orderBy('paths.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'rate' => floatval($item->rate),
                            'load' => floatval($item->load),
                            'lock' => intval($item->lock),
                            'pass' => intval($item->pass),
                            'size' => intval($item->size),
                            'done' => intval($item->done),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'pick' => $item->pick,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('paths')
		                       ->where('hide', 0);

                    if (($pick = intval($request->get('pick')))) {
                        $query->where('pick', $pick);
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
                            'pick' => intval($item->pick),
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
    				return view('/core/paths', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null
                    ]);
    		}
    	}
    }
}