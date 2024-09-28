<?php

namespace App\Http\Middleware;

use Closure;

class RestrictByDomain
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
        // Check if the request originated from the allowed domain
        if ($request->getHost() !==  env('APP_HOST')) {
            return response('Unauthorized.', 401);
        }


        return $next($request);
    }
}
