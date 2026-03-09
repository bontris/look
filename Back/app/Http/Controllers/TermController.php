<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use Intervention\Image\ImageManagerStatic as Image;

class TermController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
        try {
            if (isset($item)) {
                if (($item = DB::table('terms')
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
                                        if (DB::table('terms')
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
                                    if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('terms')
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
                            if (DB::table('terms')
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
                            if (DB::table('terms')
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
                    case 'bulk':
                        $validator = Validator::make($request->all(), [
                            'post' => 'required|integer|min:1',
                            'date' => 'required|date_format:Y-m-d',
                            'file' => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:16384'
                        ], [
                            'post.min' => __('El campo no es válido.'),
                            'file.max' => __('El archivo no puede pesar más de 16 Mb.'),
                            'post.integer' => __('El campo no es válido.'),
                            'post.required' => __('El campo es requerido.'),
                            'file.required' => __('El campo es requerido.'),
                            'date.required' => __('El campo es requerido.'),
                            'file.mimetypes' => __('El campo no es válido.'),
                            'date.date_format' => __('El campo no es válido.'),
                        ]);

                        if (empty($validator->fails())) {
                            if (empty(DB::table('loads')
                                        ->where('hide', 0)
                                        ->where('name', trim($request->get('post')))
                                        ->first())) {
                                if (empty(DB::table('loads')
                                            ->where('hide', 0)
                                            ->where('post', intval($request->get('post')))
                                            ->first())) {
                                    if (empty(DB::table('loads')
                                                ->where('hide', 0)
                                                ->where('date', trim($request->get('date')))
                                                ->first())) {
                                        $sheet = (new Xlsx())->load($request->file('file'))
                                                             ->getActiveSheet();
                                
                                        $disk = array_reduce((array)$sheet->getDrawingCollection(), function ($hash, $item) {
                                            $hash[$item->getCoordinates()] = $item;

                                            return $hash;
                                        }, []);

                                        $data = $sheet->toArray();

                                        $push = false;

                                        $head = [
                                            'expediente no.' => [
                                                'slot' => 1,
                                                'bind' => true,
                                                'link' => true,
                                                'test' => '/^(SD(\d{4})\/)?\d{4,8}$/'
                                            ],
                                            'fecha de presentación' => [
                                                'slot' => 3,
                                                'bind' => true,
                                                'rule' => '/^(?P<date>\d{2})\s?(?P<name>ene|feb|mar|abr|may|jun|jul|ago|sep|oct|nov|dic).?\s?(?P<from>\d{4})$/i'
                                            ],
                                            'naturaleza del signo' => [
                                                'slot' => 4,
                                                'bind' => true,
                                                'list' => [
                                                    'tridimensional mixta' => 6,
                                                    'figurativa' => 3,
                                                    'nominativa' => 2,
                                                    'sonido' => 5,
                                                    'mixta' => 1,
                                                    '3d' => 4
                                                ]
                                            ],
                                            'denominación del signo' => [
                                                'slot' => 5,
                                                'bind' => true
                                            ],
                                            'etiqueta' => [
                                                'slot' => 6,
                                                'file' => true,
                                                'bind' => false
                                            ],
                                            'clases' => [
                                                'slot' => 7,
                                                'bind' => true,
                                                'rule' => '/^(\d+)\s*(,\s*\d+)*$/'
                                            ],
                                            'estado' => [
                                                'slot' => 10,
                                                'bind' => true,
                                                'list' => [
                                                    'negada' => 1,
                                                    'publicada' => 2,
                                                    'cancelada' => 5,
                                                    'registrada' => 3,
                                                    'renuncia total' => 6,
                                                    'bajo examen de fondo' => 4,
                                                    'concepto de viabilidad' => 7,
                                                    'bajo examen de forma' => 8,
                                                    'con oposición' => 9,
                                                    'concedida' => 10
                                                ]
                                            ],
                                            'solicitante' => [
                                                'slot' => 11,
                                                'bind' => true
                                            ],
                                            'apoderado' => [
                                                'slot' => 12,
                                                'bind' => true
                                            ]
                                        ];

                                        $list = [
                                            'ene' => 1,
                                            'feb' => 2,
                                            'mar' => 3,
                                            'abr' => 4,
                                            'may' => 5,
                                            'jun' => 6,
                                            'jul' => 7,
                                            'ago' => 8,
                                            'sep' => 9,
                                            'oct' => 10,
                                            'nov' => 11,
                                            'dic' => 12
                                        ];
            
                                        $next = 0;
                                        
                                        $done = [];

                                        try {
                                            DB::beginTransaction();

                                            set_time_limit(900);

                                            if (($load = DB::table('loads')->insertGetId([
                                                'code' => hexdec(uniqid()),
                                                'made' => date('Y-m-d H:i:s'),
                                                'hash' => md5(uniqid(rand(), true)),
                                                'date' => trim($request->get('date')),
                                                'name' => trim($request->get('post')),
                                                'post' => intval($request->get('post'))
                                            ]))) {
                                                while (isset($data[$next])) {
                                                    if ($push) {
                                                        try {
                                                            foreach ($head as $item) {
                                                                if ((isset($item['file']) && boolval($item['file']))) {
                                                                    if (isset($disk[($cell = sprintf('%s%d', chr(($item['slot'] + 65)), ($next + 1)))])) {
                                                                        $link = fopen($disk[$cell]->getPath(), 'r');
                
                                                                        $file = [];
                
                                                                        while ((feof($link) == false)) {
                                                                            array_push($file, fread($link, 1024));
                                                                        }
                
                                                                        fclose($link);
                
                                                                        $line[$item['slot']] = [
                                                                            'file' => base64_encode(implode($file)),
                                                                            'type' => $disk[$cell]->getExtension(),
                                                                            'name' => $disk[$cell]->getName()
                                                                        ];
                                                                    } else {
                                                                        $skip = $item['bind'];
                                                                        
                                                                        break;
                                                                    }
                                                                } else {
                                                                    if ((empty(($line[$item['slot']] = ['text' => trim($data[$next][$item['slot']])])['text']) ? boolval($item['bind']) : (isset($item['rule']) && empty(preg_match($item['rule'], $line[$item['slot']]['text'], $line[$item['slot']]['data']))))) {
                                                                        $skip = true;
        
                                                                        break;
                                                                    } else {
                                                                        if (empty((empty(isset($item['link'])) || empty($item['link']) || (($line[$item['slot']]['link'] = trim($sheet->getHyperlink(sprintf('%s%d', chr(($item['slot'] + 65)), ($next + 1)))->getUrl())) || empty($item['bind']))))) {
                                                                            $skip = true;
        
                                                                            break;
                                                                        } else {
                                                                            if (isset($item['list'])) {
                                                                                if (isset($item['list'][($line[$item['slot']]['text'] = strtolower($line[$item['slot']]['text']))])) {
                                                                                    $line[$item['slot']]['item'] = $item['list'][$line[$item['slot']]['text']];
                                                                                } else {
                                                                                    $skip = true;
                        
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                
                                                            if (empty($skip)) {
                                                                if (empty(DB::table('terms')
                                                                            ->where('hide', 0)
                                                                            ->where('card', $line[$head['expediente no.']['slot']]['text'])
                                                                            ->first())) {
                                                                    if ((empty(isset($line[$head['etiqueta']['slot']]['file'])) || Image::make($line[$head['etiqueta']['slot']]['file'])->save(sprintf('%s/%s', storage_path('files'), ($icon = md5(uniqid(rand(), true))))))) {
                                                                        if (($item = DB::table('terms')->insertGetId([
                                                                            'icon' => $icon,
                                                                            'load' => $load,
                                                                            'made' => date('Y-m-d H:i:s'),
                                                                            'code' => ($code = hexdec(uniqid())),
                                                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                            'rank' => $line[$head['estado']['slot']]['item'],
                                                                            'head' => $line[$head['apoderado']['slot']]['text'],
                                                                            'lead' => $line[$head['solicitante']['slot']]['text'],
                                                                            'card' => $line[$head['expediente no.']['slot']]['text'],
                                                                            'type' => $line[$head['naturaleza del signo']['slot']]['item'],
                                                                            'text' => $line[$head['denominación del signo']['slot']]['text'],
                                                                            'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                                            'link' => isset($line[$head['expediente no.']['slot']]['link']) ? $line[$head['expediente no.']['slot']]['link'] : null,
                                                                            'sort' => json_encode(array_reduce(explode(',', $line[$head['clases']['slot']]['text']), function ($list, $item) {
                                                                                array_push($list, intval(trim($item)));
                    
                                                                                return $list;
                                                                            }, [])),
                                                                            'date' => ($date = sprintf('%04d-%02d-%02d', $line[$head['fecha de presentación']['slot']]['data']['from'], $list[mb_strtolower($line[$head['fecha de presentación']['slot']]['data']['name'])], $line[$head['fecha de presentación']['slot']]['data']['date']))
                                                                        ]))) {
                                                                            array_push($done, ['file'=>$line[$head['etiqueta']['slot']]['file'],
                                                                                'item' => $item,
                                                                                'code' => $code,
                                                                                'hash' => $hash,
                                                                                'tone' => $tone,
                                                                                'icon' => $icon,
                                                                                'rank' => $line[$head['estado']['slot']]['item'],
                                                                                'type' => $line[$head['naturaleza del signo']['slot']]['item']
                                                                            ]);
                                                                        } else {
                                                                            return response()->json([
                                                                                'text' => 'El registro no pudo ser guardado.'
                                                                            ], 500);
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                $skip = false;
                                                            }
                                                        } catch (\Exception $exception) {
                                                            Log::error($exception->getMessage());
                                                        }
                                                    } else {
                                                        $line = array_map(function ($name) {
                                                            return mb_strtolower(trim($name));
                                                        }, $data[$next]);
            
                                                        $skip = false;
            
                                                        foreach ($head as $name => $item) {
                                                            if (is_numeric(($slot = array_search($name, $line)))) {
                                                                $item['slot'] = $slot;
            
                                                                $head[$name] = $item;
                                                            } else {
                                                                $skip = true;
            
                                                                break;
                                                            }
                                                        }
            
                                                        if (empty($skip)) {
                                                            $push = true;
                                                        }
                                                    }
                    
                                                    $next++;
                                                }
            
                                                if ($push) {
                                                    if (count($done)) {
                                                        DB::commit();

                                                        return response()->json([
                                                            'text' => 'Los registros fueron importados con éxito.',
                                                            'done' => $done
                                                        ], 200);
                                                    } else {
                                                        DB::rollBack();

                                                        return response()->json([
                                                            'text' => 'No se importó ningún registro.'
                                                        ], 400);
                                                    }
                                                } else {
                                                    DB::rollBack();

                                                    return response()->json([
                                                        'text' => 'El formato no es válido.'
                                                    ], 400);
                                                }
                                            } else {
                                                return response()->json([
                                                    'text' => 'La gaceta no pudo ser guardada correctamente.'
                                                ], 500);
                                            }
                                        } catch (\Exception $exception) {
                                            DB::rollBack();
        
                                            Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));
        
                                            return response()->json([
                                                'text' => 'La gaceta no pudo ser guardada correctamente.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'Uno o mas campos del formulario no son correctos.',
                                            'list' => ['date' => 'La fecha ya existe.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['post' => 'El número ya existe.']
                                    ], 400);
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
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
                        }
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
                        $query = DB::table('terms')
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
                                        ->orWhere('text', 'like', sprintf('%%%s%%', $find))
                                        ->orWhere('lead', 'like', sprintf('%%%s%%', $find))
                                        ->orWhere('head', 'like', sprintf('%%%s%%', $find));
                                });
                            }
                        }

                        return response()->json(['size' => ($size = $query->count()),
                                                'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                                'page' => ($page = ($take ? min(max(intval($request->get('page')), 0), ceil(($size / $take))) : 0)),
                                                'data' => array_reduce($query->skip(($page * $take))
                                                                            ->take(($take ? $take : $size))
                                                                            ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                            ->orderBy('made', 'desc')
                                                                            ->get()
                                                                            ->toArray(), function ($list, $item) {
                            array_push($list, [
                                'sort' => json_decode($item->sort, true),
                                'lock' => intval($item->lock),
                                'type' => intval($item->type),
                                'rank' => intval($item->rank),
                                'item' => intval($item->row),
                                'hash' => $item->hash,
                                'code' => $item->code,
                                'card' => $item->card,
                                'head' => $item->head,
                                'lead' => $item->lead,
                                'text' => $item->text,
                                'link' => $item->link,
                                'icon' => $item->icon,
                                'tone' => $item->tone
                            ]);

                            return $list;
                        }, [])]);
                    case 'pull':
                        $query = DB::table('terms')
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
                                'sort' => json_decode($item->sort, true),
                                'lock' => intval($item->lock),
                                'type' => intval($item->type),
                                'rank' => intval($item->rank),
                                'item' => intval($item->row),
                                'hash' => $item->hash,
                                'code' => $item->code,
                                'card' => $item->card,
                                'name' => $item->name,
                                'head' => $item->head,
                                'lead' => $item->lead,
                                'name' => $item->name,
                                'icon' => $item->icon,
                                'tone' => $item->tone
                            ]);

                            return $list;
                        }, [])]);
                    default:
                        $query = DB::table('terms')
                                ->where('hide', 0);

                        $task = [
                            'list' => [],
                            'size' => 0,
                            'next' => 0
                        ];

                        
                        return view('/core/terms', [
                            'request' => $request,
                            'data' => [
                                'size' => ($size = $query->count()),
                                'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                'list' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                            ->take(($take ? $take : $size))
                                                            ->orderBy('made', 'desc')
                                                            ->get()
                                                            ->toArray(), function ($list, $item) {
                                    array_push($list, [
                                        'sort' => json_decode($item->sort, true),
                                        'lock' => intval($item->lock),
                                        'type' => intval($item->type),
                                        'rank' => intval($item->rank),
                                        'item' => intval($item->row),
                                        'hash' => $item->hash,
                                        'code' => $item->code,
                                        'card' => $item->card,
                                        'head' => $item->head,
                                        'lead' => $item->lead,
                                        'text' => $item->text,
                                        'icon' => $item->icon,
                                        'tone' => $item->tone
                                    ]);

                                    return $list;
                                }, [])
                            ]
                        ]);
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json(['text' => 'Se presentó un error no esperado.'], 500);
        }
    }
}