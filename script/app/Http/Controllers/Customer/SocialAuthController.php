<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use Auth;
use App\Domain;
use App\Models\Requestdomain;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
      return Socialite::driver('google')->with(["prompt" => "select_account"])->redirect();
    }
    
    public function handleProviderCallback(Request $request)
    {
      try{
        $user_data = Socialite::driver('google')->user();
      }catch (\Exception $e) {
        return redirect('/user/login');
      }
      
      $host=$request->getHost();
      $domain = Domain::where('domain', $host)->first();
      $custom_domain = Requestdomain::where('domain', $host)->first();

      $exitUser = Customer::where('email', $user_data->getEmail())->where('created_by', $domain->user_id ?? $custom_domain->user_id)->first();
      if($exitUser) {
        Auth::guard('customer')->login($exitUser, true);
      }else {
        $newUser = new Customer;
        $newUser->name = $user_data->getName();
        $newUser->email = $user_data->getEmail();
        $newUser->password = bcrypt($user_data->id);
        $newUser->domain_id = $domain->id ?? $custom_domain->domain_id;
        $newUser->created_by= $domain->user_id ?? $custom_domain->user_id;
        $newUser->email_verified_at = now();
        $newUser->save();

        Auth::guard('customer')->login($newUser, true);
      }
      if(session('link') != null){
        return redirect(session('link'));
      }
      else{
        return redirect('/user/dashboard');
      }
    }
}
