<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class MakeController extends Controller
{
    public function main (Request $request, $task = null, $item = null, $type = null, $part = null) {
        try {
            if (isset($item)) {
                if (($item = DB::table('makes')
                               ->where('hide', 0)
                               ->where(function ($query) use ($item) {
                                   $query->where('row', $item)
                                         ->orWhere('hash', $item);
                               })
                               ->where('bind', Auth::user()->firm->row)
                               ->first())) {
                    switch (strtolower($task)) {
                        case 'save':
                            $validator = Validator::make($request->all(), [
                                'lock' => 'nullable|in:0,1',
                                'rank' => 'nullable|in:1,2,3,4',
                                'type' => 'nullable|in:1,2,3,4,5,6',
                                'name' => 'required|max:64',
                                'text' => 'nullable|max:128',
                                'date' => 'nullable|date_format:Y-m-d',
                                'data' => 'nullable|array',
                                'data.*.code' => 'required|integer',
                                'data.*.text' => 'required|string',
                                'sort' => 'required|array|min:1|max:20',
                                'sort.*' => 'nullable|integer',
                                'link' => 'nullable|url:http,https|max:128',
                                'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                                'proxy' => 'nullable|max:64',
                                'title' => 'nullable|max:64',
                                'number' => 'nullable|regex:/^(([A-Z0-9])(\/[0-9]+)?){2,16}$/',
                                'titular' => 'nullable|max:64',
                                'priority' => 'nullable|date_format:Y-m-d',
                                'schedule' => 'nullable|in:0,1,2,3,4,5,6,7,8,9,10,11,12',
                                'reference' => 'nullable|max:64',
                                'concession' => 'nullable|date_format:Y-m-d',
                                'validation' => 'nullable|date_format:Y-m-d',
                                'opposition' => 'nullable|in:0,1',
                                'information' => 'nullable|max:512',
                                'registration' => 'nullable|date_format:Y-m-d'
                            ], [
                                'lock.in' => 'La opción no es válida.',
                                'rank.in' => 'La opción no es válida.',
                                'type.in' => 'La opción no es válida.',
                                'schedule.in' => 'La opción no es válida.',
                                'opposition.in' => 'La opción no es válida.',
                                'link.url' => 'El enlace no es válido.',
                                'sort.min' => 'El campo no es válido.',
                                'sort.max' => 'El campo no es válido.',
                                'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                                'name.max' => 'El campo no es válido.',
                                'text.max' => 'El campo no es válido.',
                                'link.max' => 'El campo no es válido.',
                                'proxy.max' => 'El campo no es válido.',
                                'titular.max' => 'El campo no es válido.',
                                'reference.max' => 'El campo no es válido.',
                                'information.max' => 'El campo no es válido.',
                                'number.regex' => 'El campo no es válido.',
                                'date.date_format' => 'El campo no es válido.',
                                'priority.date_format' => 'El campo no es válido.',
                                'concession.date_format' => 'La fecha no es válida.',
                                'validation.date_format' => 'El campo no es válido.',
                                'registration.date_format' => 'El campo no es válido.',
                                'type.required' => 'El campo es requerido.',
                                'name.required' => 'El campo es requerido.',
                                'data.*.text.string' => 'El campo no es válido.',
                                'data.*.code.integer' => 'El campo no es válido.',
                                'data.*.text.required' => 'El campo es requerido.',
                                'data.*.code.required' => 'El campo es requerido.',
                                'sort.*.required' => 'El campo no es válido.',
                                'sort.*.integer' => 'El campo no es válido.',
                                'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                            ]);
    
                            if (empty($validator->fails())) {
                                if ((empty(($same = DB::table('makes')
                                                      ->where('hide', 0)
                                                      ->where('name', trim($request->get('name')))
                                                      ->where('bind', Auth::user()->firm->row)
                                                      ->first())) || ($item->row == $same->row))) {
                                    if (count(($sort = array_reduce($request->get('sort', json_decode($item->sort, true)), function ($list, $item) {
                                        if (($item = intval($item))) {
                                            if (empty(in_array($item, $list))) {
                                                array_push($list, $item);
                                            }
                                        }
    
                                        return $list;
                                    }, [])))) {
                                        if ((empty(($icon = $request->file('icon'))) || $icon->move(public_path('images'), ($icon = md5(uniqid(rand(), true)))))) {
                                            if (DB::table('makes')->where('row', $item->row)->update([
                                                'sort' => json_encode($sort),
                                                'data' => json_encode($request->get('data', json_decode($item->data, true))),
                                                'mark' => date('Y-m-d H:i:s'),
                                                'icon' => $icon ?? $item->icon,
                                                'name' => trim($request->get('name', $item->name)),
                                                'text' => trim($request->get('text', $item->text)),
                                                'link' => trim($request->get('link', $item->link)),
                                                'date' => ($date = trim($request->get('date', $item->date))) ? $date : null,
                                                'proxy' => trim($request->get('proxy', $item->proxy)),
                                                'title' => trim($request->get('title', $item->title)),
                                                'number' => trim($request->get('number', $item->number)),
                                                'titular' => trim($request->get('titular', $item->titular)),
                                                'priority' => ($date = trim($request->get('priority', $item->priority))) ? $date : null,
                                                'reference' => trim($request->get('reference', $item->reference)),
                                                'concession' => ($date = trim($request->get('concession', $item->concession))) ? $date : null,
                                                'validation' => ($date = trim($request->get('validation', $item->validation))) ? $date : null,
                                                'information' => trim($request->get('information', $item->information)),
                                                'registration' => ($date = trim($request->get('registration', $item->registration))) ? $date : null,
                                                'lock' => intval($request->get('lock', $item->lock)),
                                                'type' => intval($request->get('type', $item->type)),
                                                'rank' => intval($request->get('rank', $item->rank)),
                                                'schedule' => intval($request->get('schedule', $item->schedule)),
                                                'opposition' => intval($request->get('opposition', $item->opposition))
                                            ])) {
                                                if ((empty(empty($icon)) && empty(empty($item->icon)) && empty(@unlink(sprintf('%s/%s', storage_path('files'), $item->icon))))) {
                                                    Log::error(sprintf('Unable to delete the file: %s', $item->icon));
                                                } 
    
                                                return response()->json([
                                                    'icon' => $icon,
                                                    'sort' => $sort,
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
                                            'form' => ['sort' => 'Se requiere por lo menos una clase válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'form' => ['card' => 'El NIT ya existe.']
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
                        case 'make':
                            switch (strtolower($type)) {
                                case 'note':
                                    $validator = Validator::make($request->all(), [
                                        'rate' => 'required|integer',
                                        'text' => 'required|max:256',
                                        'rank' => 'required|in:1,2,3',
                                        'file' => 'nullable|mimetypes:application/pdf,application/zip|max:16384'
                                    ], [
                                        'rank.in' => 'La opción no es válida.',
                                        'text.max' => 'El campo no es válido.',
                                        'file.max' => 'El documento no pude pesar más de 16 Mb.',
                                        'term.integer' => 'El campo no es válido.',
                                        'rate.integer' => 'El campo no es válido.',
                                        'rate.required' => 'El campo es requerido.',
                                        'text.required' => 'El campo es requerido.',
                                        'rank.required' => 'El campo es requerido.',
                                        'file.mimetypes' => 'El campo debe ser un documento válido.'
                                    ]);
    
                                    if (empty($validator->fails())) {
                                        if (($item = DB::table('notes')->insertGetId([
                                            'bind' => $item->row,
                                            'made' => date('Y-m-d H:i:s'),
                                            'skip' => Auth::user()->hand->row,
                                            'code' => ($code = hexdec(uniqid())),
                                            'text' => trim($request->get('text')),
                                            'link' => intval($request->get('term')),
                                            'rate' => intval($request->get('rate')),
                                            'rank' => intval($request->get('rank')),
                                            'hash' => ($hash = md5(uniqid(rand(), true)))
                                        ]))) {
                                            return response()->json([
                                                'item' => $item,
                                                'code' => $code,
                                                'hash' => $hash,
                                                'text' => 'El comentario fue guardado con éxito.'
                                            ], 200);
                                        } else {
                                            return response()->json([
                                                'text' => 'El comentario no pudo ser guardado.'
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
                                    break;
                                default:
                                    return response()->json([
                                        'text' => sprintf('La opción no es válida: %s', $type)
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
                                        if (DB::table('makes')
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
                                    if ((empty(empty($item->icon)) && @unlink(sprintf('%s/%s', storage_path('files'), $item->icon)) && DB::table('makes')
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
                            if (DB::table('makes')
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
                            if (DB::table('makes')
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
                        case 'seek':
                            $list = json_decode($item->sort, true);
    
                            $name = strtolower($item->name);
    
                            $data = [];
    
                            foreach (DB::table('terms')->where('load', intval($request->get('load')))->get() as $term) {
                                similar_text($name, ($text = strtolower($term->text)), $rank);
    
                                $rate = 0;
    
                                if (($rank == 100)) {
                                    $rate = 3;
                                } else {
                                    if (($rank > 70)) {
                                        $rate = 2;
                                    } else {
                                        if (($rank > 50)) {
                                            $rate = 1;
                                        }
                                    }
                                }
    
                                if (empty(strcasecmp(metaphone($name), metaphone($text)))) {
                                    $rate++;
                                }
                                
                                if ($rate) {
                                    if (count(array_filter(($sort = json_decode($term->sort, true)), function ($item) use ($list) {
                                        return in_array($item, $list);
                                    }))) {
                                        $rate++;
                                    }
    
                                    array_push($data, [
                                        'type' => intval($term->type),
                                        'rank' => intval($term->rank),
                                        'term' => intval($term->row),
                                        'hash' => $term->hash,
                                        'card' => $term->card,
                                        'head' => $term->head,
                                        'lead' => $term->lead,
                                        'text' => $term->text,
                                        'icon' => $term->icon,
                                        'date' => $term->date,
                                        'sort' => $sort,
                                        'rate' => $rate
                                    ]);
                                }
                            }
    
                            return response()->json([
                                'item' => intval($item->row),
                                'hash' => $item->hash,
                                'name' => $item->name,
                                'icon' => $item->icon,
                                'data' => $data
                            ], 200);
                        case 'load':
                            switch (strtolower($type)) {
                                case 'note':
                                    $query = DB::table('notes')
                                               ->where('hide', 0)
                                               ->where('bind', $item->row);
                                        
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
                                                        ->orWhere('text', 'like', sprintf('%%%s%%', $find));
                                                });
                                            }
                                        }
                    
                                        return response()->json(['size' => ($size = $query->count()),
                                                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($size / $take))) : 0)),
                                                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                                            ->take(($take ? $take : $size))
                                                                                            ->select('*', DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                                            ->orderBy('made', 'desc')
                                                                                            ->get()
                                                                                            ->toArray(), function ($list, $item) {
                                            array_push($list, [
                                                'rate' => intval($item->rate),
                                                'rank' => intval($item->rank),
                                                'item' => intval($item->row),
                                                'code' => $item->code,
                                                'hash' => $item->hash,
                                                'text' => $item->text,
                                                'made' => $item->made
                                            ]);
                    
                                            return $list;
                                        }, [])]);
                                default:
                                    return response()->json([
                                        'text' => sprintf('La opción no es válida: %s', $type)
                                    ], 400);
                            }
                        default:
                            $item = get_object_vars($item);
    
                            array_walk($item, function (&$item, $name) {
                                switch ($name) {
                                    case 'sort':
                                        $item = json_decode($item, true);
                                        break;
                                    case 'data':
                                        $item = json_decode($item, true);
                                        break;
                                    case 'lock':
                                        $item = intval($item);
                                        break;
                                    case 'type':
                                        $item = intval($item);
                                        break;
                                    case 'row':
                                        $item = intval($item);
                                        break;
                                }
                            });
    
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
                            'firm' => 'required|integer',
                            'file' => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:5120'
                        ], [
                            'file.max' => 'La documento no puede pesar más de 5 Mb.',
                            'frim.integer' => __('La opción no es válida.'),
                            'frim.required' => __('La opción es requerida.'),
                            'file.required' => __('El campo es requerido.'),
                            'file.mimetypes' => __('El campo no es válido.')
                        ]);
    
                        if (empty($validator->fails())) {
                            try {
                                if (($firm = DB::table('firms')
                                               ->where('hide', 0)
                                               ->where('row', intval($request->get('firm')))
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
                                        'número de caso' => [
                                            'slot' => 0,
                                            'bind' => true,
                                            'link' => true,
                                            'test' => '/^(SD(\d{4})\/)?\d{4,8}$/'
                                        ],
                                        'caso título' => [
                                            'slot' => 1,
                                            'bind' => true
                                        ],
                                        'imagen' => [
                                            'slot' => 2,
                                            'file' => true,
                                            'bind' => false
                                        ],
                                        'fecha de radicación' => [
                                            'slot' => 3,
                                            'bind' => true,
                                            'rule' => '/^(?P<date>\d{2})\s?(?P<name>\w{3}).?\s?(?P<from>\d{4})$/'
                                        ],
                                        'vigencia' => [
                                            'slot' => 4,
                                            'bind' => false,
                                            'rule' => '/^(?P<date>\d{2})\s?(?P<name>\w{3}).?\s?(?P<from>\d{4})$/'
                                        ],
                                        'estado del caso' => [
                                            'slot' => 5,
                                            'bind' => true
                                        ],
                                        'referencia del solicitante' => [
                                            'slot' => 6,
                                            'bind' => false
                                        ],
                                        'titular' => [
                                            'slot' => 7,
                                            'bind' => true
                                        ],
                                        'descripción de productos y servicios' => [
                                            'slot' => 8,
                                            'bind' => true
                                        ],
                                        'calendario clasificación de niza' => [
                                            'slot' => 9,
                                            'bind' => true
                                        ],
                                        'productos y servicios descripción' => [
                                            'slot' => 10,
                                            'bind' => true
                                        ],
                                        'bajo oposición' => [
                                            'slot' => 11,
                                            'bind' => true
                                        ],
                                        'apoderado' => [
                                            'slot' => 12,
                                            'bind' => true
                                        ],
                                        'fecha de registro' => [
                                            'slot' => 13,
                                            'bind' => false
                                        ],
                                        'fecha de la publicación' => [
                                            'slot' => 14,
                                            'bind' => true,
                                            'rule' => '/^(?P<date>\d{2})\s?(?P<name>\w{3}).?\s?(?P<from>\d{4})$/'
                                        ],
                                        'fecha de prioridad' => [
                                            'slot' => 15,
                                            'bind' => false,
                                            'rule' => '/^(?P<date>\d{2})\s?(?P<name>\w{3}).?\s?(?P<from>\d{4})$/'
                                        ],
                                        'otra información' => [
                                            'slot' => 16,
                                            'bind' => false
                                        ]
                                    ];
                                    
                                    $pile = [
                                        'name' => [
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
                                        ],
                                        'type' => [
                                            'tridimensional mixta' => 6,
                                            'figurativa' => 3,
                                            'nominativa' => 2,
                                            'sonido' => 5,
                                            'mixta' => 1,
                                            '3d' => 4
                                        ],
                                        'rank' => [
                                            'negada' => 1,
                                            'publicada' => 2,
                                            'registrada' => 3,
                                            'bajo examen de fondo' => 4
                                        ],
                                        'turn' => [
                                            'sí' => 1,
                                            'no' => 0
                                        ]
                                    ];
        
                                    $next = 0;
                                    
                                    $done = [];

                                    while (isset($data[$next])) {
                                        if ($push) {
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
                                                    if (empty((empty(($line[$item['slot']] = ['text' => trim($data[$next][$item['slot']])])['text']) ? boolval($item['bind']) : (isset($item['rule']) && preg_match($item['rule'], $line[$item['slot']]['text'], $line[$item['slot']]['data']))))) {
                                                        $skip = true;
        
                                                        break;
                                                    } else {
                                                        if (empty((empty(isset($item['link'])) || empty($item['link']) || (($line[$item['slot']]['link'] = trim($sheet->getHyperlink(sprintf('%s%d', chr(($item['slot'] + 65)), ($next + 1)))->getUrl())) || empty($item['bind']))))) {
                                                            $skip = true;
        
                                                            break;
                                                        }
                                                    }
                                                }
                                            }

                                            if (empty($skip)) {
                                                if (count(($sort = array_reduce(explode(',', $line[$head['descripción de productos y servicios']['slot']]['text']), function ($list, $item) {
                                                    if (($item = intval($item))) {
                                                        if (empty(in_array($item, $list))) {
                                                            array_push($list, $item);
                                                        }
                                                    }

                                                    return $list;
                                                }, [])))) {
                                                    if (count(($list = array_reduce(explode("\n", $line[$head['productos y servicios descripción']['slot']]['text']), function ($list, $item) use ($sort) {
                                                        if (($item = trim($item))) {
                                                            if (preg_match('/^(?P<code>\d+)\.?\s?(?P<text>.+)$/', $item, $item)) {
                                                                if (in_array($item['code'], $sort)) {
                                                                    array_push($list, [
                                                                        'code' => $item['code'],
                                                                        'text' => trim($item['text'])
                                                                    ]);
                                                                }
                                                            }
                                                        }
        
                                                        return $list;
                                                    }, [])))) {
                                                        if (empty(DB::table('makes')
                                                                    ->where('hide', 0)
                                                                    ->where('code', ($code = hexdec(uniqid())))
                                                                    ->first())) {
                                                            if (empty(DB::table('makes')
                                                                        ->where('hide', 0)
                                                                        ->where('number', $line[$head['número de caso']['slot']]['text'])
                                                                        ->first())) {
                                                                if ((empty(isset($line[$head['imagen']['slot']]['file'])) || (new ImageManager(Driver::class))->read($line[$head['imagen']['slot']]['file'])->save(sprintf('%s/%s', public_path('images'), ($icon = md5(uniqid(rand(), true))))))) {
                                                                    if (($item = DB::table('makes')->insertGetId([
                                                                        'code' => $code,
                                                                        'icon' => $icon,
                                                                        'sort' => json_encode($sort),
                                                                        'data' => json_encode($list),
                                                                        'made' => date('Y-m-d H:i:s'),
                                                                        'link' => isset($line[$head['número de caso']['slot']]['link']) ? $line[$head['número de caso']['slot']]['link'] : null,
                                                                        'name' => $line[$head['caso título']['slot']]['text'],
                                                                        'proxy' => current(explode(',', $line[$head['apoderado']['slot']]['text'])),
                                                                        'title' => $line[$head['caso título']['slot']]['text'],
                                                                        'number' => $line[$head['número de caso']['slot']]['text'],
                                                                        'titular' => current(explode(',', $line[$head['titular']['slot']]['text'])),
                                                                        'reference' => $line[$head['referencia del solicitante']['slot']]['text'],
                                                                        'validation' => isset($line[$head['vigencia']['slot']]['data']) ? sprintf('%04d-%02d-%02d', $line[$head['vigencia']['slot']]['data']['from'], $pile['name'][mb_strtolower($line[$head['vigencia']['slot']]['data']['name'])], $line[$head['vigencia']['slot']]['data']['date']) : null,
                                                                        'information' => $line[$head['otra información']['slot']]['text'],
                                                                        'publication' => isset($line[$head['fecha de la publicación']['slot']]['data']) ? sprintf('%04d-%02d-%02d', $line[$head['fecha de la publicación']['slot']]['data']['from'], $pile['name'][mb_strtolower($line[$head['fecha de la publicación']['slot']]['data']['name'])], $line[$head['fecha de la publicación']['slot']]['data']['date']) : null,
                                                                        'presentation' => isset($line[$head['fecha de radicación']['slot']]['data']) ? sprintf('%04d-%02d-%02d', $line[$head['fecha de radicación']['slot']]['data']['from'], $pile['name'][mb_strtolower($line[$head['fecha de radicación']['slot']]['data']['name'])], $line[$head['fecha de radicación']['slot']]['data']['date']) : null,
                                                                        'registration' => isset($line[$head['fecha de registro']['slot']]['data']) ? sprintf('%04d-%02d-%02d', $line[$head['fecha de registro']['slot']]['data']['from'], $pile['name'][mb_strtolower($line[$head['fecha de registro']['slot']]['data']['name'])], $line[$head['fecha de registro']['slot']]['data']['date']) : null,
                                                                        'rank' => $pile['rank'][mb_strtolower($line[$head['estado del caso']['slot']]['text'])],
                                                                        'schedule' => intval($line[$head['calendario clasificación de niza']['slot']]['text']),
                                                                        'opposition' => $pile['turn'][mb_strtolower($line[$head['bajo oposición']['slot']]['text'])],
                                                                        'bind' => $firm->row,
                                                                        'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                        'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                                                    ]))) {
                                                                        array_push($done, [
                                                                            'item' => $item,
                                                                            'code' => $code,
                                                                            'hash' => $hash,
                                                                            'tone' => $tone,
                                                                            'data' => $line
                                                                        ]);
                                                                    } else {
                                                                        return response()->json([
                                                                            'text' => 'El registro no pudo ser guardado.'
                                                                        ], 500);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                $skip = false;
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
                                        return response()->json([
                                            'text' => 'El registro fue guardado con éxito.',
                                            'done' => $done
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'text' => 'El formato no es válido.'
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'list' => ['firm' => 'La compañía no es válida.']
                                    ], 400);
                                }
                            } catch (Exection $fail) {
                                Log::error(sprintf('Unable to read the file: %s', $request->file('file')->getClientOriginalName()));
    
                                return response()->json([
                                    'text' => 'No se pudo procesar el documento.'
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
                    case 'make':
                        $validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'rank' => 'nullable|in:1,2,3,4',
                            'type' => 'nullable|in:1,2,3,4,5,6',
                            'name' => 'required|max:64',
                            'text' => 'nullable|max:128',
                            'date' => 'nullable|date_format:Y-m-d',
                            'data' => 'nullable|array',
                            'data.*.code' => 'required|integer',
                            'data.*.text' => 'required|string',
                            'sort' => 'required|array|min:1|max:20',
                            'sort.*' => 'nullable|integer',
                            'link' => 'nullable|url:http,https|max:128',
                            'icon' => 'nullable|mimetypes:image/jpeg,image/png|max:5120',
                            'proxy' => 'nullable|max:64',
                            'title' => 'nullable|max:64',
                            'number' => 'nullable|regex:/^(([A-Z0-9])(\/[0-9]+)?){2,16}$/',
                            'titular' => 'nullable|max:64',
                            'priority' => 'nullable|date_format:Y-m-d',
                            'schedule' => 'nullable|in:1,2,3,4,5,6,7,8,9,10,11,12',
                            'reference' => 'nullable|max:64',
                            'validation' => 'nullable|date_format:Y-m-d',
                            'concession' => 'nullable|date_format:Y-m-d',
                            'opposition' => 'nullable|in:0,1',
                            'information' => 'nullable|max:512',
                            'registration' => 'nullable|date_format:Y-m-d'
                        ], [
                            'lock.in' => 'La opción no es válida.',
                            'rank.in' => 'La opción no es válida.',
                            'type.in' => 'La opción no es válida.',
                            'schedule.in' => 'La opción no es válida.',
                            'opposition.in' => 'La opción no es válida.',
                            'link.url' => 'El enlace no es válido.',
                            'sort.min' => 'El campo no es válido.',
                            'sort.max' => 'El campo no es válido.',
                            'icon.max' => 'La imágen no puede pesar más de 5 Mb.',
                            'name.max' => 'El campo no es válido.',
                            'text.max' => 'El campo no es válido.',
                            'link.max' => 'El campo no es válido.',
                            'proxy.max' => 'El campo no es válido.',
                            'titular.max' => 'El campo no es válido.',
                            'reference.max' => 'El campo no es válido.',
                            'information.max' => 'El campo no es válido.',
                            'number.regex' => 'El campo no es válido.',
                            'date.date_format' => 'El campo no es válido.',
                            'priority.date_format' => 'El campo no es válido.',
                            'concession.date_format' => 'El campo no es válido.',
                            'validation.date_format' => 'El campo no es válido.',
                            'registration.date_format' => 'El campo no es válido.',
                            'type.required' => 'El campo es requerido.',
                            'name.required' => 'El campo es requerido.',
                            'data.*.text.string' => 'El campo no es válido.',
                            'data.*.code.integer' => 'El campo no es válido.',
                            'data.*.text.required' => 'El campo es requerido.',
                            'data.*.code.required' => 'El campo es requerido.',
                            'sort.*.required' => 'El campo no es válido.',
                            'sort.*.integer' => 'El campo no es válido.',
                            'icon.mimetypes' => 'El campo debe ser una imágen válida.'
                        ]);
    
                        if (empty($validator->fails())) {
                            if (empty(DB::table('makes')
                                        ->where('hide', 0)
                                        ->where('code', ($code = hexdec(uniqid())))
                                        ->first())) {
                                if (empty(DB::table('makes')
                                            ->where('hide', 0)
                                            ->where('name', trim($request->get('name')))
                                            ->where('bind', Auth::user()->firm->row)
                                            ->first())) {
                                    if (count(($sort = array_reduce($request->get('sort'), function ($list, $item) {
                                        if (($item = intval($item))) {
                                            if (empty(in_array($item, $list))) {
                                                array_push($list, $item);
                                            }
                                        }
    
                                        return $list;
                                    }, [])))) { 
                                        if ((empty(($icon = $request->file('icon'))) || $icon->move(public_path('images'), ($icon = md5(uniqid(rand(), true)))))) {
                                            if (($item = DB::table('makes')->insertGetId([
                                                'code' => $code,
                                                'icon' => $icon,
                                                'sort' => json_encode($sort),
                                                'data' => json_encode($request->get('data')),
                                                'made' => date('Y-m-d H:i:s'),
                                                'name' => trim($request->get('name')),
                                                'text' => trim($request->get('text')),
                                                'link' => trim($request->get('link')),
                                                'date' => ($date = trim($request->get('date'))) ? $date : null,
                                                'proxy' => trim($request->get('proxy')),
                                                'title' => trim($request->get('title')),
                                                'number' => trim($request->get('number')),
                                                'titular' => trim($request->get('titular')),
                                                'priority' => ($date = trim($request->get('priority'))) ? $date : null,
                                                'reference' => trim($request->get('reference')),
                                                'validation' => ($date = trim($request->get('validation'))) ? $date : null,
                                                'information' => trim($request->get('information')),
                                                'registration' => ($date = trim($request->get('registration'))) ? $date : null,
                                                'lock' => intval($request->get('lock')),
                                                'type' => intval($request->get('type')),
                                                'rank' => intval($request->get('rank')),
                                                'schedule' => intval($request->get('schedule')),
                                                'opposition' => intval($request->get('opposition')),
                                                'bind' => Auth::user()->firm->row,
                                                'hash' => ($hash = md5(uniqid(rand(), true))),
                                                'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)])
                                            ]))) {
                                                return response()->json([
                                                    'item' => $item,
                                                    'code' => $code,
                                                    'hash' => $hash,
                                                    'sort' => $sort,
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
                                            'form' => ['sort' => 'Se requiere por lo menos una clase válida.']
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'text' => 'Uno o mas campos del formulario no son correctos.',
                                        'form' => ['name' => 'El nombre ya existe.']
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'Uno o mas campos del formulario no son correctos.',
                                    'form' => ['code' => 'El código ya existe.']
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                'form' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
                        }
                    case 'seek':
                        $heap = DB::table('terms')
                                  ->where('load', intval($request->get('load')))
                                  ->get();
    
                        $data = [];
    
                        foreach (DB::table('makes')
                                   ->where('hide', 0)
                                   ->where('bind', Auth::user()->firm->row)
                                   ->get() as $next => $make) {
                            $list = json_decode($make->sort, true);
    
                            $name = strtolower($make->name);
    
                            $bulk = [];
    
                            foreach ($heap as $term) {
                                similar_text($name, ($text = strtolower($term->text)), $rank);
    
                                $rate = 0;
    
                                if (($rank == 100)) {
                                    $rate = 3;
                                } else {
                                    if (($rank > 70)) {
                                        $rate = 2;
                                    } else {
                                        if (($rank > 50)) {
                                            $rate = 1;
                                        }
                                    }
                                }
    
                                if (empty(strcasecmp(metaphone($name), metaphone($text)))) {
                                    $rate++;
                                }
                                
                                if ($rate) {
                                    if (count(array_filter(($sort = json_decode($term->sort, true)), function ($item) use ($list) {
                                        return in_array($item, $list);
                                    }))) {
                                        $rate++;
                                    }
    
                                    /*if (empty(isset($data[$next]))) {
                                        $data[$next] = [
                                            'item' => intval($make->row),
                                            'hash' => $make->hash,
                                            'name' => $make->name,
                                            'text' => $make->text,
                                            'list' => []
                                        ];
                                    }*/
    
                                    array_push($data/*[$next]['list']*/, [
                                        'type' => intval($term->type),
                                        'rank' => intval($term->rank),
                                        'term' => intval($term->row),
                                        'make' => intval($make->row),
                                        'name' => $make->name,
                                        'hash' => $term->hash,
                                        'card' => $term->card,
                                        'head' => $term->head,
                                        'lead' => $term->lead,
                                        'text' => $term->text,
                                        'icon' => $term->icon,
                                        'date' => $term->date,
                                        'sort' => $sort,
                                        'rate' => $rate
                                    ]);
                                }
                            }
                        }
    
                        return response()->json($data/*array_values($data)*/, 200);
                    case 'load':
                        $query = DB::table('makes')
                                   ->where('hide', 0)
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
                                          ->orWhere('name', 'like', sprintf('%%%s%%', $find));
                                });
                            }
                        }
    
                        return response()->json(['high' => ($high = $query->count()),
                                                 'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                                 'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                                 'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                              ->take(($take ? $take : $high))
                                                                              ->select('*', DB::raw('(SELECT COUNT(*) FROM `notes` WHERE `notes`.`hide` = 0 AND `notes`.`bind` = `makes`.`row`) AS `size`'), DB::raw(sprintf("CONVERT_TZ(made, '%s', '%s') AS `made`", date_default_timezone_get(), env('APP_TIME', '-05:00'))))
                                                                              ->orderBy('made', 'desc')
                                                                              ->get()
                                                                              ->toArray(), function ($list, $item) {
                            array_push($list, [
                                'sort' => json_decode($item->sort, true),
                                'lock' => intval($item->lock),
                                'type' => intval($item->type),
                                'rank' => intval($item->rank),
                                'size' => intval($item->size),
                                'item' => intval($item->row),
                                'card' => $item->number,
                                'hash' => $item->hash,
                                'code' => $item->code,
                                'link' => $item->link,
                                'name' => $item->name,
                                'text' => $item->text,
                                'icon' => $item->icon,
                                'tone' => $item->tone,
                                'made' => $item->made
                            ]);
    
                            return $list;
                        }, [])]);
                    case 'pull':
                        $query = DB::table('makes')
                                   ->where('hide', 0)
                                   ->where('bind', Auth::user()->firm->row);
    
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
                                'name' => $item->name,
                                'text' => $item->text,
                                'icon' => $item->icon,
                                'tone' => $item->tone
                            ]);
    
                            return $list;
                        }, [])]);
                    case 'date':
                        return response()->json(['data' => array_reduce(DB::table('loads')
                                                                          ->where('hide', 0)
                                                                          ->orderBy('date', 'desc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                            array_push($list, [
                                'post' => intval($item->post),
                                'item' => intval($item->row),
                                'hash' => $item->hash,
                                'code' => $item->code,
                                'date' => $item->date
                            ]);
    
                            return $list;
                        }, [])]);
                    default:
                        $query = DB::table('makes')
                                   ->where('hide', 0)
                                   ->where('bind', Auth::user()->firm->row);
    
                        $task = [
                            'list' => [],
                            'size' => 0,
                            'next' => 0
                        ];
    
                        return view('/core/makes', [
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
                                        'data' => json_decode($item->data, true),
                                        'lock' => intval($item->lock),
                                        'type' => intval($item->type),
                                        'rank' => intval($item->rank),
                                        'item' => intval($item->row),
                                        'number' => $item->number,
                                        'hash' => $item->hash,
                                        'code' => $item->code,
                                        'name' => $item->name,
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

            return response()->json(['text' => 'Se presentó un error no esperado.', 'fail' => $exception->getMessage(), 'line' => $exception->getLine()], 500);
        }
    }
}