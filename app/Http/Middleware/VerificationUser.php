<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class VerificationUser
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
        if(!Auth::user()->verified) {
          return redirect(route('website.receive.opt.form'));
        }

        return $next($request);
    }
}
