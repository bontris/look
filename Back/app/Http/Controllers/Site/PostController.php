<?php

namespace App\Http\Controllers\Site;

use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class PostController extends Controller
{
	public function main (Request $request, $item = null) {
        if (isset($item)) {
            if (($post = DB::table('posts')
                           ->where('posts.hide', 0)
                           ->where('posts.lock', 0)
                           ->where('posts.slug', $item)
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
                                    'posts.content',
                                    'posts.caption',
                                    'posts.abstract',
                                    'posts.creation',
                                    'kinds.tone AS kindTone',
                                    'kinds.name AS kindName',
                                    'kinds.slug AS kindSlug',
                                    'hands.face AS handFace',
                                    'hands.note AS handNote',
                                    'hands.last AS handLast',
                                    'hands.name AS handName',
                                    'hands.slug AS handSlug')
                           ->first())) {
                return view('/site/posts/view', ['post' => ['item' => intval($post->row),
                                                            'slug' => $post->slug,
                                                            'snap' => $post->snap,
                                                            'kind' => ['tone' => $post->kindTone,
                                                                       'name' => $post->kindName,
                                                                       'slug' => $post->kindSlug],
                                                            'hand' => ['last' => $post->handLast,
                                                                       'name' => $post->handName,
                                                                       'note' => $post->handNote,
                                                                       'face' => $post->handFace,
                                                                       'slug' => $post->handSlug],
                                                            'caption' => $post->caption,
                                                            'content' => $post->content,
                                                            'abstract' => $post->abstract,
                                                            'creation' => $post->creation],
                                                 'last' => array_reduce(DB::table('posts')
                                                                          ->where('hide', 0)
                                                                          ->where('lock', 0)
                                                                          ->where('row', '<>', $post->row)
                                                                          ->orderBy('creation', 'desc')
                                                                          ->take(5)
                                                                          ->get()
                                                                          ->toArray(), function ($list, $item) {
                    array_push($list, ['item' => intval($item->row),
                                       'slug' => $item->slug,
                                       'snap' => $item->snap,
                                       'caption' => $item->caption,
                                       'creation' => $item->creation]);

                    return $list;
                }, [])]);
            } else {
                abort(404);
            }
        } else {
            return view('/site/posts/main', ['list' => array_reduce(DB::table('posts')
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