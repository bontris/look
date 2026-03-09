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

use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver;

class FirmController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
        try {
            if (isset($item)) {
                if (($item = DB::table('firms')
                               ->where('hide', 0)
                               ->where('core', 0)
                               ->where((preg_match('/^[0-9]+$/', $item) ? 'row' : 'hash'), $item)
                               ->first())) {
                    switch (strtolower($task)) {
                        case 'save':
                            $validator = Validator::make($request->all(), [
                                'type' => 'nullable|in:0,1,2,3',
                                'plan' => 'required|in:1,2,3,4',
                                'time' => 'required_if:plan,1,2|nullable|numeric|min:0',
                                'left' => 'required_if:plan,1|nullable|numeric|min:0',
                                'rate' => 'required_if:plan,1,3|nullable|numeric',
                                'cost' => 'required_if:plan,1,4|nullable|numeric',
                                'lead' => 'required|numeric',
                                'link' => 'nullable|numeric',
                                'code' => 'nullable|max:16',
                                'card' => 'required|max:16',
                                'name' => 'required|max:64',
                                'note' => 'nullable|max:512',
                                'work' => 'nullable|max:16',
                                'bank' => 'nullable|integer|min:0',
                                'date' => 'nullable|date_format:Y-m-d',
                                'next' => 'nullable:regex:/^[a-zA-Z0-9]{2,16}$/',
                                'disk' => 'nullable|regex:/^[\w\-\_]{16,64}$/i',
                                'mail' => 'required|email|max:64',
                                'page' => 'nullable|url:http,https|max:128',
                                'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                            ], [
                                'type.in' => 'El campo no es válido.',
                                'plan.in' => 'El campo no es válido.',
                                'time.min' => 'El campo no es válido.',
                                'left.min' => 'El campo no es válido.',
                                'bank.min' => 'El campo no es válido.',
                                'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                                'code.max' => 'El campo no es válido.',
                                'card.max' => 'El campo no es válido.',
                                'work.max' => 'El campo no es válido.',
                                'mail.max' => 'El campo no es válido.',
                                'page.max' => 'El campo no es válido.',
                                'name.max' => 'El campo no es válido.',
                                'head.max' => 'El campo no es válido.',
                                'note.max' => 'El campo no es válido.',
                                'page.url' => 'El campo no es válido.',
                                'disk.regex' => 'El campo no es válido.',
                                'next.regex' => 'El campo no es válido.',
                                'mail.email' => 'El campo no es válido.',
                                'bank.integer' => 'El campo no es válido.',
                                'time.numeric' => 'El campo no es válido.',
                                'left.numeric' => 'El campo no es válido.',
                                'rate.numeric' => 'El campo no es válido.',
                                'cost.numeric' => 'El campo no es válido.',
                                'link.numeric' => 'El campo no es válido.',
                                'lead.numeric' => 'El campo no es válido.',
                                'plan.required' => 'El campo es requerido.',
                                'card.required' => 'El campo es requerido.',
                                'lead.required' => 'El campo es requerido.',
                                'mail.required' => 'El campo es requerido.',
                                'name.required' => 'El campo es requerido.',
                                'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                                'date.date_format' => 'El campo no es válido.',
                                'time.required_if' => 'El campo es requerido.',
                                'left.required_if' => 'El campo es requerido.',
                                'rate.required_if' => 'El campo es requerido.',
                                'cost.required_if' => 'El campo es requerido.',
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
                                            if ((empty(($icon = $request->file('icon'))) || (new ImageManager(Driver::class))->read($request->file('snap'))->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                if (($link = intval((function ($pick, $plan, $name, $mail, $work, $note, &$fail) {
                                                    $client = new Client();

                                                    if (boolval($pick)) {
                                                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/mg6rujrzptmwtpo6/crm.company.get.json', [
                                                            RequestOptions::QUERY => [
                                                                'id' => $pick
                                                            ]
                                                        ]);
                                    
                                                        if (($response->getStatusCode() == 200)) {
                                                            if (($data = json_decode($response->getBody(), true))) {
                                                                return $data['result']['ID'];
                                                            }
                                                        }
                                                    } else {
                                                        $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/ghhifu76h9zgjjsq/crm.company.add.json', [
                                                            'form_params' => [
                                                                'fields' => [
                                                                    'TITLE' => $name,
                                                                    'EMAIL' => [['VALUE' => $mail, 'VALUE_TYPE' => 'WORK']],
                                                                    'PHONE' => [['VALUE' => $work, 'VALUE_TYPE' => 'WORK']],
                                                                    'COMMENTS' => $note,
                                                                    'COMPANY_TYPE' => 'CUSTOMER',
                                                                    'UF_CRM_1707420142779' => [1 => 411, 2 => 413, 3 => 415, 4 => 417][$plan]
                                                                ]
                                                            ]
                                                        ]);

                                                        if (($response->getStatusCode() == 200)) {
                                                            if (($data = json_decode($response->getBody(), true))) {
                                                                return $data['result'];
                                                            }
                                                        }
                                                    }
                                                })(intval($request->get('link')), intval($request->get('plan')), trim($request->get('name')), trim($request->get('mail')), trim($request->get('work')), trim($request->get('note')), $fail)))) {
                                                    if (DB::table('firms')->where('row', $item->row)->update([
                                                        'link' => $link,
                                                        'mark' => date('Y-m-d H:i:s'),
                                                        'icon' => $icon ?? $item->icon,
                                                        'date' => $request->get('date', $item->date),
                                                        'next' => trim($request->get('next', $item->next)),
                                                        'card' => trim($request->get('card', $item->card)),
                                                        'work' => trim($request->get('work', $item->work)),
                                                        'mail' => trim($request->get('mail', $item->mail)),
                                                        'page' => trim($request->get('page', $item->page)),
                                                        'name' => trim($request->get('name', $item->name)),
                                                        'note' => trim($request->get('note', $item->note)),
                                                        'disk' => trim($request->get('disk', $item->disk)),
                                                        'lead' => intval($request->get('lead', $item->lead)),
                                                        'lock' => intval($request->get('lock', $item->lock)),
                                                        'type' => intval($request->get('type', $item->type)),
                                                        'plan' => intval($request->get('plan', $item->plan)),
                                                        'time' => floatval($request->get('time', $item->time)),
                                                        'bank' => intval($request->get('bank', $item->bank)),
                                                        'left' => floatval($request->get('left', $item->left)),
                                                        'rate' => floatval($request->get('rate', $item->rate)),
                                                        'cost' => floatval($request->get('cost', $item->cost))
                                                    ])) {
                                                        if ((empty(empty($icon)) && empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->icon))))) {
                                                            Log::error(sprintf('Unable to delete the file: %s', $item->icon));
                                                        } 
        
                                                        return response()->json([
                                                            'icon' => $icon,
                                                            'link' => $link,
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
                                                        'list' => ['link' => 'La compañía no pudo ser vinculada.']
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
                        case 'dump':
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
                                            'filter' => ["COMPANY_ID" => $item->link, 'CATEGORY_ID' => '15']
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
                                                'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $deal['item'])]]
                                            ]
                                        ]);
                
                                        if (($done = ($response->getStatusCode() == 200))) {
                                            if (($data = json_decode($response->getBody(), true))) {
                                                $load = array_reduce($data['result']['tasks'], function ($hash, $item) use ($request, $client, $deal) {
                                                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/w54yvr09jedsdinv/task.elapseditem.getlist.json', [
                                                        RequestOptions::QUERY => [
                                                            $item['id'],
                                                            'ORDER' => ['CREATED_DATE' => 'desc'],
                                                            'FILTER' => ['>=CREATED_DATE' => sprintf('%s-01T00:00:00-05:00', trim($request->get('date'))), '<=CREATED_DATE' => sprintf('%sT23:59:59-05:00', date('Y-m-t', strtotime(sprintf('%s-01', trim($request->get('date'))))))]
                                                        ]
                                                    ]);
                            
                                                    if (($response->getStatusCode() == 200)) {
                                                        if (($time = json_decode($response->getBody(), true))) {
                                                            $hash = array_reduce($time['result'], function ($hash, $next) use ($deal, $item) {
                                                                if (isset($hash[$deal['item']])) {
                                                                    $hash[$deal['item']]['time'] += intval($next['SECONDS']);
                                                                } else {
                                                                    $hash[$deal['item']] = [
                                                                        'name' => $deal['name'],
                                                                        'head' => $deal['head'],
                                                                        'time' => intval($next['SECONDS']),
                                                                        'lead' => [
                                                                            'item' => $item['responsible']['id'],
                                                                            'name' => $item['responsible']['name'],
                                                                            'link' => $item['responsible']['link'],
                                                                            'role' => $item['responsible']['workPosition']
                                                                        ]
                                                                    ];
                                                                }
                                    
                                                                return $hash;
                                                            }, $hash);
                                                        }
                                                    }
                        
                                                    return $hash;
                                                }, $load);
                                            }
                                        }
                                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));
                                }
                        
                                return view('/dump/load', ['time' => time(), 'firm' => $item, 'load' => array_values($load), 'from' => strtotime(sprintf('%s-01', trim($request->get('date')))), 'stop' => strtotime(date('Y-m-t', strtotime(sprintf('%s-01', trim($request->get('date')))))), 'list' => [
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
                        case 'date':
                            $task = [
                                'list' => [],
                                'size' => 0,
                                'next' => 0
                            ];
            
                            $client = new Client();
            
                            $deal = [];
            
                            $task = [];
            
                            $load = [];
            
                            $next = 0;
            
                            do {
                                $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                                    RequestOptions::QUERY => [
                                        'order' => ['ID' => 'desc'],
                                        'start' => $next,
                                        'limit' => 50,
                                        'select' => ['ID', 'TITLE', 'COMMENTS'],
                                        'filter' => ["COMPANY_ID" => $item->link, 'CATEGORY_ID' => '15']
                                    ]
                                ]);
            
                                if (($done = ($response->getStatusCode() == 200))) {
                                    if (($data = json_decode($response->getBody(), true))) {
                                        $deal = array_merge($deal, array_reduce($data['result'], function ($hash, $item) {
                                            $hash[sprintf('D_%d', $item['ID'])] = ['item' => $item['ID'], 'name' => $item['TITLE'], 'note' => $item['COMMENTS']];
            
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
                                            'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $deal['item'])]]
                                        ]
                                    ]);
            
                                    if (($done = ($response->getStatusCode() == 200))) {
                                        if (($data = json_decode($response->getBody(), true))) {
                                            $load = array_reduce($data['result']['tasks'], function ($hash, $item) use ($client) {
                                                $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/w54yvr09jedsdinv/task.elapseditem.getlist.json', [
                                                    RequestOptions::QUERY => [
                                                        $item['id'],
                                                        'ORDER' => ['CREATED_DATE' => 'desc']
                                                    ]
                                                ]);
                        
                                                if (($response->getStatusCode() == 200)) {
                                                    if (($time = json_decode($response->getBody(), true))) {
                                                        $hash = array_reduce($time['result'], function ($hash, $next) use ($item) {
                                                            if (isset($hash[($date = date('Y-m', ($time = strtotime($next['CREATED_DATE']))))])) {
                                                                $hash[$date]['time'] += intval($next['SECONDS']);
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
                                                                    12 => 'Diciembre'][date('n', $time)], date('Y', $time)), 'time' => intval($next['SECONDS'])];
                                                            }
                                
                                                            return $hash;
                                                        }, $hash);
                                                    }
                                                }
                    
                                                return $hash;
                                            }, $load);
                                        }
                                    }
                                } while (($done && isset($data['next']) && ($next = intval($data['next']))));
                            }

                            return response()->json(array_values($load), 200);
                        case 'icon':
                            $validator = Validator::make($request->all(), [
                                'file' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                            ], [
                                'file.max' => 'La imágen no puede pesar más de 5 Mb.',
                                'file.mimetypes' => 'La imágen no es válida.'
                            ]);

                            if (empty($validator->fails())) {
                                if ($request->file('file')) {
                                    if ((new ImageManager(Driver::class))->read($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
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
                        case 'team':
                            $client = new Client();
        
                            $list = [];
        
                            $next = 0;
        
                            do {
                                $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/dvunwnf9osrzgkfp/crm.contact.list.json', [
                                    RequestOptions::QUERY => [
                                        'filter' => ['COMPANY_ID' => $item->link],
                                        'select' => ['ID', 'PHOTO', 'PHONE', 'NAME', 'EMAIL', 'LAST_NAME'],
                                        'order' => ['NAME' => 'asc', 'LAST_NAME' => 'asc'],
                                        'start' => $next,
                                        'limit' => 50
                                    ]
                                ]);
            
                                if (($done = ($response->getStatusCode() == 200))) {
                                    if (($data = json_decode($response->getBody(), true))) {
                                        $list = array_merge($list, array_reduce($data['result'], function ($list, $item) {
                                            array_push($list, [
                                                'item' => $item['ID'],
                                                'name' => $item['NAME'],
                                                'last' => $item['LAST_NAME'],
                                                'icon' => isset($item['PHOTO']) ? $item['PHOTO'] : null,
                                                'mail' => isset($item['EMAIL'][0]['VALUE']) ? $item['EMAIL'][0]['VALUE'] : null,
                                                'work' => isset($item['PHONE'][0]['VALUE']) ? $item['PHONE'][0]['VALUE'] : null,
                                            ]);
                
                                            return $list;
                                        }, []));
                                    }
                                }
                            } while (($done && isset($data['next']) && ($next = intval($data['next']))));
        
                            return response()->json($list, 200);
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
                            'test' => 'nullable|in:0,1',
                            'type' => 'nullable|in:0,1,2,3',
                            'plan' => 'required|in:1,2,3,4',
                            'time' => 'required_if:plan,1,2|nullable|numeric|min:0',
                            'left' => 'required_if:plan,1|nullable|numeric|min:0',
                            'rate' => 'required_if:plan,1,3|nullable|numeric',
                            'cost' => 'required_if:plan,1,4|nullable|numeric',
                            'lead' => 'required|numeric',
                            'link' => 'nullable|numeric',
                            'code' => 'nullable|max:16',
                            'card' => 'required|max:16',
                            'name' => 'required|max:64',
                            'note' => 'nullable|max:512',
                            'work' => 'nullable|max:16',
                            'date' => 'nullable|date_format:Y-m-d',
                            'next' => 'nullable:regex:/^[a-zA-Z0-9]{2,16}$/',
                            'bank' => 'nullable|integer|min:0',
                            'disk' => 'nullable|regex:/^[\w\-\_]{16,64}$/i',
                            'mail' => 'required|email|max:64',
                            'page' => 'nullable|url:http,https|max:128',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'plan.in' => 'El campo no es válido.',
                            'time.min' => 'El campo no es válido.',
                            'left.min' => 'El campo no es válido.',
                            'bank.min' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'code.max' => 'El campo no es válido.',
                            'card.max' => 'El campo no es válido.',
                            'work.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'page.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'head.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'page.url' => 'El campo no es válido.',
                            'next.regex' => 'El campo no es válido.',
                            'disk.regex' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.',
                            'bank.integer' => 'El campo no es válido.',
                            'time.numeric' => 'El campo no es válido.',
                            'left.numeric' => 'El campo no es válido.',
                            'rate.numeric' => 'El campo no es válido.',
                            'cost.numeric' => 'El campo no es válido.',
                            'link.numeric' => 'El campo no es válido.',
                            'lead.numeric' => 'El campo no es válido.',
                            'plan.required' => 'El campo es requerido.',
                            'card.required' => 'El campo es requerido.',
                            'lead.required' => 'El campo es requerido.',
                            'mail.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.',
                            'date.date_format' => 'El campo no es válido.',
                            'time.required_if' => 'El campo es requerido.',
                            'left.required_if' => 'El campo es requerido.',
                            'rate.required_if' => 'El campo es requerido.',
                            'cost.required_if' => 'El campo es requerido.'
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
                                            if (($lead = DB::table('hands')
                                                        ->where('hide', 0)
                                                        ->where('row', intval($request->get('lead')))
                                                        ->first())) {
                                                if ((empty(($icon = $request->file('icon'))) || (new ImageManager(Driver::class))->read($icon)->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                    if (($link = intval((function ($pick, $plan, $name, $mail, $work, $note, &$fail) {
                                                        $client = new Client();
        
                                                        if (boolval($pick)) {
                                                            $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/mg6rujrzptmwtpo6/crm.company.get.json', [
                                                                RequestOptions::QUERY => [
                                                                    'id' => $pick
                                                                ]
                                                            ]);
                                        
                                                            if (($response->getStatusCode() == 200)) {
                                                                if (($data = json_decode($response->getBody(), true))) {
                                                                    return $data['result']['ID'];
                                                                }
                                                            }
                                                        } else {
                                                            $response = $client->request('POST', 'https://tallera.bitrix24.es/rest/1/ghhifu76h9zgjjsq/crm.company.add.json', [
                                                                'form_params' => [
                                                                    'fields' => [
                                                                        'TITLE' => $name,
                                                                        'EMAIL' => [['VALUE' => $mail, 'VALUE_TYPE' => 'WORK']],
                                                                        'PHONE' => [['VALUE' => $work, 'VALUE_TYPE' => 'WORK']],
                                                                        'COMMENTS' => $note,
                                                                        'COMPANY_TYPE' => 'CUSTOMER',
                                                                        'UF_CRM_1707420142779' => [1 => 411, 2 => 413, 3 => 415, 4 => 417][$plan]
                                                                    ]
                                                                ]
                                                            ]);
        
                                                            if (($response->getStatusCode() == 200)) {
                                                                if (($data = json_decode($response->getBody(), true))) {
                                                                    return $data['result'];
                                                                }
                                                            }
                                                        }
                                                    })(intval($request->get('link')), intval($request->get('plan')), trim($request->get('name')), trim($request->get('mail')), trim($request->get('work')), trim($request->get('note')), $fail)))) {
                                                        if (($item = DB::table('firms')->insertGetId([
                                                            'code' => $code,
                                                            'icon' => $icon,
                                                            'link' => $link,
                                                            'made' => date('Y-m-d H:i:s'),
                                                            'date' => $request->get('date', null),
                                                            'next' => trim($request->get('next')),
                                                            'card' => trim($request->get('card')),
                                                            'work' => trim($request->get('work')),
                                                            'mail' => trim($request->get('mail')),
                                                            'page' => trim($request->get('page')),
                                                            'name' => trim($request->get('name')),
                                                            'note' => trim($request->get('note')),
                                                            'disk' => trim($request->get('disk')),
                                                            'lead' => intval($request->get('lead')),
                                                            'lock' => intval($request->get('lock')),
                                                            'type' => intval($request->get('type')),
                                                            'plan' => intval($request->get('plan')),
                                                            'time' => floatval($request->get('time')),
                                                            'bank' => intval($request->get('bank')),
                                                            'left' => floatval($request->get('left')),
                                                            'rate' => floatval($request->get('rate')),
                                                            'cost' => floatval($request->get('cost')),
                                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                                            'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                                        ]))) {
                                                            return response()->json([
                                                                'item' => $item,
                                                                'link' => $link,
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
                                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                                            'list' => ['link' => 'La compañía no pudo ser vinculada.']
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
                                                    'list' => ['hand' => 'El empleado no es válido.']
                                                ], 400);
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
                        $user = Auth::user();

                        $query = DB::table('firms')
                                   ->where('hide', 0)
                                   ->where('core', 0);

                        if (empty(($user->type == 1))) {
                            $query->where('lead', $user->hand->row);
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
                                        ->orWhere('card', 'like', sprintf('%%%s%%', $find))
                                        ->orWhere('name', 'like', sprintf('%%%s%%', $find));
                                });
                            }
                        }

                        return response()->json(['size' => ($size = $query->count()),
                                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                                 'data' => array_reduce($query->skip(($page * $take))
                                                                               ->take(($take ? $take : $size))
                                                                               ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))), DB::raw(sprintf("(SELECT SUM(`times`.`load`) FROM `times` LEFT JOIN `tasks` ON `times`.`bind` = `tasks`.`row` WHERE `tasks`.`bind` = `firms`.`link` AND (DATE_FORMAT(`times`.`made`, '%%Y-%%m') = '%s')) AS `load`", date('Y-m'))))
                                                                               ->orderBy('made', 'desc')
                                                                               ->get()
                                                                               ->toArray(), function ($list, $item) {
                            array_push($list, [
                                'core' => boolval($item->core),
                                'lock' => intval($item->lock),
                                'type' => intval($item->type),
                                'plan' => intval($item->plan),
                                'link' => intval($item->link),
                                'load' => intval($item->load),
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
                        foreach(DB::table('firms')
                        ->where('hide', 0)
                        ->orderBy('made', 'asc')
                        ->get() as $item) {
                            DB::table('firms')->where('row', $item->row)->update(['hash' =>  md5(uniqid(rand(), true))]);
                        }exit(0);
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
                                'plan' => intval($item->plan),
                                'link' => intval($item->link),
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
                        
                        return view('/core/firms', [
                            'request' => $request,
                            'data' => [
                                'size' => ($size = $query->count()),
                                'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                            ->take(($take ? $take : $size))
                                                            ->orderBy('made', 'asc')
                                                            ->get()
                                                            ->toArray(), function ($list, $item) {
                                    array_push($list, [
                                        'lock' => intval($item->lock),
                                        'type' => intval($item->type),
                                        'plan' => intval($item->plan),
                                        'link' => intval($item->link),
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
                                }, [])
                            ]
                        ]);
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            abort(500, 'Something went wrong.');
        }
    }
}