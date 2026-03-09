<?php

namespace App\Http\Controllers\Team;

use Auth;

use User;

use Validator;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('nodes')
                           ->join('units', function ($join) {
                                $join->on('nodes.unit', 'units.row')
                                     ->where('units.firm', Auth::user()->firm);
                           })
                           ->where('nodes.hide', 0)
                           ->where('nodes.hash', $item)
                           ->select('nodes.*')
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
							'lock' => 'nullable|in:0,1',
                            'live' => 'nullable|in:0,1,2',
                            'type' => 'nullable|in:1,2',
                            'card' => 'nullable|regex:/^([0-9A-F]{2}[:-]){3,5}([0-9A-Fa-f]{2})$/i',
                            'nick' => 'nullable|regex:/^([0-9A-Z]+(?:-[0-9A-Z]+)*){2,32}$/i',
                            'name' => 'nullable|max:32',
                            'unit' => 'nullable|numeric'
			            ], [
							'lock.in' => 'El campo no es válido.',
                            'live.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'card.regex' => 'El campo no es válido.',
                            'nick.regex' => 'El campo no es válido.',
                            'unit.numeric' => 'El campo no es válido.'
			            ]);

			            if (empty($validator->fails())) {
							if ((empty(($same = DB::table('nodes')
                                                  ->where('hide', 0)
                                                  ->where('card', trim($request->get('card', $item->card)))
												  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('nodes')
                                                      ->where('hide', 0)
                                                      ->where('name', trim($request->get('name', $item->name)))
                                                      ->where('unit', intval($request->get('unit', $item->unit)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($same = DB::table('nodes')
                                                          ->where('hide', 0)
                                                          ->where('nick', trim($request->get('nick', $item->nick)))
                                                          ->where('unit', intval($request->get('unit', $item->unit)))
                                                          ->first())) || ($item->row == $same->row))) {
                                        if ((($unit = DB::table('units')
                                                        ->where('firm', Auth::user()->firm)
                                                        ->where(preg_match('/^[a-z0-9]{32}$/i', trim($request->get('unit', $item->unit))) ? 'hash' : 'row', trim($request->get('unit', $item->unit)))
                                                        ->first()) && ((empty(intval($unit->hide)) && empty(intval($unit->lock))) || ($item->unit == $unit->row)))) {
                                            if (DB::table('nodes')
                                                  ->where('row', $item->row)
                                                  ->update(['unit' => $unit->row,
                                                            'nick' => trim($request->get('nick', $item->nick)),
                                                            'name' => trim($request->get('name', $item->name)),
                                                            'type' => intval($request->get('type', $item->type)),
                                                            'live' => intval($request->get('live', $item->live)),
                                                            'lock' => intval($request->get('lock', $item->lock)),
                                                            'card' => strtoupper(trim($request->get('card', $item->card)))])) {
                                                return response()->json(['unit' => $unit->name, 'text' => 'El registro fue actualizado con éxito.'], 200);
                                            } else {
                                                return response()->json(['text' => 'El registro no pudo ser actualizado.'], 500);
                                            }
                                        } else {
                                            return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													                 'list' => ['unit' => 'La opción no es válida.']], 400);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                                 'list' => ['nick' => 'El alias ya existe.']], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                             'list' => ['name' => 'El nombre ya existe.']], 400);
                                }
							} else {
								return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
														 'list' => ['card' => 'El dirección ya existe.']], 400);
							}
			            } else {
			            	return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {
								return current($item);
							}, $validator->errors()->toArray())], 400);
			            }
			        case 'lock':
			        	if (DB::table('nodes')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($lock = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['lock' => $lock, 'text' => 'El registro fue modificado con éxito.'], 200);
			            } else {
			              	return response()->json(['text' => 'El registro no pudo ser modificado.'], 500);
			            }
                    case 'live':
                        if (DB::table('nodes')
                                ->where('row', $item->row)
                                ->update(['live' => ($live = intval($item->live) ? 0 : 1)])) {
                            return response()->json(['live' => $live, 'text' => 'El registro fue modificado con éxito.'], 200);
                        } else {
                            return response()->json(['text' => 'El registro no pudo ser modificado.'], 500);
                        }
    				case 'drop':
    					if (DB::table('nodes')
			                  ->where('row', $item->row)
			                  ->update(['hide' => 1, 'deletion' => date('Y-m-d H:i:s')])) {
			              	return response()->json(['text' => 'El registro fue eliminado con éxito.'], 200);
			            } else {
			              	return response()->json(['text' => 'El registro no pudo ser eliminado.'], 500);
			            }
    				case 'load':
    					return response()->json($item, 200);
    			}
    		} else {
    			return response()->json(['text' => 'El registro no fue encontrado.'], 404);
    		}
    	} else {
    		switch (strtolower($task)) {
    			case 'make':
    				$validator = Validator::make($request->all(), [
			            'lock' => 'nullable|in:0,1',
                        'live' => 'nullable|in:0,1,2',
                        'type' => 'required|in:1,2',
                        'card' => 'required|regex:/^([0-9A-F]{2}[:-]){5}([0-9A-Fa-f]{2})$/i',
						'nick' => 'nullable|regex:/^([0-9A-Z]+(?:-[0-9A-Z]+)*){2,32}$/i',
			            'name' => 'required|max:32',
                        'unit' => 'required|numeric'
		            ], [
		            	'lock.in' => 'El campo no es válido.',
                        'live.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
						'name.max' => 'El campo no es válido.',
                        'card.regex' => 'El campo no es válido.',
						'nick.regex' => 'El campo no es válido.',
                        'unit.numeric' => 'El campo no es válido.',
                        'name.required' => 'El campo es requerido.',
                        'card.required' => 'El campo es requerido.',
                        'type.required' => 'El campo es requerido.',
		                'unit.required' => 'El campo es requerido.'
		            ]);

		            if (empty($validator->fails())) {
                        if (empty(($same = DB::table('nodes')
											 ->where('hide', 0)
											 ->where('card', trim($request->get('card')))
											 ->first()))) {
                            if (empty(($same = DB::table('nodes')
                                                 ->where('hide', 0)
                                                 ->where('name', trim($request->get('name')))
                                                 ->where('unit', intval($request->get('unit')))
                                                 ->first()))) {
                                if (empty(($same = DB::table('nodes')
                                                     ->where('hide', 0)
                                                     ->where('unit', intval($request->get('unit')))
                                                     ->where('nick', ($nick = trim($request->get('nick')) ? trim($request->get('nick')) : Str::slug($request->get('name'), '-')))
                                                     ->first()))) {
                                    if (($unit = DB::table('units')
                                                   ->where('hide', 0)
                                                   ->where('lock', 0)
                                                   ->where('firm', Auth::user()->firm)
                                                   ->where(preg_match('/^[a-z0-9]{32}$/i', trim($request->get('unit'))) ? 'hash' : 'row', trim($request->get('unit')))
                                                   ->first())) {
                                        if (($item = DB::table('nodes')
                                                       ->insertGetId(['nick' => $nick,
                                                                      'unit' => $unit->row,
                                                                      'name' => trim($request->get('name')),
                                                                      'code' => ($code = hexdec(uniqid())),
                                                                      'lock' => intval($request->get('lock')),
                                                                      'live' => intval($request->get('live')),
                                                                      'type' => intval($request->get('type')),
                                                                      'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                      'card' => strtoupper(trim($request->get('card'))),
                                                                      'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                                      'creation' => date('Y-m-d H:i:s')]))) {
                                                return response()->json(['item' => $item,
                                                                         'code' => $code,
                                                                         'nick' => $nick,
                                                                         'hash' => $hash,
                                                                         'tone' => $tone,
                                                                         'unit' => $unit->name,
                                                                         'text' => 'El registro fue guardado con éxito.'], 200);
                                        } else {
                                            return response()->json(['text' => 'El registro no pudo ser guardado.'], 500);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													             'list' => ['unit' => 'La opción no es válida.']], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                             'list' => ['nick' => 'El alias ya existe.']], 400);
                                }
                            } else {
                                return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                        'list' => ['name' => 'El nombre ya existe.']], 400);
                            }
                        } else {
                            return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													 'list' => ['card' => 'La dirección ya existe.']], 400);
                        }
		            } else {
		            	return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {
							return current($item);
						}, $validator->errors()->toArray())], 400);
		            }
    			case 'load':
    				$query = DB::table('nodes')
                               ->join('units', function ($join) {
                                   $join->on('nodes.unit', 'units.row')
                                        ->where('units.firm', Auth::user()->firm);
                               })
			                   ->where('nodes.hide', 0);

			        if (($find = trim($request->post('find')))) {
			            $match = [];

			            $rules = [];

			            $names = [];

			            $texts = [];

			            $dates = [];

			            $items = [];

			            foreach (array_slice(explode(',', $find), 0, 20) as $part) {
			              if (($part = trim($part))) {
			                if (preg_match('%^((?P<from>\w+)\.)?(?P<name>\w+):(\s+)?(?P<sign>[!<=>])?(?P<data>.+)$%', $part, $match)) {
			                  if (($part = trim($match['data']))) {
			                    $rules[strtolower(pick($match['from'], 'main'))] = pair('name', strtolower($match['name']),
			                                                                            'sign', $match['sign'],
			                                                                            'data', $part);
			                  }
			                } else {
			                  if (preg_match('/^[0-9]{1,2}\\/[0-9]{1,2}\\/[0-9]{4}$/', $part)) {
			                    $dates[] = date('Y-m-d', strtotime(strtr($part, '/', '-')));
			                  } else {
			                    if (preg_match('/^@\w+$/i', $part)) {
			                      $names[] = strtolower(substr($part, 1));
			                    } else {
			                      if (preg_match('/^#\d+$/', $part)) {
			                        $items[] = strtolower(substr($part, 1));
			                      } else {
			                        $texts[] = strtolower($part);
			                      }
			                    }
			                  }
			                }
			              } 
			            }

			            if (count($texts)) {
			              	$query->where(function ($query) use ($texts) {
				                foreach ($texts as $item => $data) {
				                  if (empty($item)) {
				                    $query->where(function ($query) use ($data) {
				                      $query->where('nodes.code', 'like', sprintf('%%%s%%', $data))
									        ->orWhere('nodes.nick', 'like', sprintf('%%%s%%', $data))
                                            ->orWhere('nodes.name', 'like', sprintf('%%%s%%', $data));
				                    });
				                  } else {
				                    $query->orWhere(function ($query) use ($data) {
				                      $query->where('nodes.code', 'like', sprintf('%%%s%%', $data))
									        ->orWhere('nodes.nick', 'like', sprintf('%%%s%%', $data))
                                            ->orWhere('nodes.name', 'like', sprintf('%%%s%%', $data));
				                    });
				                  }
				                }
			              	});
			            }
			        }

			        return response()->json(['high' => ($high = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
			                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $high))
			                                                              ->orderBy('nodes.creation', 'desc')
                                                                          ->select('nodes.*', 'units.name AS unit')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
                                           'live' => intval($item->live),
                                           'type' => intval($item->type),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
			                               'name' => $item->name,
										   'tone' => $item->tone,
			                               'nick' => $item->nick,
                                           'card' => $item->card,
                                           'unit' => $item->unit]);

			            return $list;
			        }, [])]);
				case 'pull':
					$query = DB::table('nodes')
							   ->where('nodes.hide', 0)
							   ->join('units', function ($join) {
                                   $join->on('nodes.unit', 'units.row')
                                        ->where('units.firm', Auth::user()->firm);
                               });

					return response()->json(['high' => ($high = $query->count()),
											 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
											 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
											 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
																		  ->take(($take ? $take : $high))
																		  ->orderBy('nodes.name', 'asc')
                                                                          ->select('nodes.*')
																		  ->get()
																		 ->toArray(), function ($list, $item) {
						array_push($list, ['lock' => intval($item->lock),
                                           'type' => intval($item->type),
                                           'unit' => intval($item->unit),
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
                                           'card' => $item->card,
										   'name' => $item->name,
										   'tone' => $item->tone]);

						return $list;
					}, [])]);
				default:
    				return view('/team/nodes', ['request' => $request]);
    		}
    	}
    }
}