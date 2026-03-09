<?php

namespace App\Http\Controllers\Team;

use Auth;

use User;

use Validator;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

class LoadController extends Controller
{
    public function main (Request $request, $task = null, $item = null) {
    	if (isset($item)) {
    		if (($item = DB::table('loads')
                           ->join('items', function ($join) {
                               $join->on('loads.item', 'items.row');
                           })
                           ->join('nodes', function ($join) {
                               $join->on('items.node', 'nodes.row');
                           })
                           ->join('units', function ($join) {
                               $join->on('nodes.unit', 'units.row')
                                    ->where('units.firm', Auth::user()->firm);
                           })
                           ->where('loads.hide', 0)
                           ->where('loads.hash', $item)
                           ->select('loads.*')
                           ->first())) {
    			switch (strtolower($task)) {
    				case 'drop':
    					if (DB::table('items')
			                  ->where('row', $item->row)
			                  ->update(['hide' => 1, 'deletion' => date('Y-m-d H:i:s')])) {
			              	return response()->json(['text' => 'El registro fue eliminado con éxito.'], 200);
			            } else {
			              	return response()->json(['text' => 'El registro no pudo ser eliminado.'], 500);
			            }
    				default:
    					return response()->json($item, 200);
    			}
    		} else {
    			return response()->json(['text' => 'El registro no fue encontrado.'], 404);
    		}
    	} else {
    		switch (strtolower($task)) {
    			case 'load':
    				$query = DB::table('loads')
                               ->join('items', function ($join) {
                                   $join->on('loads.item', 'items.row');
                               })
                               ->join('nodes', function ($join) {
                                   $join->on('items.node', 'nodes.row');
                               })
                               ->join('units', function ($join) {
                                   $join->on('nodes.unit', 'units.row')
                                        ->where('units.firm', Auth::user()->firm);
                               })
			                   ->where('loads.hide', 0);

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

			            if (count($texts)) {
			              	$query->where(function ($query) use ($texts) {
				                foreach ($texts as $item => $data) {
				                  if (empty($item)) {
				                    $query->where(function ($query) use ($data) {
				                      $query->where('items.code', 'like', sprintf('%%%s%%', $data))
									        ->orWhere('items.nick', 'like', sprintf('%%%s%%', $data))
                                            ->orWhere('items.name', 'like', sprintf('%%%s%%', $data));
				                    });
				                  } else {
				                    $query->orWhere(function ($query) use ($data) {
				                      $query->where('items.code', 'like', sprintf('%%%s%%', $data))
									        ->orWhere('items.nick', 'like', sprintf('%%%s%%', $data))
                                            ->orWhere('items.name', 'like', sprintf('%%%s%%', $data));
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
			                                                              ->orderBy('loads.date', 'desc')
                                                                          ->select('loads.*', 'items.type', 'items.nick', 'items.name', 'nodes.card', 'nodes.name AS node')
			                                                              ->get()
			                                                              ->toArray(), function ($list, $item) {
			            array_push($list, ['test' => intval($item->test),
                                           'type' => intval($item->type),
			            	               'item' => intval($item->row),
			                               'hash' => $item->hash,
			                               'name' => $item->name,
			                               'nick' => $item->nick,
                                           'card' => $item->card,
                                           'node' => $item->node,
                                           'date' => $item->date,
                                           'code' => $item->code,
                                           'data' => $item->data]);

			            return $list;
			        }, [])]);
				default:
    				return view('/team/loads', ['request' => $request]);
    		}
    	}
    }
}