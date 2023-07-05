<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Domain;
use App\Models\Requestdomain;
use Auth;

class LoginController extends Controller
{
   	public function __construct()
    {
       if(env('MULTILEVEL_CUSTOMER_REGISTER') != true || url('/') == env('APP_URL')){
        abort(404);
       }


    }
   	use ThrottlesLogins;

   	/**
    * Max login attempts allowed.
    */
   	public $maxAttempts = 5;

   /**
   * Number of minutes to lock the login.
   */
   public $decayMinutes = 3;

    /**
     * Login the admin.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|max:100|email',
            'password' => 'required',
        ]);

    	//check if the user has too many login attempts.
    	if ($this->hasTooManyLoginAttempts($request)){
        //Fire the lockout event.
    		$this->fireLockoutEvent($request);

        //redirect the user back after lockout.
    		return $this->sendLockoutResponse($request);
    	}
      $host=$request->getHost();
      $domain = Domain::where('domain', $host)->first();
      $custom_domain = Requestdomain::where('domain', $host)->first();
      
    	if(Auth::guard('customer')->attempt(['status'=>1,'email' => $request->email, 'password' => $request->password, 'created_by' => $domain->user_id ?? $custom_domain->user_id],$request->filled('remember'))){
        if(Auth::guard('customer')->user()->email_verified_at == null || Auth::guard('customer')->user()->email_verified_at == null) {
          Auth::guard('customer')->logout();

          return back()->with('error', __('Your Email is not Verified!'));
        }
        //Authentication passed...
    		return redirect()
    		->intended(url('/user/dashboard'))
    		->with('status','You are Logged in as Admin!');
    	}

    	//keep track of login attempts from the user.
    	$this->incrementLoginAttempts($request);

       //Authentication failed...
    	return $this->loginFailed();
    }

     /**
     * Username used in ThrottlesLogins trait
     * 
     * @return string
     */
    public function username(){
        return 'email';
    }

    /**
     * Logout the admin.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
    	Auth::guard('customer')->logout();
    	return redirect('/');
    }

    /**
     * Validate the form data.
     * 
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    private function validator(Request $request)
    {
      //validate the form...
    }

    /**
     * Redirect back after a failed login.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {

      return redirect()
        ->back()
        ->withInput()
        ->with('error','Login failed, please try again!');
    }
}
