<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

class KindController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('kinds')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'code' => 'required|integer',
                            'name' => 'required|max:64',
                            'note' => 'required|max:512',
                            'more' => 'nullable|max:512'
                        ], [
                            'lock.in' => 'La opción no es válida.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'more.max' => 'El campo no es válido.',
                            'code.regex' => 'El campo no es válido.',
                            'code.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'note.required' => 'El campo es requerido.',
                        ]);

			            if (empty($validator->fails())) {
			            	if ((empty(($same = DB::table('kinds')
                                                  ->where('hide', 0)
                                                  ->where('code', intval($request->get('code', $item->code)))
                                                  ->first())) || ($item->row == $same->row))) {
                                if ((empty(($same = DB::table('kinds')
                                                      ->where('hide', 0)
                                                      ->where('name', trim($request->get('name', $item->name)))
                                                      ->first())) || ($item->row == $same->row))) {
                                    if (DB::table('kinds')->where('row', $item->row)->update([
                                        'mark' => date('Y-m-d H:i:s'),
                                        'name' => trim($request->get('name', $item->name)),
                                        'note' => trim($request->get('note', $item->note)),
                                        'more' => trim($request->get('more', $item->more)),
                                        'code' => intval($request->get('code', $item->code)),
                                        'lock' => intval($request->get('lock', $item->lock))
                                    ])) {
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
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['name' => 'El nombre ya existe.']
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
			        case 'lock':
			        	if (DB::table('kinds')
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
    					if (DB::table('kinds')
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
                        'code' => 'required|integer',
                        'name' => 'required|max:64',
                        'note' => 'required|max:512',
                        'more' => 'nullable|max:512'
                    ], [
                        'lock.in' => 'La opción no es válida.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'more.max' => 'El campo no es válido.',
                        'code.integer' => 'El campo no es válido.',
                        'code.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'note.required' => 'El campo es requerido.',
                    ]);

		            if (empty($validator->fails())) {
		            	if (empty(DB::table('kinds')
                                    ->where('hide', 0)
                                    ->where('code', intval($request->get('code')))
                                    ->first())) {
                            if (empty(DB::table('kinds')
                                        ->where('hide', 0)
                                        ->where('name', trim($request->get('name')))
                                        ->first())) {
                                if (($item = DB::table('kinds')->insertGetId([
                                    'made' => date('Y-m-d H:i:s'),
                                    'name' => trim($request->get('name')),
                                    'note' => trim($request->get('note')),
                                    'more' => trim($request->get('more')),
                                    'code' => intval($request->get('code')),
                                    'lock' => intval($request->get('lock')),
                                    'hash' => ($hash = md5(uniqid(rand(), true)))
                                ]))) {
                                    return response()->json([
                                        'item' => $item,
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
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => ['name' => 'El nombre ya existe.']
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
    				$query = DB::table('kinds')
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
                                $query->where('code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('code', 'asc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'lock' => intval($item->lock),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'note' => $item->note,
                            'more' => $item->more,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('kinds')
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
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'made' => $item->made,
                            'mark' => $item->mark
                        ]);

			            return $list;
			        }, [])]);
    		}
    	}
    }
}