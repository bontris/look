<?php

namespace App\Http\Controllers\Site;

use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
	public function main (Request $request, $path = null) {
		switch (strtolower($path)) {
			case 'nosotros':
				return view('/site/pages/0001');
			default:
				return view('/site/home', ['cards' => array_reduce(DB::table('cards')
				                                                     ->where('hide', 0)
																	 ->where('lock', 0)
																	 ->take(10)
																	 ->get()
																	 ->toArray(), function ($list, $item) {
					array_push($list, ['item' => intval($item->row),
					                   'code' => $item->code,
					                   'name' => $item->name,
									   'text' => $item->text,
									   'link' => $item->link,
									   'snap' => $item->snap]);
					
					return $list;
				}, []),
				'posts' => array_reduce(DB::table('posts')
								->where('posts.hide', 0)
								->where('posts.lock', 0)
								->join('kinds', function ($join) use ($request) {
									$join->on('posts.kind', 'kinds.row');
								})
								->join('hands', function ($join) use ($request) {
									$join->on('posts.hand', 'hands.row');
								})
								->select('posts.row',
								         'posts.slug',
										 'posts.hash',
										 'posts.snap',
										 'posts.caption',
										 'posts.abstract',
										 'posts.creation',
										 'kinds.tone AS kindTone',
										 'kinds.name AS kindName',
										 'kinds.slug AS kindSlug',
										 'hands.face AS handFace',
										 'hands.last AS handLast',
										 'hands.name AS handName',
										 'hands.slug AS handSlug')
								->take(10)
								->get()
								->toArray(), function ($list, $item) {
					array_push($list, ['item' => intval($item->row),
					                   'slug' => $item->slug,
									   'snap' => $item->snap,
									   'kind' => ['tone' => $item->kindTone,
									              'name' => $item->kindName,
												  'slug' => $item->kindSlug],
									   'hand' => ['last' => $item->handLast,
									              'name' => $item->handName,
												  'face' => $item->handFace,
												  'slug' => $item->handSlug],
									   'caption' => $item->caption,
									   'abstract' => $item->abstract,
									   'creation' => $item->creation]);

					return $list;
				}, [])]);
		}
	}
}