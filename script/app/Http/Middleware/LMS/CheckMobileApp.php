<?php

namespace App\Http\Middleware\LMS;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMobileApp
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->getPathInfo() != '/lms/mobile-app' and !request()->is('laravel-filemanager*')) {
            if (!empty(getFeaturesSettings('mobile_app_status')) and getFeaturesSettings('mobile_app_status')) {
                return redirect('/lms/mobile-app');
            }
        }

        return $next($request);
    }
}
