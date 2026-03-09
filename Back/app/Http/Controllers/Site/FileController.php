<?php

namespace App\Http\Controllers\Site;

use Auth;

use User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
	public function main (Request $request, $type = null) {
        if (empty(strcmp(strtoupper($request->method()), 'POST'))) {
        	if (isset($type)) {
        		$query = DB::table('documents')
		                   ->where('documents.hide', 0)
		                   ->where('documents.area', intval($request->get('area')))
		                   ->where('documents.type', ['POD' => 1,
		                   	                          'EOT' => 2,
		                   	                          'PDD' => 3][($type = strtoupper($type))])
		                   ->join('files', function ($join) {
	                           $join->on('documents.file', 'files.row');
	                       });
	            
	            if ((empty(Auth::check()) || (empty((Auth::user()->type == User::ROOT)) &&
	            	                          empty((Auth::user()->type == User::TEAM))))) {
	            	$query->where('documents.lock', 0);
	            }

	            if (($find = trim($request->get('find')))) {
		            $match = [];

		            $rules = [];

		            $names = [];

		            $texts = [];

		            $dates = [];

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
		                    $texts[] = strtolower($part);
		                  }
		                }
		              } 
		            }

		            foreach ($rules as $from => $data) {
		              switch ($from) {
		                default:
		                  switch ($data['name']) {
		                  	case 'name':
		                      $query->where('documents.name', 'like', sprintf('%%%s%%', $data['data']));
		                      break;
		                    case 'code':
		                      $query->where('documents.code', 'like', sprintf('%%%s%%', $data['data']));
		                      break;
		                  }
		              }
		            }

		            if (count($dates)) {
			            $query->where(function ($query) use ($dates) {
			                foreach ($dates as $item => $date) {
			                    if (empty($item)) {
			                        $query->where('documents.creation', $date);
			                    } else {
			                        $query->orWhere('documents.creation', $date);
			                    }
			                }
			            });
		            }

		            if (count($texts)) {
		              	$query->where(function ($query) use ($texts) {
			                foreach ($texts as $item => $text) {
			                  if (empty($item)) {
			                    $query->where(function ($query) use ($text) {
			                      $query->where('documents.code', 'like', sprintf('%%%s%%', $text))
	                                    ->orWhere('documents.name', 'like', sprintf('%%%s%%', $text));
			                    });
			                  } else {
			                    $query->orWhere(function ($query) use ($text) {
			                      $query->where('documents.code', 'like', sprintf('%%%s%%', $text))
	                                    ->orWhere('documents.name', 'like', sprintf('%%%s%%', $text));
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
		                                                              ->orderBy('documents.row', 'desc')
		                                                              ->select('files.hash',
		                                                              	       'files.type',
		                                                              	       'files.size',
		                                                              	       'documents.code',
		                                                                       'documents.name',
		                                                                       'documents.creation')
		                                                              ->get()
		                                                              ->toArray(), function ($list, $item) {
				    if (($size = max(intval($item->size), 0))) {
				      $size = round($size / pow(1024, floor(log($size, 1024))), 2);
				    }

		            array_push($list, ['hash' => $item->hash,
		                               'code' => $item->code,
		                               'name' => $item->name,
		                               'type' => $item->type,
		                               'size' => sprintf('%d %s', $size, ['Bytes',
										                                  'KB',
										                                  'MB',
										                                  'GB',
										                                  'TB',
										                                  'PB',
										                                  'EB',
										                                  'ZB',
										                                  'YB'][$size ? floor(log(intval($item->size), 1024)) : 0]),
		                               'date' => date('d/m/Y H:m', strtotime($item->creation))]);

		            return $list;
		        }, [])]);
        	} else {
        		$query = DB::table('cartographies')
		                   ->where('cartographies.hide', 0)
		                   ->where('cartographies.town', intval($request->get('area')))
		                   ->join('files AS mains', function ($join) {
	                           $join->on('cartographies.main', 'mains.row');
	                       })
	                       ->leftJoin('files AS datas', function ($join) {
	                           $join->on('cartographies.data', 'datas.row');
	                       })
                           ->leftJoin('files', function ($join) {
	                           $join->on('cartographies.file', 'files.row');
	                       })
	                       ->join('features', function ($join) {
	                           $join->on('cartographies.feature', 'features.row');
	                       });
	            
	            if ((empty(Auth::check()) || (empty((Auth::user()->type == User::ROOT)) &&
	            	                          empty((Auth::user()->type == User::TEAM))))) {
	            	$query->where('cartographies.lock', 0);
	            }

	            if (($find = trim($request->get('find')))) {
		            $match = [];

		            $rules = [];

		            $names = [];

		            $texts = [];

		            $dates = [];

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
		                    $texts[] = strtolower($part);
		                  }
		                }
		              } 
		            }

		            foreach ($rules as $from => $data) {
		              switch ($from) {
		                default:
		                  switch ($data['name']) {
		                  	case 'name':
		                      $query->where('cartographies.name', 'like', sprintf('%%%s%%', $data['data']));
		                      break;
		                    case 'code':
		                      $query->where('cartographies.code', 'like', sprintf('%%%s%%', $data['data']));
		                      break;
		                  }
		              }
		            }

		            if (count($dates)) {
			            $query->where(function ($query) use ($dates) {
			                foreach ($dates as $item => $date) {
			                    if (empty($item)) {
			                        $query->where('cartographies.creation', $date);
			                    } else {
			                        $query->orWhere('cartographies.creation', $date);
			                    }
			                }
			            });
		            }

		            if (count($texts)) {
		              	$query->where(function ($query) use ($texts) {
			                foreach ($texts as $item => $text) {
			                  if (empty($item)) {
			                    $query->where(function ($query) use ($text) {
			                      $query->where('cartographies.code', 'like', sprintf('%%%s%%', $text))
	                                    ->orWhere('cartographies.name', 'like', sprintf('%%%s%%', $text));
			                    });
			                  } else {
			                    $query->orWhere(function ($query) use ($text) {
			                      $query->where('cartographies.code', 'like', sprintf('%%%s%%', $text))
	                                    ->orWhere('cartographies.name', 'like', sprintf('%%%s%%', $text));
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
		                                                              ->orderBy('cartographies.row', 'desc')
		                                                              ->select('cartographies.*',
		                                                              	       'mains.hash AS main',
		                                                              	       'datas.hash AS data',
		                                                              	       'files.hash AS file',
		                                                              	       'features.type AS type',
		                                                              	       'features.size AS size',
		                                                              	       'features.name AS feature')
		                                                              ->get()
		                                                              ->toArray(), function ($list, $item) {
				    if (($size = max(intval($item->size), 0))) {
				      $size = round($size / pow(1024, floor(log($size, 1024))), 2);
				    }

		            array_push($list, ['hash' => $item->hash,
		                               'code' => $item->code,
		                               'name' => $item->name,
		                               'type' => $item->type,
		                               'size' => $item->size,
		                               'main' => $item->main,
		                               'data' => $item->data,
		                               'file' => $item->file,
		                               'date' => date('d/m/Y H:m', strtotime($item->creation)),
		                               'feature' => $item->feature]);

		            return $list;
		        }, [])]);
        	}
        } else {
        	return view('/site/file', ['type' => $type]);
        }
	}
}