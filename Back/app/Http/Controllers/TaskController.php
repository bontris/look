<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Style\Fill;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

use PhpOffice\PhpSpreadsheet\Style\Alignment;


class TaskController extends Controller
{
	public function main (Request $request, $task = null, $item = null) {
        if (isset($item)) {
            switch (strtolower($task)) {
                case 'load':
                    set_time_limit(900);

                    ignore_user_abort(true);

                    $client = new Client([
                        'request.options' => [
                            'timeout' => 1000,
                            'connect_timeout' => 1000
                        ]
                    ]);

                    $list = [];

                    $from = 0;

                    do {

                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                            RequestOptions::QUERY => [
                                'order' => ['ID' => 'desc'],
                                'start' => $from,
                                'limit' => 50,
                                //'select' => ['ID', 'TITLE', 'CONTACT_ID', 'COMPANY_ID'],
                                'filter' => ['CATEGORY_ID' => '15']
                            ]
                        ]);

                        if (($more = ($response->getStatusCode() == 200))) {
                            if (($main = json_decode($response->getBody(), true))) {
                                foreach ($main['result'] as $deal) {
                                    $next = 0;

                                    do {
                                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/t3ez7rurxe35vrto/tasks.task.list.json', [
                                            RequestOptions::QUERY => [
                                                'limit' => 50,
                                                'start' => $next,
                                                'order' => ['ID' => 'asc'],
                                                'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $deal['ID'])]],
                                                'select' => ['ID', 'GUID', 'TITLE', 'DEADLINE', 'STATUS', 'PRIORITY', 'DESCRIPTION', 'CREATED_BY', 'CREATED_DATE', 'DURATION_TYPE', 'RESPONSIBLE_ID', 'TIME_SPENT_IN_LOGS'],
                                            ]
                                        ]);

                                        if (($done = ($response->getStatusCode() == 200))) {
                                            if (($data = json_decode($response->getBody(), true))) {return response()->json($data);
                                                foreach ($data['result']['tasks'] as $item) {
                                                    array_push($list, [
                                                        'item' => intval($item['id']),
                                                        'link' => intval($deal['CONTACT_ID']),
                                                        'bind' => intval($deal['COMPANY_ID']),
                                                        'skip' => intval($item['createdBy']),
                                                        'done' => intval($item['status']),
                                                        'rank' => intval($item['priority']),
                                                        'name' => trim($item['title']),
                                                        'note' => trim($item['description']),
                                                        'head' => intval($item['responsibleId']),
                                                        'time' => intval($item['timeSpentInLogs']),
                                                        'hash' => preg_replace('/[^\w\d]/', '', $item['guid']),
                                                        'made' => date('Y-m-d H:i:s', strtotime($item['createdDate'])),
                                                        'date' => isset($item['deadline']) ? date('Y-m-d H:i:s', strtotime($item['deadline'])) : null
                                                    ]);
                                                }
                                            }
                                        }
                                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));
                                }
                            }
                        }
                    } while (($more && isset($main['next']) && ($from = intval($main['next']))));
                    
                    return response()->json($list);
                    /// PRODUCTION

                    $page = max(intval($request->get('page')), 0);
    
                    $take = 50;
    
                    $size = 0;

                    $response = (new Client())->request('GET', 'https://tallera.bitrix24.es/rest/1/t3ez7rurxe35vrto/tasks.task.list.json', [
                        RequestOptions::QUERY => [
                            'limit' => $take,
                            'start' => $page * $take,
                            'order' => ['ID' => 'desc'],
                            'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $item)]]
                        ]
                    ]);

                    if (($done = ($response->getStatusCode() == 200))) {
                        if (($data = json_decode($response->getBody(), true))) {
                            return response()->json(['page' => $page,
                                                     'take' => $take,
                                                     'size' => intval($data['total']),
                                                     'list' => array_reduce($data['result']['tasks'], function ($list, $item) {
                                array_push($list, [
                                    'name' => $item['title'],
                                    'item' => intval($item['id']),
                                    'made' => $item['createdDate'],
                                    'rank' => intval($item['status']),
                                    'time' => intval($item['timeSpentInLogs']),
                                    'head' => [
                                        'item' => $item['creator']['id'],
                                        'name' => $item['creator']['name'],
                                        'link' => $item['creator']['link'],
                                        'icon' => strcmp($item['responsible']['icon'], '/bitrix/images/tasks/default_avatar.png') ? $item['responsible']['icon'] : null
                                    ],
                                    'lead' => [
                                        'item' => $item['responsible']['id'],
                                        'name' => $item['responsible']['name'],
                                        'link' => $item['responsible']['link'],
                                        'role' => $item['responsible']['workPosition'],
                                        'icon' => strcmp($item['responsible']['icon'], '/bitrix/images/tasks/default_avatar.png') ? $item['responsible']['icon'] : null
                                    ]
                                ]);
    
                                return $list;
                            }, [])], 200);
                        } else {
                            return response()->json([
                                'text' => 'La consulta presentó un error.'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'text' => 'No se pudo realizar la consulta.'
                        ], 500);
                    }
                default:
                    $response = (new Client())->request('GET', 'https://tallera.bitrix24.es/rest/1/pvxzorum0wxywvcb/crm.deal.get.json', [
                        RequestOptions::QUERY => [
                            'id' => $item
                        ]
                    ]);

                    if (($done = ($response->getStatusCode() == 200))) {
                        if (($data = json_decode($response->getBody(), true))) {
                            if (true/*empty(strcmp($data['result']['COMPANY_ID'], Auth::user()->firm->link))*/) {
                                return response()->json(['name' => $data['result']['TITLE'], 'note' => $data['result']['COMMENTS'], 'date' => $data['result']['DATE_CREATE']], 200);
                            } else {
                                return response()->json([
                                    'text' => 'No tienes permiso para acceder a este objeto.'
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'text' => 'La consulta presentó un error.'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'text' => 'No se pudo realizar la consulta.'
                        ], 500);
                    }
            }
        } else {
            switch (strtolower($task)) {
                case 'load':
                    $query = DB::table('tasks')
                               ->where('tasks.hide', 0)
                               ->leftJoin('firms', function ($join) {
                                    $join->on('tasks.bind', '=', 'firms.link')
                                         ->where('firms.hide', '=', 0)
                                         ->where('firms.core', '=', 0);
                               });

                    if (Auth::check() && Auth::user()->type != 1) {
                        $query->where('tasks.bind', Auth::user()->firm->link);
                    }

                    if (($find = trim($request->get('find')))) {
                        $query->where(function ($query) use ($find) {
                            $query->where('tasks.name', 'like', sprintf('%%%s%%', $find))
                                  ->orWhere('firms.name', 'like', sprintf('%%%s%%', $find));
                        });
                    }

                    $size = $query->count();
                    $take = min(max(intval($request->get('take')), 0), 64);
                    $page = $take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0;

                    return response()->json([
                        'size' => $size,
                        'take' => $take,
                        'page' => $page,
                        'list' => array_reduce($query->skip($take ? ($page * $take) : 0)
                                                     ->take($take ? $take : $size)
                                                     ->select('tasks.*', DB::raw('firms.name AS firm'), DB::raw(sprintf("CONVERT_TZ(tasks.made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                     ->orderBy('tasks.made', 'desc')
                                                     ->get()
                                                     ->toArray(), function ($list, $item) {
                            array_push($list, [
                                'item' => intval($item->row),
                                'hash' => $item->hash,
                                'name' => $item->name,
                                'head' => intval($item->head),
                                'done' => intval($item->done),
                                'time' => intval($item->time),
                                'firm' => $item->firm,
                                'made' => $item->made
                            ]);

                            return $list;
                        }, [])
                    ]);
                case 'post':
                    $validator = Validator::make($request->all(), [
                        'name' => 'required|max:64',
                        'note' => 'required|max:2048',
                        'file' => 'nullable|max:16777216|mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,image/jpeg,image/png|max:5120'
                    ], [
                        'name.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'file.max' => 'Cada documento no puede pesar meas de 5 Mb.',
                        'name.required' => 'El campo es requerido.',
                        'note.required' => 'El campo es requerido.',
                        'file.mimetypes' => 'El documento no es válido.',
                    ]);
    
                    if (empty($validator->fails())) {
                        $client = new Client();
    
                        $user = Auth::user();

                        if (intval($user->lead->firm->link)) {
                            $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/v483s7y2yetcfagu/crm.deal.add.json', [
                                'http_errors' => false,
                                'form_params' => [
                                    'fields' => [
                                        'TITLE' => sprintf('%s_%s', $user->firm->name, trim($request->get('name'))),
                                        'TYPE_ID' => 'SALE',
                                        'STAGE_ID' => 'C15:NEW',
                                        'LEAD_ID' => $user->lead->firm->hand->link,
                                        'COMPANY_ID' => $user->lead->firm->link,
                                        'CONTACT_ID' => $user->lead->link,
                                        'CATEGORY_ID' => '15',
                                        'COMMENTS' => trim($request->get('note')),
                                        'UF_CRM_1642540414116' => trim($request->get('name')),
                                        'SOURCE_ID' => 'MAIL',
                                        'ORIGIN_ID' => '2'
                                    ]
                                ]
                            ]);

                            if (($response->getStatusCode() == 200)) {
                                if (($data = json_decode($response->getBody(), true))) {
                                    if (($file = $request->file('file'))) {
                                        $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/z1q44ljdoc1j5tes/tasks.task.add.json', [
                                            'http_errors' => false,
                                            'form_params' => [
                                                'fields' => [
                                                    'TITLE' => trim($request->get('name')),
                                                    'DESCRIPTION' => trim($request->get('note')),
                                                    'UF_CRM_TASK' => [sprintf('D_%d', $data['result'])],
                                                    'RESPONSIBLE_ID' => $user->lead->firm->hand->link
                                                ]
                                            ]
                                        ]);

                                        if (($response->getStatusCode() == 200)) {
                                            if (($task = json_decode($response->getBody(), true))) {
                                                $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/28oah8m5l4zh7bvd/task.item.addfile.json', [
                                                    'http_errors' => false,
                                                    'json' => [
                                                        'TASK_ID' => $task['result']['task']['id'],
                                                        'FILE' => [
                                                            'NAME' => $file->getClientOriginalName(),
                                                            'CONTENT' =>  base64_encode(file_get_contents($file->getRealPath()))
                                                        ]
                                                    ]
                                                ]);

                                                if (($response->getStatusCode() == 200)) {
                                                    $data['file'] = json_decode($response->getBody(), true);
                                                } else {
                                                    return response()->json([
                                                        'text' => 'No se pudo cargar el documento a Bitrix24.'
                                                    ], 500);
                                                }
                                            }
                                        } else {
                                            return response()->json([
                                                'text' => 'No se pudo crear la tarea en Bitrix24.'
                                            ], 500);
                                        }
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
                                Log::error(sprintf('Bitrix24 crm.deal.add failed: status=%d, body=%s', $response->getStatusCode(), $response->getBody()));

                                return response()->json([
                                    'text' => 'El servicio no pudo ser creado con éxito.'
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'text' => 'La empresa no está vinculada.'
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
                    $page = max(intval($request->get('page')), 0);
    
                    set_time_limit(0);
    
                    $task = [
                        'list' => [],
                        'size' => 0,
                        'next' => 0
                    ];
    
                    $client = new Client();
    
                    $user = Auth::user();
    
                    $list = [];
    
                    $task = [];
    
                    $task = [];
    
                    $take = 50;
    
                    $size = 0;
                    
                    if (intval($user->firm->link)) {
                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                            RequestOptions::QUERY => [
                                'limit' => $take,
                                'start' => $page * $take,
                                'order' => ['DATE_CREATE' => 'desc'],
                                'select' => ['ID', 'TITLE', 'COMMENTS', 'DATE_CREATE', 'ASSIGNED_BY_ID'],
                                'filter' => ['COMPANY_ID' => $user->firm->link, 'CATEGORY_ID' => '15']
                            ]
                        ]);
        
                        if (($done = ($response->getStatusCode() == 200))) {
                            if (($data = json_decode($response->getBody(), true))) {
                                return response()->json(['page' => $page,
                                                         'take' => $take,
                                                         'size' => intval($data['total']),
                                                         'list' => array_reduce($data['result'], function ($list, $item) use ($client) {
                                    array_push($list, [
                                        'item' => $item['ID'],
                                        'name' => $item['TITLE'],
                                        'note' => $item['COMMENTS'],
                                        'made' => $item['DATE_CREATE'],
                                        'head' => intval($item['ASSIGNED_BY_ID'])
                                    ]);
    
                                    return $list;
                                }, [])], 200);
                            } else {
                                return response()->json([
                                    'text' => 'La consulta presentó un error.'
                                ], 500);
                            }
                        } else {
                            return response()->json([
                                'text' => 'No se pudo realizar la consulta.'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'text' => 'La empresa no está vinculada.'
                        ], 400);
                    }
            }
        }
	}
}