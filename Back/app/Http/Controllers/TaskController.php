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


class TaskController extends Controller
{
	public function main (Request $request, $task = null, $item = null) {
        if (isset($item)) {
            switch (strtolower($task)) {
                case 'load':
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
                case 'post':
                    $validator = Validator::make($request->all(), [
                        'name' => 'required|max:64',
                        'note' => 'required|max:2048',
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
                                    'TITLE' => sprintf('%s_%s', $user->firm->name, trim($request->get('name'))),
                                    'TYPE_ID' => 'SALE',
                                    'STAGE_ID' => 'C15:NEW',
                                    'LEAD_ID' => $user->firm->hand->link,
                                    'COMPANY_ID' => $user->firm->link,
                                    'CONTACT_ID' => $user->gent->link,
                                    'CATEGORY_ID' => '15',
                                    'COMMENTS' => trim($request->get('note')),
                                    'SOURCE_ID' => 'MAIL',
                                    'ORIGIN_ID' => '2'
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
    
                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                        RequestOptions::QUERY => [
                            'limit' => $take,
                            'start' => $page * $take,
                            'order' => ['DATE_CREATE' => 'desc'],
                            'select' => ['ID', 'TITLE', 'COMMENTS', 'DATE_CREATE', 'ASSIGNED_BY_ID'],
                            'filter' => [/*'COMPANY_ID' => Auth::user()->firm->link,*/ 'CATEGORY_ID' => '15']
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
            }
        }
	}
}