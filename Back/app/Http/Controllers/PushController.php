<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class PushController extends Controller
{
    public function main (Request $request) {
    	file_put_contents('data.txt', $request->getContent());
    }
}