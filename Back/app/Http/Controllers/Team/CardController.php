<?php

namespace App\Http\Controllers\Team;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class CardController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('cards')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'code' => 'nullable|max:16',
                            'name' => 'required|max:64',
                            'text' => 'required|max:128',
                            'link' => 'nullable|url|max:64',
                            'snap' => 'required|mimetypes:image/jpeg,image/png|max:3072'
			            ], [
			            	'lock.in' => 'El campo no es válido.',
                            'code.max' => 'El campo no es válido.',
                            'text.max' => 'El campo no es válido.',
                            'link.max' => 'El campo no es válido.',
                            'link.url' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'snap.max' => 'El campo no es válido.',
                            'snap.mimetypes' => 'El campo no es válido.'
			            ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('cards')
												  ->where('code', trim($request->get('code', $item->code)))
												  ->first())) || ($item->row == $same->row))) {
                                if ((empty($request->file('snap')) || Image::make($request->file('snap'))->save(sprintf('%s/%s', storage_path('files'), ($snap = md5(uniqid(rand(), true))))))) {
                                    if (DB::table('cards')
                                            ->where('row', $item->row)
                                            ->update(['code' => trim($request->get('code', $item->code)),
                                                      'name' => trim($request->get('name', $item->name)),
                                                      'text' => trim($request->get('text', $item->text)),
                                                      'link' => trim($request->get('link', $item->link)),
                                                      'snap' => ($snap = isset($snap) ? $snap : $item->snap),
                                                      'modification' => date('Y-m-d H:i:s')])) {
                                        return response()->json(['snap' => $snap,
                                                                 'text' => 'El registro fue actualizado con éxito.'], 200);
                                    } else {
                                        return response()->json(['text' => 'El registro no pudo ser actualizado.'], 500);
                                    }
                                } else {
                                    return response()->json(['text' => 'Se presentó un error el el servidor.',
                                                             'list' => ['snap' => 'La imágen no pudo ser guardada.']], 400);
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
			        	if (DB::table('cards')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($flag = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['flag' => $flag, 'text' => ($flag ? 'El registro fue habilitado con éxito.' : 'El registro fue bloqueado con éxito.')], 200);
			            } else {
			              	return response()->json(['text' => ($flag ? 'El registro no pudo ser habilitado.' : 'El registro no pudo ser bloqueado.')], 500);
			            }
    				case 'drop':
    					if (DB::table('cards')
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
                        'code' => 'nullable|max:16',
                        'name' => 'required|max:64',
                        'text' => 'required|max:128',
                        'link' => 'nullable|url|max:64',
                        'snap' => 'required|mimetypes:image/jpeg,image/png|max:3072'
		            ], [
		            	'lock.in' => 'El campo no es válido.',
		            	'code.max' => 'El campo no es válido.',
                        'text.max' => 'El campo no es válido.',
						'link.max' => 'El campo no es válido.',
                        'link.url' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
		                'name.required' => 'El campo es requerido.',
                        'snap.max' => 'El campo no es válido.',
                        'snap.mimetypes' => 'El campo no es válido.',
                        'snap.required' => 'El campo es requerido.'
		            ]);

		            if (empty($validator->fails())) {
		            	if (empty(($same = DB::table('cards')
											 ->where('hide', 0)
											 ->where('code', ($code = hexdec(uniqid())))
											 ->first()))) {
                            if (Image::make($request->file('snap'))->save(sprintf('%s/%s', storage_path('files'), ($snap = md5(uniqid(rand(), true)))))) {
                                if (($item = DB::table('posts')
                                                ->insertGetId(['code' => $code,
                                                               'snap' => $snap,
                                                               'name' => trim($request->get('name')),
                                                               'text' => trim($request->get('text')),
                                                               'link' => trim($request->get('link')),
                                                               'lock' => intval($request->get('lock')),
                                                               'hash' => ($hash = md5(uniqid(rand(), true))),
                                                               'creation' => date('Y-m-d H:i:s')]))) {
                                    return response()->json(['item' => $item,
                                                             'code' => $code,
                                                             'hash' => $hash,
                                                             'snap' => $snap,
                                                             'text' => 'El registro fue guardado con éxito.'], 200);
                                } else {
                                    return response()->json(['text' => 'El registro no pudo ser guardado.'], 500);
                                }
                            } else {
                                return response()->json(['text' => 'Se presentó un error el el servidor.',
                                                            'list' => ['snap' => 'La imágen no pudo ser guardada.']], 400);
                            }
						} else {
							return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
													 'list' => ['code' => 'El código ya existe.']], 400);
						}
		            } else {
		            	return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {
                            return current($item);
                        }, $validator->errors()->toArray())], 400);
		            }
    			case 'load':
    				$query = DB::table('cards')
			                   ->where('hide', 0);
			                
			        if (($find = trim($request->post('find')))) {
			            $query->where(function ($query) use ($find) {
							$query->where('code', 'like', sprintf('%%%s%%', $find))
                                  ->orWhere('name', 'like', sprintf('%%%s%%', $find))
							      ->orWhere('text', 'like', sprintf('%%%s%%', $find));
						});
			        }

			        return response()->json(['high' => ($high = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
			                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $high))
			                                                              ->orderBy(($take ? 'creation' : 'code'), ($take ? 'desc' : 'asc'))
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
                                           'link' => $item->link,
                                           'snap' => $item->snap,
                                           'name' => $item->name]);

			            return $list;
			        }, [])]);
			    case 'pull':
		            $query = DB::table('cards')
		                       ->where('hide', 0);

			        return response()->json(['high' => ($high = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
			                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $high))
			                                                              ->orderBy('creation', 'asc')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
										   'name' => $item->slug,
										   'link' => $item->link,
										   'snap' => $item->snap]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/team/cards', ['request' => $request]);
    		}
    	}
    }
}