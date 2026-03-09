<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class PullController extends Controller
{
    public function main (Request $request, $kind) {
        switch (strtolower(trim($kind))) {
            case 'spot':
                $query = DB::table('spots')
                           ->where('hide', 0)
                           ->where('lock', 0)
                           ->where('language', strtolower($request->get('code', 'es')))
                           ->orderBy('sort', 'asc');

                return response()->json(['high' => ($high = $query->count()),
                                         'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                         'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                         'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                      ->take(($take ? $take : $high))
                                                                      ->orderBy('row', 'desc')
                                                                      ->select('spots.*', DB::raw('(SELECT COUNT(*) FROM gifts WHERE gifts.firm = spots.firm AND gifts.hide = 0 AND gifts.lock = 0) AS `gift`'))
                                                                      ->get()
                                                                      ->toArray(), function ($list, $item) {
                    array_push($list, ['description' => trim($item->description),
                                       'transparent' => boolval($item->transparent),
                                       'translate' => json_decode($item->translate),
                                       'longitude' => floatval($item->longitude),
                                       'latitude' => floatval($item->latitude),
                                       'altitude' => floatval($item->altitude),
                                       'rotate' => json_decode($item->rotate),
                                       'scale' => json_decode($item->scale),
                                       'size' => json_decode($item->size),
                                       'firm' => intval($item->firm),
                                       'gift' => intval($item->gift),
                                       'type' => intval($item->type),
                                       'show' => json_decode($item->show),
                                       'item' => intval($item->row),
                                       'name' => trim($item->name),
                                       'file' => trim($item->file),
                                       'path' => trim($item->path),
                                       'hash' => $item->hash,
                                       'code' => $item->code]);

                    return $list;
                }, [])]);
            case 'site':
                $query = DB::table('sites')
                           ->where('hide', 0)
                           ->where('lock', 0)
                           ->where('language', strtolower($request->get('code', 'es')));

                return response()->json(['high' => ($high = $query->count()),
                                         'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                         'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                         'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                      ->take(($take ? $take : $high))
                                                                      ->orderBy('row', 'desc')
                                                                      ->get()
                                                                      ->toArray(), function ($list, $item) {
                    array_push($list, ['recommendations' => trim($item->recommendations),
                                       'description' => trim($item->description),
                                       'activities' => trim($item->activities),
                                       'longitude' => floatval($item->longitude),
                                       'latitude' => floatval($item->latitude),
                                       'street' => trim($item->street),
                                       'views' => intval($item->views),
                                       'likes' => intval($item->likes),
                                       'phone' => trim($item->phone ? $item->phone : ''),
                                       'town' => intval($item->town),
                                       'type' => intval($item->type),
                                       'item' => intval($item->row),
                                       'date' => trim($item->date),
                                       'file' => trim($item->file),
                                       'snap' => trim($item->snap),
                                       'clip' => trim($item->clip),
                                       'link' => trim($item->link),
                                       'name' => $item->name,
                                       'hash' => $item->hash,
                                       'code' => $item->code]);

                    return $list;
                }, [])]);
            case 'post':
                $query = DB::table('posts')
                            ->where('hide', 0)
                            ->where('lock', 0)
                            ->where('language', strtolower($request->get('code', 'es')));

                return response()->json(['high' => ($high = $query->count()),
                                         'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                         'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                         'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                        ->take(($take ? $take : $high))
                                                                        ->orderBy('row', 'desc')
                                                                        ->get()
                                                                        ->toArray(), function ($list, $item) {
                    array_push($list, ['creation' => trim($item->creation),
                                       'content' => trim($item->content),
                                       'caption' => trim($item->caption),
                                       'extract' => trim($item->extract),
                                       'views' => intval($item->views),
                                       'likes' => intval($item->likes),
                                       'item' => intval($item->row),
                                       'hash' => $item->hash,
                                       'code' => $item->code,
                                       'snap' => $item->snap,
                                       'link' => $item->link]);

                    return $list;
                }, [])]);
            case 'link':
                $query = DB::table('links')
                           ->where('hide', 0)
                           ->where('lock', 0)
                           ->where('language', strtolower($request->get('code', 'es')));

                return response()->json(['high' => ($high = $query->count()),
                                         'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                         'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                         'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                      ->take(($take ? $take : $high))
                                                                      ->orderBy('row', 'desc')
                                                                      ->get()
                                                                      ->toArray(), function ($list, $item) {
                    array_push($list, ['creation' => trim($item->creation),
                                       'content' => trim($item->content),
                                       'caption' => trim($item->caption),
                                       'picture' => trim($item->picture),
                                       'path' => trim($item->path),
                                       'item' => intval($item->row),
                                       'hash' => $item->hash,
                                       'code' => $item->code]);

                    return $list;
                }, [])]);
            case 'gift':
                $query = DB::table('gifts')
                           ->where('gifts.hide', 0)
                           ->where('gifts.lock', 0)
                           ->join('firms', function ($join) {
                    $join->on('gifts.firm', 'firms.row');
                })->leftJoin('chits', function ($join) {
                    $join->on('gifts.row', 'chits.item')
                         ->where('chits.type', 1)
                         ->where('chits.skip', Auth::check() ? Auth::user()->id : 0);
                });

                return response()->json(['high' => ($high = $query->count()),
                                         'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                         'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                         'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                      ->take(($take ? $take : $high))
                                                                      ->orderBy('gifts.rate', 'desc')
                                                                      ->select(
                                                                        'gifts.row',
                                                                        'gifts.hash',
                                                                        'gifts.code',
                                                                        'gifts.rate',
                                                                        'gifts.term',
                                                                        'gifts.firm',
                                                                        'gifts.snap',
                                                                        'firms.name',
                                                                        'firms.face',
                                                                        'chits.lock',
                                                                        DB::raw('ST_AsText(firms.spot) AS spot')
                                                                      )
                                                                      ->get()
                                                                      ->toArray(), function ($list, $item) {
                    array_push($list, ['spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : null,
                                       'snap' => $item->snap ?? $item->face,
                                       'lock' => boolval($item->lock),
                                       'firm' => intval($item->firm),
                                       'rate' => intval($item->rate),
                                       'item' => intval($item->row),
                                       'term' => trim($item->term),
                                       'name' => trim($item->name),
                                       'code' => trim($item->code),
                                       'hash' => $item->hash]);

                    return $list;
                }, [])]);
            case 'card':
                $query = DB::table('cards')
                            ->where('hide', 0)
                            ->where('lock', 0)
                            ->where('language', strtolower($request->get('code', 'es')));

                return response()->json(['high' => ($high = $query->count()),
                                         'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                         'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                         'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                      ->take(($take ? $take : $high))
                                                                      ->orderBy('sort', 'asc')
                                                                      ->orderBy('name', 'asc')
                                                                      ->get()
                                                                      ->toArray(), function ($list, $item) {
                    array_push($list, ['item' => intval($item->row),
                                       'snap' => trim($item->snap),
                                       'link' => trim($item->link),
                                       'name' => $item->name,
                                       'hash' => $item->hash,
                                       'code' => $item->code]);

                    return $list;
                }, [])]);
            case 'town':
                $query = DB::table('towns')
                            ->where('hide', 0)
                            ->where('lock', 0);

                return response()->json(['high' => ($high = $query->count()),
                                            'take' => ($take = min(max(intval($request->get('take')), 0), 64)),
                                            'page' => ($page = ($take ? min(max(intval($request->get('page')), 1), ceil(($high / $take))) : 0)),
                                            'data' => array_reduce($query->skip(($take ? (($page - 1) * $take) : 0))
                                                                        ->take(($take ? $take : $high))
                                                                        ->orderBy('name', 'asc')
                                                                        ->get()
                                                                        ->toArray(), function ($list, $item) {
                    array_push($list, ['item' => intval($item->row),
                                        'snap' => trim($item->snap),
                                        'name' => $item->name,
                                        'hash' => $item->hash,
                                        'code' => $item->code]);

                    return $list;
                }, [])]);
            default:
                return response('Bad Request', 400);
        }
    }
}