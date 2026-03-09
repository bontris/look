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

use Intervention\Image\ImageManagerStatic as Image;

class PostController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('posts')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'code' => 'nullable|max:16',
                            'slug' => 'nullable|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i|min:4|max:64',
                            'kind' => 'nullable|numeric',
                            'hand' => 'nullable|numeric',
                            'snap' => 'nullable|mimetypes:image/jpeg,image/png|max:3072',
                            'caption' => 'nullable|max:64',
                            'content' => 'nullable|max:16384',
                            'abstract' => 'nullable|max:256'
			            ], [
			            	'lock.in' => 'El campo no es válido.',
                            'code.max' => 'El campo no es válido.',
                            'slug.max' => 'El campo no es válido.',
                            'slug.max' => 'El campo no es válido.',
                            'slug.regex' => 'El campo no es válido.',
                            'kind.numeric' => 'El campo no es válido.',
                            'hand.numeric' => 'El campo no es válido.',
                            'snap.max' => 'El campo no es válido.',
                            'snap.mimetypes' => 'El campo no es válido.',
                            'caption.max' => 'El campo no es válido.',
                            'content.max' => 'El campo no es válido.',
                            'abstract.max' => 'El campo no es válido.'
			            ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('posts')
												  ->where('code', trim($request->get('code', $item->code)))
												  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('posts')
                                                      ->where('slug', trim($request->get('slug', $item->slug)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((($pick = DB::table('kinds')
                                                    ->where('row', intval($request->get('kind', $item->kind)))
                                                    ->first()) && (($pick->row == $item->kind) || (empty(intval($pick->hide)) && empty(intval($pick->lock)))))) {
                                        if ((($pick = DB::table('hands')
                                                        ->where('row', intval($request->get('hand', $item->hand)))
                                                        ->first()) && (($pick->row == $item->hand) || (empty(intval($pick->hide)) && empty(intval($pick->lock)))))) {
                                            if ((empty($request->file('snap')) || Image::make($request->file('snap'))->save(sprintf('%s/%s', storage_path('files'), ($snap = md5(uniqid(rand(), true))))))) {
                                                if (DB::table('posts')
                                                      ->where('row', $item->row)
                                                      ->update(['code' => trim($request->get('code', $item->code)),
                                                                'slug' => trim($request->get('slug', $item->slug)),
                                                                'kind' => intval($request->get('kind', $item->kind)),
                                                                'hand' => intval($request->get('hand', $item->hand)),
                                                                'snap' => ($snap = isset($snap) ? $snap : $item->snap),
                                                                'caption' => trim($request->get('caption', $item->caption)),
                                                                'content' => trim($request->get('content', $item->content)),
                                                                'abstract' => trim($request->get('abstract', $item->abstract)),
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
                                            return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                                     'list' => ['hand' => 'La opción no es válida.']], 400);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													             'list' => ['kind' => 'La opción no es válida.']], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													         'list' => ['slug' => 'El enlace único ya existe.']], 400);
                                }
							} else {
								return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
															'list' => ['code' => 'El código ya existe.']], 400);
							}
			            } else {
			            	return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
			            }
			        case 'lock':
			        	if (DB::table('posts')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($flag = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['flag' => $flag, 'text' => ($flag ? 'El registro fue habilitado con éxito.' : 'El registro fue bloqueado con éxito.')], 200);
			            } else {
			              	return response()->json(['text' => ($flag ? 'El registro no pudo ser habilitado.' : 'El registro no pudo ser bloqueado.')], 500);
			            }
    				case 'drop':
    					if (DB::table('posts')
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
                        'slug' => 'nullable|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i|min:4|max:64',
                        'kind' => 'required|numeric',
                        'hand' => 'required|numeric',
                        'snap' => 'required|mimetypes:image/jpeg,image/png|max:3072',
			            'caption' => 'required|max:64',
			            'content' => 'required|max:16384',
			            'abstract' => 'required|max:256'
		            ], [
		            	'lock.in' => 'El campo no es válido.',
		            	'code.max' => 'El campo no es válido.',
                        'slug.min' => 'El campo no es válido.',
						'slug.max' => 'El campo no es válido.',
                        'slug.regex' => 'El campo no es válido.',
						'kind.numeric' => 'El campo no es válido.',
                        'kind.required' => 'El campo es requerido.',
                        'hand.numeric' => 'El campo no es válido.',
                        'hand.required' => 'El campo es requerido.',
                        'snap.max' => 'El campo no es válido.',
                        'snap.mimetypes' => 'El campo no es válido.',
                        'snap.required' => 'El campo es requerido.',
		                'caption.max' => 'El campo no es válido.',
		                'caption.required' => 'El campo es requerido.',
                        'content.max' => 'El campo no es válido.',
		                'content.required' => 'El campo es requerido.',
		                'abstract.max' => 'El campo no es válido.',
		                'abstract.required' => 'El campo es requerido.'
		            ]);

		            if (empty($validator->fails())) {
		            	if (empty(($same = DB::table('posts')
											 ->where('hide', 0)
											 ->where('code', ($code = hexdec(uniqid())))
											 ->first()))) {
                            if (empty(($same = DB::table('posts')
                                                ->where('hide', 0)
                                                ->where('slug', ($slug = trim($request->get('slug')) ? trim($request->get('slug')) : Str::slug($request->get('caption'), '-')))
                                                ->first()))) {
                                if (($pick = DB::table('kinds')
                                               ->where('hide', 0)
                                               ->where('lock', 0)
                                               ->where('row', trim($request->get('kind')))
                                               ->first())) {
                                    if (($pick = DB::table('hands')
                                                   ->where('hide', 0)
                                                   ->where('lock', 0)
                                                   ->where('row', trim($request->get('hand')))
                                                   ->first())) {
                                        if (Image::make($request->file('snap'))->save(sprintf('%s/%s', storage_path('files'), ($snap = md5(uniqid(rand(), true)))))) {
                                            if (($item = DB::table('posts')
                                                           ->insertGetId(['code' => $code,
                                                                          'slug' => $slug,
                                                                          'snap' => $snap,
                                                                          'lock' => intval($request->get('lock')),
                                                                          'kind' => intval($request->get('kind')),
                                                                          'hand' => intval($request->get('hand')),
                                                                          'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                          'caption' => trim($request->get('caption')),
                                                                          'content' => trim($request->get('content')),
                                                                          'abstract' => trim($request->get('abstract')),
                                                                          'creation' => date('Y-m-d H:i:s')]))) {
                                                return response()->json(['item' => $item,
                                                                         'code' => $code,
                                                                         'hash' => $hash,
                                                                         'slug' => $slug,
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
                                        return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                                'list' => ['hand' => 'La opción no es válida.']], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													         'list' => ['kind' => 'La opción no es válida.']], 400);
                                }
                            } else {
                                return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
													     'list' => ['slug' => 'El enlace único ya existe.']], 400);
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
    				$query = DB::table('posts')
			                   ->where('posts.hide', 0)
                               ->join('kinds', function ($join) {
                                   $join->on('posts.kind', 'kinds.row');
                               });
			                
			        if (($find = trim($request->post('find')))) {
			            $query->where(function ($query) use ($find) {
							$query->where('posts.code', 'like', sprintf('%%%s%%', $find))
                                  ->orWhere('posts.slug', 'like', sprintf('%%%s%%', $find))
							      ->orWhere('posts.caption', 'like', sprintf('%%%s%%', $find))
								  ->orWhere('posts.abstract', 'like', sprintf('%%%s%%', $find));
						});
			        }

			        return response()->json(['high' => ($high = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
			                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $high))
                                                                          ->select('posts.*', 'kinds.name AS kind')
			                                                              ->orderBy(($take ? 'posts.creation' : 'posts.code'), ($take ? 'desc' : 'asc'))
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['lock' => intval($item->lock),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'code' => $item->code,
                                           'slug' => $item->slug,
			                               'kind' => $item->kind,
                                           'snap' => $item->snap,
                                           'caption' => $item->caption,
                                           'creation' => $item->creation]);

			            return $list;
			        }, [])]);
			    case 'pull':
		            $query = DB::table('posts')
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
										   'slug' => $item->slug,
										   'snap' => $item->snap,
										   'caption' => $item->caption]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/team/posts', ['request' => $request]);
    		}
    	}
    }
}