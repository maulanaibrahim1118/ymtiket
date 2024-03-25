<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class CsrfTokenLifetimeForLoginPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->routeIs('login.*')) {
            Config::set('session.lifetime', 480); // Set lifetime CSRF token login ke 480 menit
        }

        return $next($request);
    }
}