<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Style\Fill;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

use PhpOffice\PhpSpreadsheet\Style\Alignment;


class LoadController extends Controller
{
	public function main (Request $request, $task = null) {
		switch (strtolower($task)) {
            case 'make':
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:64',
                    'note' => 'required|max:512',
                    'load.*' => 'nullable|mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,image/jpeg,image/png|max:5120'
                ], [
                    'name.max' => 'El campo no es válido.',
                    'note.max' => 'El campo no es válido.',
                    'load.*.max' => 'Cada documento no puede pesar meas de 5 Mb.',
                    'name.required' => 'El campo es requerido.',
                    'note.required' => 'El campo es requerido.',
                    'load.*.mimetypes' => 'El documento no es válido.',
                ]);

                if (empty($validator->fails())) {
                    $client = new Client();

                    $user = Auth::user();
                    
                    $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/v483s7y2yetcfagu/crm.deal.add.json', [
                        'http_errors' => false,
                        'form_params' => [
                            'fields' => [
                                'TITLE' => sprintf('%s_%s', $user->lead->firm->name, trim($request->get('name'))),
                                "TYPE_ID" => "SALE",
                                "STAGE_ID" => "C15:NEW",
                                "LEAD_ID" => $user->lead->firm->hand->link,
                                "COMPANY_ID" => $user->lead->firm->link,
                                "CONTACT_ID" => $user->lead->link,
                                "CATEGORY_ID" => "15",
                                "COMMENTS" => trim($request->get('note')),
                                "SOURCE_ID" => "MAIL",
                                "ORIGIN_ID" => "2"
                            ]
                        ]
                    ]);

                    /*$response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/z1q44ljdoc1j5tes/tasks.task.add.json', [
                        'http_errors' => false,
                        'form_params' => [
                            'fields' => [
                                'TITLE' => trim($request->get('name')),
                                'DESCRIPTION' => trim($request->get('note')),
                                'RESPONSIBLE_ID' => 879
                            ]
                        ]
                    ]);*/

                    if (($response->getStatusCode() == 200)) {
                        if (($data = json_decode($response->getBody(), true))) {print_r($data); exit(0);
                            if (count($request->file('load'))) {

                            }
                            
                            return response()->json([
                                'text' => 'El servicio fue creado con éxito.',
                                'code' => 200,
                                'data' => json_encode($data)
                                /*'task' => [
                                    'item' => $data['result']['task']['id'],
                                    'name' => $data['result']['task']['title'],
                                    'rank' => $data['result']['task']['status'],
                                    'made' => $data['result']['task']['createdDate'],
                                    'time' => intval($data['result']['task']['timeSpentInLogs']),
                                    'head' => [
                                        'item' => $data['result']['task']['creator']['id'],
                                        'name' => $data['result']['task']['creator']['name'],
                                        'link' => $data['result']['task']['creator']['link'],
                                        'icon' => $data['result']['task']['creator']['icon'],
                                        'icon' => strcmp($data['result']['task']['responsible']['icon'], '/bitrix/images/tasks/default_avatar.png') ? $data['result']['task']['responsible']['icon'] : null
                                    ],
                                    'lead' => [
                                        'item' => $data['result']['task']['responsible']['id'],
                                        'name' => $data['result']['task']['responsible']['name'],
                                        'link' => $data['result']['task']['responsible']['link'],
                                        'role' => $data['result']['task']['responsible']['workPosition'],
                                        'icon' => strcmp($data['result']['task']['responsible']['icon'], '/bitrix/images/tasks/default_avatar.png') ? $data['result']['task']['responsible']['icon'] : null
                                    ]
                                ]*/
                            ], 200);
                        } else {
                            return response()->json([
                                'text' => 'Se presentó un error no esperado.'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'text' => 'El servicio no pudo ser creado con éxito.'
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
            case 'save':
                $validator = Validator::make($request->all(), [
					'date' => 'required|date_format:Y-m'
				], [
                    'date.required' => 'El campo es requerido.',
					'date.date_format' => 'El campo no es válido.'
				]);

                if (empty($validator->fails())) {
                    $client = new Client();

                    $user = Auth::user();

                    $deal = [];

                    $deal = [];

                    $load = [];

                    $next = 0;

                    do {
                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                            RequestOptions::QUERY => [
                                'order' => ['ID' => 'desc'],
                                'start' => $next,
                                'limit' => 50,
                                'select' => ['ID', 'TITLE', 'COMMENTS', 'CONTACT_ID'],
                                'filter' => ["COMPANY_ID" => $user->lead->firm->link, 'CATEGORY_ID' => '15']
                            ]
                        ]);

                        if (($done = ($response->getStatusCode() == 200))) {
                            if (($data = json_decode($response->getBody(), true))) {
                                $deal = array_merge($deal, array_reduce($data['result'], function ($hash, $item) use ($client) {
                                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/su1llgi310o5vp59/crm.contact.get.json', [
                                        RequestOptions::QUERY => [
                                            'id' => $item['CONTACT_ID']
                                        ]
                                    ]);
                
                                    if (($response->getStatusCode() == 200)) {
                                        if (($data = json_decode($response->getBody(), true))) {
                                            $hash[sprintf('D_%d', $item['ID'])] = [
                                                'item' => $item['ID'],
                                                'name' => $item['TITLE'],
                                                'note' => $item['COMMENTS'],
                                                'head' => [
                                                    'name' => $data['result']['NAME'],
                                                    'last' => $data['result']['LAST_NAME'],
                                                    'icon' => isset($data['result']['PHOTO']) ? $data['result']['PHOTO'] : null
                                                ]
                                            ];
                                        }
                                    }

                                    return $hash;
                                }, []));
                            }
                        }
                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));

                    foreach ($deal as $deal) {
                        $next = 0;
    
                        do {
                            $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/t3ez7rurxe35vrto/tasks.task.list.json', [
                                RequestOptions::QUERY => [
                                    'order' => ['ID' => 'desc'],
                                    'start' => $next,
                                    'limit' => 50,
                                    'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $deal['item'])], '>=DATE_START' => sprintf('%s-01T00:00:00-05:00', trim($request->get('date'))), '<=DATE_START' => sprintf('%sT23:59:59-05:00', date('Y-m-t', strtotime(sprintf('%s-01', trim($request->get('date'))))))]
                                ]
                            ]);
    
                            if (($done = ($response->getStatusCode() == 200))) {
                                if (($data = json_decode($response->getBody(), true))) {
                                    $load = array_reduce($data['result']['tasks'], function ($hash, $item) use ($deal) {
                                        if (isset($item['dateStart'])) {
                                            if (isset($hash[$deal['item']])) {
                                                $hash[$deal['item']]['time'] += intval($item['timeSpentInLogs']);
                                            } else {
                                                $hash[$deal['item']] = [
                                                    'name' => $deal['name'],
                                                    'head' => $deal['head'],
                                                    'time' => intval($item['timeSpentInLogs']),
                                                    'lead' => [
                                                        'item' => $item['responsible']['id'],
                                                        'name' => $item['responsible']['name'],
                                                        'link' => $item['responsible']['link'],
                                                        'role' => $item['responsible']['workPosition']
                                                    ]
                                                ];
                                            }
                                        }

                                        
            
                                        return $hash;
                                    }, $load);
                                }
                            }
                        } while (($done && isset($data['next']) && ($next = intval($data['next']))));
                    }
               
                    return view('/dump/load', ['time' => time(), 'firm' => $user->lead->firm, 'load' => array_values($load), 'from' => strtotime(sprintf('%s-01', trim($request->get('date')))), 'stop' => strtotime(date('Y-m-t', strtotime(sprintf('%s-01', trim($request->get('date')))))), 'list' => [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    ]]);
				} else {
					return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
											 'list' => array_map(function ($item) {
						return current($item);
					}, $validator->errors()->toArray())], 400);
				}
			case 'load':
                set_time_limit(0);

                $client = new Client();

                $firm = Auth::user()->firm;

                $done = false;

                $heap = [];

                $load = [];

                $take = 50;

                $next = 0;

                if (intval($firm->link)) {
                    do {
                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                            RequestOptions::QUERY => [
                                'limit' => $take,
                                'start' => $next,
                                'order' => ['DATE_CREATE' => 'desc'],
                                'select' => ['ID', 'TITLE', 'COMMENTS'],
                                'filter' => ["COMPANY_ID" => $firm->link, 'CATEGORY_ID' => '15']
                            ]
                        ]);
    
                        if (($done = ($response->getStatusCode() == 200))) {
                            if (($data = json_decode($response->getBody(), true))) {
                                $heap = array_merge($heap, array_reduce($data['result'], function ($hash, $item) {
                                    $hash[sprintf('D_%d', $item['ID'])] = ['item' => $item['ID'], 'name' => $item['TITLE'], 'note' => $item['COMMENTS']];
    
                                    return $hash;
                                }, []));
                            }
                        }
                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));
                }

                foreach ($heap as $deal) {
                    $next = 0;

                    do {
                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/t3ez7rurxe35vrto/tasks.task.list.json', [
                            RequestOptions::QUERY => [
                                'order' => ['ID' => 'desc'],
                                'start' => $next,
                                'limit' => 50,
                                'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $deal['item'])]]
                            ]
                        ]);

                        if (($done = ($response->getStatusCode() == 200))) {
                            if (($data = json_decode($response->getBody(), true))) {
                                $load = array_reduce($data['result']['tasks'], function ($hash, $item) use ($deal) {
                                    if (isset($item['dateStart'])) {
                                        if (isset($hash[($date = date('Y-m', ($time = strtotime($item['dateStart']))))])) {
                                            $hash[$date]['time'] += intval($item['timeSpentInLogs']);
                                        } else {
                                            $hash[$date] = ['date' => $date, 'name' => sprintf('%s de %d', [
                                                1 => 'Enero',
                                                2 => 'Febrero',
                                                3 => 'Marzo',
                                                4 => 'Abril',
                                                5 => 'Mayo',
                                                6 => 'Junio',
                                                7 => 'Julio',
                                                8 => 'Agosto',
                                                9 => 'Septiembre',
                                                10 => 'Octubre',
                                                11 => 'Noviembre',
                                                12 => 'Diciembre'
                                            ][date('n', $time)], date('Y', $time)), 'time' => intval($item['timeSpentInLogs'])];
                                        }
                                    }
        
                                    return $hash;
                                }, $load);
                            }
                        }
                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));
                }

                return response()->json(['list' => array_reduce($load, function ($list, $item) use ($firm) {
                    switch ($firm->plan) {
                        case 1:
                            array_push($list, [
                                'name' => $item['name'],
                                'date' => $item['date'],
                                'time' => $item['time'],
                                'cost' => ($firm->cost + ($firm->rate * max((($item['time'] / 3600) - $firm->time), 0)))
                            ]);
                            break;
                        case 3:
                            array_push($list, [
                                'name' => $item['name'],
                                'date' => $item['date'],
                                'time' => $item['time'],
                                'cost' => ($firm->rate * ($item['time'] / 3600))
                            ]);
                            break;
                        case 4:
                            array_push($list, [
                                'name' => $item['name'],
                                'date' => $item['date'],
                                'time' => $item['time'],
                                'cost' => $firm->cost
                            ]);
                            break;
                        default:
                            array_push($list, [
                                'name' => $item['name'],
                                'date' => $item['date'],
                                'time' => $item['time']
                            ]);
                    }

                    return $list;
                }, [])], 200);
		}
	}
}