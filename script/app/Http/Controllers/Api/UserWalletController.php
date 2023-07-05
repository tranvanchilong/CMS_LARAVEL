<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Useroption;
use App\Models\WalletTransactions;
use Illuminate\Support\Facades\Validator;
use Auth;

class UserWalletController extends Controller
{
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }

        $wallet_status= Useroption::where('user_id',domain_info('user_id'))->where('key','wallet_status')->first();
        $wallet_status = !empty($wallet_status) ? $wallet_status->value : null;

        if($wallet_status == 1)
        {
            $user = Auth::user();
            $total_wallet_balance = $user->wallet_balance;
            $wallet_transactio_list = WalletTransactions::where('user_id',domain_info('user_id'))->where('customer_id', $user->id)
                                                    ->latest()
                                                    ->paginate($request['limit'], ['*'], 'page', $request['offset']);

            return response()->json([
                'limit'=>(integer)$request->limit,
                'offset'=>(integer)$request->offset,
                'total_wallet_balance'=>$total_wallet_balance,
                'total_wallet_transaction'=>$wallet_transactio_list->total(),
                'wallet_transaction_list'=>$wallet_transactio_list->items()
            ],200);

        }else{

            return response()->json(['message' => 'Access Denied!'], 422);
        }
    }

    public function addMoney(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'transaction' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }

        if(!$request->amount || $request->amount <= 0 || !$request->transaction){
            return response()->json(['error'=>'Deposit Failed'], 401);
        }

        $check = WalletTransactions::where('blockchain_transaction', $request->transaction)->first();
        if($check){
            return response()->json(['message'=>'Deposit successfully'], 200);
        }
        $customer = Auth::user();

        $amount = $request->amount;
        $balance = $customer->wallet_balance + $amount;
        WalletTransactions::create([
            'transaction_type' => 'Add Money',
            'customer_id' => $customer->id,
            'user_id' => domain_info('user_id'),
            'amount' => $amount,
            'status' => 0,
            'blockchain_transaction' => $request->transaction
        ]);

       $customer->wallet_balance = $balance;
       $customer->save();

        return response()->json(['message' => 'Add Money successfully !'], 200);
    }
}
