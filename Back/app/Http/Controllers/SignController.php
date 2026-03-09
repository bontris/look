<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManagerStatic as Image;

class SignController extends Controller
{
    public function main (Request $request, $type, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('signs')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->where('type', $type)
                           ->where('bind', Auth::user()->firm->row)
                           ->first())) {
    			switch (strtolower(trim($task))) {
			        case 'lock':
			        	if (DB::table('signs')
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
    					if (DB::table('signs')
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
                        default:
                            $validator = Validator::make($request->all(), [
                                'list' => 'required|array',
                                'cell' => 'required|in:true,false',
                                'hint' => 'required|min:1|max:126',
                                'note' => 'required|min:1|max:256',
                                'file' => 'required|mimetypes:application/pdf|max:5120',
                                'list.*.name' => 'required|min:1|max:64',
                                'list.*.mail' => 'required|email|max:64',
                                'list.*.cell' => 'required|regex:/^(\+?[0-9]{2,3})?\s?([0-9]{3})(\s?[0-9]{3})?\s?([0-9]{2,4})$/'
                            ], [
                                'name.min' => 'El campo no es válido.',
                                'hint.min' => 'El campo no es válido.',
                                'note.min' => 'El campo no es válido.',
                                'name.max' => 'El campo no es válido.',
                                'mail.max' => 'El campo no es válido.',
                                'hint.max' => 'El campo no es válido.',
                                'note.max' => 'El campo no es válido.',
                                'file.max' => 'El campo no es válido.',
                                'mail.email' => 'El campo no es válido.',
                                'cell.boolean' => 'El campo no es válido.',
                                'name.required' => 'El campo es requerido.',
                                'mail.required' => 'El campo es requerido.',
                                'hint.required' => 'El campo es requerido.',
                                'note.required' => 'El campo es requerido.',
                                'cell.required' => 'El campo es requerido.',
                                'file.required' => 'El campo es requerido.',
                                'file.mimetypes' => 'El campo no es válido.'
                            ]);

                            if (empty($validator->fails())) {
                                $client = new Client(['headers' => [
                                    'Content-Length' => strlen(($data = json_encode([
                                        'file' => base64_encode(file_get_contents($request->file('file'))),
                                        'name' => pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME),
                                        'email' => env('API_AUCO_USER'),
                                        'subject' => trim($request->get('hint')),
                                        'message' => trim($request->get('note')),
                                        'remember' => 3,
                                        'otpCode' => true,
                                        'options' => [
                                            'otpCode' => 'phone',
                                            'whatsapp' => empty(strcmp($request->get('cell'), 'true'))
                                        ],
                                        'signProfile' => array_map(function ($item) {
                                            return [
                                                'name' => trim($item['name']),
                                                'email' => trim($item['mail']),
                                                'phone' => trim($item['cell']),
                                            ];
                                        }, $request->get('list'))
                                    ]))),
                                    'Content-Type' => 'application/json',
                                    'Authorization' => env('API_AUCO_PRIVATE')]]
                                );
                                
                                $response = $client->request('POST', sprintf('%s/%s', env('API_AUCO_ENVIRONMENT'), 'document/upload'), [
                                    'http_errors' => false,
                                    'body' => $data
                                ]);
        
                                if (($response->getStatusCode() == 200)) {
                                    if (($data = json_decode($response->getBody(), true))) {
                                        foreach ($request->get('list') as $item) {
                                            DB::table('signs')->insertGetId([
                                                'type' => 1,
                                                'cost' => 10000,
                                                'code' => $data['document'],
                                                'skip' => Auth::user()->id,
                                                'date' => date('Y-m-d H:i:s'),
                                                'name' => trim($item['name']),
                                                'mail' => trim($item['mail']),
                                                'cell' => trim($item['cell']),
                                                'hash' => md5(uniqid(rand(), true)),
                                                'hint' => trim($request->get('hint')),
                                                'note' => trim($request->get('note')),
                                                'bind' => Auth::user()->firm->row
                                            ]);
                                        }

                                        return response()->json([
                                            'code' => $data['document'],'cell'=>boolval($request->get('cell'))
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'text' => 'No se pudo procesar la solicitud.'
                                        ], 500);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'No se pudo realizar la solicitud.','code'=>$response->getStatusCode(), 'body' => $data
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
                    }
    			case 'load':
    				$query = DB::table('signs')
                               ->where('hide', 0)
                               ->where('type', $type)
                               ->where('bind', Auth::user()->firm->row);
                    
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
                                $query->where('code', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('mail', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('name', 'like', sprintf('%%%s%%', $find))
                                      ->orWhere('hint', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('*', DB::raw(sprintf("CONVERT_TZ(date, '%s', '%s') AS `date`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('date', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'lock' => intval($item->lock),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'hint' => $item->hint,
                            'note' => $item->note,
                            'name' => $item->name,
                            'mail' => $item->mail,
                            'cell' => $item->cell,
                            'date' => $item->date
                        ]);

                        return $list;
                    }, [])]);
    			default:
    				return view(sprintf('/core/sign/%s', strtolower(trim($type))), [
                        'request' => $request
                    ]);
    		}
    	}
    }
}