<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Auth;
use Cache;
use App\Models\Requestdomain;
use Session;

class SellerMiddleware
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
        if (Auth::check() && Auth::User()->role_id == 3) {
         if (Auth::user()->status==3) {
           return redirect(env('APP_URL').'/merchant/dashboard');
          }
          if (Auth::user()->status === 0 || Auth::user()->status == 2) {
            return redirect(env('APP_URL').'/suspended');
          }
          if (url('/') == env('APP_URL') && Auth::user()->status == 1) {
           Auth::logout();
           return redirect()->route('login');
          }
          $host=$request->getHost();
          $customdomain = Requestdomain::where('domain',$host)->where('status',1)->first();
          $customdomainfull = $customdomain->full_domain ?? env('APP_APP_URL');

          $subdomain = Auth::user()->user_domain->full_domain ?? env('APP_APP_URL');

          $url= $customdomainfull ?? $subdomain;

          if($url != str_replace('www.', '', url('/'))){
            Auth::logout();
            return redirect()->route('login');
          }
          
          if(Auth::user()->email_verified == 0 && !$request->is('seller/email')){
            Session::flash('warning', __('Your email have not verified yet !!!'));
            return redirect('/seller/email');
          }
          if(Auth::user()->email_verified == 1 && !Auth::User()->user_domain->template_id && !$request->is('seller/setting/template*') && !$request->is('seller/setting/theme*')){
            Session::flash('warning', __('Warning Config Template'));
            return redirect('/seller/setting/template');
          }

          return $next($request);
        }else{
            return redirect()->route('login');
        }
    }
}
