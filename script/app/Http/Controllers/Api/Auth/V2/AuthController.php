<?php

namespace App\Http\Controllers\Api\Auth\V2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Domain;
use App\Option;
use App\Models\Userplan;
use Carbon\Carbon;
use App\Models\Customer;
use Hash;
use App\Helpers\Helper;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'password' => 'required|min:8',
            'gender' => 'required',
            'phone' => 'required|max:20'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helper::error_processor($validator)], 403);
        }

        $user_id = domain_info('user_id');

        $checkMail = Customer::where([['created_by',$user_id],['email',$request->email]])->first();
     
        $checkPhone = Customer::where([['created_by',$user_id],['phone',$request->phone]])->first();
       
        
        if(!empty($checkPhone)){
            if($request->phone){
                $tokenResult = $checkPhone->createToken('Personal Access Token');
                return $this->loginSuccess($tokenResult, $checkPhone, 'Opps the phone address already exists...!!');
            }
            
        }elseif(!empty($checkMail)){
            $tokenResult = $checkMail->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $checkMail, 'Opps the email address already exists...!!');
        }

	   	$user= new Customer();
        $domain = Domain::where('user_id', $user_id)->first();
	   	$user->email=$request->email;
	   	$user->name=$request->name;
	   	$user->gender=$request->gender;
	   	$user->password=Hash::make($request->password);
        $user->phone = $request->phone;
	   	$user->domain_id=$domain->id;
	   	$user->created_by=$user_id;
	   	$user->save();
        Auth::guard('customer')->loginUsingId($user->id);
        $output = [
            'status' => 'success',
            'customer' => $user
        ];

        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return response()->json(['data' => $output,'token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:20',
            'password' => 'string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }
        $user = Customer::where('phone', $request->phone)->where('status', 1)->where('created_by', domain_info('user_id'))->first();
        if ($user) {
            $tokenResult = $user->createToken('Personal Access Token');
            if($request->password){
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return $this->loginSuccess($tokenResult, $user, 'Login Successfully');
                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }
            }
            return $this->loginSuccess($tokenResult, $user, 'Login Successfully');
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }

    }

    protected function loginSuccess($tokenResult, $user, $message)
    {
        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
            'customer' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_by' => $user->created_by,
                'domain_id' => $user->domain_id
            ],
            'message' => $message
        ]);
    }
}
