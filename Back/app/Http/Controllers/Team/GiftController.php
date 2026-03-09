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

use Intervention\Image\ImageManagerStatic as Image;

class GiftController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('gifts')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
							'lock' => 'nullable|in:0,1',
							'test' => 'nullable|in:0,1',
							'firm' => 'sometimes|required|integer',
							'rate' => 'sometimes|required|integer|min:1|max:100',
							'code' => 'sometimes|required|max:16',
							'name' => 'sometimes|required|max:64',
							'term' => 'sometimes|required|max:512',
							'note' => 'nullable|max:256',
							'wait' => 'nullable|date_format:Y-m-d',
							'stop' => 'nullable|date_format:Y-m-d',
							'snap' => 'nullable|image|mimetypes:jpg,png'
						], [
							'lock.in' => 'El campo no es válido.',
							'test.in' => 'El campo no es válido.',
							'rate.min' => 'El campo no es válido.',
							'rate.max' => 'El campo no es válido.',
							'code.max' => 'El campo no es válido.',
							'name.max' => 'El campo no es válido.',
							'term.max' => 'El campo no es válido.',
							'note.max' => 'El campo no es válido.',
							'snap.mimes' => 'El campo debe ser una imágen válida.',
							'firm.integer' => 'El campo no es válido.',
							'rate.integer' => 'El campo no es válido.',
							'firm.required' => 'El campo es requerido.',
							'rate.required' => 'El campo es requerido.',
							'code.required' => 'El campo es requerido.',
							'name.required' => 'El campo es requerido.',
							'term.required' => 'El campo es requerido.',
							'wait.date_format' => 'El campo es requerido.',
							'stop.date_format' => 'El campo es requerido.'
						]);

			            if (empty($validator->fails())) {
							if (($firm = DB::table('firms')
										   ->where('hide', 0)
										   ->where('row', intval($request->get('firm', $item->firm)))
										   ->first())) {
								if ((empty(($same = DB::table('gifts')
											          ->where('hide', 0)
													  ->where('firm', $firm->row)
											          ->where('code', trim($request->get('code', $item->code)))
											          ->first())) || ($item->row == $same->row))) {
									if ((empty(($snap = $request->file('snap'))) || Image::make($snap)->save(sprintf('%s/%s', storage_path('files'), ($snap = md5(uniqid(rand(), true))))))) {
										if (DB::table('gifts')
											  ->where('row', $item->row)
											  ->update([
											'firm' => $firm->row,
											'seen' => date('Y-m-d H:i:s'),
											'snap' => $snap ?? $item->snap,
											'code' => trim($request->get('code', $item->code)),
											'name' => trim($request->get('name', $item->name)),
											'term' => trim($request->get('term', $item->term)),
											'note' => trim($request->get('note', $item->note)),
											'rate' => intval($request->get('rate', $item->rate)),
											'wait' => ($wait = trim($request->get('wait', $item->wait))) ? $wait : null,
											'stop' => ($stop = trim($request->get('stop', $item->stop))) ? $stop : null,
										])) {
											if ((empty(empty($snap)) && empty(empty($item->snap)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->snap))))) {
												Log::error(sprintf('Unable to delete the file: %s', $item->snap));
											} 
											
											return response()->json([
												'snap' => $snap,
												'firm' => $firm->name,
												'text' => 'El registro fue actualizado con éxito.'
											], 200);
										} else {
											return response()->json([
												'text' => 'El registro no pudo ser actualizado.'
											], 500);
										}
									} else {
										return response()->json([
											'text' => 'La imágen no pudo ser cargada con éxito.'
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
                                    'list' => ['firm' => 'La opción no es válida.']
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
					case 'face':
						$validator = Validator::make($request->all(), [
							'file' => 'nullable|mimetypes:image/jpeg,image/png'
						], [
							'file.mimes' => 'La imágen no es válida.'
						]);

						if (empty($validator->fails())) {
							if ($request->file('file')) {
								if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
									if (DB::table('gifts')
										  ->where('row', $item->row)
										  ->update(['snap' => $file, 'seen' => date('Y-m-d H:i:s')])) {
										if ((empty(empty($item->snap)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->snap))))) {
											Log::error(sprintf('Unable to delete the file: %s', $item->snap));
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
								if ((empty(empty($item->snap)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->snap)) && DB::table('gifts')
																																	 ->where('row', $item->row)
																																	 ->update(['snap' => null]))) {
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
								'text' => 'Uno o mas campos del formulario no son correctos.',
								'list' => array_map(function ($item) {
									return current($item);
								}, $validator->errors()->toArray())
							], 400);
						}
                    case 'bind':
                        if (($chit = DB::table('chits')
                                       ->where('hide', 0)
                                       ->where('type', 1)
                                       ->where('item', $item->row)
                                       ->where('skip', Auth::user()->id)
                                       ->first())) {
                            return response()->json([
                                'item' => intval($chit->row),
                                'code' => $chit->code,
                                'hash' => $chit->hash
                            ], 200);
                        } else {
                            if (empty(boolval($item->lock))) {
                                if (($chit = DB::table('chits')
                                               ->insertGetId([
                                    'type' => 1,
                                    'item' => $item->row,
                                    'skip' => Auth::user()->id,
                                    'code' => ($code = hexdec(uniqid())),
                                    'hash' => ($hash = md5(uniqid(rand(), true)))
                                ]))) {
                                    return response()->json([
                                        'item' => $chit,
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
									'text' => 'El registro no está activo.'
								], 400);
                            }
                        }
			        case 'lock':
			        	if (DB::table('gifts')
			                  ->where('row', $item->row)
			                  ->update(['lock' => boolval($request->get('flag')), 'seen' => date('Y-m-d H:i:s')])) {
			              	return response()->json([
                                'text' => 'El registro fue actualizado con éxito.'
                            ], 200);
			            } else {
			              	return response()->json([
								'text' => 'El registro no pudo ser actualizado.'
							], 500);
			            }
    				case 'drop':
    					if (DB::table('gifts')
			                  ->where('row', $item->row)
			                  ->update(['hide' => 1, 'seen' => date('Y-m-d H:i:s')])) {
			              	return response()->json([
								'text' => 'El registro fue eliminado con éxito.'
							], 200);
			            } else {
			              	return response()->json([
								'text' => 'El registro no pudo ser eliminado.'
							], 500);
			            }
    				case 'load':
    					return response()->json($item, 200);
    			}
    		} else {
    			return response()->json([
					'text' => 'El registro no fue encontradox.'
				], 404);
    		}
    	} else {
    		switch (strtolower($task)) {
    			case 'make':
    				$validator = Validator::make($request->all(), [
                        'lock' => 'nullable|in:0,1',
                        'test' => 'nullable|in:0,1',
						'firm' => 'required|integer',
						'rate' => 'required|integer|min:1|max:100',
                        'code' => 'required|max:16',
                        'name' => 'required|max:64',
						'term' => 'required|max:512',
                        'note' => 'nullable|max:256',
                        'wait' => 'nullable|date_format:Y-m-d',
						'stop' => 'nullable|date_format:Y-m-d',
                        'snap' => 'nullable|image|mimetypes:jpg,png'
                    ], [
                        'lock.in' => 'El campo no es válido.',
						'test.in' => 'El campo no es válido.',
						'rate.min' => 'El campo no es válido.',
						'rate.max' => 'El campo no es válido.',
                        'code.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
						'term.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
						'snap.mimes' => 'El campo debe ser una imágen válida.',
                        'firm.integer' => 'El campo no es válido.',
                        'rate.integer' => 'El campo no es válido.',
                        'firm.required' => 'El campo es requerido.',
						'rate.required' => 'El campo es requerido.',
                        'code.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
						'term.required' => 'El campo es requerido.',
						'wait.date_format' => 'El campo es requerido.',
						'stop.date_format' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
						if (($firm = DB::table('firms')
									   ->where('hide', 0)
									   ->where('row', intval($request->get('firm')))
									   ->first())) {
							if (empty(DB::table('gifts')
										->where('hide', 0)
										->where('firm', $firm->row)
										->where('code', trim($request->get('code')))
										->first())) {
								if ((empty(($snap = $request->file('snap'))) || Image::make($snap)->save(sprintf('%s/%s', storage_path('files'), ($snap = md5(uniqid(rand(), true))))))) {
									if (($item = DB::table('gifts')->insertGetId([
											'snap' => $snap,
											'firm' => $firm->row,
											'made' => date('Y-m-d H:i:s'),
											'code' => trim($request->get('code')),
											'name' => trim($request->get('name')),
											'term' => trim($request->get('term')),
											'note' => trim($request->get('note')),
											'lock' => intval($request->get('lock')),
											'rate' => intval($request->get('rate')),
											'hash' => ($hash = md5(uniqid(rand(), true))),
											'wait' => ($wait = trim($request->get('wait'))) ? $wait : null,
											'stop' => ($stop = trim($request->get('stop'))) ? $stop : null,
											'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
									]))) {
										return response()->json([
											'item' => $item,
											'hash' => $hash,
											'snap' => $snap,
											'tone' => $tone,
											'firm' => $firm->name,
											'text' => 'El registro fue guardado con éxito.'
										], 200);
									} else {
										return response()->json([
											'text' => 'El registro no pudo ser guardado.'
										], 500);
									}
								} else {
									return response()->json([
										'text' => 'La imágen no pudo ser cargada con éxito.'
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
								'list' => ['firm' => 'La opción no es válida.']
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
    				$query = DB::table('gifts')
			                   ->where('gifts.hide', 0)
							   ->join('firms', function ($join) {
						$join->on('gifts.firm', 'firms.row');
					});

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
			                      			$query->where('gifts.name', 'like', sprintf('%%%s%%', $data['data']));
			                      			break;
			                    		case 'code':
			                      			$query->where('gifts.code', 'like', sprintf('%%%s%%', $data['data']));
			                      			break;
			                  		}
			              	}
			            }

			            if (count($items)) {
			              	$query->where(function ($query) use ($items) {
			                	foreach ($items as $item => $data) {
			                  		if (empty($item)) {
			                    		$query->where('gifts.row', $data);
			                  		} else {
			                    		$query->orWhere('gifts.row', $data);
			                  		}
			                	}
			              	});
			            }

			            if (count($dates)) {
				            $query->where(function ($query) use ($dates) {
				                foreach ($dates as $item => $data) {
				                    if (empty($item)) {
				                        $query->where('gifts.made', $data);
				                    } else {
				                        $query->orWhere(gifts.'made', $data);
				                    }
				                }
				            });
			            }

			            if (count($texts)) {
			              	$query->where(function ($query) use ($texts) {
				                foreach ($texts as $item => $data) {
				                  	if (empty($item)) {
				                    	$query->where(function ($query) use ($data) {
				                      		$query->where('gifts.code', 'like', sprintf('%%%s%%', $data))
                                            	  ->orWhere('gifts.name', 'like', sprintf('%%%s%%', $data));
				                    	});
				                  	} else {
				                    	$query->orWhere(function ($query) use ($data) {
				                      		$query->where('gifts.code', 'like', sprintf('%%%s%%', $data))
                                            	  ->orWhere('gifts.name', 'like', sprintf('%%%s%%', $data));
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
																		  ->select(
																			'gifts.row',
																			'gifts.lock',
																			'gifts.hash',
																			'gifts.code',
																			'gifts.name',
																			'gifts.rate',
																			'gifts.wait',
																			'gifts.stop',
																			'gifts.tone',
																			'gifts.snap',
																			'firms.name AS firm',
																			DB::raw(sprintf("CONVERT_TZ(gifts.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
																		  )
			                                                              ->orderBy('gifts.made', 'desc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
										   'rate' => intval($item->rate),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
										   'wait' => $item->wait,
										   'stop' => $item->stop,
			                               'name' => $item->name,
										   'firm' => $item->firm,
										   'tone' => $item->tone,
										   'snap' => $item->snap,
										   'made' => $item->made]);

			            return $list;
			        }, [])]);
                case 'pull':
                    $query = DB::table('gifts')
                               ->where('hide', 0);

                    if ($request->has('lock')) {
                        $query->where('lock', intval($request->get('lock')));
                    }

					if ($request->has('firm')) {
                        $query->where('firm', intval($request->get('firm')));
                    }
					
                    return response()->json(['high' => ($high = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $high))
                                                                          ->orderBy('made', 'asc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, ['rate' => floatval($item->rate),
						                   'lock' => intval($item->lock),
										   'firm' => intval($item->firm),
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
										   'wait' => $item->wait,
										   'stop' => $item->stop,
										   'name' => $item->name,
										   'term' => $item->term,
										   'tone' => $item->tone,
										   'snap' => $item->snap,
										   'made' => $item->made]);

                        return $list;
                    }, [])]);
                case 'scan':
                    if (($item = DB::table('chits')
                                   ->where('chits.hide', 0)
                                   ->where('chits.type', 1)
                                   ->where('chits.hash', trim($request->get('code')))
								   ->join('gifts', function ($join) use ($request) {
						$join->on('chits.item', 'gifts.row')
						     ->where('gifts.firm', Auth::user()->firm);
					})->join('users', function ($join) use ($request) {
						$join->on('chits.skip', 'users.id');
					})->select(
						'chits.row',
						'chits.lock',
						'chits.date',
						'chits.hash',
						'gifts.code',
						'gifts.rate',
						'users.name',
						'users.last',
					)->first())) {
						return response()->json([
							'lock' => boolval($item->lock),
							'rate' => intval($item->rate),
						    'item' => intval($item->row),
							'date' => $item->date,
                            'code' => $item->code,
                            'hash' => $item->hash,
							'name' => $item->name,
							'last' => $item->last
						], 200);
                    } else {
						return response()->json(['text' => 'El código no fue encontrado.'], 404);
					}
				case 'lock':
					if (DB::table('chits')
                          ->where('hide', 0)
                          ->where('type', 1)
                          ->where('hash', trim($request->get('code')))
						  ->update(['lock' => 1, 'date' => date('Y-m-d H:i:s')])) {
						return response()->json(['text' => 'El código fue redimido correctamente.'], 200);
                    } else {
						return response()->json(['text' => 'El código no pudo ser redimido.'], 400);
					}
    			default:
    				return view('/team/gifts', ['request' => $request]);
    		}
    	}
    }
}