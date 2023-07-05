<?php

namespace App\Http\Middleware\LMS;

use Closure;
use Illuminate\Support\Facades\Auth;

class Impersonate
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
        if (session()->has('impersonated')) {
            Auth::guard('lms_user')->onceUsingId(session()->get('impersonated'));
        }

        return $next($request);
    }
}
