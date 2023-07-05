<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use App\Models\Requestdomain;
use Illuminate\Support\Facades\Auth;
use Cache;
use Carbon\Carbon;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
         if($guard == "customer"){
            if (url('/') == env('APP_URL')) {
               Auth::logout();
               Auth::guard('customer')->logout();
            }
            $url= Auth::guard('customer')->user()->user_domain->full_domain ?? '';

            return redirect($url.'/user/dashboard');
         }  

         if (Auth::guard($guard)->check() && Auth::User()->role_id == 1) {
             return redirect(env('APP_URL').'/admin/dashboard');
         }
        elseif (Auth::guard($guard)->check() && Auth::User()->role_id == 2) {
           
           $url=  Auth::user()->user_domain->full_domain ?? env('APP_URL');
           
           return redirect($url.'/user/dashboard');
        }
        elseif(Auth::guard($guard)->check() && Auth::User()->role_id == 3)
        {
            

            if (Auth::user()->status==3) {
                $redirectTo=env('APP_URL').'/merchant/dashboard';
                return redirect($redirectTo);
            }
            elseif (Auth::user()->status === 0 || Auth::user()->status == 2) {
                $redirectTo=env('APP_URL').'/suspended';
                return redirect($redirectTo);
            }
            else{
                
                $host=$request->getHost();
                
                $custom_domain = Requestdomain::where('domain', $host)->first();
                $customdomainfull = $custom_domain->full_domain ?? env('APP_APP_URL');
                $subdomain = Auth::user()->user_domain->full_domain ?? env('APP_APP_URL');
                $url = $customdomainfull ?? $subdomain;
                
                if (str_replace('www.', '', url('/')) != $url) {
                    Auth::logout();
                    return redirect($url.'/login');
                }

                Auth::user()->last_login = Carbon::now()->toDateTimeString();
                Auth::user()->save();
               return redirect($url.'/seller/dashboard');
            }
           
            
        }
      }

    return $next($request);
    }
}
