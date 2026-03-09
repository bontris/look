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

class UnitController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('units')
                           ->where('hide', 0)
                           ->where('hash', $item)
						   ->where('firm', Auth::user()->firm)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
							'nick' => 'nullable|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i|min:4|max:32',
							'name' => 'nullable|max:32',
							'hint' => 'nullable|max:128'
			            ], [
							'nick.max' => 'El campo no es válido.',
							'name.max' => 'El campo no es válido.',
							'hint.max' => 'El campo no es válido.'
			            ]);

			            if (empty($validator->fails())) {
							if ((empty(($same = DB::table('units')
												  ->where('hide', 0)
												  ->where('firm', Auth::user()->firm)
												  ->where('name', trim($request->get('name', $item->name)))
												  ->first())) || ($item->row == $same->row))) {
								if ((empty(($same = DB::table('units')
													  ->where('hide', 0)
													  ->where('firm', Auth::user()->firm)
													  ->where('nick', trim($request->get('nick', $item->nick)))
													  ->first())) || ($item->row == $same->row))) {
									if (DB::table('units')
										  ->where('row', $item->row)
										  ->update(['nick' => trim($request->get('nick', $item->nick)),
													'name' => trim($request->get('name', $item->name)),
													'hint' => trim($request->get('hint', $item->hint))])) {
										return response()->json(['text' => 'El registro fue actualizado con éxito.'], 200);
									} else {
										return response()->json(['text' => 'El registro no pudo ser actualizado.'], 500);
									}
								} else {
									return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
															 'list' => ['nick' => 'El alias ya existe.']], 400);
								}
							} else {
								return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
															'list' => ['name' => 'El nombre ya existe.']], 400);
							}
			            } else {
			            	return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {
								return current($item);
							}, $validator->errors()->toArray())], 400);
			            }
			        case 'lock':
			        	if (DB::table('units')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($flag = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['flag' => $flag, 'text' => ($flag ? 'El registro fue habilitado con éxito.' : 'El registro fue bloqueado con éxito.')], 200);
			            } else {
			              	return response()->json(['text' => ($flag ? 'El registro no pudo ser habilitado.' : 'El registro no pudo ser bloqueado.')], 500);
			            }
    				case 'drop':
    					if (DB::table('units')
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
						'nick' => 'nullable|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i|min:4|max:32',
			            'name' => 'required|max:32',
						'hint' => 'nullable|max:128'
		            ], [
		            	'lock.in' => 'El campo no es válido.',
						'nick.max' => 'El campo no es válido.',
						'hint.max' => 'El campo no es válido.',
						'name.max' => 'El campo no es válido.',
		                'name.required' => 'El campo es requerido.',
		            ]);

		            if (empty($validator->fails())) {
						if (empty(($same = DB::table('units')
											 ->where('hide', 0)
											 ->where('firm', Auth::user()->firm)
											 ->where('name', trim($request->get('name')))
											 ->first()))) {
							if (empty(($same = DB::table('units')
												 ->where('hide', 0)
												 ->where('firm', Auth::user()->firm)
												 ->where('nick', ($nick = trim($request->get('nick')) ? trim($request->get('nick')) : Str::slug($request->get('name'), '-')))
												 ->first()))) {
								if (($item = DB::table('units')
											   ->insertGetId(['nick' => $nick,
											                  'firm' => Auth::user()->firm,
															  'code' => ($code = hexdec(uniqid())),
															  'name' => trim($request->get('name')),
															  'hint' => trim($request->get('hint')),
															  'hash' => ($hash = md5(uniqid(rand(), true))),
															  'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
															  'creation' => date('Y-m-d H:i:s')]))) {
										return response()->json(['item' => $item,
																 'code' => $code,
																 'nick' => $nick,
																 'hash' => $hash,
																 'tone' => $tone,
																 'text' => 'El registro fue guardado con éxito.'], 200);
								} else {
									return response()->json(['text' => 'El registro no pudo ser guardado.'], 500);
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
		            	return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {
							return current($item);
						}, $validator->errors()->toArray())], 400);
		            }
    			case 'load':
    				$query = DB::table('units')
			                   ->where('hide', 0)
							   ->where('firm', Auth::user()->firm);

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

			            foreach ($rules as $from => $data) {
			              switch ($from) {
			                default:
			                  switch ($data['name']) {
			                  	case 'name':
			                      $query->where('name', 'like', sprintf('%%%s%%', $data['data']));
			                      break;
								case 'code':
									$query->where('nick', 'like', sprintf('%%%s%%', $data['data']));
									break;
			                    case 'code':
			                      $query->where('code', 'like', sprintf('%%%s%%', $data['data']));
			                      break;
			                  }
			              }
			            }

			            if (count($items)) {
			              $query->where(function ($query) use ($items) {
			                foreach ($items as $item => $data) {
			                  if (empty($item)) {
			                    $query->where('row', $data);
			                  } else {
			                    $query->orWhere('row', $data);
			                  }
			                }
			              });
			            }

			            if (count($dates)) {
				            $query->where(function ($query) use ($dates) {
				                foreach ($dates as $item => $data) {
				                    if (empty($item)) {
				                        $query->where('creation', $data);
				                    } else {
				                        $query->orWhere('creation', $data);
				                    }
				                }
				            });
			            }

			            if (count($texts)) {
			              	$query->where(function ($query) use ($texts) {
				                foreach ($texts as $item => $data) {
				                  if (empty($item)) {
				                    $query->where(function ($query) use ($data) {
				                      $query->where('code', 'like', sprintf('%%%s%%', $data))
									        ->orWhere('nick', 'like', sprintf('%%%s%%', $data))
                                            ->orWhere('name', 'like', sprintf('%%%s%%', $data));
				                    });
				                  } else {
				                    $query->orWhere(function ($query) use ($data) {
				                      $query->where('code', 'like', sprintf('%%%s%%', $data))
									        ->orWhere('nick', 'like', sprintf('%%%s%%', $data))
                                            ->orWhere('name', 'like', sprintf('%%%s%%', $data));
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
			                                                              ->orderBy('creation', 'desc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
			                               'name' => $item->name,
										   'tone' => $item->tone,
			                               'nick' => $item->nick]);

			            return $list;
			        }, [])]);
				case 'pull':
					$query = DB::table('units')
							   ->where('hide', 0)
							   ->where('firm', Auth::user()->firm);

					return response()->json(['high' => ($high = $query->count()),
											 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
											 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
											 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
																		  ->take(($take ? $take : $high))
																		  ->orderBy('name', 'asc')
																		  ->get()
																		 ->toArray(), function ($list, $item) {
						array_push($list, ['lock' => intval($item->lock),
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
										   'name' => $item->name,
										   'tone' => $item->tone]);

						return $list;
					}, [])]);
				default:
    				return view('/team/units', ['request' => $request]);
    		}
    	}
    }
}