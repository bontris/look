<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
	public function main (Request $request, $sort = null) {
        if ((strtoupper($request->method()) === 'POST')) {
            switch (strtolower(trim($sort))) {
                default:
                    if (($item = DB::table('chits')
                                   ->where('chits.hide', 0)
                                   ->where('chits.type', 1)
                                   ->where('chits.hash', trim($request->get('code')))
                                   ->join('gifts', function ($join) use ($request) {
                        $join->on('chits.item', 'gifts.row')
                             ->where('gifts.firm', Auth::user()->id);
                    })->join('users', function ($join) use ($request) {
                        $join->on('chits.skip', 'users.id');
                    })->select(
                        'chits.row',
                        'chits.lock',
                        'chits.date',
                        'chits.hash',
                        'gifts.code',
                        'gifts.rate',
                        'users.name',
                        'users.last',
                    )->first())) {
                        return response()->json([
                            'lock' => boolval($item->lock),
                            'rate' => intval($item->rate),
                            'item' => intval($item->row),
                            'date' => $item->date,
                            'code' => $item->code,
                            'hash' => $item->hash,
                            'name' => $item->name,
                            'last' => $item->last
                        ], 200);
                    } else {
                        return response()->json(['text' => 'El código no fue encontrado.'], 404);
                    }
            }
        } else {
            return view('/team/scan', ['request' => $request]);
        }
	}
}
