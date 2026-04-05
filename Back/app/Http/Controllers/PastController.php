<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use App\Models\Past;

use GuzzleHttp\Client;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class PastController extends Controller
{
    public function main (Request $request, $type, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = Past::where('hide', 0)
                             ->where('hash', $item)
                             ->where('bind', Auth::user()->firm->row)
                             ->where('type', $type)
                             ->first())) {
    			switch (strtolower(trim($task))) {
                    case 'dump':
                        $client = new Client(['headers' => [
                            'Authorization' => env('API_AUCO_PUBLIC')]]
                        );
                        
                        $response = $client->request('GET', sprintf('%s/%s?code=%s', env('API_AUCO_ENVIRONMENT'), 'validate/background', trim($item->data->code)), [
                            'http_errors' => false
                        ]);

                        if (($response->getStatusCode() == 200)) {
                            if (($data = json_decode($response->getBody(), true))) {
                                return response()->json([
                                    'link' => isset($data['url']) ? $data['url'] : null,
                                    'done' => boolval($data['ready']),
                                    'data' => $data['data']
                                ], 200);
                            } else {
                                return response()->json([
                                    'text' => 'No se pudo obtener la información del registro.'
                                ], 500);
                            }
                        } else {
                            return response()->json(['body'=>$response->getBody(),
                                'text' => 'No se pudo obtener la información del registro.'
                            ], 404);
                        }
			        case 'lock':
			        	if (DB::table('pasts')
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
    					if (DB::table('pasts')
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
    				default:
    					return response()->json($item, 200);
    			}
    		} else {
    			return response()->json([
                    'text' => 'El registro no fue encontrado.'
                ], 404);
    		}
    	} else {
    		switch (strtolower(trim($task))) {
    			case 'post':
                    set_time_limit(0);

                    switch (strtolower(trim($type))) {
                        case 2:
                            $validator = Validator::make($request->all(), [
                                'card' => 'required|max:16',
                            ], [
                                'card.max' => 'El campo no es válido.',
                                'card.required' => 'El campo es requerido.'
                            ]);
        
                            if (empty($validator->fails())) {
                                $client = new Client(['headers' => [
                                    'Content-Length' => strlen(($data = json_encode([
                                        'email' => env('API_AUCO_EMAIL'),
                                        'country' => 'CO',
                                        'documentNumber' => trim($request->get('card'))
                                    ]))),
                                    'Content-Type' => 'application/json',
                                    'Authorization' => env('API_AUCO_PRIVATE')]]
                                );
                                
                                $response = $client->request('POST', sprintf('%s/%s', env('API_AUCO_ENVIRONMENT'), 'validate/company/representatives'), [
                                    'http_errors' => false,
                                    'body' => $data
                                ]);
        
                                if (($response->getStatusCode() == 200)) {
                                    if (($data = json_decode($response->getBody(), true))) {
                                        if (($item = DB::table('pasts')->insertGetId([
                                            'type' => 2,
                                            'cost' => 10000,
                                            'skip' => Auth::user()->id,
                                            'data' => json_encode($data),
                                            'date' => date('Y-m-d H:i:s'),
                                            'code' => ($code = hexdec(uniqid())),
                                            'card' => trim($request->get('card')),
                                            'bind' => Auth::user()->firm->row,
                                            'hash' => ($hash = md5(uniqid(rand(), true)))
                                        ]))) {
                                            DB::table('firms')
                                                ->where('row', Auth::user()->firm->row)
                                                ->decrement('bank');

                                            return response()->json([
                                                'item' => $item,
                                                'hash' => $hash,
                                                'code' => $code,
                                                'data' => $data
                                            ], 200);
                                        } else {
                                            return response()->json(['body2'=>$response->getBody(),
                                                'text' => 'No se pudo guardar la validación.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json(['body1'=>$response->getBody(),
                                            'text' => 'No se pudo procesar la validación.'
                                        ], 500);
                                    }
                                } else {
                                    return response()->json(['body'=>$response->getBody(),
                                        'text' => 'No se pudo realizar la validación.'
                                    ], 404);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => array_map(function ($item) {
                                        return current($item);
                                    }, $validator->errors()->toArray())
                                ], 400);
                            }
                        default:
                            $validator = Validator::make($request->all(), [
                                'card' => 'required|max:16',
                                'type' => 'required|in:CC,CE,NIT,PPT',
                                'date' => 'nullable|date_format:Y-m-d'
                            ], [
                                'type.in' => 'El campo no es válido.',
                                'card.max' => 'El campo no es válido.',
                                'date.date_format' => 'El campo no es válido.',
                                'type.required' => 'El campo es requerido.',
                                'from.required' => 'El campo es requerido.',
                                'card.required' => 'El campo es requerido.'
                            ]);
        
                            if (empty($validator->fails())) {
                                $client = new Client(['headers' => [
                                    'Content-Length' => strlen(($data = json_encode(array_merge([
                                        'type' => trim($request->get('type')),
                                        'email' => env('API_AUCO_EMAIL'),
                                        'identification' => trim($request->get('card'))
                                    ], ($date = trim($request->get('date'))) ? ['expeditionDate' => date('d/m/Y', strtotime($date))] : [])))),
                                    'Content-Type' => 'application/json',
                                    'Authorization' => env('API_AUCO_PRIVATE')]]
                                );
                                
                                $response = $client->request('POST', sprintf('%s/%s', env('API_AUCO_ENVIRONMENT'), 'validate/background'), [
                                    'http_errors' => false,
                                    'body' => $data
                                ]);

                                if (($response->getStatusCode() == 200)) {
                                    if (($data = json_decode($response->getBody(), true))) {
                                        if (($item = DB::table('pasts')->insertGetId([
                                            'type' => 1,
                                            'cost' => 10000,
                                            'skip' => Auth::user()->id,
                                            'data' => json_encode($data),
                                            'date' => date('Y-m-d H:i:s'),
                                            'code' => ($code = hexdec(uniqid())),
                                            'card' => trim($request->get('card')),
                                            'bind' => Auth::user()->firm->row,
                                            'hash' => ($hash = md5(uniqid(rand(), true)))
                                        ]))) {
                                            DB::table('firms')
                                                ->where('row', Auth::user()->firm->row)
                                                ->decrement('bank');

                                            return response()->json([
                                                'item' => $item,
                                                'hash' => $hash,
                                                'code' => $code
                                            ], 200);
                                        } else {
                                            return response()->json([
                                                'text' => 'No se pudo guardar la validación.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'No se pudo procesar la validación.'
                                        ], 500);
                                    }
                                } else {
                                    return response()->json(['body'=> $response->getBody()->getContents(),
                                        'text' => 'No se pudo realizar la validación.'
                                    ], 500);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'list' => array_map(function ($item) {
                                        return current($item);
                                    }, $validator->errors()->toArray())
                                ], 400);
                            }
                    }
    			case 'load':
    				$query = DB::table('pasts')
                               ->where('pasts.hide', 0)
                               ->where('pasts.type', $type)
                               ->leftJoin('firms', 'pasts.bind', '=', 'firms.row');

                    if (Auth::check() && Auth::user()->type != 1) {
                        $query->where('pasts.bind', Auth::user()->firm->row);
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
                                                $query->where(DB::raw('YEAR(date)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(date))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(date))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(date))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(date))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(date))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(date))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(date))'), date('Y-m-d', strtotime($data[0])));
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
                                $query->where('pasts.code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('pasts.card', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('firms.name', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('pasts.*', DB::raw('firms.name AS firm'), DB::raw(sprintf("CONVERT_TZ(pasts.date, '%s', '%s') AS `date`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('date', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'data' => json_decode($item->data, true),
                            'lock' => intval($item->lock),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'card' => $item->card,
                            'firm' => $item->firm,
                            'date' => $item->date
                        ]);

                        return $list;
                    }, [])]);
    			default:
    				return view(sprintf('/core/past/%s', strtolower(trim($type))), [
                        'request' => $request
                    ]);
    		}
    	}
    }
}