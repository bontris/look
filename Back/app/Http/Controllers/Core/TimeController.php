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

class TimeController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('times')
                           ->where('times.hide', 0)
                           ->where('times.hash', $item)
                           ->join('works', function ($join) {
                            $join->on('times.bind', 'works.row')
                                 ->where('works.bind', Auth::user()->bind);
                           })
                           ->select('times.*')
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'bind' => 'required|integer',
                            'pick' => 'nullable|integer',
                            'next' => 'required|integer',
                            'note' => 'nullable|max:256',
                            'open' => 'required|date_format:Y-m-d',
                            'stop' => 'required|date_format:Y-m-d',
                            'code' => 'nullable|regex:/^[a-zA-Z0-9]{2,16}$/'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'code.regex' => 'El campo no es válido.',
                            'bind.integer' => 'El campo no es válido.',
                            'pick.integer' => 'El campo no es válido.',
                            'next.integer' => 'El campo no es válido.',
                            'next.required' => 'El campo es requerido.',
                            'bind.required' => 'El campo es requerido.',
                            'open.required' => 'El campo es requerido.',
                            'stop.required' => 'El campo es requerido.',
                            'open.date_format' => 'El campo no es válido.',
                            'stop.date_format' => 'El campo no es válido.'
                        ]);

			            if (empty($validator->fails())) {
                            if (($bind = DB::table('works')
                                           ->where('row', intval($request->get('bind', $item->bind)))
                                           ->where('hide', 0)
                                           ->where('type', 1)
                                           ->where('bind', Auth::user()->bind)
                                           ->first())) {
                                if (($next = DB::table('steps')
                                               ->where('hide', 0)
                                               ->where('bind', $bind->row)
                                               ->where('row', intval($request->get('next', $item->next)))
                                               ->first())) {
                                    if ((boolval($bind->once) || ($pick = DB::table('units')
                                                                            ->where('hide', 0)
                                                                            ->where('bind', $bind->row)
                                                                            ->where('row', intval($request->get('pick', $item->pick)))
                                                                            ->first()))) {
                                        if ((empty(($same = DB::table('times')
                                                              ->where('hide', 0)
                                                              ->where('next', $next->row)
                                                              ->where('bind', $bind->row)
                                                              ->where('pick', isset($pick) ? $pick->row : 0)
                                                              ->first())) || ($item->row == $same->row))) {
                                            if ((empty(($same = DB::table('times')
                                                                  ->where('hide', 0)
                                                                  ->where('bind', $bind->row)
                                                                  ->where('code', trim($request->get('code', $item->code)))
                                                                  ->first())) || ($item->row == $same->row))) {
                                                if (true) {
                                                    if (DB::table('times')->where('row', $item->row)->update([
                                                        'mark' => date('Y-m-d H:i:s'),
                                                        'code' => trim($request->get('code', $item->code)),
                                                        'open' => trim($request->get('open', $item->open)),
                                                        'stop' => trim($request->get('stop', $item->stop)),
                                                        'note' => trim($request->get('note', $item->note)),
                                                        'next' => intval($request->get('next', $item->next)),
                                                        'bind' => intval($request->get('bind', $item->bind)),
                                                        'pick' => intval($request->get('pick', $item->pick)),
                                                        'lock' => intval($request->get('lock', $item->lock))
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
                                                        'list' => ['rule' => 'La opción no es válida.']
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
                                                'list' => ['step' => 'La obra ya existe.']
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
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
			        case 'lock':
			        	if (DB::table('times')
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
    					if (DB::table('times')
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
                                       ->where('gains.bind', $item->bind)
                                       ->leftJoin('units', function ($join) {
                                            $join->on('units.row', 'gains.link')
                                                 ->where('gains.sort', 2);
                                       })
                                       ->leftJoin('works', function ($join) {
                                           $join->where(function ($query) {
                                               $query->where('works.row', DB::raw('gains.link'))
                                                    ->where('gains.sort', 1);
                                           })->orWhere(function ($query) {
                                               $query->where('works.row', DB::raw('units.link'))
                                                     ->where('gains.sort', 2);
                                           });
                                       })
                                       ->leftJoin('steps', 'steps.row', 'gains.next')
                                       ->leftJoin('users', 'users.id', 'gains.skip')
                                       ->select(
                                        'gains.*',
                                        'steps.mode',
                                        'steps.unit',
                                        'steps.rate',
                                        'steps.size',
                                        'works.cost',
                                        'works.town',
                                        DB::raw('works.name AS work'),
                                        DB::raw('units.code AS code'),
                                        DB::raw('steps.name AS step'),
                                        DB::raw('ST_AsText(gains.spot) AS spot'),
                                        DB::raw('CONCAT(`users`.`name`, " ", `users`.`last`) AS skip'),
                                        DB::raw(sprintf("CONVERT_TZ(gains.mark, '%s', '%s') AS `mark`", date_default_timezone_get(), env('APP_TIME', '-05:00'))),
                                        DB::raw(sprintf("CONVERT_TZ(gains.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                       )->first())) {
                            return response()->json([
                                'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $data->spot, $data->spot) ? [floatval($data->spot[1]), floatval($data->spot[3])] : null,
                                'file' => json_decode($data->file),
                                'cost' => floatval($data->cost),
                                'lock' => intval($data->lock),
                                'sort' => intval($data->sort),
                                'rate' => intval($data->rate),
                                'pass' => intval($data->pass),
                                'mode' => intval($data->mode),
                                'size' => intval($data->size),
                                'load' => intval($data->load),
                                'item' => intval($data->row),
                                'hash' => $data->hash,
                                'code' => $data->code,
                                'work' => $data->work,
                                'town' => $data->town,
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
                                case 'file':
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
                        'note' => 'nullable|max:256',
                        'open' => 'required|date_format:Y-m-d',
                        'stop' => 'nullable|date_format:Y-m-d',
                        'code' => 'nullable|regex:/^[a-zA-Z0-9]{2,16}$/'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'code.regex' => 'El campo no es válido.',
                        'bind.integer' => 'El campo no es válido.',
                        'pick.integer' => 'El campo no es válido.',
                        'next.integer' => 'El campo no es válido.',
                        'next.required' => 'El campo es requerido.',
                        'bind.required' => 'El campo es requerido.',
                        'open.required' => 'El campo es requerido.',
                        'open.date_format' => 'El campo no es válido.',
                        'stop.date_format' => 'El campo no es válido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (($bind = DB::table('works')
                                       ->where('hide', 0)
                                       ->where('type', 1)
                                       ->where('bind', Auth::user()->bind)
                                       ->where('row', intval($request->get('bind')))
                                       ->first())) {
                            if (($next = DB::table('steps')
                                           ->where('hide', 0)
                                           ->where('bind', $bind->row)
                                           ->where('row', intval($request->get('next')))
                                           ->first())) {
                                if ((boolval($bind->once) || ($pick = DB::table('units')
                                                                        ->where('hide', 0)
                                                                        ->where('bind', $bind->row)
                                                                        ->where('row', intval($request->get('pick')))
                                                                        ->first()))) {
                                    if (empty(DB::table('times')
                                                ->where('hide', 0)
                                                ->where('next', $next->row)
                                                ->where('bind', $bind->row)
                                                ->where('pick', isset($pick) ? $pick->row : 0)
                                                ->first())) {
                                        if (empty(DB::table('times')
                                                    ->where('hide', 0)
                                                    ->where('bind', $bind->row)
                                                    ->where('code', ($code = hexdec(uniqid())))
                                                    ->first())) {
                                            if (($item = DB::table('times')->insertGetId([
                                                'code' => $code,
                                                'open' => trim($request->get('open')),
                                                'stop' => trim($request->get('stop')),
                                                'note' => trim($request->get('note')),
                                                'bind' => intval($request->get('bind')),
                                                'pick' => intval($request->get('pick')),
                                                'next' => intval($request->get('next')),
                                                'lock' => intval($request->get('lock')),
                                                'made' => ($made = date('Y-m-d H:i:s')),
                                                'hash' => ($hash = md5(uniqid(rand(), true)))
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
                                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['code' => 'El código ya existe.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['step' => 'La obra ya existe.']
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
    				$query = DB::table('times')
                               ->where('times.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('times.bind', 'works.row')
                                     ->where('works.bind', Auth::user()->bind);
                               })
                               ->join('steps', 'steps.row', 'times.next')
                               ->leftJoin('chips', 'chips.row', 'steps.unit')
                               ->leftJoin('units', 'units.row', 'times.pick');
                    
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
                                            $query->where(DB::raw('YEAR(times.made)'), date('Y', time()));
                                            break;
                                        case 'MH':
                                            $query->where(DB::raw('MONTH(times.made))'), date('m', time()));
                                            break;
                                        case 'WK':
                                            $query->whereBetween(DB::raw('DATE(times.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                            break;
                                        case 'DY':
                                            $query->where(DB::raw('DAY(times.made))'), date('d', time()));
                                            break;
                                        case 'NW':
                                            $query->where(function ($query) {
                                                $query->where(DB::raw('DAY(times.made))'), date('d', time()))
                                                        ->where(DB::raw('HOUR(times.made))'), date('H', time()));
                                            });
                                            break;
                                        default:
                                            if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                $query->whereBetween(DB::raw('DATE(times.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                            } else {
                                                $query->where(DB::raw('DATE(times.made))'), date('Y-m-d', strtotime($data[0])));
                                            }
                                    }
                                    break;
                                case 'done':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('times.done', intval($data));
                                            } else {
                                                $query->orWhere('times.done', intval($data));
                                            }
                                        }
                                    });
                                    break;
                                case 'lock':
                                    $query->where(function ($query) use ($item) {
                                        foreach (explode(' ', trim($item['data'])) as $item => $data) {
                                            if (empty($item)) {
                                                $query->where('times.lock', intval($data));
                                            } else {
                                                $query->orWhere('times.lock', intval($data));
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
                                        $query->where('times.row', $data);
                                    } else {
                                        $query->orWhere('times.row', $data);
                                    }
                                }
                            });
                        }

                        if (count($codes)) {
                            $query->where(function ($query) use ($codes) {
                                foreach ($codes as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('times.hash', $data);
                                    } else {
                                        $query->orWhere('times.hash', $data);
                                    }
                                }
                            });
                        }
    
                        if (count($dates)) {
                            $query->where(function ($query) use ($dates) {
                                foreach ($dates as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('times.open', $data);
                                    } else {
                                        $query->orWhere('times.open', $data);
                                    }
                                }
                            });
                        }
    
                        if (count($texts)) {
                            $query->where(function ($query) use ($texts) {
                                foreach ($texts as $item => $data) {
                                    if (empty($item)) {
                                        $query->where(function ($query) use ($data) {
                                            $query->where('times.code', 'like', sprintf('%%%s%%', $data));
                                        });
                                    } else {
                                        $query->orWhere(function ($query) use ($data) {
                                            $query->where('times.code', 'like', sprintf('%%%s%%', $data));
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
                                                                            'times.*',
                                                                            'units.name',
                                                                            'steps.mode',
                                                                            'steps.rate',
                                                                            'steps.term',
                                                                            'steps.sort',
                                                                            'steps.size',
                                                                            'works.cost',
                                                                            DB::raw('works.name AS `work`'),
                                                                            DB::raw('steps.name AS `step`'),
                                                                            DB::raw('chips.code AS `unit`'),
                                                                            DB::raw('(SELECT SUM(gains.load) FROM gains WHERE (gains.pass = 1) AND (gains.next = times.next) AND ((gains.bind = times.bind) AND (gains.pick = times.pick) AND (gains.next = times.next))) AS `load`'),
                                                                            DB::raw(sprintf("CONVERT_TZ(times.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                                                          )
                                                                          ->orderBy('times.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'cost' => floatval($item->cost),
                            'lock' => intval($item->lock),
                            'rate' => intval($item->rate),
                            'done' => intval($item->done),
                            'mode' => intval($item->mode),
                            'size' => intval($item->size),
                            'load' => intval($item->load),
                            'term' => intval($item->term),
                            'sort' => intval($item->sort),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'work' => $item->work,
                            'step' => $item->step,
                            'unit' => $item->unit,
                            'open' => $item->open,
                            'stop' => $item->stop,
                            'date' => $item->date,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('times')
		                       ->where('times.hide', 0)
                               ->join('works', function ($join) {
                                $join->on('times.bind', 'works.row')
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
			                                                              ->orderBy('times.made', 'asc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'lock' => intval($item->lock),
                            'done' => intval($item->done),
                            'bind' => intval($item->bind),
                            'pick' => intval($item->pick),
                            'next' => intval($item->next),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'note' => $item->note,
                            'open' => $item->open,
                            'stop' => $item->stop,
                            'date' => $item->date,
                            'mark' => $item->mark,
                            'made' => $item->made
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/core/times', [
                        'seek' => preg_match('/^\w{32}$/', $task ?? $item) ? sprintf('{%s}', $task ?? $item) : null,
                        'date' => date('Y-m-d')
                    ]);
    		}
    	}
    }
}