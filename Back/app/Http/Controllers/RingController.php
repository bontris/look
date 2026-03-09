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

class RingController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('rings')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'type' => 'required|in:1,2,3,4',
                            'name' => 'required|max:64',
                            'note' => 'required|max:256',
                            'skip' => 'required|integer',
                            'link' => 'nullable|url:http,https|max:128'
                        ], [
                            'type.in' => 'La opción no es válida.',
                            'name.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'link.max' => 'El campo no es válido.',
                            'link.url' => 'El campo no es válido.',
                            'skip.integer' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'note.required' => 'El campo es requerido.',
                            'skip.required' => 'El campo es requerido.'
                        ]);

			            if (empty($validator->fails())) {
			            	if ((($skip = DB::table('users')
                                            ->where('hide', 0)
                                            ->where('id', intval($request->get('skip', $item->skip)))
                                            ->first()))) {
                                if (DB::table('rings')->where('row', $item->row)->update([
                                    'mark' => date('Y-m-d H:i:s'),
                                    'name' => trim($request->get('name', $item->name)),
                                    'note' => trim($request->get('note', $item->note)),
                                    'link' => trim($request->get('link', $item->link)),
                                    'type' => intval($request->get('type', $item->type)),
                                    'skip' => intval($request->get('skip', $item->skip)),
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
                                    'list' => ['skip' => 'El usuario no existe.']
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
    				case 'drop':
    					if (DB::table('rings')
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
                        'type' => 'required|in:1,2,3,4',
                        'name' => 'required|max:64',
                        'note' => 'required|max:256',
                        'skip' => 'required|integer',
                        'link' => 'nullable|url:http,https|max:128'
                    ], [
                        'type.in' => 'La opción no es válida.',
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'link.max' => 'El campo no es válido.',
                        'link.url' => 'El campo no es válido.',
                        'skip.integer' => 'El campo no es válido.',
                        'type.required' => 'El campo es requerido.',
                        'name.required' => 'El campo es requerido.',
                        'note.required' => 'El campo es requerido.',
                        'skip.required' => 'El campo es requerido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if ((($skip = DB::table('users')
                                        ->where('hide', 0)
                                        ->where('id', intval($request->get('skip')))
                                        ->first()))) {
                            if (($item = DB::table('rings')->insertGetId([
                                'made' => date('Y-m-d H:i:s'),
                                'name' => trim($request->get('name')),
                                'note' => trim($request->get('note')),
                                'link' => trim($request->get('link')),
                                'type' => intval($request->get('type')),
                                'skip' => intval($request->get('skip')),
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
                                'list' => ['skip' => 'El usuario no existe existe.']
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
    				$query = DB::table('rings')
                               ->where('rings.hide', 0)
                               ->join('users', function ($join) {
                                    $join->on('rings.skip', 'users.id');
                                });
                    
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
                                                $query->where(DB::raw('YEAR(rings.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(rings.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(rings.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(rings.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(rings.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(rings.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(rings.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(rings.made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                }
                            }
                        } else {
                            $query->where(function ($query) use ($find) {
                                $query->where('rings.name', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('rings.note', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('rings.*', 'users.mail',  DB::raw("CONCAT(users.name, ' ', users.last) AS `skip`"), DB::raw(sprintf("CONVERT_TZ(rings.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'skip' => intval($item->skip),
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'link' => $item->link,
                            'mail' => $item->mail,
                            'skip' => $item->skip,
                            'name' => $item->name,
                            'note' => $item->note,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('rings')
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
                            'skip' => intval($item->skip),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'link' => $item->link,
                            'name' => $item->name,
                            'note' => $item->note,
                            'made' => $item->made,
                            'mark' => $item->mark
                        ]);

			            return $list;
			        }, [])]);
    		}
    	}
    }
}