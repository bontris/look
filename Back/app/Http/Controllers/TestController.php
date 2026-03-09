<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use App\Models\Test;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class TestController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = Test::with('list')
                             ->where('hide', 0)
                             ->where('hash', $item)
                             ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
                        $validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'bind' => 'required|integer',
                            'code' => 'required|max:16',
                            'area' => 'nullable|max:128',
                            'work' => 'nullable|max:1024',
                            'list' => 'required|array|min:1',
                            'list.*.item' => 'required|integer',
                            'list.*.rate' => 'nullable|integer',
                            'list.*.data' => 'required|in:0,1,2,3',
                            'list.*.risk' => 'nullable|in:0,1,2,3',
                            'list.*.note' => 'nullable|string|max:1024',
                            'list.*.plan' => 'nullable|string|max:1024',
                            'file' => 'nullable|mimetypes:application/pdf|max:5120'
                        ], [
                            'lock.in' => 'El valor no es válido.',
                            'list.min' => 'El valor no es válid0.',
                            'area.max' => 'El valor no es válido.',
                            'work.max' => 'El valor no es válido.',
                            'file.max' => 'El documento no puede pesar más de 5 Mb.',
                            'list.array' => 'El campo no es válido.',
                            'bind.integer' => 'El valor no es válido.',
                            'list.required' => 'El campo es requerido.',
                            'bind.required' => 'El campo es requerido.',
                            'file.mimetypes' => 'El campo no es válido.',
                            'list.*.data.in' => 'La opción no es válida.',
                            'list.*.risk.in' => 'La opción no es válida.',
                            'list.*.note.string' => 'El campo no es válido.',
                            'list.*.plan.string' => 'El campo no es válido.',
                            'list.*.rate.integer' => 'El valor no es válido.',
                            'list.*.item.integer' => 'El valor no es válido.',
                            'list.*.item.required' => 'El campo es requerido.',
                            'list.*.data.required' => 'El campo es requerido.'
                        ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('tests')
                                                  ->where('hide', 0)
                                                  ->where('code', trim($request->get('code')))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('tests')
                                                      ->where('hide', 0)
                                                      ->where('bind', trim($request->get('bind', $item->bind)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if (count(($list = array_reduce($request->get('list'), function ($list, $next) use ($item) {
                                        if ((empty(isset($next['sort'])) || intval($next['sort']))) {
                                            if (($data = DB::table('picks')
                                                            ->where('bind', $item['row'])
                                                            ->where('item', $next['item'])
                                                            ->first())) {
                                                array_push($list, [
                                                    'item' => $data->row,
                                                    'data' => [
                                                        'data' => $next['data'] ?? $data->data ?? 0,
                                                        'risk' => $next['risk'] ?? $data->risk ?? 0,
                                                        'rate' => $next['rate'] ?? $data->rate ?? 0,
                                                        'note' => $next['note'] ?? $data->note ?? '',
                                                        'plan' => $next['plan'] ?? $data->plan ?? ''
                                                    ]
                                                ]);
                                            } else {
                                                array_push($list, [
                                                    'data' => [
                                                        'item' => $next['item'] ?? 0,
                                                        'data' => $next['data'] ?? 0,
                                                        'risk' => $next['risk'] ?? 0,
                                                        'rate' => $next['rate'] ?? 0,
                                                        'note' => $next['note'] ?? '',
                                                        'plan' => $next['plan'] ?? ''
                                                    ]
                                                ]);
                                            }
                                        }

                                        return $list;
                                    }, [])))) {
                                        try {
                                            DB::beginTransaction();

                                            if ((empty(($file = $request->file('file'))) || ($file->move(storage_path('files'), ($hash = md5(uniqid(rand(), true)))) && DB::table('files')->insertGetId([
                                                'hash' => $hash,
                                                'skip' => Auth::user()->id,
                                                'bind' => Auth::user()->bind,
                                                'made' => date('Y-m-d H:i:s'),
                                                'size' => 0,//$request->file('file')->getSize(),
                                                'type' => $file->getClientMimeType(),
                                                'name' => $file->getClientOriginalName()])))) {
                                                if (DB::table('tests')
                                                    ->where('row', $item->row)
                                                    ->update([
                                                    'mark' => date('Y-m-d H:i:s'),
                                                    'file' => isset($file) ? $hash : $item->file,
                                                    'code' => trim($request->get('code', $item->code)),
                                                    'area' => trim($request->get('area', $item->area)),
                                                    'work' => trim($request->get('work', $item->work)),
                                                    'bind' => intval($request->get('bind', $item->bind)),
                                                    'lock' => intval($request->get('lock', $item->lock))
                                                ])) {
                                                    foreach (array_reduce($item->list->toArray(), function ($data, $next) use ($list) {
                                                        if ((function ($list, $next) {
                                                            foreach ($list as $item) {
                                                                if (isset($item['item'])) {
                                                                    if (($next['item'] == $item['item'])) {
                                                                        return true;
                                                                    }
                                                                }
                                                            }

                                                            return false;
                                                        })($list, $next)) {
                                                            array_push($data, $next);
                                                        }
                                                        
                                                        return $data;
                                                    }, []) as $drop) {
                                                        DB::table('picks')
                                                          ->where('row', $drop['item'])
                                                          ->delete();
                                                    }

                                                    foreach ($list as $save) {
                                                        if (isset($save['item'])) {
                                                            DB::table('picks')
                                                              ->where('row', $save['item'])
                                                              ->update($save['data']);
                                                        } else {
                                                            DB::table('picks')->insert(array_merge($save['data'], [
                                                                'bind' => $item->row,
                                                                'code' => hexdec(uniqid()),
                                                                'hash' => md5(uniqid(rand(), true))
                                                            ]));
                                                        }
                                                    }

                                                    if ((empty(empty($file)) && empty(empty($item->file)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->file))) && empty(DB::table('files')->where('hash', $item->file)->delete()))) {
                                                        Log::error(sprintf('Unable to delete the file: %s', $item->file));
                                                    } 

                                                    DB::commit();

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
                                                    'text' => 'El documento no pudo ser cargado con éxito.'
                                                ], 500);
                                            }
                                        } catch (\Exception $exception) {
                                            DB::rollBack();
        
                                            Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));
        
                                            return response()->json([
                                                'text' => 'El registro no pudo ser actualizado.', 'fail' => $exception->getMessage(), 'line' => $exception->getLine()
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o más campos del formulario no son correctos.',
                                            'form' => ['list' => 'La opción no es válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'form' => ['bind' => 'La compañia ya existe.']
                                    ], 400);
                                }
				            } else {
				            	return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'form' => ['code' => 'El código ya existe.']
                                ], 400);
				            }
			            } else {
			            	return response()->json([
                                'text' => 'Uno o más campos del formulario no son correctos.','data' => $request->get('list'),
                                'form' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
			        case 'lock':
			        	if (DB::table('tests')
			                  ->where('row', $item->row)
			                  ->update(['lock' => boolval($request->get('lock')), 'mark' => date('Y-m-d H:i:s')])) {
			              	return response()->json([
                                'text' => 'El registro fue actualizado con éxito.'
                            ], 200);
			            } else {
                            return response()->json([
								'text' => 'El registro no pudo ser actualizado.'
							], 500);
			            }
    				case 'drop':
    					if (DB::table('tests')
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
                        'area' => 'nullable|max:128',
                        'work' => 'nullable|max:1024',
                        'list' => 'required|array|min:1',
                        'list.*.item' => 'required|integer',
                        'list.*.rate' => 'nullable|integer',
                        'list.*.data' => 'required|in:0,1,2,3',
                        'list.*.risk' => 'nullable|in:0,1,2,3',
                        'list.*.note' => 'nullable|string|max:1024',
                        'list.*.plan' => 'nullable|string|max:1024',
                        'file' => 'nullable|mimetypes:application/pdf|max:5120'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'list.min' => 'El valor no es válido.',
                        'area.max' => 'El valor no es válido.',
                        'work.max' => 'El valor no es válido.',
                        'file.max' => 'El documento no puede pesar más de 5 Mb.',
                        'list.array' => 'El campo no es válido.',
                        'bind.integer' => 'El valor no es válido.',
                        'list.required' => 'El campo es requerido.',
                        'bind.required' => 'El campo es requerido.',
                        'file.mimetypes' => 'El campo no es válido.',
                        'list.*.data.in' => 'La opción no es válida.',
                        'list.*.risk.in' => 'La opción no es válida.',
                        'list.*.note.string' => 'El campo no es válido.',
                        'list.*.plan.string' => 'El campo no es válido.',
                        'list.*.item.integer' => 'El valor no es válido.',
                        'list.*.rate.integer' => 'El valor no es válido.',
                        'list.*.item.required' => 'El campo es requerido.',
                        'list.*.data.required' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('tests')
                                    ->where('hide', 0)
                                    ->where('code', ($code = hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('tests')
                                        ->where('hide', 0)
                                        ->where('bind', trim($request->get('bind')))
                                        ->first())) {
                                try {
                                    DB::beginTransaction();
                                    
                                    if ((empty(($file = $request->file('file'))) || ($file->move(storage_path('files'), ($hash = md5(uniqid(rand(), true)))) && DB::table('files')->insertGetId([
                                            'hash' => $hash,
                                            'skip' => Auth::user()->id,
                                            'bind' => Auth::user()->bind,
                                            'made' => date('Y-m-d H:i:s'),
                                            'size' => 0,//$request->file('file')->getSize(),
                                            'type' => $file->getClientMimeType(),
                                            'name' => $file->getClientOriginalName()])))) {
                                        if (($item = DB::table('tests')->insertGetId([
                                            'code' => $code,
                                            'made' => date('Y-m-d H:i:s'),
                                            'file' => isset($file) ? $hash : null,
                                            'area' => trim($request->get('area')),
                                            'work' => trim($request->get('work')),
                                            'lock' => intval($request->get('lock')),
                                            'bind' => intval($request->get('bind')),
                                            'hash' => ($hash = md5(uniqid(rand(), true)))
                                        ]))) {
                                            foreach ($request->get('list') as $pick) {
                                                if ((empty(isset($pick['sort'])) || intval($pick['sort']))) {
                                                    DB::table('picks')->insert([
                                                        'bind' => $item,
                                                        'item' => $pick['item'],
                                                        'data' => $pick['data'],
                                                        'risk' => $pick['risk'],
                                                        'rate' => $pick['rate'],
                                                        'note' => $pick['note'],
                                                        'plan' => $pick['plan'],
                                                        'code' => hexdec(uniqid()),
                                                        'hash' => md5(uniqid(rand(), true))
                                                    ]);
                                                }
                                            }

                                            DB::commit();

                                            return response()->json([
                                                'item' => $item,
                                                'code' => $code,
                                                'hash' => $hash,
                                                'text' => 'El registro fue guardado con éxito.'
                                            ], 200);
                                        } else {
                                            return response()->json([
                                                'text' => 'El registro no pudo ser guardado.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'El documento no pudo ser cargado con éxito.'
                                        ], 500);
                                    }
                                } catch (\Exception $exception) {
                                    DB::rollBack();

                                    Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));

                                    return response()->json([
                                        'text' => 'El registro no pudo ser actualizado.', 'fail'=> $exception->getMessage()
                                    ], 500);
                                }
                                if (empty(DB::table('firms')
                                            ->where('hide', 0)
                                            ->where('work', trim($request->get('work')))
                                            ->first())) {
                                    if (empty(DB::table('firms')
                                                ->where('hide', 0)
                                                ->where('mail', trim($request->get('mail')))
                                                ->first())) {
                                        if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                            
                                        } else {
                                            return response()->json([
                                                'text' => 'La imágen no pudo ser cargada con éxito.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'form' => ['mail' => 'El correo ya existe.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'form' => ['work' => 'El teléfono ya existe.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'form' => ['firm' => 'La compañia ya existe.']
                                ], 400);
                            }
		            	} else {
                            return response()->json([
                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                'form' => ['code' => 'El código ya existe.']
                            ], 400);
		            	}
		            } else {
		            	return response()->json([
                            'text' => 'Uno o mas campos del formulario no son correctos.',
                            'form' => array_map(function ($item) {
                                return current($item);
                            }, $validator->errors()->toArray())
                        ], 400);
		            }
    			case 'load':
                    $user = Auth::user();
                    
    				$query = DB::table('tests')
                               ->where('tests.hide', 0)
                               ->join('firms', function ($join) {
                                  $join->on('tests.bind', 'firms.row');
                               });
                    
                    if (in_array($user->type, [4, 5])) {
                        $query->where('firms.row', $user->firm->row);
                    }

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
                                                $query->where(DB::raw('YEAR(tests.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(tests.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(tests.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(tests.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(tests.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(tests.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(tests.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(tests.made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'lock':
                                        $query->where(function ($query) use ($data) {
                                            foreach (explode(' ', trim($data)) as $item => $data) {
                                                if (empty($item)) {
                                                    $query->where('lock', intval($data));
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
                                $query->where('tests.code', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('tests.*', 'firms.name AS firm', DB::raw(sprintf("CONVERT_TZ(tests.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('tests.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'firm' => $item->firm,
                            'file' => $item->file,
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
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
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
                            'icon' => $item->icon,
                            'tone' => $item->tone
                        ]);

			            return $list;
			        }, [])]);
    			default:
                    $query = DB::table('firms')
		                       ->where('hide', 0);

                    $task = [
                        'list' => [],
                        'size' => 0,
                        'next' => 0
                    ];

                    
    				return view('/core/test', [
                        'request' => $request,
                        'data' => []
                    ]);
    		}
    	}
    }

    public function form (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('firms')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'type' => 'nullable|in:0,1,2,3',
                            'code' => 'nullable|max:16',
                            'card' => 'required|max:16',
                            'name' => 'required|max:64',
                            'note' => 'nullable|max:512',
                            'work' => 'nullable|max:16',
                            'mail' => 'required|email|max:64',
                            'page' => 'nullable|url:http,https|max:128',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                        ], [
                            'type.in' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.max' => 'El campo no es válido.',
                            'card.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'page.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'page.url' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.',
                            'card.required' => 'El campo es requerido.',
                            'mail.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                        ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('firms')
                                                  ->where('hide', 0)
                                                  ->where('card', trim($request->get('card')))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('firms')
                                                      ->where('hide', 0)
                                                      ->where('work', trim($request->get('work', $item->work)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if ((empty(($same = DB::table('firms')
                                                          ->where('hide', 0)
                                                          ->where('mail', trim($request->get('mail', $item->mail)))
                                                          ->first())) || ($item->row == $same->row))) {
                                        if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                            if (DB::table('firms')->where('row', $item->row)->update([
                                                'mark' => date('Y-m-d H:i:s'),
                                                'icon' => $icon ?? $item->icon,
                                                'card' => trim($request->get('card', $item->card)),
                                                'work' => trim($request->get('work', $item->work)),
                                                'mail' => trim($request->get('mail', $item->mail)),
                                                'page' => trim($request->get('page', $item->page)),
                                                'name' => trim($request->get('name', $item->name)),
                                                'note' => trim($request->get('note', $item->note)),
                                                'lock' => intval($request->get('lock', $item->lock)),
                                                'type' => intval($request->get('type', $item->type))
                                            ])) {
                                                if ((empty(empty($icon)) && empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->icon))))) {
                                                    Log::error(sprintf('Unable to delete the file: %s', $item->icon));
                                                } 

                                                return response()->json([
                                                    'icon' => $icon,
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
                                            'list' => ['work' => 'El correo ya existe.']
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
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
                    case 'icon':
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                        ], [
                            'file.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'file.mimetypes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('firms')
                                          ->where('row', $item->row)
                                          ->update(['icon' => $file, 'mark' => date('Y-m-d H:i:s')])) {
                                        if ((empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->face))))) {
                                            Log::error(sprintf('Unable to delete the file: %s', $item->icon));
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
                                if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('firms')
                                                                                                                                     ->where('row', $item->row)
                                                                                                                                     ->update(['icon' => null, 'mark' => date('Y-m-d H:i:s')]))) {
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
			        case 'lock':
			        	if (DB::table('firms')
			                  ->where('row', $item->row)
			                  ->update(['lock' => boolval($request->get('lock')), 'mark' => date('Y-m-d H:i:s')])) {
			              	return response()->json([
                                'text' => 'El registro fue actualizado con éxito.'
                            ], 200);
			            } else {
                            return response()->json([
								'text' => 'El registro no pudo ser actualizado.'
							], 500);
			            }
    				case 'drop':
    					if (DB::table('firms')
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
                        'type' => 'nullable|in:0,1,2,3',
                        'card' => 'required|max:16',
                        'name' => 'required|max:64',
                        'note' => 'nullable|max:512',
                        'work' => 'nullable|max:16',
                        'mail' => 'required|email|max:64',
                        'page' => 'nullable|url:http,https|max:128',
                        'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                        'card.max' => 'El campo no es válido.',
                        'work.max' => 'El campo no es válido.',
                        'mail.max' => 'El campo no es válido.',
                        'page.max' => 'El campo no es válido.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'page.url' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'card.required' => 'El campo es requerido.',
                        'mail.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('firms')
                                    ->where('hide', 0)
                                    ->where('code', ($code = hexdec(uniqid())))
                                    ->first())) {
                            if (empty(DB::table('firms')
                                        ->where('hide', 0)
                                        ->where('card', trim($request->get('card')))
                                        ->first())) {
                                if (empty(DB::table('firms')
                                            ->where('hide', 0)
                                            ->where('work', trim($request->get('work')))
                                            ->first())) {
                                    if (empty(DB::table('firms')
                                                ->where('hide', 0)
                                                ->where('mail', trim($request->get('mail')))
                                                ->first())) {
                                        if ((empty(($icon = $request->file('icon'))) || Image::make($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                            if (($item = DB::table('firms')->insertGetId([
                                                'code' => $code,
                                                'icon' => $icon,
                                                'made' => date('Y-m-d H:i:s'),
                                                'card' => trim($request->get('card')),
                                                'work' => trim($request->get('work')),
                                                'mail' => trim($request->get('mail')),
                                                'page' => trim($request->get('page')),
                                                'name' => trim($request->get('name')),
                                                'note' => trim($request->get('note')),
                                                'lock' => intval($request->get('lock')),
                                                'type' => intval($request->get('type')),
                                                'hash' => ($hash = md5(uniqid(rand(), true))),
                                                'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                            ]))) {
                                                return response()->json([
                                                    'item' => $item,
                                                    'code' => $code,
                                                    'hash' => $hash,
                                                    'icon' => $icon,
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
                                    'list' => ['card' => 'El número de identificación tributaria ya existe.']
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
                                                                          ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
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
                            'icon' => $item->icon,
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
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, [
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
                            'icon' => $item->icon,
                            'tone' => $item->tone
                        ]);

			            return $list;
			        }, [])]);
    			default:
                    if (false) {
                        $validator = Validator::make($request->all(), [
                            'data' => 'required|array',
                            'data.*' => 'required|in:0,1',
                        ], [
                            'data.*.integer' => 'El campo no es válido.',
                            'data.*.required' => 'El campo es requerido.',
                        ]);
                    } else {
                        $query = DB::table('firms')
		                       ->where('hide', 0);

                        $task = [
                            'list' => [],
                            'size' => 0,
                            'next' => 0
                        ];

                        
                        return view('/core/test/form', [
                            'request' => $request,
                            'data' => []
                        ]);
                    }
    		}
    	}
    }
}