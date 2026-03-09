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

class SpotController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('spots')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'sza' => 'nullable|numeric',
                            'szi' => 'nullable|numeric',
                            'swa' => 'nullable|numeric',
                            'swi' => 'nullable|numeric',
                            'sca' => 'nullable|numeric',
                            'scb' => 'nullable|numeric',
                            'scc' => 'nullable|numeric',
                            'scx' => 'nullable|numeric',
                            'scy' => 'nullable|numeric',
                            'scz' => 'nullable|numeric',
                            'rta' => 'nullable|numeric',
                            'rtb' => 'nullable|numeric',
                            'rtc' => 'nullable|numeric',
                            'rtx' => 'nullable|numeric',
                            'rty' => 'nullable|numeric',
                            'rtz' => 'nullable|numeric',
                            'tra' => 'nullable|numeric',
                            'trb' => 'nullable|numeric',
                            'trc' => 'nullable|numeric',
                            'trx' => 'nullable|numeric',
                            'try' => 'nullable|numeric',
                            'trz' => 'nullable|numeric',
                            'trl' => 'nullable|numeric',
                            'trt' => 'nullable|numeric',
                            'file' => 'required_if:type,2,4|max:256',
                            'path' => 'nullable|max:256',
                            'type' => 'sometimes|required|in:1,2,3,4',
                            'code' => 'sometimes|required|max:16',
							'name' => 'sometimes|required|max:64',
                            'altitude' => 'sometimes|required|numeric',
                            'latitude' => 'sometimes|required|numeric',
                            'longitude' => 'sometimes|required|numeric',
                            'description' => 'nullable|max:256'
			            ], [
                            'sza.numeric' => 'El campo no es válido.',
                            'szi.numeric' => 'El campo no es válido.',
                            'swa.numeric' => 'El campo no es válido.',
                            'swi.numeric' => 'El campo no es válido.',
                            'sca.numeric' => 'El campo no es válido.',
                            'scb.numeric' => 'El campo no es válido.',
                            'scc.numeric' => 'El campo no es válido.',
                            'scx.numeric' => 'El campo no es válido.',
                            'scy.numeric' => 'El campo no es válido.',
                            'scz.numeric' => 'El campo no es válido.',
                            'rta.numeric' => 'El campo no es válido.',
                            'rtb.numeric' => 'El campo no es válido.',
                            'rtc.numeric' => 'El campo no es válido.',
                            'rtx.numeric' => 'El campo no es válido.',
                            'rty.numeric' => 'El campo no es válido.',
                            'rtz.numeric' => 'El campo no es válido.',
                            'tra.numeric' => 'El campo no es válido.',
                            'trb.numeric' => 'El campo no es válido.',
                            'trc.numeric' => 'El campo no es válido.',
                            'trx.numeric' => 'El campo no es válido.',
                            'try.numeric' => 'El campo no es válido.',
                            'trz.numeric' => 'El campo no es válido.',
                            'trl.numeric' => 'El campo no es válido.',
                            'trt.numeric' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
							'code.max' => 'El campo no es válido.',
							'file.max' => 'El campo no es válido.',
                            'path.max' => 'El campo no es válido.',
							'name.max' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'code.required' => 'El campo es requerido.',
							'name.required' => 'El campo es requerido.',
                            'file.required_if' => 'El campo es requerido.',
                            'altitude.required' => 'El campo es requerido.',
                            'latitude.required' => 'El campo es requerido.',
                            'longitude.required' => 'El campo es requerido.',
			            ]);

			            if (empty($validator->fails())) {
							if ((empty(($same = DB::table('spots')
											      ->where('hide', 0)
											      ->where('code', trim($request->get('code', $item->code)))
											      ->first())) || ($item->row == $same->row))) {
                                if (DB::table('spots')
                                      ->where('row', $item->row)
                                      ->update(['code' => trim($request->get('code', $item->code)),
                                                'name' => trim($request->get('name', $item->name)),
                                                'type' => intval($request->get('type', $item->type)),
                                                'show' => json_encode(strcmp($request->get('swa'), $request->get('swi')) ?
                                                                        ['a' => intval($request->get('swa')),
                                                                         'i' => intval($request->get('swi'))] : intval($request->get('swa'))),
                                                  'size' => json_encode(strcmp($request->get('sza'), $request->get('szi')) ?
                                                                        ['a' => floatval($request->get('sza')),
                                                                         'i' => floatval($request->get('szi'))] : floatval($request->get('sza'))),
                                                  'scale' => json_encode(['a' => floatval($request->get('sca')),
                                                                          'b' => floatval($request->get('scb')),
                                                                          'c' => floatval($request->get('scc')),
                                                                          'x' => floatval($request->get('scx')),
                                                                          'y' => floatval($request->get('scy')),
                                                                          'z' => floatval($request->get('scz'))]),
                                                  'rotate' => json_encode(['a' => floatval($request->get('rta')),
                                                                           'b' => floatval($request->get('rtb')),
                                                                           'c' => floatval($request->get('rtc')),
                                                                           'x' => floatval($request->get('rtx')),
                                                                           'y' => floatval($request->get('rty')),
                                                                           'z' => floatval($request->get('rtz'))]),
                                                  'translate' => json_encode(['a' => floatval($request->get('tra')),
                                                                              'b' => floatval($request->get('trb')),
                                                                              'c' => floatval($request->get('trc')),
                                                                              'x' => floatval($request->get('trx')),
                                                                              'y' => floatval($request->get('try')),
                                                                              'z' => floatval($request->get('trz')),
                                                                              'l' => floatval($request->get('trl')),
                                                                              't' => floatval($request->get('trt'))]),
                                                  'altitude' => floatval($request->get('altitude', $item->altitude)),
                                                  'latitude' => floatval($request->get('latitude', $item->latitude)),
                                                  'longitude' => floatval($request->get('longitude', $item->altitude)),
                                                  'description' => trim($request->get('description', $item->description)),
                                                  'modification' => date('Y-m-d H:i:s')])) {
                                    return response()->json(['text' => 'El registro fue actualizado con éxito.'], 200);
                                } else {
                                    return response()->json(['text' => 'El registro no pudo ser actualizado.'], 500);
                                }
							} else {
								return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
	                                                     'list' => ['code' => 'El código ya existe.']], 400);
							}
			            } else {
			            	return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {
								return current($item);
							}, $validator->errors()->toArray())], 400);
			            }
			        case 'lock':
			        	if (DB::table('spots')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($flag = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['flag' => $flag, 'text' => ($flag ? 'El registro fue habilitado con éxito.' : 'El registro fue bloqueado con éxito.')], 200);
			            } else {
			              	return response()->json(['text' => ($flag ? 'El registro no pudo ser habilitado.' : 'El registro no pudo ser bloqueado.')], 500);
			            }
    				case 'drop':
    					if (DB::table('spots')
			                  ->where('row', $item->row)
			                  ->update(['hide' => 1, 'deletion' => date('Y-m-d H:i:s')])) {
			              	return response()->json(['text' => 'El registro fue eliminado con éxito.'], 200);
			            } else {
			              	return response()->json(['text' => 'El registro no pudo ser eliminado.'], 500);
			            }
    				case 'load':
                        $size = json_decode($item->size, true);

                        $show = json_decode($item->show, true);

                        $scale = json_decode($item->scale, true);

                        $rotate = json_decode($item->rotate, true);

                        $translate = json_decode($item->translate, true);

    					return response()->json([
                            'sza' => isset($size['a']) ? $size['a'] : $size,
                            'szi' => isset($size['i']) ? $size['i'] : $size,
                            'swa' => isset($show['a']) ? $show['a'] : $show,
                            'swi' => isset($show['i']) ? $show['i'] : $show,
                            'sca' => isset($scale['a']) ? $scale['a'] : 0,
                            'scb' => isset($scale['b']) ? $scale['b'] : 0,
                            'scc' => isset($scale['c']) ? $scale['c'] : 0,
                            'scx' => isset($scale['x']) ? $scale['x'] : 0,
                            'scy' => isset($scale['y']) ? $scale['y'] : 0,
                            'scz' => isset($scale['z']) ? $scale['z'] : 0,
                            'rta' => isset($rotate['a']) ? $rotate['a'] : 0,
                            'rtb' => isset($rotate['b']) ? $rotate['b'] : 0,
                            'rtc' => isset($rotate['c']) ? $rotate['c'] : 0,
                            'rtx' => isset($rotate['x']) ? $rotate['x'] : 0,
                            'rty' => isset($rotate['y']) ? $rotate['y'] : 0,
                            'rtz' => isset($rotate['z']) ? $rotate['z'] : 0,
                            'tra' => isset($translate['a']) ? $translate['a'] : 0,
                            'trb' => isset($translate['b']) ? $translate['b'] : 0,
                            'trc' => isset($translate['c']) ? $translate['c'] : 0,
                            'trx' => isset($translate['x']) ? $translate['x'] : 0,
                            'try' => isset($translate['y']) ? $translate['y'] : 0,
                            'trz' => isset($translate['z']) ? $translate['z'] : 0,
                            'trl' => isset($translate['l']) ? $translate['l'] : 0,
                            'trt' => isset($translate['t']) ? $translate['t'] : 0,
                            'item' => $item->row,
                            'type' => $item->type,
                            'code' => $item->code,
                            'name' => $item->name,
                            'file' => $item->file,
                            'path' => $item->path,
                            'altitude' => $item->altitude,
                            'latitude' => $item->latitude,
                            'longitude' => $item->longitude,
                            'description' => $item->description
                        ], 200);
    			}
    		} else {
    			return response()->json(['text' => 'El registro no fue encontrado.'], 404);
    		}
    	} else {
    		switch (strtolower($task)) {
    			case 'make':
    				$validator = Validator::make($request->all(), [
			            'sza' => 'nullable|numeric',
                        'szi' => 'nullable|numeric',
                        'swa' => 'nullable|numeric',
                        'swi' => 'nullable|numeric',
                        'sca' => 'nullable|numeric',
                        'scb' => 'nullable|numeric',
                        'scc' => 'nullable|numeric',
                        'scx' => 'nullable|numeric',
                        'scy' => 'nullable|numeric',
                        'scz' => 'nullable|numeric',
                        'rta' => 'nullable|numeric',
                        'rtb' => 'nullable|numeric',
                        'rtc' => 'nullable|numeric',
                        'rtx' => 'nullable|numeric',
                        'rty' => 'nullable|numeric',
                        'rtz' => 'nullable|numeric',
                        'tra' => 'nullable|numeric',
                        'trb' => 'nullable|numeric',
                        'trc' => 'nullable|numeric',
                        'trx' => 'nullable|numeric',
                        'try' => 'nullable|numeric',
                        'trz' => 'nullable|numeric',
                        'trl' => 'nullable|numeric',
                        'trt' => 'nullable|numeric',
                        'file' => 'required_if:type,2,4|max:256',
                        'path' => 'nullable|max:256',
                        'type' => 'required|in:1,2,3,4',
                        'code' => 'nullable|max:16',
                        'name' => 'required|max:64',
                        'altitude' => 'required|numeric',
                        'latitude' => 'required|numeric',
                        'longitude' => 'required|numeric',
                        'description' => 'nullable|max:256'
		            ], [
		            	'sza.numeric' => 'El campo no es válido.',
                        'szi.numeric' => 'El campo no es válido.',
                        'swa.numeric' => 'El campo no es válido.',
                        'swi.numeric' => 'El campo no es válido.',
                        'sca.numeric' => 'El campo no es válido.',
                        'scb.numeric' => 'El campo no es válido.',
                        'scc.numeric' => 'El campo no es válido.',
                        'scx.numeric' => 'El campo no es válido.',
                        'scy.numeric' => 'El campo no es válido.',
                        'scz.numeric' => 'El campo no es válido.',
                        'rta.numeric' => 'El campo no es válido.',
                        'rtb.numeric' => 'El campo no es válido.',
                        'rtc.numeric' => 'El campo no es válido.',
                        'rtx.numeric' => 'El campo no es válido.',
                        'rty.numeric' => 'El campo no es válido.',
                        'rtz.numeric' => 'El campo no es válido.',
                        'tra.numeric' => 'El campo no es válido.',
                        'trb.numeric' => 'El campo no es válido.',
                        'trc.numeric' => 'El campo no es válido.',
                        'trx.numeric' => 'El campo no es válido.',
                        'try.numeric' => 'El campo no es válido.',
                        'trz.numeric' => 'El campo no es válido.',
                        'trl.numeric' => 'El campo no es válido.',
                        'trt.numeric' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'code.max' => 'El campo no es válido.',
                        'file.max' => 'El campo no es válido.',
                        'path.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'code.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'file.required_if' => 'El campo es requerido.',
                        'altitude.required' => 'El campo es requerido.',
                        'latitude.required' => 'El campo es requerido.',
                        'longitude.required' => 'El campo es requerido.',
		            ]);

		            if (empty($validator->fails())) {
						if (empty(($same = DB::table('spots')
											 ->where('hide', 0)
											 ->where('code', ($code = hexdec(uniqid())))
											 ->first()))) {
                            if (($item = DB::table('spots')
                                            ->insertGetId(['code' => $code,
                                                            'name' => trim($request->get('name')),
                                                            'file' => trim($request->get('file')),
                                                            'path' => trim($request->get('path')),
                                                            'type' => intval($request->get('type')),
                                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                                            'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                            'show' => json_encode(strcmp($request->get('swa'), $request->get('swi')) ?
                                                                                  ['a' => intval($request->get('swa')),
                                                                                   'i' => intval($request->get('swi'))] : intval($request->get('swa'))),
                                                            'size' => json_encode(strcmp($request->get('sza'), $request->get('szi')) ?
                                                                                  ['a' => floatval($request->get('sza')),
                                                                                   'i' => floatval($request->get('szi'))] : floatval($request->get('sza'))),
                                                            'scale' => json_encode(['a' => floatval($request->get('sca')),
                                                                                    'b' => floatval($request->get('scb')),
                                                                                    'c' => floatval($request->get('scc')),
                                                                                    'x' => floatval($request->get('scx')),
                                                                                    'y' => floatval($request->get('scy')),
                                                                                    'z' => floatval($request->get('scz'))]),
                                                            'rotate' => json_encode(['a' => floatval($request->get('rta')),
                                                                                     'b' => floatval($request->get('rtb')),
                                                                                     'c' => floatval($request->get('rtc')),
                                                                                     'x' => floatval($request->get('rtx')),
                                                                                     'y' => floatval($request->get('rty')),
                                                                                     'z' => floatval($request->get('rtz'))]),
                                                            'translate' => json_encode(['a' => floatval($request->get('tra')),
                                                                                        'b' => floatval($request->get('trb')),
                                                                                        'c' => floatval($request->get('trc')),
                                                                                        'x' => floatval($request->get('trx')),
                                                                                        'y' => floatval($request->get('try')),
                                                                                        'z' => floatval($request->get('trz')),
                                                                                        'l' => floatval($request->get('trl')),
                                                                                        't' => floatval($request->get('trt'))]),
                                                            'altitude' => floatval($request->get('altitude', $item->altitude)),
                                                            'latitude' => floatval($request->get('latitude', $item->latitude)),
                                                            'longitude' => floatval($request->get('longitude', $item->altitude)),
                                                            'description' => trim($request->get('description', $item->description)),
                                                            'creation' => date('Y-m-d H:i:s')]))) {
                                    return response()->json(['item' => $item,
                                                             'code' => $code,
                                                             'hash' => $hash,
                                                             'tone' => $tone,
                                                             'text' => 'El registro fue guardado con éxito.'], 200);
                            } else {
                                return response()->json(['text' => 'El registro no pudo ser guardado.'], 500);
                            }
						} else {
							return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													 'list' => ['code' => 'El código ya existe.']], 400);
						}
		            } else {
		            	return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {
							return current($item);
						}, $validator->errors()->toArray())], 400);
		            }
    			case 'load':
    				$query = DB::table('spots')
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
			                                                              ->orderBy('creation', 'desc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
                                           'type' => intval($item->type),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
			                               'name' => $item->name,
										   'tone' => $item->tone]);

			            return $list;
			        }, [])]);
				case 'pull':
					$query = DB::table('spots')
							   ->where('hide', 0);

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
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
										   'name' => $item->name,
										   'tone' => $item->tone]);

						return $list;
					}, [])]);
				default:
    				return view('/spot', ['request' => $request]);
    		}
    	}
    }
}