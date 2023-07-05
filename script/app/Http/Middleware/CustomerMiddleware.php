<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Cache;
use App\Models\Requestdomain;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::guard('customer')->check()) {
             
            $url= Auth::guard('customer')->user()->user_domain->full_domain ?? '';
            $domain_id = Auth::guard('customer')->user()->user_domain->id;
            $customdomain = Requestdomain::where('domain_id',$domain_id)->where('status',1)->first();
            $customdomainfull = $customdomain->full_domain ?? '';
            if($url != url('/') && $customdomainfull != url('/')){
               
               Auth::guard('customer')->logout();
               return redirect()->route('login');
            }
           return $next($request);
        }else{
            if(Auth::check()){
                Auth::guard('customer')->logout();
                Auth::logout(); 
            }
            return redirect('user/login');
        } 
    }
}
