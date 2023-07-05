<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AffiliateLog;
use App\AffiliateWithdrawRequest;
use App\AffiliateUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    public function get_earning_history(Request $request)
    {
        try {
            $user = Auth::user();
            $earning_historys = AffiliateLog::where('user_id', domain_info('user_id'))->where('referred_by_user', $user->id)->latest()->paginate(10);
            return response()->json(['total_size' => $earning_historys->count(),'earning_historys' =>$earning_historys], 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_withdraw_request_history()
    {
        try {
            $user = Auth::user();
            $withdraw_request_historys = AffiliateWithdrawRequest::where('user_id', domain_info('user_id'))->where('customer_id', $user->id)->orderBy('id', 'desc')->latest()->paginate(10);
            return response()->json(['total_size' => $withdraw_request_historys->count(),'earning_historys' =>$withdraw_request_historys], 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function withdraw_request_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }
        $customer = Auth::user();
        $withdraw_request           = new AffiliateWithdrawRequest;
        $withdraw_request->customer_id  = $customer->id;
        $withdraw_request->amount   = $request->amount;
        $withdraw_request->status   = 0 ;
        $withdraw_request->user_id  = domain_info('user_id');
        if($withdraw_request->save()){

            $affiliate_user = AffiliateUser::where('user_id', domain_info('user_id'))->where('customer_id', $customer->id)->first();
            $affiliate_user->balance = $affiliate_user->balance - $request->amount;
            $affiliate_user->save();
            return response()->json(['message' =>' Withdraw Amount Successfully !', 'data' =>$affiliate_user], 200);
        }

        return response()->json(['message' =>'Please Withdraw Amount again !', 'data' =>$affiliate_user], 403);

    }

    public function affiliate_refferal_users()
    {
        try {
            $user = Auth::user();
            $refferal_users =$user->refferals()->latest()->paginate(10);
            return response()->json(['total_size' => $refferal_users->count(),'refferal_users' =>$refferal_users], 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
