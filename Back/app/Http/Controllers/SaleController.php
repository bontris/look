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

class SaleController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('sales')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'save':
    					$validator = Validator::make($request->all(), [
                            'paid' => 'nullable|in:0,1',
                            'time' => 'required|integer|min:1',
                            'cost' => 'required|numeric|min:0',
                            'note' => 'nullable|string|max:256',
                            'date' => 'required|date_format:Y-m-d',
                            'code' => 'nullable|regex:/^([A-Z0-9]){2,16}$/'
                        ], [
                            'type.in' => 'La opción no es válida.',
                            'paid.in' => 'La opción no es válida.',
                            'time.min' => 'El campo no es válido.',
                            'cost.min' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'code.regex' => 'El campo no es válido.',
                            'note.string' => 'El campo no es válido.',
                            'bind.integer' => 'El campo no es válido.',
                            'time.integer' => 'El campo no es válido.',
                            'cost.numeric' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'bind.required' => 'El campo es requerido.',
                            'time.required' => 'El campo es requerido.',
                            'cost.required' => 'El campo es requerido.',
                            'date.required' => 'El campo es requerido.',
                            'date.date_format' => 'El campo no es válido.'
                        ]);

			            if (empty($validator->fails())) {
                            if (DB::table('sales')->where('id', $item->id)->update([
                                'mark' => date('Y-m-d H:i:s'),
                                'date' => trim($request->get('date', $item->date)),
                                'code' => trim($request->get('code', $item->code)),
                                'note' => trim($request->get('note', $item->note)),
                                'time' => intval($request->get('time', $item->time)),
                                'paid' => intval($request->get('paid', $item->paid)),
                                'cost' => floatval($request->get('cost', $item->cost))
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
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
			            }
			        case 'lock':
			        	if (DB::table('sales')
			                  ->where('id', $item->id)
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
    					if (DB::table('sales')
			                  ->where('id', $item->id)
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
                        'type' => 'required|in:1',
                        'paid' => 'nullable|in:0,1',
                        'push' => 'nullable|in:0,1',
                        'bind' => 'required|integer',
                        'time' => 'required|numeric|min:1',
                        'cost' => 'required|numeric|min:0',
                        'note' => 'nullable|string|max:256',
                        'date' => 'required|date_format:Y-m-d',
                        'code' => 'nullable|regex:/^([A-Z0-9]){2,16}$/'
                    ], [
                        'type.in' => 'La opción no es válida.',
                        'paid.in' => 'La opción no es válida.',
                        'push.in' => 'La opción no es válida.',
                        'time.min' => 'El campo no es válido.',
                        'cost.min' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'code.regex' => 'El campo no es válido.',
                        'note.string' => 'El campo no es válido.',
                        'bind.integer' => 'El campo no es válido.',
                        'time.numeric' => 'El campo no es válido.',
                        'cost.numeric' => 'El campo no es válido.',
                        'type.required' => 'El campo es requerido.',
                        'bind.required' => 'El campo es requerido.',
                        'time.required' => 'El campo es requerido.',
                        'cost.required' => 'El campo es requerido.',
                        'date.required' => 'El campo es requerido.',
                        'date.date_format' => 'El campo no es válido.'
                    ]);

		            if (empty($validator->fails())) {
		            	if (($firm = DB::table('firms')
                                       ->where('hide', 0)
                                       ->where('row', intval($request->get('bind')))
                                       ->first())) {
                            try {
                                DB::beginTransaction();

                                if (($item = DB::table('sales')->insertGetId([
                                    'made' => date('Y-m-d H:i:s'),
                                    'date' => trim($request->get('date')),
                                    'note' => trim($request->get('note')),
                                    'bind' => intval($request->get('bind')),
                                    'type' => intval($request->get('type')),
                                    'time' => floatval($request->get('time')),
                                    'paid' => intval($request->get('paid')),
                                    'push' => intval($request->get('push')),
                                    'cost' => floatval($request->get('cost')),
                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                    'code' => trim($request->get('code', hexdec(uniqid())))
                                ]))) {
                                    if ((empty(intval($request->get('push'))) || DB::table('firms')->where('row', $firm->row)->update(['mark' => date('Y-m-d H:i:s'), 'time' => floatval($firm->time) +  floatval($request->get('time'))]))) {
                                        DB::commit();

                                        return response()->json([
                                            'item' => $item,
                                            'hash' => $hash,
                                            'text' => 'El registro fue guardado con éxito.'
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'text' => 'No se pudo actualizar la empresa.'
                                        ], 500);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'El registro no pudo ser guardado.'
                                    ], 500);
                                }
                            } catch (\Exception $exception) {
                                DB::rollBack();

                                Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));

                                return response()->json([
                                    'text' => 'ESe presentó un error no esperado.'
                                ], 500);
                            }
		            	} else {
                            return response()->json([
                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                'list' => ['bind' => 'La opción no es válida.']
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
    				$query = DB::table('sales')
                               ->where('sales.hide', 0)
                               ->join('firms', function ($join) {
                                    $join->on('sales.bind', 'firms.row');
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
                                                $query->where(DB::raw('YEAR(sales.date)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(sales.date))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(sales.date))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                              date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(sales.date))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(sales.date))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(sales.date))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(sales.date))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(sales.date))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'made':
                                        switch (($data = strtoupper($data))) {
                                            case 'YR':
                                                $query->where(DB::raw('YEAR(sales.made)'), date('Y', time()));
                                                break;
                                            case 'MH':
                                                $query->where(DB::raw('MONTH(sales.made))'), date('m', time()));
                                                break;
                                            case 'WK':
                                                $query->whereBetween(DB::raw('DATE(sales.made))'), [date('Y-m-d', strtotime(date('Y-m-d', strtotime('monday this week', time())))),
                                                                                                    date('Y-m-d', strtotime(date('Y-m-d', strtotime('sunday this week', time()))))]);
                                                break;
                                            case 'DY':
                                                $query->where(DB::raw('DAY(sales.made))'), date('d', time()));
                                                break;
                                            case 'NW':
                                                $query->where(function ($query) {
                                                    $query->where(DB::raw('DAY(sales.made))'), date('d', time()))
                                                            ->where(DB::raw('HOUR(sales.made))'), date('H', time()));
                                                });
                                                break;
                                            default:
                                                if (empty(count(($data = explode(' ', $data))) % 2)) {
                                                    $query->whereBetween(DB::raw('DATE(sales.made))'), [date('Y-m-d', strtotime($data[0])), date('Y-m-d', strtotime($data[1]))]);
                                                } else {
                                                    $query->where(DB::raw('DATE(sales.made))'), date('Y-m-d', strtotime($data[0])));
                                                }
                                        }
                                        break;
                                    case 'lock':
                                        $query->where(function ($query) use ($data) {
                                            foreach (explode(' ', trim($data)) as $item => $data) {
                                                if (empty($item)) {
                                                    $query->where('sales.lock', intval($data));
                                                } else {
                                                    $query->orWhere('sales.lock', intval($data));
                                                }
                                            }
                                        });
                                        break;
                                }
                            }
                        } else {
                            $query->where(function ($query) use ($find) {
                                $query->where('sales.code', 'like', sprintf('%%%s%%', $find));
                            });
                        }
                    }

                    return response()->json(['size' => ($size = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($page * $take))
                                                                          ->take(($take ? $take : $size))
                                                                          ->select('sales.*', DB::raw('firms.name AS firm'), DB::raw(sprintf("CONVERT_TZ(sales.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                          ->orderBy('sales.made', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'cost' => floatval($item->cost),
                            'type' => intval($item->type),
                            'time' => intval($item->time),
                            'lock' => intval($item->lock),
                            'paid' => intval($item->paid),
                            'item' => intval($item->id),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'firm' => $item->firm,
                            'date' => $item->date,
                            'made' => $item->made
                        ]);

                        return $list;
                    }, [])]);
			    case 'pull':
		            $query = DB::table('sales')
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
                            'cost' => floatval($item->cost),
                            'time' => intval($item->time),
                            'lock' => intval($item->lock),
                            'bind' => intval($item->bind),
                            'type' => intval($item->type),
                            'paid' => intval($item->paid),
                            'item' => intval($item->id),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'made' => $item->made,
                            'mark' => $item->mark
                        ]);

			            return $list;
			        }, [])]);
    		}
    	}
    }
}