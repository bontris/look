<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */

    protected $except = [
        '*'
    ];
    
    public function handle($request, Closure $next) {
    	if (($request->is('ping') || $request->is('push/*') || $request->header('hook') || $request->header('sign') || $request->header('Authorization'))) {
    		return $next($request);
    	}

    	return parent::handle($request, $next);
    }
}
