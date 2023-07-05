<?php

namespace App\Http\Middleware\LMS;

use Closure;

class UserNotAccess
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
        if (auth()->guard('lms_user')->check() and !auth()->guard('lms_user')->user()->isUser()) {
            return $next($request);
        }

        abort(404);
    }
}
