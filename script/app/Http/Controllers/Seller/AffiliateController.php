<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\AffiliateStats;
use App\AffiliateConfig;
use App\AffiliateLog;
use App\AffiliateOption;
use App\AffiliatePayment;
use App\AffiliateUser;
use App\AffiliateWithdrawRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Models\Customer;

class AffiliateController extends Controller
{
    public function index(){
        $user_registration = AffiliateOption::where('type', 'user_registration')->where('user_id', auth()->id())->first();
        $first_purchase = AffiliateOption::where('type', 'user_registration_first_purchase')->where('user_id', auth()->id())->first();
        $next_time_purchase = AffiliateOption::where('type', 'user_registration_next_time_purchase')->where('user_id', auth()->id())->first();
        $product_sharing = AffiliateOption::where('type', 'product_sharing')->where('user_id', auth()->id())->first();
        $total_level = AffiliateConfig::where('type', 'total_level')->where('user_id', auth()->id())->first();
        $levels = AffiliateOption::where('type', 'like' ,'level%')->where('user_id', auth()->id())->orderBy('type','asc')->get();
        return view('seller.affiliate.index', compact('user_registration','first_purchase','next_time_purchase','product_sharing','total_level','levels'));
    }

    public function affiliate_option_store(Request $request){
        $affiliate_option = AffiliateOption::where('type', $request->type)->where('user_id', auth()->id())->first();
        if($affiliate_option == null){
            $affiliate_option = new AffiliateOption;
            $affiliate_option->user_id = auth()->id();
        }
        $affiliate_option->type = $request->type;
        $affiliate_option->percentage = $request->percentage ?? 0;
        $commision_details = array();
        if ($request->type == 'product_sharing') {
            $commision_details['commission'] = $request->amount;
            $commision_details['commission_type'] = $request->amount_type;
        }

        $affiliate_option->details = json_encode($commision_details);

        if ($request->has('status')) {
            $affiliate_option->status = 1;
        }
        else {
            $affiliate_option->status = 0;
        }
        $affiliate_option->save();

        return response()->json(['Affiliate Updated']);
    }

    public function config_store(Request $request){
        // if($request->type == 'validation_time') {
        //     //affiliate validation time
        //     $affiliate_config = AffiliateConfig::where('type', $request->type)->where('user_id', auth()->id())->first();
        //     if($affiliate_config == null){
        //         $affiliate_config = new AffiliateConfig;
        //         $affiliate_config->user_id = auth()->id();
        //     }
        //     $affiliate_config->type = $request->type;
        //     $affiliate_config->value = $request[$request->type];
        //     $affiliate_config->save();

        //     return response()->json(['Affiliate Updated']);
        // } 
        if($request->type == 'total_level') {
            //affiliate customer level
            $affiliate_config = AffiliateConfig::where('type', $request->type)->where('user_id', auth()->id())->first();
            if($affiliate_config == null){
                $affiliate_config = new AffiliateConfig;
                $affiliate_config->user_id = auth()->id();
            }
            $affiliate_config->type = $request->type;
            $affiliate_config->value = $request->total_level;
            $affiliate_config->save();
            $levels = AffiliateOption::where('type', 'like' ,'level%')->where('user_id', auth()->id())->orderBy('type','asc')->get();
            if($levels->count() > 0){
                foreach($levels as $level){
                    $level->delete();
                }
            }
            for($i = $request->total_level; $i > 0; $i--)
            {
                $affiliate_option = AffiliateOption::where('type', 'level_'.$i)->where('user_id', auth()->id())->first();
                if($affiliate_option == null){
                    $affiliate_option = new AffiliateOption;
                    $affiliate_option->user_id = auth()->id();
                }
                $affiliate_option->type = 'level_'.$i;
                $affiliate_option->percentage = $request->percentage['level_'.$i] ?? 0;
                $affiliate_option->save();
            }

            return response()->json(['Affiliate Updated']);
        } 
    }

    public function affiliate_logs_admin()
    {
        $affiliate_logs = AffiliateLog::where('user_id', auth()->id())->latest()->paginate(10);
        return view('seller.affiliate.affiliate_logs',compact('affiliate_logs'));
    }

    public function users(){
        $affiliate_users = AffiliateUser::where('user_id', auth()->id())->paginate(10);
        return view('seller.affiliate.affiliate_user', compact('affiliate_users'));
    }

    public function updateApproved(Request $request)
    {
        $affiliate_user = AffiliateUser::findOrFail($request->id);
        $affiliate_user->status = $request->status;
        $affiliate_user->save();

        return response()->json(['Update Approved Success']);
    }

    public function payment_modal(Request $request)
    {
        $affiliate_user = AffiliateUser::findOrFail($request->id);
        return view('seller.affiliate.payment_modal', compact('affiliate_user'));
    }

    public function payment_store(Request $request){
        $affiliate_payment = new AffiliatePayment;
        $affiliate_payment->user_id = auth()->id();
        $affiliate_payment->affiliate_user_id = $request->affiliate_user_id;
        $affiliate_payment->amount = $request->amount;
        $affiliate_payment->payment_method = $request->payment_method;
        $affiliate_payment->save();

        $affiliate_user = AffiliateUser::findOrFail($request->affiliate_user_id);
        $affiliate_user->balance -= $request->amount;
        $affiliate_user->save();

        return response()->json(['Payment Success']);
    }

    public function payment_history($id){
        $affiliate_user = AffiliateUser::findOrFail($id);
        $affiliate_payments = $affiliate_user->affiliate_payments();
        return view('seller.affiliate.payment_history', compact('affiliate_payments', 'affiliate_user'));
    }

    public function refferal_users()
    {
        $refferal_users = Customer::where('referred_by', '=' , null)->where('created_by', auth()->id())->paginate(10);
        return view('seller.affiliate.refferal_users', compact('refferal_users'));
    }

    public function affiliate_withdraw_requests()
    {
        $affiliate_withdraw_requests = AffiliateWithdrawRequest::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(10);
        return view('seller.affiliate.affiliate_withdraw_requests', compact('affiliate_withdraw_requests'));
    }

    public function affiliate_withdraw_modal(Request $request)
    {
        $affiliate_withdraw_request = AffiliateWithdrawRequest::findOrFail($request->id);
        $affiliate_user = AffiliateUser::where('customer_id',$affiliate_withdraw_request->customer_id)->first();
        return view('seller.affiliate.affiliate_withdraw_modal', compact('affiliate_withdraw_request','affiliate_user'));
    }

    public function withdraw_request_payment_store(Request $request){
        $affiliate_payment = new AffiliatePayment;
        $affiliate_payment->user_id = auth()->id();
        $affiliate_payment->affiliate_user_id = $request->affiliate_user_id;
        $affiliate_payment->amount = $request->amount;
        $affiliate_payment->payment_method = $request->payment_method;
        $affiliate_payment->save();

        if ($request->has('affiliate_withdraw_request_id')) {
            $affiliate_withdraw_request = AffiliateWithdrawRequest::findOrFail($request->affiliate_withdraw_request_id);
            $affiliate_withdraw_request->status = 1;
            $affiliate_withdraw_request->save();
        }

        return response()->json(['Payment Success']);
    }

    public function reject_withdraw_request($id)
    {
        $affiliate_withdraw_request = AffiliateWithdrawRequest::findOrFail($id);
        $affiliate_withdraw_request->status = 2;
        if($affiliate_withdraw_request->save()){

            $affiliate_user = AffiliateUser::where('customer_id', $affiliate_withdraw_request->customer_id)->first();
            $affiliate_user->balance = $affiliate_user->balance + $affiliate_withdraw_request->amount;
            $affiliate_user->save();

            return response()->json(['Reject Withdraw Request Success']);
        }
    }
}
