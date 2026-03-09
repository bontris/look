<?php

namespace App\Http\Controllers\Site;

use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class HandController extends Controller
{
	public function main (Request $request, $item = null) {
        if (isset($item)) {
            if (($item = DB::table('hands')
                           ->where('hide', 0)
                           ->where('lock', 0)
                           ->where('slug', $item)
                           ->first())) {print_r($item);
            } else {
                abort(404);
            }
        } else {

        }
	}
}