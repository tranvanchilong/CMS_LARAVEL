<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Hash;
use Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Mail\Sendotp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ForgotPassword extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * password broker for admin guard.
     * 
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(){
    	return Password::broker('customers');
    }

    /**
     * Get the guard to be used during authentication
     * after password reset.
     * 
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard(){
    	return Auth::guard('customers');
    }

    // public function __invoke(Request $request)
    // {
    //     $this->validateEmail($request);
    //     // We will send the password reset link to this user. Once we have attempted
    //     // to send the link, we will examine the response then see the message we
    //     // need to show to the user. Finally, we'll send out a proper response.
    //     $response = $this->broker()->sendResetLink(
    //         $request->only('email')
    //     );
    //     // return ['received' => true];
    //     return $response == Password::RESET_LINK_SENT
    //         ? response()->json(['message' => 'Reset link sent to your email.', 'success' => true], 201)
    //         : response()->json(['message' => 'Unable to send reset link', 'success' => false], 401);
    // }

    public function sendResetOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }
        $id = $request['email'];
        $customer=Customer::where('email', 'like', "%{$id}%")->first();

        if(empty($customer)){
            return response()->json()(['error','We can\'t find a user with that email address.'], 401);
        }

        $userInfo=rand(2000,1000000);
        $customer->token = $userInfo;
        $customer->update();
       
        $data = [
            'name' => $customer->name,
            'email' => $customer->email,
            'otp' => $userInfo
        ];
        Mail::to($customer->email)->send(new Sendotp($data));

        return response()->json(['message' => 'We sent an otp code on your mail.', 'success' => true], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'message'=>$validator->errors()], 401);            
        }

        $id = $request['email'];
        $data = Customer::where('email', 'like', "%{$id}%")->first();

        if($request->otp == $data['token']){
        	
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);
    
            if ($validator->fails()) { 
                return response()->json(['status'=> false, 'message'=>$validator->errors()], 401);            
            }
        	$user=Customer::findorFail($data['id']);
        	$user->password=Hash::make($request->password);
        	$user->save();

        	return response()->json(['status'=>true, 'message' => 'Password changed successfully.'], 200);
        	
        }
        return response()->json(['status' => false, 'message' => 'Invalid token.'], 400);
        
    }
}
