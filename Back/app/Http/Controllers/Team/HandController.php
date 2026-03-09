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

class HandController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('hands')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
							'code' => 'nullable|max:16',
				            'mail' => 'nullable|email',
                            'work' => 'nullable|max:16',
				            'name' => 'nullable|max:32',
				            'last' => 'nullable|max:32',
				            'note' => 'nullable|min:32|max:256',
			            ], [
			            	'code.max' => 'El campo no es válido.',
							'work.max' => 'El campo no es válido.',
							'note.min' => 'El campo no es válido.',
			            	'note.max' => 'El campo no es válido.',
			                'name.max' => 'El campo no es válido.',
			                'last.max' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.'
			            ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('hands')
													->where('code', trim($request->get('code', $item->code)))
													->first())) || ($item->row == $same->row))) {
								if ((empty(trim($request->get('work', $item->work))) || (empty(($same = DB::table('hands')
																											->where('hide', 0)
																											->where('work', trim($request->get('work', $item->work)))
																											->first())) || ($item->row == $same->row)))) {
									if ((empty(trim($request->get('mail', $item->mail))) || (empty(($same = DB::table('hands')
																												->where('hide', 0)
																												->where('mail', trim($request->get('mail', $item->mail)))
																												->first())) || ($item->row == $same->row)))) {
										if (DB::table('hands')
											  ->where('row', $item->row)
											  ->update(['code' => trim($request->get('code', $item->code)),
														'name' => trim($request->get('name', $item->name)),
														'last' => trim($request->get('last', $item->last)),
														'work' => trim($request->get('work', $item->work)),
														'mail' => trim($request->get('mail', $item->name)),
														'note' => trim($request->get('note', $item->note)),
														'modification' => date('Y-m-d H:i:s')])) {
											return response()->json(['text' => 'El registro fue actualizado con éxito.'], 200);
										} else {
											return response()->json(['text' => 'El registro no pudo ser actualizado.'], 500);
										}
									} else {
										return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
																 'list' => ['mail' => 'El correo ya existe.']], 400);
									}
								} else {
									return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
															 'list' => ['work' => 'El teléfono ya existe.']], 400);
								}
							} else {
								return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
															'list' => ['code' => 'El código ya existe.']], 400);
							}
			            } else {
			            	return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
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
									if (DB::table('hands')
											->where('row', $item->row)
											->update(['face' => $file])) {
										if ((empty($item->face) || @unlink(sprintf('%s/%s', storage_path('files'), $item->face)))) {
											return response()->json(['file' => $file, 'text' => 'La imágen fue actualizada con éxito.'], 200);
										} else {
											return response()->json(['text' => 'La imágen no pudo ser actualizada correctamente.'], 500);
										}
									} else {
										return response()->json(['text' => 'La imágen no pudo ser actualizada.'], 500);
									}
								} else {
									return response()->json(['text' => 'La imágen no pudo ser cargada.'], 500);
								}
							} else {
								if (((empty($item->face) == false) && @unlink(sprintf('%s/%s', storage_path('files'), $item->face)) && DB::table('hands')
																																		 ->where('row', $item->row)
																																		 ->update(['face' => null]))) {
									return response()->json(['text' => 'La imágen fue eliminada con éxito.'], 200);
								} else {
									return response()->json(['text' => 'La imágen no pudo ser eliminada.'], 500);
								}
							}
						} else {
							return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
														'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
						}
			        case 'lock':
			        	if (DB::table('hands')
			                  ->where('row', $item->row)
			                  ->update(['lock' => ($flag = (intval($item->lock) ? 0 : 1))])) {
			              	return response()->json(['flag' => $flag, 'text' => ($flag ? 'El registro fue habilitado con éxito.' : 'El registro fue bloqueado con éxito.')], 200);
			            } else {
			              	return response()->json(['text' => ($flag ? 'El registro no pudo ser habilitado.' : 'El registro no pudo ser bloqueado.')], 500);
			            }
    				case 'drop':
    					if (DB::table('hands')
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
			            'name' => 'required|max:32',
			            'last' => 'required|max:32',
			            'note' => 'required|min:32|max:256',
						'mail' => 'nullable|email|max:64',
			            'work' => 'nullable|max:16',
		            ], [
		            	'lock.in' => 'El campo no es válido.',
		            	'code.max' => 'El campo no es válido.',
						'work.max' => 'El campo no es válido.',
						'note.min' => 'El campo no es válido.',
		            	'note.max' => 'El campo no es válido.',
						'mail.max' => 'El campo no es válido.',
		            	'mail.email' => 'El campo no es válido.',
		                'name.max' => 'El campo no es válido.',
		                'name.required' => 'El campo es requerido.',
		                'last.max' => 'El campo no es válido.',
		                'last.required' => 'El campo es requerido.'
		            ]);

		            if (empty($validator->fails())) {
		            	if (empty(($same = DB::table('hands')
											 ->where('hide', 0)
											 ->where('code', ($code = hexdec(uniqid())))
											 ->first()))) {
							if ((empty(trim($request->get('work'))) || empty(($same = DB::table('hands')
																						->where('hide', 0)
																						->where('work', trim($request->get('work')))
																						->first())))) {
								if ((empty(trim($request->get('mail'))) || empty(($same = DB::table('hands')
																							->where('hide', 0)
																							->where('mail', trim($request->get('mail')))
																							->first())))) {
									if (($item = DB::table('hands')
												   ->insertGetId(['code' => $code,
																  'work' => trim($request->get('work')),
																  'mail' => trim($request->get('mail')),
																  'name' => trim($request->get('name')),
																  'last' => trim($request->get('last')),
																  'note' => trim($request->get('note')),
																  'lock' => intval($request->get('lock')),
																  'hash' => ($hash = md5(uniqid(rand(), true))),
																  'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
																  'creation' => date('Y-m-d H:i:s')]))) {
										return response()->json(['item' => $item,
																 'code' => $code,
																 'hash' => $hash,
																 'tone' => sprintf('#%s', $tone),
																 'text' => 'El registro fue guardado con éxito.'], 200);
									} else {
										return response()->json(['text' => 'El registro no pudo ser guardado.'], 500);
									}
								} else {
									return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
															 'list' => ['mail' => 'El correo ya existe.']], 400);
								}
							} else {
								return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
														 'list' => ['work' => 'El teléfono ya existe.']], 400);
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
    				$query = DB::table('hands')
			                   ->where('hide', 0);
			                
			        if (($find = trim($request->post('find')))) {
			            $query->where(function ($query) use ($find) {
							$query->where('code', 'like', sprintf('%%%s%%', $find))
							      ->orWhere('mail', 'like', sprintf('%%%s%%', $find))
								  ->orWhere('work', 'like', sprintf('%%%s%%', $find))
								  ->orWhere('last', 'like', sprintf('%%%s%%', $find))
								  ->orWhere('name', 'like', sprintf('%%%s%%', $find));
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
			            array_push($list, ['tone' => sprintf('#%s', $item->tone),
                                           'lock' => intval($item->lock),
			            	               'item' => intval($item->row),
										   'tone' => $item->tone,
			                               'hash' => $item->hash,
			                               'code' => $item->code,
			                               'work' => $item->work,
                                           'mail' => $item->mail,
			                               'name' => $item->name,
			                               'last' => $item->last,
			                               'face' => $item->face]);

			            return $list;
			        }, [])]);
			    case 'pull':
		            $query = DB::table('hands')
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
										   'item' => intval($item->row),
										   'hash' => $item->hash,
										   'code' => $item->code,
										   'name' => $item->name,
										   'last' => $item->last,
										   'tone' => $item->tone,
										   'face' => $item->face]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/team/hands', ['request' => $request]);
    		}
    	}
    }
}