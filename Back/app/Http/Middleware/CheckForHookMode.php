<?php

namespace App\Http\Middleware;

use Auth;

use Closure;

use App\Models\User;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

class CheckForHookMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	if (($sign = $request->header('Authorization'))) {
            if (preg_match('/^(?P<hash>\w+):(?P<pass>\w+)$/', base64_decode($sign), $sign)) {
                if (($code = DB::table('codes')
                               ->where('type', 3)
                               ->where('lock', false)
                               ->where('hash', $sign['hash'])
                               ->first())) {
                    if (Hash::check($sign['pass'], $code->pass)) {
                        if (($user = User::where('id', $code->item)
                                         ->where('hide', false)
                                         ->where('lock', false)
                                         ->first())) {
                            if (empty(intval($user->lock))) {
                                $user->test = $code->mask & 0x01;
                                
                                Auth::login($user);
                            }
                        }
                    }
                }
            }
    	}
        
        return $next($request)->header('Access-Control-Allow-Origin', '*')
                              ->header('Access-Control-Allow-Methods', '*')
                              ->header('Access-Control-Allow-Headers', '*');
    }
}