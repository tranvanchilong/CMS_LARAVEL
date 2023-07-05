<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Models\User;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     public function redirectTo()
    {

        if (Auth::user()->role_id==1) {
           if (url('/') != env('APP_URL')) {
            Auth::logout();
            $this->redirectTo=env('APP_URL').'/login';
            return $this->redirectTo;
           }else{
            $this->redirectTo=env('APP_URL').'/admin/dashboard';
            return $this->redirectTo;
           } 
           
        }
        elseif (Auth::user()->role_id==2) {
           $url= Auth::user()->user_domain->full_domain;
            // dd(str_replace('www.','',url('/'));
           if (str_replace('www.','',url('/')) != $url) {
             Auth::logout();
             return $this->redirectTo=$url.'/user/login';
           }
           else{
           return  $this->redirectTo=$url.'/user/dashboard';
           }
          
          
       }
       elseif (Auth::user()->role_id==3) {
        
          
           
       }
       $this->middleware('guest')->except('logout');
   }
}
