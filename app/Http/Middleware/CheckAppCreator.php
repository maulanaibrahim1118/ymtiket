<?php

namespace App\Http\Middleware;

use Closure;

class CheckAppCreator
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
        if (config('app.creator') !== 'maulana ibrahim') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}