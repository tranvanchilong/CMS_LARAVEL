<?php

namespace App\Http\Middleware\LMS;

use Closure;

class WebAuthenticate
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
        if (auth()->guard('lms_user')->check()) {
            return $next($request);
        }
        return redirect('/lms/login');
    }
}
