<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ExceptParamDomain
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
        \URL::defaults([
            'subdomain' => $request->route()->parameter('subdomain'),
            'domain' => $request->route()->parameter('domain'),
        ]);

        // Remove these params so they aren't passed to controllers.
        $request->route()->forgetParameter('subdomain');
        $request->route()->forgetParameter('domain');

        return $next($request);
    }
}
