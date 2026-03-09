<?php

namespace App\Http\Controllers\Team;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Mail\Message;

use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;

class BondController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('bonds')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
							'name' => 'required|max:64',
							'mail' => 'required|email|max:64',
							'load' => 'required|numeric',
							'from' => 'required|date_format:Y-m-d',
							'stop' => 'required|date_format:Y-m-d',
							'land' => 'required|in:CO,US,CA,MX,CL,AR,EC,CA,GB,FR,DE,ES,IT',
							'cell' => 'required|max:16'
						], [
							'name.required' => 'El campo es requerido.',
							'mail.required' => 'El campo es requerido.',
							'load.required' => 'El campo es requerido.',
							'from.required' => 'El campo es requerido.',
							'stop.required' => 'El campo es requerido.',
							'land.required' => 'El campo es requerido.',
							'cell.required' => 'El campo es requerido.',
							'name.max' => 'El campo no es válido.',
							'mail.max' => 'El campo no es válido.',
							'cell.max' => 'El campo no es válido.',
							'mail.email' => 'El campo no es válido.',
							'load.numeric' => 'El campo no es válido.',
							'from.date_format' => 'El campo no es válido.',
							'stop.date_format' => 'El campo no es válido.',
						]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('bonds')
				            	                  ->where('hide', 0)
	                                              ->where('code', trim($request->get('code', $item->code)))
	                                              ->first())) || ($item->row == $same->row)))  {
								if (DB::table('bonds')
									  ->where('row', $item->row)
									  ->update(['code' => trim($request->get('code', $item->code)),
												'name' => trim($request->get('name', $item->name)),
												'cell' => trim($request->get('cell', $item->cell)),
												'mail' => trim($request->get('mail', $item->mail)),
												'land' => trim($request->get('land', $item->land)),
												'from' => trim($request->get('from', $item->from)),
												'stop' => trim($request->get('stop', $item->stop)),
												'note' => trim($request->get('note', $item->note)),
												'load' => floatval($request->get('load', $item->load)),
												'date' => date('Y-m-d H:i:s')])) {
									return response()->json(['text' => 'El registro fue actualizado con éxito.'], 200);
								} else {
									return response()->json(['text' => 'El registro no pudo ser actualizado.'], 500);
								}
				            } else {
				            	return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
	                                                     'list' => ['name' => 'El nombre ya existe.']], 400);
				            }
			            } else {
			            	return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
			            }
			        case 'lock':
			        	if (DB::table('bonds')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($flag = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['flag' => $flag, 'text' => ($flag ? 'El registro fue habilitado con éxito.' : 'El registro fue bloqueado con éxito.')], 200);
			            } else {
			              	return response()->json(['text' => ($flag ? 'El registro no pudo ser habilitado.' : 'El registro no pudo ser bloqueado.')], 500);
			            }
    				case 'drop':
    					if (DB::table('bonds')
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
			            'name' => 'required|max:64',
                        'mail' => 'required|email|max:64',
                        'load' => 'required|numeric',
                        'from' => 'required|date_format:Y-m-d',
                        'stop' => 'required|date_format:Y-m-d',
                        'land' => 'required|in:CO,US,CA,MX,CL,AR,EC,CA,GB,FR,DE,ES,IT',
						'cell' => 'required|max:16'
		            ], [
						'name.required' => 'El campo es requerido.',
                        'mail.required' => 'El campo es requerido.',
                        'load.required' => 'El campo es requerido.',
                        'from.required' => 'El campo es requerido.',
                        'stop.required' => 'El campo es requerido.',
                        'land.required' => 'El campo es requerido.',
                        'cell.required' => 'El campo es requerido.',
                        'name.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'cell.max' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'load.numeric' => 'El campo no es válido.',
                        'from.date_format' => 'El campo no es válido.',
                        'stop.date_format' => 'El campo no es válido.',
		            ]);

		            if (empty($validator->fails())) {
                        if (empty(($same = DB::table('bonds')
                                             ->where('hide', 0)
                                             ->where('code', (($code = trim($request->get('code'))) ? $code : ($code = hexdec(uniqid()))))
                                             ->first()))) {
                            if (($item = DB::table('bonds')
                                           ->insertGetId(['code' => $code,
                                                          'user' => Auth::user()->id,
                                                          'name' => trim($request->get('name')),
                                                          'mail' => trim($request->get('mail')),
                                                          'land' => trim($request->get('land')),
                                                          'cell' => trim($request->get('cell')),
                                                          'from' => trim($request->get('from')),
                                                          'stop' => trim($request->get('stop')),
                                                          'load' => floatval($request->get('load')),
                                                          'hash' => ($hash = md5(uniqid(rand(), true))),
                                                          'date' => date('Y-m-d H:i:s')]))) {
                                Mail::send('mail.bond', ['name' => trim($request->get('name')),
								                         'firm' => Auth::user()->from,
                                                         'hash' => $hash], function ($message) use ($request) {
                                       $message->to(trim($request->get('mail')), trim($request->get('name')))
                                               ->from(env('APP_MAIL'), env('APP_NAME'))
                                               ->subject('Gracias por tu donación');
                                });

                                return response()->json(['item' => $item,
                                                         'code' => $code,
                                                         'hash' => $hash,
                                                         'text' => 'El registro fue guardado con éxito.'], 200);
                            } else {
                                return response()->json(['text' => 'El registro no pudo ser guardado.'], 500);
                            }
                        } else {
                            return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                        'list' => ['code' => 'El código ya existe.']], 400);
                        }
		            } else {
		            	return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
		            }
    			case 'load':
    				$query = DB::table('bonds')
			                   ->where('hide', 0);

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
			                    		$rules[strtolower(pick($match['from'], 'main'))] = ['name' => strtolower($match['name']),
																							'sign' => $match['sign'],
																							'data' => $part];
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
				                        $query->where('date', $data);
				                    } else {
				                        $query->orWhere('date', $data);
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
                                            	  ->orWhere('name', 'like', sprintf('%%%s%%', $data));
				                    	});
				                  	} else {
				                    	$query->orWhere(function ($query) use ($data) {
				                      		$query->where('code', 'like', sprintf('%%%s%%', $data))
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
			                                                              ->orderBy(($take ? 'date' : 'name'), ($take ? 'desc' : 'asc'))
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['load' => floatval($item->load),
						                   'lock' => intval($item->lock),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
										   'from' => $item->from,
										   'stop' => $item->stop,
										   'mail' => $item->mail,
			                               'name' => $item->name]);

			            return $list;
			        }, [])]);
                case 'pull':
                    $query = DB::table('items')
                               ->where('hide', 0);

                    if ($request->has('lock')) {
                        $query->where('lock', intval($request->get('lock')));
                    }

					if ($request->has('type')) {
                        $query->where('type', intval($request->get('type')));
                    }

					if ($request->has('unit')) {
                        $query->where('unit', intval($request->get('unit')));
                    }
					
                    return response()->json(['high' => ($high = $query->count()),
                                            'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                            'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                            'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                        ->take(($take ? $take : $high))
                                                                        ->orderBy('name', 'asc')
                                                                        ->get()
                                                                        ->toArray(), function ($list, $item) {
                        array_push($list, ['lock' => intval($item->lock),
						                   'type' => intval($item->type),
										   'unit' => intval($item->unit),
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
										   'name' => $item->name]);

                        return $list;
                    }, [])]);
    			default:
    				return view('/team/bonds', ['request' => $request]);
    		}
    	}
    }
}