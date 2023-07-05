<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class SocialAuthController extends Controller
{
    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'token' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }

        $client = new Client();
        $token = $request['token'];
        $email = $request['email'];
        try {
            $res = $client->request('GET', 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token);
            $data = json_decode($res->getBody()->getContents(), true);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'wrong credential.']);
        }

        $user = Customer::where('email', $email)->where('created_by', domain_info('user_id'))->first();
        if (isset($user) == false) {
            $user = new Customer;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['id']);
            $user->domain_id = domain_info('domain_id');
            $user->created_by= domain_info('user_id');
            $user->email_verified_at = now();
            $user->save();
        } 

        $token = self::login_process_passport($user, $user->email, $data['id']);
        if ($token != null) {
            return response()->json(['token' => $token]);
        }
        return response()->json(['error_message' => 'Customer not found or Account has been suspended']);
    }

    public static function login_process_passport($user, $email, $password)
    {
        $data = [
            'email' => $email,
            'password' => $password,
            'created_by' => domain_info('user_id')
        ];

        if (isset($user) && Auth::guard('customer')->attempt($data)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
        } else {
            $token = null;
        }

        return $token;
    }
}
