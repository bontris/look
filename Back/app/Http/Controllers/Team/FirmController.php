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

class FirmController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('firms')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->select('*', DB::raw('ST_AsText(spot) AS spot'))
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'test' => 'nullable|in:0,1',
                            'type' => 'sometimes|required|in:1,2,3',
                            'code' => 'nullable|max:16',
                            'card' => 'nullable|max:16',
                            'name' => 'sometimes|required|max:64',
                            'head' => 'nullable|max:64',
                            'note' => 'nullable|max:256',
                            'work' => 'nullable|max:16',
                            'mail' => 'sometimes|required|email|max:64',
                            'page' => 'nullable|max:128',
                            'face' => 'nullable|image|mimetypes:jpg,png',
                            'spot' => 'nullable|array|min:2',
                            'spot.*' => 'nullable|numeric'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'spot.min' => 'El campo no es válido.',
                            'code.max' => 'El campo no es válido.',
                            'card.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'page.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'head.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'page.url' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.',
                            'face.mimes' => 'El campo debe ser una imágen válida.',
                            'spot.array' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'card.required' => 'El campo es requerido.',
                            'mail.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'spot.*.numeric' => 'El campo no es válido.'
                        ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('firms')
                                                  ->where('hide', 0)
                                                  ->where('code', trim($request->get('code', $item->code)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('firms')
                                                      ->where('hide', 0)
                                                      ->where('card', trim($request->get('card', $item->card)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(trim($request->get('work', $item->work))) || (empty(($same = DB::table('firms')
                                                                                                              ->where('hide', 0)
                                                                                                              ->where('work', trim($request->get('work', $item->work)))
                                                                                                              ->first())) || ($item->row == $same->row)))) {
                                        if ((empty(trim($request->get('mail', $item->mail))) || (empty(($same = DB::table('firms')
                                                                                                                  ->where('hide', 0)
                                                                                                                  ->where('mail', trim($request->get('mail', $item->mail)))
                                                                                                                  ->first())) || ($item->row == $same->row)))) {
                                            if ((empty(($face = $request->file('face'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($face = md5(uniqid(rand(), true))))))) {
                                                if (DB::table('firms')->where('row', $item->row)->update([
                                                    'seen' => date('Y-m-d H:i:s'),
                                                    'face' => $face ?? $item->face,
                                                    'code' => trim($request->get('code', $item->code)),
                                                    'card' => trim($request->get('card', $item->card)),
                                                    'name' => trim($request->get('name', $item->name)),
                                                    'head' => trim($request->get('head', $item->head)),
                                                    'work' => trim($request->get('work', $item->work)),
                                                    'mail' => trim($request->get('mail', $item->mail)),
                                                    'page' => trim($request->get('page', $item->page)),
                                                    'note' => trim($request->get('note', $item->note)),
                                                    'type' => intval($request->get('type', $item->type)),
                                                    'spot' => isset(($spot = $request->post('spot'))[0]) && isset($spot[1]) ? DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                        return floatval($item);
                                                    }, $spot)))) : $item->spot
                                                ])) {
                                                    if ((empty(empty($face)) && empty(empty($item->face)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->face))))) {
                                                        Log::error(sprintf('Unable to delete the file: %s', $item->face));
                                                    } 

                                                    return response()->json([
                                                        'face' => $face,
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
                                                'list' => ['mail' => 'El correo ya existe.']
                                            ], 400);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['work' => 'El teléfono ya existe.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['card' => 'El NIT ya existe.']
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
                            'file.mimetypes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('firms')
                                          ->where('row', $item->row)
                                          ->update(['face' => $file, 'seen' => date('Y-m-d H:i:s')])) {
                                        if ((empty(empty($item->face)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->face))))) {
                                            Log::error(sprintf('Unable to delete the file: %s', $item->face));
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
                                if ((empty(empty($item->face)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->face)) && DB::table('firms')
                                                                                                                                     ->where('row', $item->row)
                                                                                                                                     ->update(['face' => null]))) {
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
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
                        }
                    case 'move':
                        $validator = Validator::make($request->all(), [
                            'spot' => 'required|array|min:2',
                            'spot.*' => 'required|numeric',
                        ], [
                            'spot.array' => 'La posición no es válida.',
                            'spot.required' => 'El campo es requerido.',
                            'spot.*.numeric' => 'La posición no es válida.',
                            'spot.*.required' => 'El campo es requerido.'
                        ]);

                        if (empty($validator->fails())) {
                            if (DB::table('firms')
                                  ->where('row', $item->row)
                                  ->update(['spot' => DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                return floatval($item);
                            }, $request->post('spot'))))), 'seen' => date('Y-m-d H:i:s')])) {
                                return response()->json([
                                    'text' => 'La ubicación fue actualizada con éxito.'
                                ], 200);
                            } else {
                                return response()->json([
                                    'text' => 'La ubicación no pudo ser actualizada.'
                                ], 500);
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
			        	if (DB::table('firms')
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
    					if (DB::table('firma')
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
    				case 'load':
    					$item = get_object_vars($item);

                        array_walk($item, function (&$item, $name) {
                            switch ($name) {
                                case 'spot':
                                    if (preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item, $item)) {
                                        $item = [floatval($item[1]), floatval($item[3])];
                                    }
                                    break;
                                case 'row':
                                    $item = intval($item);
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
                        'test' => 'nullable|in:0,1',
                        'type' => 'required|in:1,2,3',
                        'code' => 'nullable|max:16',
                        'card' => 'nullable|max:16',
                        'name' => 'required|max:64',
                        'head' => 'nullable|max:64',
                        'note' => 'nullable|max:256',
                        'work' => 'nullable|max:16',
                        'mail' => 'required|email|max:64',
                        'page' => 'nullable|max:128',
                        'face' => 'nullable|image|mimetypes:jpg,png',
                        'spot' => 'nullable|array|min:2',
                        'spot.*' => 'nullable|numeric',
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'spot.min' => 'El campo no es válido.',
                        'code.max' => 'El campo no es válido.',
                        'card.max' => 'El campo no es válido.',
                        'work.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'page.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'head.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'page.url' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'face.mimes' => 'El campo debe ser una imágen válida.',
                        'spot.array' => 'El campo no es válido.',
                        'type.required' => 'El campo es requerido.',
                        'card.required' => 'El campo es requerido.',
                        'mail.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'spot.*.numeric' => 'El campo no es válido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('firms')
                                    ->where('hide', 0)
                                    ->where('code', (($code = trim($request->get('code'))) ? $code : ($code = hexdec(uniqid()))))
                                    ->first())) {
                            if (empty(DB::table('firms')
                                        ->where('hide', 0)
                                        ->where('card', trim($request->get('code')))
                                        ->first())) {
                                if ((empty(trim($request->get('work'))) || empty(DB::table('firms')
                                                                                   ->where('hide', 0)
                                                                                   ->where('work', trim($request->get('work')))
                                                                                   ->first()))) {
                                    if ((empty(trim($request->get('mail'))) || empty(DB::table('firms')
                                                                                       ->where('hide', 0)
                                                                                       ->where('mail', trim($request->get('mail')))
                                                                                       ->first()))) {
                                        if ((empty(($face = $request->file('face'))) || Image::make($face)->save(sprintf('%s/%s', storage_path('files'), ($face = md5(uniqid(rand(), true))))))) {
                                            if (($item = DB::table('firms')->insertGetId([
                                                'code' => $code,
                                                'face' => $face,
                                                'made' => date('Y-m-d H:i:s'),
                                                'card' => trim($request->get('card')),
                                                'work' => trim($request->get('work')),
                                                'mail' => trim($request->get('mail')),
                                                'page' => trim($request->get('page')),
                                                'name' => trim($request->get('name')),
                                                'head' => trim($request->get('head')),
                                                'note' => trim($request->get('note')),
                                                'lock' => intval($request->get('lock')),
                                                'type' => intval($request->get('type')),
                                                'hash' => ($hash = md5(uniqid(rand(), true))),
                                                'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                'spot' => isset(($spot = $request->post('spot'))[0]) && isset($spot[1]) ? DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($item) {
                                                    return floatval($item);
                                                }, $spot)))) : null
                                            ]))) {
                                                return response()->json([
                                                    'item' => $item,
                                                    'code' => $code,
                                                    'hash' => $hash,
                                                    'face' => $face,
                                                    'tone' => $tone,
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
                                            'list' => ['mail' => 'El correo ya existe.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['work' => 'El teléfono ya existe.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['card' => 'El NIT ya existe.']
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
                            'list' => array_map(function ($item) {
                                return current($item);
                            }, $validator->errors()->toArray())
                        ], 400);
		            }
    			case 'load':
    				$query = DB::table('firms')
                               ->where('hide', 0);
                    
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
                                                $query->where(DB::raw('YEAR(made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'lock':
                                        $query->where(function ($query) use ($data) {
                                            foreach (explode(' ', trim($data)) as $item => $data) {
                                                if (empty($item)) {
                                                    $query->where('.lock', intval($data));
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
                                $query->where('code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('card', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['high' => ($high = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $high))
                                                                          ->select(
                                                                            '*',
                                                                            DB::raw('ST_AsText(spot) AS spot'),
                                                                            DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00')))
                                                                          )
                                                                          ->orderBy('made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'page' => $item->page,
                            'name' => $item->name,
                            'face' => $item->face,
                            'tone' => $item->tone,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('firms')
		                       ->where('hide', 0);

			        return response()->json(['high' => ($high = $query->count()),
			                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
			                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
			                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
			                                                              ->take(($take ? $take : $high))
			                                                              ->orderBy('made', 'asc')
                                                                          ->select('*', DB::raw('ST_AsText(spot) AS spot'))
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'work' => $item->work,
                            'mail' => $item->mail,
                            'page' => $item->page,
                            'name' => $item->name,
                            'face' => $item->face,
                            'tone' => $item->tone
                        ]);

			            return $list;
			        }, [])]);
    			default:
    				return view('/team/firms', [
                        'request' => $request
                    ]);
    		}
    	}
    }
}