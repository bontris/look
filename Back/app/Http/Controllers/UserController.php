<?php

namespace App\Http\Controllers;

use User;

use Auth;

use Session;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Mail\Message;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Hash;

//use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
        if (isset($item)) {
            if (($item = DB::table('users')
                           ->where('hide', 0)
                           ->where('hash', $item)
                           ->first())) {
                switch (strtolower($task)) {
                    case 'save':
                        $validator = Validator::make($request->all(), [
                            'lock' => 'nullable|in:0,1',
                            'test' => 'nullable|in:0,1',
                            'code' => 'nullable|max:16',
                            'pass' => 'nullable|min:6',
                            'type' => 'nullable|in:1,2',
                            'last' => 'nullable|max:32',
                            'name' => 'nullable|max:32',
                            'note' => 'nullable|max:256',
                            'nick' => 'nullable|regex:/^\w+(.\w+)*$/i|max:32',
                            'cell' => 'nullable|max:16',
                            'mail' => 'nullable|email|max:64'
                        ], [
                            'lock.in' => 'El campo no es válido.',
                            'code.max' => 'El campo no es válido.',
                            'cell.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'type.in' => 'El campo no es válido.',
                            'pass.min' => 'El campo no es válido.',
                            'nick.max' => 'El campo no es válido.',
                            'nick.regex' => 'El campo no es válido.',
                            'last.max' => 'El campo no es válido.',
                            'name.max' => 'El campo no es válido.',
                            'mail.max' => 'El campo no es válido.',
                            'mail.email' => 'El campo no es válido.'
                        ]);
                        
                        if (empty($validator->fails())) {
                            if ((empty(($same = DB::table('users')
                                                  ->where('hide', 0)
                                                  ->where('code', trim($request->get('code', $item->code)))
                                                  ->first())) || ($same->id == $item->id))) {
                                if ((empty(($same = DB::table('users')
                                                      ->where('hide', 0)
                                                      ->where('mail', trim($request->get('mail', $item->mail)))
                                                      ->first())) || ($same->id == $item->id))) {
                                    if ((empty(trim($request->get('nick'))) || (empty(($same = DB::table('users')
                                                                                                 ->where('hide', 0)
                                                                                                 ->where('nick', trim($request->get('nick')))
                                                                                                 ->first())) || ($same->id == $item->id)))) {
                                        if (DB::table('users')
                                              ->where('id', $item->id)
                                              ->update(['date' => date('Y-m-d H:i:s'),
                                                        'lock' => intval($request->get('lock', $item->lock)),
                                                        'test' => intval($request->get('test', $item->test)),
                                                        'type' => intval($request->get('type', $item->type)),
                                                        'code' => trim($request->get('code', $item->code)),
                                                        'nick' => trim($request->get('nick', $item->nick)),
                                                        'last' => trim($request->get('last', $item->last)),
                                                        'name' => trim($request->get('name', $item->name)),
                                                        'cell' => trim($request->get('cell', $item->cell)),
                                                        'mail' => trim($request->get('mail', $item->mail)),
                                                        'note' => trim($request->get('note', $item->note)),
                                                        'pass' => trim($request->get('pass')) ? Hash::make(trim($request->get('pass'))) : $item->pass])) {
                                            return response()->json(['text' => 'El usuario fue actualizado correctamente.'], 200);
                                        } else {
                                            return response()->json(['text' => 'No se pudo actualizar el usuario.'], 500);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                                 'list' => ['nick' => 'El nombre ya existe.']], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                             'list' => ['mail' => 'El correo ya existe.']], 400);
                                }
                            } else {
                                return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                         'list' => ['code' => 'El código ya existe.']], 400);
                            }
                        } else {
                            return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
                        }
                    case 'face':
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|mimetypes:image/jpeg,image/png'
                        ], [
                            'file.mimes' => 'La imágen no es válida.'
                        ]);

                        if (empty($validator->fails())) {
                            if ($request->file('file')) {
                                if (Image::make($request->file('file'))->save(sprintf('%s/%s', storage_path('files'), ($file = md5(uniqid(rand(), true)))))) {
                                    if (DB::table('users')
                                          ->where('id', $item->id)
                                          ->update(['face' => $file])) {
                                        if ((empty($item->face) || @unlink(sprintf('%s/%s', storage_path('files'), $item->face)))) {
                                            return response()->json(['file' => $file, 'text' => 'La imágen fue actualizada con éxito.'], 200);
                                        } else {
                                            return response()->json(['text' => 'La imágen no pudo ser actualizada correctamente.'], 500);
                                        }
                                    } else {
                                        return response()->json(['text' => 'La imágen no pudo ser actualizada.'], 500);
                                    }
                                } else {
                                    return response()->json(['text' => 'La imágen no pudo ser cargada.'], 500);
                                }
                            } else {
                                if (((empty($item->face) == false) && @unlink(sprintf('%s/%s', storage_path('files'), $item->face)) && DB::table('users')
                                                                                                                                         ->where('id', $item->id)
                                                                                                                                         ->update(['face' => null]))) {
                                    return response()->json(['text' => 'La imágen fue eliminada con éxito.'], 200);
                                } else {
                                    return response()->json(['text' => 'La imágen no pudo ser eliminada.'], 500);
                                }
                            }
                        } else {
                            return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                     'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
                        }
                    case 'wait':
                        if ((empty((intval($item->id) == Auth::user()->id)) && DB::table('users')
                                                                                 ->where('id', $item->id)
                                                                                 ->update(['wait' => null]))) {
                            return response()->json(['text' => 'El bloqueo fue quitado con éxito.'], 200);
                        } else {
                            return response()->json(['text' => 'El bloqueo no pudo ser quitado.'], 500);
                        }
                    case 'lock':
                        if ((empty((intval($item->id) == Auth::user()->id)) && DB::table('users')
                                                                                 ->where('id', $item->id)
                                                                                 ->update(['lock' => ($lock = (intval($item->lock) ? 0 : 1))]))) {
                            return response()->json(['lock' => $lock, 'text' => $lock ? 'El usuario fue habilitado con éxito.' : 'El usuario fue bloqueado con éxito.'], 200);
                        } else {
                            return response()->json(['text' => empty(intval($item->lock)) ? 'El usuario no pudo ser habilitado.' : 'El usuario no pudo ser bloqueado.'], 500);
                        }
                    case 'drop':
                        if ((empty((intval($item->id) == Auth::user()->id)) && DB::table('users')
                                                                                  ->where('id', $item->id)
                                                                                  ->update(['hide' => 1, 'deletion' => date('Y-m-d H:i:s')]))) {
                            return response()->json(['text' => 'El susuario fue eliminado con éxito.'], 200);
                        } else {
                            return response()->json(['text' => 'El usuario no pudo ser eliminado.'], 400);
                        }
                    case 'load':
                        return response()->json($item, 200);
                }
            } else {
                return response()->json(['text' => 'El usuario no fue encontrado.'], 404);
            }
        } else {
            switch (strtolower($task)) {
                case 'make':
                    $validator = Validator::make($request->all(), [
                        'lock' => 'nullable|in:0,1',
                        'test' => 'nullable|in:0,1',
                        'code' => 'nullable|max:16',
                        'pass' => 'required|min:6',
                        'type' => 'required|in:1,2',
                        'last' => 'required|max:32',
                        'name' => 'required|max:32',
                        'note' => 'nullable|max:256',
                        'nick' => 'nullable|regex:/^\w+(.\w+)*$/i|max:32',
                        'cell' => 'nullable|max:16',
                        'mail' => 'required|email|max:64',
                        'face' => 'nullable|image|mimetypes:jpg,png'
                    ], [
                        'lock.in' => 'El campo no es válido.',
                        'code.max' => 'El campo no es válido.',
                        'cell.max' => 'El campo no es válido.',
                        'note.max' => 'El campo no es válido.',
                        'type.in' => 'El campo no es válido.',
                        'type.required' => 'El campo es requerido.',
                        'pass.min' => 'El campo no es válido.',
                        'pass.required' => 'El campo es requerido.',
                        'nick.max' => 'El campo no es válido.',
                        'nick.regex' => 'El campo no es válido.',
                        'last.max' => 'El campo no es válido.',
                        'last.required' => 'El campo es requerido.',
                        'name.max' => 'El campo no es válido.',
                        'name.required' => 'El campo es requerido.',
                        'mail.max' => 'El campo no es válido.',
                        'mail.email' => 'El campo no es válido.',
                        'mail.required' => 'El campo es requerido.',
                        'face.mimes' => 'El campo debe ser una imágen válida.'
                    ]);

                    if (empty($validator->fails())) {
                        if (empty(DB::table('users')
                                    ->where('hide', 0)
                                    ->where('code', (($code = trim($request->get('code'))) ? $code : ($code = hexdec(uniqid()))))
                                    ->first())) {
                            if (empty(DB::table('users')
                                        ->where('hide', 0)
                                        ->where('mail', trim($request->get('mail')))
                                        ->first())) {
                                if ((empty(trim($request->get('cell'))) || empty(DB::table('users')
                                                                                   ->where('hide', 0)
                                                                                   ->where('cell', trim($request->get('cell')))
                                                                                   ->first()))) {
                                    if ((empty(trim($request->get('nick'))) || empty(DB::table('users')
                                                                                       ->where('hide', 0)
                                                                                       ->where('nick', trim($request->get('nick')))
                                                                                       ->first()))) {
                                        if ((empty($request->file('face')) || Image::make($request->file('face'))->save(sprintf('%s/%s', storage_path('files'), ($face = md5(uniqid(rand(), true))))))) {
                                            if (($item = DB::table('users')
                                                           ->insertGetId(['code' => $code,
                                                                          'face' => isset($face) ? $face : null,
                                                                          'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                          'lock' => intval($request->get('lock')),
                                                                          'test' => intval($request->get('test')),
                                                                          'type' => intval($request->get('type')),
                                                                          'lock' => intval($request->get('lock')),
                                                                          'nick' => trim($request->get('nick')),
                                                                          'last' => trim($request->get('last')),
                                                                          'name' => trim($request->get('name')),
                                                                          'mail' => trim($request->get('mail')),
                                                                          'cell' => trim($request->get('cell')),
                                                                          'note' => trim($request->get('note')),
                                                                          'tone' => ($tone = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                                          'pass' => trim($request->get('pass')) ? Hash::make(trim($request->get('pass'))) : null,
                                                                          'date' => date('Y-m-d H:i:s'),
                                                                          'creation' => date('Y-m-d H:i:s')]))) {
                                                if ((intval($request->get('lock')))) {
                                                    if (DB::table('codes')
                                                          ->insert(['type' => 1,
                                                                    'item' => $item,
                                                                    'date' => date('Y-m-d H:i:s'),
                                                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                    'pass' => Hash::make(($pass = mt_rand(10000000, 99999999)))])) {
                                                        Mail::send('mail.code', ['name' => trim($request->get('name')), 'type' => 'mail', 'hash' => $hash, 'pass' => $pass], function($message) use ($request) {
                                                            $message->to(trim($request->get('mail')), trim($request->get('name')))
                                                                    ->from('notifications@viferente.com','Team')
                                                                    ->subject('Activa tu cuenta');
                                                        });
                                                    }
                                                }

                                                return response()->json(['hash' => $hash,
                                                                         'code' => $code,
                                                                         'tone' => $tone,
                                                                         'face' => isset($face) ? $face : null,
                                                                         'text' => 'El usuario fue registrado correctamente.'], 200);
                                            } else {
                                                return response()->json(['text' => 'No se pudo registrar el usuario.'], 500);
                                            }
                                        } else {
                                            return response()->json(['text' => 'La imágen no pudo ser cargada correctamente.'], 500);
                                        } 
                                    } else {
                                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                                 'list' => ['nick' => 'El nombre ya existe.']], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                             'list' => ['cell' => 'El número ya existe.']], 400);
                                }
                            } else {
                                return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                         'list' => ['mail' => 'El correo ya existe.']], 400);
                            }
                        } else {
                            return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                'list' => ['code' => 'El código ya existe.']], 400);
                        }
                    } else {
                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
                    }
                case 'load':
                    $query = DB::table('users')
                               ->where('users.hide', 0);

                    if (($find = trim($request->post('find')))) {
                        $match = [];

                        $rules = [];

                        $names = [];

                        $texts = [];

                        $dates = [];

                        $items = [];

                        foreach (array_slice(explode(',', $find), 0, 20) as $part) {
                            if (($part = trim($part))) {
                                if (preg_match('%^((?P<from>\w+)\.)?(?P<name>\w+):(\s+)?(?P<sign>[!<=>])?(?P<data>.+)$%', $part, $match)) {
                                    if (($part = trim($match['data']))) {
                                        $rules[strtolower(pick($match['from'], 'main'))] = pair('name', strtolower($match['name']),
                                                                                            'sign', $match['sign'],
                                                                                            'data', $part);
                                    }
                                } else {
                                    if (preg_match('/^[0-9]{1,2}\\/[0-9]{1,2}\\/[0-9]{4}$/', $part)) {
                                        $dates[] = date('Y-m-d', strtotime(strtr($part, '/', '-')));
                                    } else {
                                        if (preg_match('/^@\w+$/i', $part)) {
                                            $names[] = strtolower(substr($part, 1));
                                        } else {
                                            if (preg_match('/^#\d+$/', $part)) {
                                                $items[] = strtolower(substr($part, 1));
                                            } else {
                                                $texts[] = strtolower($part);
                                            }
                                        }
                                    }
                                }
                            } 
                        }

                        if (count($items)) {
                            $query->where(function ($query) use ($items) {
                                foreach ($items as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('id', $data);
                                    } else {
                                        $query->orWhere('id', $data);
                                    }
                                }
                            });
                        }

                        if (count($dates)) {
                            $query->where(function ($query) use ($dates) {
                                foreach ($dates as $item => $data) {
                                    if (empty($item)) {
                                        $query->where('creation', $data);
                                    } else {
                                        $query->orWhere('creation', $data);
                                    }
                                }
                            });
                        }

                        if (count($texts)) {
                            $query->where(function ($query) use ($texts) {
                                foreach ($texts as $item => $data) {
                                    if (empty($item)) {
                                        $query->where(function ($query) use ($data) {
                                            $query->where('code', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('nick', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('last', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('name', 'like', sprintf('%%%s%%', $data));
                                        });
                                    } else {
                                        $query->orWhere(function ($query) use ($data) {
                                            $query->where('code', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('nick', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('last', 'like', sprintf('%%%s%%', $data))
                                                  ->orWhere('name', 'like', sprintf('%%%s%%', $data));
                                        });
                                    }
                                }
                            });
                        }
                    }

                    return response()->json(['high' => ($high = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $high))
                                                                          ->orderBy('code', ($take ? 'desc' : 'asc'))
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, ['lock' => intval($item->lock),
                                           'type' => intval($item->type),
                                           'live' => intval($item->live),
                                           'item' => intval($item->id),
                                           'wait' => $item->wait,
                                           'hash' => $item->hash,
                                           'code' => $item->code,
                                           'seen' => $item->seen,
                                           'nick' => $item->nick,
                                           'mail' => $item->mail,
                                           'cell' => $item->cell,
                                           'last' => $item->last,
                                           'name' => $item->name,
                                           'face' => $item->face,
                                           'tone' => $item->tone]);

                        return $list;
                    }, [])]);
                case 'pull':
                    $query = DB::table('users')
                               ->where('hide', 0);

                    return response()->json(['high' => ($high = $query->count()),
                                             'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                             'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                             'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                          ->take(($take ? $take : $high))
                                                                          ->orderBy('last', 'asc')
                                                                          ->orderBy('name', 'asc')
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                        array_push($list, ['lock' => intval($item->lock),
                                           'type' => intval($item->type),
                                           'item' => intval($item->id),
                                           'hash' => $item->hash,
                                           'code' => $item->code,
                                           'nick' => $item->nick,
                                           'mail' => $item->mail,
                                           'cell' => $item->cell,
                                           'last' => $item->last,
                                           'name' => $item->name,
                                           'face' => $item->face,
                                           'tone' => $item->tone]);

                        return $list;
                    }, [])]);
                default:
                $AllNFTSUpdate = array(
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' Xoeyam','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1],
                    ['/assets/img/nft-table-img1.png','View Card by Jeff Davis',' John Cartl','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',0],
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' Twillor swift','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1],
                    ['/assets/img/nft-table-img1.png','View Card by Jeff Davis',' Mr Bradman','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',0],
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' John wick','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1]
                );
        
                $AllNFTSUpdateV2 = array(
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' Xoeyam','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1],
                    ['/assets/img/nft-table-img1.png','View Card by Jeff Davis',' John Cartl','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',0],
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' Twillor swift','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1],
                    ['/assets/img/nft-table-img1.png','View Card by Jeff Davis',' Mr Bradman','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',0],
                );
        
                $AllNFTSUpdateV3 = array(
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' Xoeyam','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1],
                    ['/assets/img/nft-table-img1.png','View Card by Jeff Davis',' John Cartl','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',0],
                    ['/assets/img/nft-table-img1.png','Mullican Computer Joy',' Twillor swift','7473 ETH',6392.99,'-24.75 (11.5%)',343,'2 Hours 1 min 30s',1],
                );
        
                $TopSeller = array(
                    ['/assets/img/verfify-sign.png','/assets/img/seller-1.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',8435],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',7435],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-3.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',5435],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-4.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',3435],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',5735],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-4.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',3935],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',3335],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-3.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',3435],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-1.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',7435],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',3435]
                    
                );
        
                $TopBuyer = array(
                    ['/assets/img/verfify-sign.png','/assets/img/seller-4.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',78],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-1.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',80],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-3.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',81],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-4.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',74],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',71],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-1.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',65],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',49],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',39],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-1.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',35],
                    ['/assets/img/verfify-sign.png','/assets/img/seller-2.png','Brokln Simons','@broklinslam_75','/assets/img/diamond-icon.png',35]
                    
                );
        
                $SellHistory = array(
                    ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    [90, 55, 80, 25, 65, 40, 95],
                    [85, 80, 50, 75, 45, 55, 80]
                );
                $MarketVisitor = array(
                    ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5', 'Jan 6', 'Jan 7','Jan 8','Jan 9','Jan 10','Jan 11','Jan 12','Jan 13','Jan 14','Jan 15'],
                    [50, 20, 45, 15, 55, 20,60, 20,70,45,64,20,72,22,66]
                );
                $MarketVisitorWeekly = array(
                    ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5', 'Jan 6', 'Jan 7'],
                    [50, 20, 45, 15, 55, 20,60]
                );
                $MarketVisitorMonthly = array(
                    ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5', 'Jan 6', 'Jan 7','Jan 8','Jan 9','Jan 10','Jan 11','Jan 12','Jan 13','Jan 14','Jan 15','Jan 16', 'Jan 17', 'Jan 18', 'Jan 19', 'Jan 20', 'Jan 21', 'Jan 22','Jan 23','Jan 24','Jan 25','Jan 26','Jan 27','Jan 28','Jan 29','Jan 30'],
                    [10, 20, 35, 15, 55, 20,60, 20,70,45,64,20,72,22,66,50, 20, 45, 15, 55, 20,60, 20,70,45,64,20,72,22,66]
                );
        
                $sliderBanner = array(
                    '/assets/img/dashboard-slider-1.png','/assets/img/slide_2.jpg','/assets/img/slide_3.jpg','/assets/img/slide_2.jpg'
                );
        
                $TrendingAction = array(
                    ['/assets/img/trending-img-1.png','/assets/img/author-pic.png','Bilout jesmin','Lock and Lob x Fiesta ',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-2.png','/assets/img/author-pic.png','Brokln Simons','Interconnected Planes',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-3.png','/assets/img/author-pic.png','Bilout jesmin','Lock and Lob x Fiesta ',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-4.png','/assets/img/author-pic.png','Brokln Simons','Interconnected Planes',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-1.png','/assets/img/author-pic.png','Bilout jesmin','Lock and Lob x Fiesta ',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-2.png','/assets/img/author-pic.png','Brokln Simons','Interconnected Planes',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-3.png','/assets/img/author-pic.png','Bilout jesmin','Lock and Lob x Fiesta ',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-4.png','/assets/img/author-pic.png','Brokln Simons','Interconnected Planes',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-1.png','/assets/img/author-pic.png','Bilout jesmin','Lock and Lob x Fiesta ',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                    ['/assets/img/trending-img-2.png','/assets/img/author-pic.png','Brokln Simons','Interconnected Planes',2320382,'@broklinslam_75',75320,773.69,'2023/12/26'],
                );
                $CurrentBid = array(
                    'name' => 'Lock and Lob x Fiesta Spurs',
                    'image' => '/assets/img/profile-pic-2.png',
                    'id' => 2320382,
                    'user' => 'Brokln Simons',
                    'user_name' => 'broklinslam@75',
                    'bids' => 75320,
                    'usd' => 773.69,
                    'count_down_from' => '2023/12/26'
                );
                    return view('/core/users', ['request' => $request, 'AllNFTSUpdate'=>$AllNFTSUpdate,'AllNFTSUpdateV2'=>$AllNFTSUpdateV2,'AllNFTSUpdateV3'=>$AllNFTSUpdateV3,'TopSeller'=>$TopSeller,'TopBuyer'=>$TopBuyer,'SellHistory'=>$SellHistory,'MarketVisitor'=>$MarketVisitor,'sliderBanner'=>$sliderBanner,'TrendingAction'=>$TrendingAction,'CurrentBid'=>$CurrentBid,'MarketVisitorMonthly'=>$MarketVisitorMonthly,'MarketVisitorWeekly'=>$MarketVisitorWeekly]);
            }
        }
    }

    public function card (Request $request, $item = null) {
        if (Auth::check()) {
            if (($user = DB::table('users')
                           ->where('hide', 0)
                           ->where('hash', $item ?? Auth::user()->hash)
                           ->first())) {
                return response()->json(['hash' => $user->hash,
                                         'code' => $user->code,
                                         'mail' => $user->mail,
                                         'cell' => $user->cell,
                                         'last' => $user->last,
                                         'name' => $user->name,
                                         'fill' => $user->fill,
                                         'face' => $user->face], 200);
            } else {
                return response()->json(['text' => 'Not found.'], 404);
            }
        } else {
           return response()->json(['text' => 'Forbidden.'], 403);
        }
    }

    public function ping (Request $request) {
        if (($hook = $request->get('hook'))) {
            if (($hook = DB::table('hooks')
                           ->where('lock', false)
                           ->where('hide', false)
                           ->where('code', $hook)
                           ->first())) {
                if (Hash::check($request->get('pass'), $hook->pass)) {
                    if (DB::table('codes')
                          ->insert(['type' => 3,
                                    'item' => $hook->row,
                                    'date' => date('Y-m-d H:i:s'),
                                    'mask' => intval($hook->mask),
                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                    'pass' => Hash::make(($pass = strval(mt_rand(10000000, 99999999))))])) {
                        return response()->json(['host' => $hook->host,
                                                 'name' => $hook->name,
                                                 'firm' => $hook->firm,
                                                 'type' => intval($hook->type),
                                                 'mask' => intval($hook->mask),
                                                 'pass' => base64_encode(sprintf('%s:%s', $hash, $pass))], 200);
                    } else {
                        return response()->json(['text' => 'Internal server error.'], 500);
                    }
                } else {
                    return response()->json(['text' => 'Pass not match.'], 400);
                }
            } else {
                return response()->json(['text' => 'Hook not found.'], 404);
            }
        } else {
            if (Auth::check()) {
                if (($user = User::where('id', Auth::user()->id)
                                 ->where('hide', 0)
                                 ->first())) {
                    $client = new Client();

                    $head = [];
        
                    $link = [];
        
                    $disk = [];
        
                    $next = 0;
        
                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/i668hxjt9d3qn08b/user.search.json', [
                        RequestOptions::QUERY => [
                            'order' => ['NAME' => 'asc', 'LAST_NAME' => 'asc'],
                            'fields' => ['ID', 'NAME', 'EMAIL', 'USER_TYPE', 'IS_ONLINE', 'LAST_NAME', 'PERSONAL_CITY', 'PERSONAL_PHOTO', 'USER_TYPE' => 'employee']
                        ]
                    ]);
        
                    if (($response->getStatusCode() == 200)) {
                        if (($data = json_decode($response->getBody(), true))) {
                            $head = array_reduce($data['result'], function ($list, $item) {
                                if (trim($item['NAME'])) {
                                    array_push($list, [
                                        'item' => $item['ID'],
                                        'name' => $item['NAME'],
                                        'mail' => $item['EMAIL'],
                                        'last' => $item['LAST_NAME'],
                                        'spot' => $item['PERSONAL_CITY'] ?? null,
                                        'icon' => $item['PERSONAL_PHOTO'] ?? null,
                                        'live' => empty(strcmp($item['IS_ONLINE'], 'Y'))
                                    ]);
                                }
                                
                                return $list;
                            }, []);
                        }
                    }
        
                    do {
                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/3v18fnz4ugfcfxho/crm.company.list.json', [
                            RequestOptions::QUERY => [
                                'order' => ['TITLE' => 'asc'],
                                'start' => $next,
                                'limit' => 50
                            ]
                        ]);
            
                        if (($done = ($response->getStatusCode() == 200))) {
                            if (($data = json_decode($response->getBody(), true))) {
                                $link = array_merge($link, array_reduce($data['result'], function ($list, $item) {
                                    array_push($list, [
                                        'item' => $item['ID'],
                                        'name' => $item['TITLE']
                                    ]);
            
                                    return $list;
                                }, []));
                            }
                        }
                    } while (($done && isset($data['next']) && ($next = intval($data['next']))));
        
                    $client = new \Google\Client();
        
                    $client->setClientId(env('API_GOOGLE_CLIENT'));
        
                    $client->setClientSecret(env('API_GOOGLE_SECRET'));
        
                    $client->refreshToken(env('API_GOOGLE_TOKEN'));
        
                    $client->addScope(\Google\Service\Drive::DRIVE_FILE);
        
                    $drive = new \Google\Service\Drive($client);
        
                    $data = $drive->files->listFiles(['q' => sprintf('\'%s\' in parents and trashed = false and mimeType = \'application/vnd.google-apps.folder\'', env('API_GOOGLE_FOLDER'))]);
        
                    if (isset($data)) {
                        $disk = array_reduce($data->files, function ($list, $item) {
                            array_push($list, [
                                'item' => $item->id,
                                'name' => $item->name
                            ]);
        
                            return $list;
                        }, []);
                    }

                    return response()->json([
                        'hash' => $user->hash,
                        'code' => $user->code,
                        'nick' => $user->nick,
                        'mail' => $user->mail,
                        'cell' => $user->cell,
                        'last' => $user->last,
                        'name' => $user->name,
                        'fill' => $user->fill,
                        'face' => $user->face,
                        'type' => $user->type,
                        'item' => strval($user->id),
                        'firm' => [
                            'rate' => floatval($user->firm->rate),
                            'cost' => floatval($user->firm->cost),
                            'core' => boolval($user->firm->core),
                            'plan' => intval($user->firm->plan),
                            'hash' => $user->firm->hash,
                            'icon' => $user->firm->icon,
                            'name' => $user->firm->name
                        ],
                        'heap' => [
                            'head' => $head,
                            'link' => $link,
                            'disk' => $disk,
                            'hand' => array_reduce(DB::table('hands')
                                                     ->where('hide', 0)
                                                     ->orderBy('name', 'asc')
                                                     ->get()
                                                     ->toArray(), function ($list, $item) {
                                array_push($list, [
                                    'type' => intval($item->type),
                                    'link' => intval($item->link),
                                    'item' => intval($item->row),
                                    'hash' => $item->hash,
                                    'code' => $item->code,
                                    'card' => $item->card,
                                    'last' => $item->last,
                                    'name' => $item->name,
                                    'icon' => $item->icon,
                                    'tone' => $item->tone
                                ]);

                                return $list;
                            }, []),
                            'firm' => array_reduce(DB::table('firms')
                                                     ->where('hide', 0)
                                                     ->orderBy('name', 'asc')
                                                     ->get()
                                                     ->toArray(), function ($list, $item) {
                                array_push($list, [
                                    'lock' => intval($item->lock),
                                    'type' => intval($item->type),
                                    'item' => intval($item->row),
                                    'hash' => $item->hash,
                                    'code' => $item->code,
                                    'card' => $item->card,
                                    'name' => $item->name,
                                    'icon' => $item->icon,
                                    'tone' => $item->tone
                                ]);

                                return $list;
                            }, [])
                        ]
                    ], 200);
                } else {
                    return response()->json(['text' => 'User not found.'], 404);
                }
            } else {
                return response()->json(['text' => 'Forbidden.'], 403);
            }
        }
    }

    public function seek (Request $request, $hash = null) {
        if (($bond = DB::table('bonds')
                        ->where('hide', 0)
                        ->where('lock', 0)
                        ->where('hash', $hash)
                        ->first())) {
            return response()->json(['item' => $bond->row, 'hash' => $bond->hash, 'code' => $bond->code, 'mail' => $bond->mail, 'cell' => $bond->cell, 'name' => $bond->name, 'from' => $bond->from, 'stop' => $bond->stop], 200);
        } else {
            return response()->json(['text' => 'El código no fue encontrado.'], 400);
        }
    }

    public function scan (Request $request, $hash = null) {
        if (($bond = DB::table('bonds')
                       ->where('hide', 0)
                       ->where('lock', 0)
                       ->where('hash', $hash)
                       ->first())) {
            if (((strtotime($bond->from) < ($time = time())) && (strtotime($bond->stop) > $time))) {
                return response()->json(['item' => $bond->row, 'hash' => $bond->hash, 'code' => $bond->code, 'mail' => $bond->mail, 'cell' => $bond->cell, 'name' => $bond->name, 'from' => $bond->from, 'stop' => $bond->stop], 200);
            } else {
                return response()->json(['text' => 'El código ya no es válido.', 'data'=>$bond], 400);
            }
        } else {
            return response()->json(['text' => 'El código no fue encontrado.'], 400);
        }
    }

    public function sign (Request $request) {
        if (Auth::check()) {
            if (empty(strcasecmp($request->method(), 'POST'))) {
                return response()->json(['next' => route('dash')], 200);
            } else {
                return redirect()->route('dash');
            }
        } else {
            if (empty(strcasecmp($request->method(), 'POST'))) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|min:2',
                    'pass' => 'required|string|min:6'
                ], [
                    'name.min' => 'El campo no es válido.',
                    'pass.min' => 'El campo no es válido.',
                    'name.required' => 'El campo es requerido.',
                    'pass.required' => 'El campo es requerido.'
                ]);

                if (empty($validator->fails())) {
                    if (($user = User::where('hide', 0)
                                     ->where('lock', 0)
                                     ->where(function ($query) use ($request) {
                        $query->where('nick', trim($request->get('name')))
                              ->orWhere('mail', trim($request->get('name')))
                              ->orWhere('code', trim($request->get('name')));
                    })->first())) {
                        if (Hash::check(trim($request->get('pass')), $user->pass)) {
                            if (empty(($lock = intval($user->lock)))) {
                                if (($hook = trim($request->header('hook')))) {
                                    if (preg_match('/^(?P<hash>\w+):(?P<pass>\w+)$/', base64_decode($hook), $hook)) {
                                        if (($item = DB::table('hooks')
                                                       ->where('hide', 0)
                                                       ->where('lock', 0)
                                                       ->where('hash', $hook['hash'])
                                                       ->first())) {
                                            if (Hash::check($hook['pass'], $item->pass)) {
                                                if (DB::table('marks')
                                                      ->insert([
                                                    'sort' => 'core',
                                                    'skip' => $user->id,
                                                    'type' => User::SIGN,
                                                    'date' => date('Y-m-d H:i:s'),
                                                    'hash' => md5(uniqid(rand(), true)),
                                                    'data' => json_encode(['hook' => $item->row])])) {
                                                    if (DB::table('users')
                                                          ->where('id', $user->id)
                                                          ->update(['seen' => date('Y-m-d H:i:s')])) {
                                                        if (DB::table('codes')
                                                              ->insert([
                                                            'type' => 3,
                                                            'item' => $user->id,
                                                            'hook' => $code->hook,
                                                            'date' => date('Y-m-d H:i:s'),
                                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                                            'pass' => Hash::make(($pass = Str::random(8)))])) {
                                                            return response()->json(['pass' => base64_encode(sprintf('%s:%s', $hash, $pass))], 200);
                                                        } else {
                                                            return response()->json(['text' => 'Internal server error.'], 500);
                                                        }
                                                    } else {
                                                        return response()->json(['text' => 'Internal server error.'], 500);
                                                    }
                                                } else {
                                                    return response()->json(['text' => 'Internal server error.'], 500);
                                                }
                                            } else {
                                                return response()->json(['text' => 'Pass do not match.'], 400);
                                            }
                                        } else {
                                            return response()->json(['text' => 'Hook not found.'], 400);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Data not valid.'], 400);
                                    }
                                } else {
                                    if ((empty($user->from->mark) || DB::table('marks')
                                                                       ->insert([
                                            'sort' => 'core',
                                            'skip' => $user->id,
                                            'type' => User::SIGN,
                                            'date' => date('Y-m-d H:i:s'),
                                            'hash' => md5(uniqid(rand(), true)),
                                            'data' => json_encode(['node' => $request->ip()])]))) {
                                        if (DB::table('users')
                                              ->where('id', $user->id)
                                              ->update(['seen' => date('Y-m-d H:i:s')])) {
                                            if (boolval($request->get('code'))) {
                                                if (DB::table('codes')
                                                      ->insert([
                                                        'type' => 3,
                                                        'item' => $user->id,
                                                        'date' => date('Y-m-d H:i:s'),
                                                        'hash' => ($hash = md5(uniqid(rand(), true))),
                                                        'pass' => Hash::make(($pass = Str::random(8)))])) {
                                                    return response()->json([
                                                        'pass' => base64_encode(sprintf('%s:%s', $hash, $pass))
                                                    ], 200);
                                                } else {
                                                    return response()->json(['text' => 'Internal server error.'], 500);
                                                }
                                            } else {
                                                Auth::login($user);
        
                                                return response()->json(['next' => route('dash')], 200);
                                            }
                                        } else {
                                            return response()->json(['text' => 'Internal server error.'], 500);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Internal server error.'], 500);
                                    }
                                }
                            } else {
                                return response()->json([
                                    'text' => 'No se pudo iniciar sesión correctamente.',
                                    'list' => ['name' => 'El usuario está bloqueado.']
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'text' => 'Uno o mas campos del formulario no son correctos.',
                                'list' => ['pass' => 'La contraseña no es válida.']
                            ], 400);
                        }
                    } else {
                        return response()->json([
                            'text' => 'Uno o mas campos del formulario no son correctos.',
                            'list' => ['name' => 'El usuario no fue encontrado.']
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
            } else {
                if ($request->headers->has('data')) {
                    return response()->json(['form' => csrf_field()], 200);
                } else {
                    return view('/base/sign', ['request' => $request]);
                }
            }
        }
    }

    public function join (Request $request) {
        if (Auth::check()) {
            return redirect()->route('dash');
        } else {
            if ((strtoupper($request->method()) === 'POST')) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'last' => 'required|string',
                    'mail' => 'required|email',
                    'town' => 'required|string|max:64',
                    'cell' => 'nullable|string|max:16',
                    'pass' => 'nullable|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/'
                ], [
                    'name.required' => 'El campo es requerido.',
                    'last.required' => 'El campo es requerido.',
                    'mail.required' => 'El campo es requerido.',
                    'rown.required' => 'El campo es requerido.',
                    'mail.email' => 'El campo debe ser un correo válido.',
                    'cell.required' => 'El campo es requerido.',
                    'cell.max' => 'El campo debe tener máximo 16 caracteres.',
                    'pass.regex' => 'La contraseña debe tener minimo 8 y maximo 12 caracteres, al menos una letra mayúscula, una letra minuzcula, un numero y un caracter especial.',
                ]);

                if (empty($validator->fails())) {
                    if (empty(($same = DB::table('users')
                                         ->where('hide', false)
                                         ->where('mail', trim($request->get('mail')))
                                         ->first()))) {
                        if (($item = DB::table('users')
                                        ->insertGetId(['lock' => 1,
                                                       'type' => 4,
                                                       'code' => hexdec(uniqid()),
                                                       'hash' => md5(uniqid(rand(), true)),
                                                       'last' => trim($request->get('last')),
                                                       'name' => trim($request->get('name')),
                                                       'mail' => trim($request->get('mail')),
                                                       'town' => trim($request->get('town')),
                                                       'fill' => ($fill = ['548BF2', '7DBE71', 'B68148', 'EBB410', 'E66D5F', '9976DE'][rand(0, 5)]),
                                                       'creation' => date('Y-m-d H:i:s')]))) {
                            if (DB::table('codes')
                                    ->insert(['type' => 1,
                                            'item' => $item,
                                            'date' => date('Y-m-d H:i:s'),
                                            'hash' => ($hash = md5(uniqid(rand(), true))),
                                            'pass' => Hash::make($pass = rand(10000000, 99999999))])) {
                                Mail::send('mail.code', ['name' => trim($request->get('name')),
                                                         'last' => trim($request->get('last')),
                                                         'type' => 'mail',
                                                         'hash' => $hash,
                                                         'pass' => $pass], function ($message) use ($request) {
                                    $message->to(trim($request->get('mail')), sprintf('%s %s', trim($request->get('name')),
                                                                                                trim($request->get('last'))))
                                            ->from(env('APP_MAIL'), env('APP_NAME'))
                                            ->subject('Taller A te da la bienvenida');
                                });

                                Mail::send('mail.join', ['name' => trim($request->get('name')),
                                                         'last' => trim($request->get('last')),
                                                         'mail' => trim($request->get('mail')),
                                                         'town' => trim($request->get('town'))], function ($message) use ($request) {
                                    $message->to(env('APP_MAIL'), env('APP_NAME'))
                                            ->from(env('APP_MAIL'), env('APP_NAME'))
                                            ->subject('Nuevo registro en Taller A');
                                });

                                if (($data = trim($request->header('hook')))) {
                                    if (preg_match('/^(?P<hash>\w+):(?P<pass>\w+)$/', base64_decode($data), $data)) {
                                        if (($hook = DB::table('hooks')
                                                        ->where('lock', 0)
                                                        ->where('hash', $data['hash'])
                                                        ->first())) {
                                            if (Hash::check($data['pass'], $hook->pass)) {
                                                if (DB::table('codes')
                                                        ->insert(['type' => 4,
                                                                  'item' => $item,
                                                                  'hook' => $hook->row,
                                                                  'date' => date('Y-m-d H:i:s'),
                                                                  'hash' => ($hash = md5(uniqid(rand(), true))),
                                                                  'pass' => Hash::make(($pass = strval(mt_rand(10000000, 99999999))))])) {
                                                    return response()->json(['pass' => base64_encode(sprintf('%s:%s', $hash, $pass))], 200);
                                                } else {
                                                    return response()->json(['text' => 'Internal Server Error'], 500);
                                                }
                                            } else {
                                                return response()->json(['text' => 'Pass do not match'], 400);
                                            }
                                        } else {
                                            return response()->json(['text' => 'Hook not found.'], 400);
                                        }
                                    } else {
                                        return response()->json(['text' => 'Data not valid.'], 400);
                                    }
                                } else {
                                    return response()->json(['next' => route('code', ['type' => 'mail',
                                                                                        'item' => $hash])], 200);
                                }
                            } else {
                                return response()->json(['text' => 'No se pudo realizar el registro correctamente.'], 500);
                            }
                        } else {
                            return response()->json(['text' => 'No se pudo realizar el registro correctamente.'], 400);
                        }
                    } else {
                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => ['mail' => 'El correo electrónio ya existe.']], 400);
                    }
                } else {
                    return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                             'list' => array_map(function ($item) {
                        return current($item);
                    }, $validator->errors()->toArray())], 400);
                }
            } else {
                return view('/base/join', ['request' => $request]);
            }
        }
    }

	public function exit (Request $request) {
		if (Auth::check()) {
            if (($sign = trim($request->header('Authorization')))) {
                if (preg_match('/^(?P<hash>\w+):(?P<pass>\w+)$/', base64_decode($sign), $sign)) {
                    if (($code = DB::table('codes')
                               ->where('type', 3)
                               ->where('hash', $sign['hash'])
                               ->first())) {
                        if (Hash::check($sign['pass'], $code->pass)) {
                            if (DB::table('codes')
                                  ->where('row', $code->row)
                                  ->delete()) {
                                return response()->json(['text' => 'La sesión fue cerrada correctamente.'], 200);
                            } else {
                                return response()->json(['text' => 'No se pudo cerrar la sesión.'], 500);
                            }
                        } else {
                            return response()->json(['text' => 'No se pudo cerrar la sesión.'], 500);
                        }
                    } else {
                        return response()->json(['text' => 'La sesión no fue encontrada.'], 404);
                    }
                } else {
                    return response()->json(['text' => 'No se pudo cerrar la sesión.'], 500);
                }
            } else {
                Auth::logout();

                Session::flush();

                return redirect()->route('sign');
            }
        } else {
            if (empty($request->header('Authorization'))) {
                return redirect()->route('sign');
            } else {
                return response()->json(['text' => 'Bad Request.'], 400);
            }
        }
	}

	public function lost (Request $request) {
		if (Auth::check()) {
            return response()->json(['next' => route('dash')], 200);
        } else {
    	    if ((strtoupper($request->method()) === 'POST')) {
    		    $validator = Validator::make($request->all(), [
                    'mail' => 'required|email',
                ], [
                    'mail.required' => 'El campo es requerido.',
                    'mail.email' => 'El campo debe ser un correo válido.',
                ]);

                if ($validator->fails()) {
                    return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                             'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
                } else {
                    if (($item = DB::table('users')
                                   ->where('hide', 0)
                                   ->where('lock', 0)
                                   ->where('mail', trim($request->get('mail')))
                                   ->first())) {
                        if (DB::table('codes')
                              ->insert(['type' => 5,
                                        'item' => $item->id,
                                        'date' => date('Y-m-d H:i:s'),
                                        'hash' => ($hash = md5(uniqid(rand(), true))),
                                        'pass' => Hash::make(($pass = md5(uniqid(rand(10000000, 99999999), true))))])) {
                            Mail::send('mail.lost', ['name' => trim($request->get('name')),
                                                     'last' => trim($request->get('last')),
                                                     'hash' => $hash,
                                                     'pass' => $pass], function ($message) use ($request) {
                              $message->to(trim($request->get('mail')), sprintf('%s %s', trim($request->get('name')),
                                                                                         trim($request->get('last'))))
                                      ->from(env('APP_MAIL'), env('APP_NAME'))
                                      ->subject('Cambiar contraseña');
                            });

                            return response()->json(['text' => 'A tu correo se han enviado instrucciones sobre cómo cambiar la contraseña.'], 200);
                        } else {
                            return response()->json(['text' => 'No se pudo realizar la recuperación correctamente.'], 500);
                        }
                    } else {
                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => ['mail' => 'El correo electrónico no fue encontrado.']], 400);
                    }
                }
    	    } else {
    		    return view('/base/lost', ['request' => $request]);
    	    }
        }
	}

    public function pass (Request $request, $hash, $pass) {
        if (Auth::check()) {
            return response()->json(['next' => route('dash')], 200);
        } else {
            if ((($item = DB::table('codes')
                            ->join('users', 'codes.user', '=', 'users.id')
                            ->where('codes.type', 5)
                            ->where('codes.lock', false)
                            ->where('codes.hash', $hash)
                            ->select('codes.row', 'codes.hash', 'codes.item', 'codes.pass', 'users.code', 'users.last', 'users.name', 'users.mail', 'users.cell')
                            ->first()) && Hash::check(trim($pass), $item->pass))) {
                if ((strtoupper($request->method()) === 'POST')) {
                    $validator = Validator::make($request->all(), [
                        'pass' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/',
                        'same' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/'
                    ], [
                        'pass.required' => 'El campo es requerido.',
                        'pass.regex' => 'La contraseña debe tener minimo 8 y maximo 12 caracteres, al menos una letra mayúscula, una letra minuzcula, un numero y un caracter especial.',
                        'same.required' => 'El campo es requerido.',
                        'same.regex' => 'La contraseña debe tener minimo 8 y maximo 12 caracteres, al menos una letra mayúscula, una letra minuzcula, un numero y un caracter especial.'
                    ]);

                    if (empty($validator->fails())) {
                        if (empty(strcmp(trim($request->get('pass')), trim($request->get('same'))))) {
                            if (DB::table('codes')->where('row', $item->row)->update(['lock' => true])) {
                                if (DB::table('users')->where('id', $item->item)->update(['pass' => Hash::make(trim($request->get('pass')))])) {
                                    if (DB::table('codes')
                                          ->insert(['type' => 5,
                                                    'item' => $item->item,
                                                    'date' => date('Y-m-d H:i:s'),
                                                    'hash' => ($hash = md5(uniqid(rand(), true))),
                                                    'pass' => Hash::make(($pass = md5(uniqid(rand(10000000, 99999999), true))))])) {
                                        Mail::send('mail.pass', ['hash' => $hash,
                                                                 'pass' => $pass,
                                                                 'last' => $item->last,
                                                                 'name' => $item->name,
                                                                 'mail' => $item->mail,
                                                                 'cell' => $item->cell], function($message) use ($item) {
                                            $message->to($item->mail, sprintf('%s %s', $item->name, $item->last))
                                                    ->from(env('APP_MAIL'), env('APP_NAME'))
                                                    ->subject('Cambio de contraseña');
                                        });

                                        return response()->json(['next' => route('sign')], 200);
                                    } else {
                                        return response()->json(['text' => 'No se pudo cambiar la contraseña correctamente.'], 400);
                                    }
                                } else {
                                    return response()->json(['text' => 'No se pudo cambiar la contraseña correctamente.'], 400);
                                } 
                            } else {
                                return response()->json(['text' => 'No se pudo cambiar la contraseña correctamente.'], 400);
                            }
                        } else {
                            return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                     'list' => ['same' => 'La contraseña no coincide.']], 400);
                        }
                    } else {
                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) {return current($item);}, $validator->errors()->toArray())], 400);
                    }
                } else {
                    return view('/base/pass', ['request' => $request, 'hash' => $hash, 'pass' => $pass]);
                }
            } else {
                return view('/base/lost', ['request' => $request]);
            }
        }
    }

    public function lock (Request $request, $hash) {
		if (Auth::check()) {
            return redirect()->route('dash');
        } else {
            if (($code = DB::table('codes')
                           ->where('lock', 0)
                           ->where('type', 1)
                           ->where('hash', $hash)
                           ->first())) {
                if (($user = DB::table('users')
                               ->where('hide', 0)
                               ->where('lock', 1)
                               ->where('id', $code->item)
                               ->first())) {
                    if (empty($user->pass)) {
                        if (empty(strcasecmp($request->method(), 'POST'))) {
                            $validator = Validator::make($request->all(), [
                                'pass' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/',
                                'same' => 'required|same:pass'
                            ], [
                                'same.same' => 'La contraseña debe coincidir.',
                                'pass.regex' => 'La contraseña debe tener mínimo 8 y como máximo 12 caracteres, al menos una letra mayúscula, una letra minúscula, un número y un caracter especial.',
                                'pass.required' => 'El campo es requerido.',
                                'same.required' => 'El campo es requerido.'
                            ]);

                            if (empty($validator->fails())) {
                                if (DB::table('users')
                                      ->where('id', $user->id)
                                      ->update(['lock' => 0, 'pass' => Hash::make(trim($request->get('pass')))])) {
                                    return response()->json(['next' => route('sign')], 200);
                                } else {
                                    abort(500, 'User could not be updated.');
                                }
                            } else {
                                return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                                         'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())], 400);
                            }
                        } else {
                            return view('/base/pass', ['request' => $request, 'hash' => $hash, 'name' => 'lock']);
                        }
                    } else {
                        if ($user->update(['lock' => 0])) {
                            return redirect()->route('sign');
                        } else {
                            abort(500, 'User could not be updated.');
                        }
                    }
                } else {
                    abort(404, 'User not found.');
                }
            } else{
                abort(404, 'Code not found.');
            }
        }
	}

    public function done (Request $request, $item) {
        if (Auth::check()) {
            return redirect()->route('dash');
        } else {
            if (($item = DB::table('codes')
                           ->where('lock', true)
                           ->where('hash', $item)
                           ->first())) {
                return view('/base/done', ['request' => $request, 'item' => $item]);
            } else {
                abort(404, 'Code not found.');
            }
        }
    }

	public function code (Request $request, $type, $hash, $pass = null) {
		if (Auth::check()) {
            return redirect()->route('dash');
        } else {
            if (($item = DB::table('codes')
                            ->join('users', 'codes.item', '=', 'users.id')
                            ->where('codes.lock', 0)
                            ->where('codes.hash', $hash)
                            ->where('codes.type', ((strtolower($type) == 'cell') ? 2 : 1))
                            ->select('codes.row', 'codes.hash', 'codes.item', 'codes.pass', 'users.code', 'users.last', 'users.name', 'users.mail', 'users.cell')
                            ->first())) {
                if ((($request->method() === 'POST') || empty(empty(trim($pass))))) {
                    $validator = Validator::make($request->all(), [
                        'pass' => 'required|string'
                    ], [
                        'pass.required' => 'El campo es requerido.'
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                 'list' => array_map(function ($item) { return current($item); }, $validator->errors()->toArray())], 400);
                    } else {
                        if (Hash::check(trim($request->get('pass')), $item->pass)) {
                            if (DB::table('codes')->where('row', $item->row)->update(['lock' => 1])) {
                                if (DB::table('users')->where('id', $item->item)->update(['lock' => 0])) {
                                    Mail::send('mail.join', ['last' => $item->last, 'name' => $item->name, 'mail' => $item->mail, 'cell' => $item->cell], function($message) use ($item) {
                                        $message->to('admin@viferente.com', env('APP_NAME'))
                                                ->from($item->mail, sprintf('%s %s', $item->name, $item->last))
                                                ->subject('Nuevo usuario registrado');
                                    });

                                    return response()->json(['next' => route('done', ['item' => $item->hash])], 200);
                                } else {
                                    return response()->json(['text' => 'No se pudo activar la cuenta correctamente.'], 400);
                                } 
                            } else {
                                return response()->json(['text' => 'No se pudo activar la cuenta correctamente.'], 400);
                            }
                        } else {
                            return response()->json(['text' => 'Uno o mas campos del formulario no son correctos.',
                                                     'list' => ['pass' => 'El código de activación no es correcto.']], 400);
                        }
                    }
                } else {
                    return view('/base/code', ['request' => $request, 'type' => $type, 'item' => $item->hash]);
                }
            } else{
                abort(404, 'Code not found.');
            }
        }
	}
}