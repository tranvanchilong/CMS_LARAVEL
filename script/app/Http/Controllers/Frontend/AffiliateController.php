<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\AffiliateStats;
use App\AffiliateConfig;
use App\AffiliateLog;
use App\AffiliateOption;
use App\AffiliatePayment;
use App\AffiliateUser;
use App\AffiliateWithdrawRequest;
use App\Order;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Auth;
use DB;

class AffiliateController extends Controller
{
    public function payment_settings(){
        $customer_id = Auth::guard('customer')->user()->id;
        $affiliate_customer=AffiliateUser::where('customer_id',$customer_id)->where('user_id',domain_info('user_id'))->first();
        return view(base_view().'.account.affiliate_payment_settings', compact('affiliate_customer'));
    }

    public function payment_settings_store(Request $request){
        $customer_id = Auth::guard('customer')->user()->id;
        $affiliate_customer = AffiliateUser::where('customer_id',$customer_id)->where('user_id',domain_info('user_id'))->first();
        $affiliate_customer->paypal_email = $request->paypal_email;
        $affiliate_customer->bank_information = $request->bank_information;
        $affiliate_customer->save();
        return back()->with('success', __('Affiliate payment settings has been updated successfully'));

    }

    public function processAffiliatePoints(Order $order){
        if(feature_is_activated('affiliate_status', domain_info('user_id'))){
            $levels = AffiliateOption::where('user_id', domain_info('user_id'))->where('type','like', '%'.'level'.'%')->orderBy('type','asc')->get();
            if ($order->customer != null && $order->customer->orders->count() == 0) {
                if($order->customer->id != null){
                    $user = Customer::find($order->customer->referred_by);
                    $customer_parent = $order->customer;
                    if ($user != null) {
                        $amount = AffiliateOption::where('user_id', domain_info('user_id'))->where('type', 'user_registration_first_purchase')->first();

                        if($amount){
                            $amount = $amount->percentage * ($order->total)/100;
                        }else{
                            $amount = 10 * ($order->total)/100;
                        }
                        // Affiliate log
                        $affiliate_log                      = new AffiliateLog;
                        $affiliate_log->user_id             = domain_info('user_id');
                        $affiliate_log->customer_id         = $order->customer_id;
                        $affiliate_log->referred_by_user    = $order->customer->referred_by;
                        $affiliate_log->amount              = $amount;
                        $affiliate_log->order_id            = $order->id;
                        $affiliate_log->affiliate_type      = 'user_registration_first_purchase';
                        $affiliate_log->save();
                        //Commision Referal User
                        if(count($levels) == 0){
                            $affiliate_user = $user->affiliate_user;
                            if($affiliate_user != null){
                                $affiliate_user->balance += $amount;
                                $affiliate_user->save();
                            }
                            
                        } else {
                            foreach($levels as $key=>$level){
                                $customer_parent = $customer_parent->refferal;
                               
                                if($customer_parent){
                                    $customer_parent_affiliate_user = $customer_parent->affiliate_user;
                                    $customer_parent_affiliate_user->balance += $amount * ($level->percentage)/100;
                                    $customer_parent_affiliate_user->save();
                                } else {
                                    break;
                                }   
                            }
                        }
                        
                    }
                } 
            } else {
                if($order->customer->id != null){
                    $user = Customer::find($order->customer->referred_by);
                    $customer_parent = $order->customer;
                    if ($user != null) {
                        $amount = AffiliateOption::where('user_id', domain_info('user_id'))->where('type', 'user_registration_next_time_purchase')->first();
                        if($amount){
                            $amount = $amount->percentage * ($order->total)/100;
                        }else{
                            $amount = 5 * ($order->total)/100;
                        }
                        // Affiliate log
                        $affiliate_log                      = new AffiliateLog;
                        $affiliate_log->user_id             = domain_info('user_id');
                        $affiliate_log->customer_id         = $order->customer_id;
                        $affiliate_log->referred_by_user    = $order->customer->referred_by;
                        $affiliate_log->amount              = $amount;
                        $affiliate_log->order_id            = $order->id;
                        $affiliate_log->affiliate_type      = 'user_registration_next_time_purchase';
                        $affiliate_log->save();
                        //Commision Referal User
                        if(count($levels) == 0){
                            $affiliate_user = $user->affiliate_user;
                            if($affiliate_user != null){
                                $affiliate_user->balance += $amount;
                                $affiliate_user->save();
                            } 
                        } else {
                            foreach($levels as $key=>$level){
                                $customer_parent = $customer_parent->refferal;
                               
                                if($customer_parent){
                                    $customer_parent_affiliate_user = $customer_parent->affiliate_user;
                                    $customer_parent_affiliate_user->balance += $amount * ($level->percentage)/100;
                                    $customer_parent_affiliate_user->save();
                                } else {
                                    break;
                                }   
                            }
                        }
                    }
                }
                foreach ($order->order_item as $key => $orderDetail) {
                    $amount = 0;
                    if($orderDetail->product_referral_code != null){
                        $referred_by_user = Customer::where('created_by', domain_info('user_id'))->where('referral_code', $orderDetail->product_referral_code)->first();
                        $customer_parent = $order->customer;
                        if($referred_by_user != null) {
                            if(AffiliateOption::where('user_id', domain_info('user_id'))->where('type', 'product_sharing')->first()->details ?? '' != null && json_decode(AffiliateOption::where('type', 'product_sharing')->first()->details)->commission_type == 'amount'){
                                $amount = json_decode(AffiliateOption::where('type', 'product_sharing')->first()->details)->commission;
                            }
                            elseif(AffiliateOption::where('user_id', domain_info('user_id'))->where('type', 'product_sharing')->first()->details ?? '' != null && json_decode(AffiliateOption::where('type', 'product_sharing')->first()->details)->commission_type == 'percent') {
                                $amount = (json_decode(AffiliateOption::where('type', 'product_sharing')->first()->details)->commission * $orderDetail->amount)/100;
                            }
                            // Affiliate log
                            $affiliate_log                      = new AffiliateLog;
                            $affiliate_log->user_id             = domain_info('user_id');
                            if($order->user_id != null){
                                $affiliate_log->customer_id     = $order->customer_id;
                            }
                            else{
                                $affiliate_log->customer_id     = null;
                            }
                            $affiliate_log->referred_by_user    = $referred_by_user->id;
                            $affiliate_log->amount              = $amount;
                            $affiliate_log->order_id            = $order->id;
                            $affiliate_log->affiliate_type      = 'product_sharing';
                            $affiliate_log->save();
                            //Commision Referal User
                            if(count($levels) == 0){
                                $affiliate_user = $user->affiliate_user;
                                if($affiliate_user != null){
                                    $affiliate_user->balance += $amount;
                                    $affiliate_user->save();
                                }
                            } else {
                                foreach($levels as $key=>$level){
                                    $customer_parent = $customer_parent->refferal;
                                
                                    if($customer_parent){
                                        $customer_parent_affiliate_user = $customer_parent->affiliate_user;
                                        $customer_parent_affiliate_user->balance += $amount * ($level->percentage)/100;
                                        $customer_parent_affiliate_user->save();
                                    } else {
                                        break;
                                    }   
                                }
                            }                       
                        }
                    }
                }
            }
            
        }
        
    }
    /**
     * This function updates the affiliate statistics for a given user with the number of clicks, items
     * ordered, items delivered, and items cancelled.
     * 
     * @param affiliate_user_id The ID of the affiliate user for whom the stats are being processed.
     * @param no_click The number of clicks made by the affiliate user.
     * @param no_item The number of items ordered by customers through the affiliate's referral link.
     * @param no_delivered The number of items that have been delivered to customers through the
     * affiliate's referral link.
     * @param no_cancel The parameter "no_cancel" represents the number of orders that were cancelled
     * by customers who clicked on the affiliate link. This function updates the affiliate stats for
     * the given affiliate user ID with the provided values for clicks, items ordered, items delivered,
     * and cancelled orders. If there is no existing record for the
     */
    public function processAffiliateStats($affiliate_user_id, $user_id, $no_click = 0, $no_item = 0, $no_delivered = 0, $no_cancel = 0)
    {

        $affiliate_stats = AffiliateStats::whereDate('created_at', Carbon::today())->where('affiliate_user_id', $affiliate_user_id)
            ->where('user_id', $user_id)
            ->first();
        if (!$affiliate_stats) {
            $affiliate_stats = new AffiliateStats;
            $affiliate_stats->no_of_order_item = 0;
            $affiliate_stats->no_of_delivered = 0;
            $affiliate_stats->no_of_cancel = 0;
            $affiliate_stats->no_of_clicks = 0;
        }

        $affiliate_stats->no_of_order_item += $no_item;
        $affiliate_stats->no_of_delivered += $no_delivered;
        $affiliate_stats->no_of_cancel += $no_cancel;
        $affiliate_stats->no_of_clicks += $no_click;
        $affiliate_stats->affiliate_user_id = $affiliate_user_id;
        $affiliate_stats->user_id = $user_id;

        $affiliate_stats->save();
    }

    // Affiliate Withdraw Request
    public function withdraw_request_store(Request $request)
    {
        $withdraw_request           = new AffiliateWithdrawRequest;
        $withdraw_request->customer_id  = Auth::guard('customer')->user()->id;
        $withdraw_request->amount   = $request->amount;
        $withdraw_request->status   = 0 ;
        $withdraw_request->user_id  = domain_info('user_id');

        if($withdraw_request->save()){

            $affiliate_user = AffiliateUser::where('customer_id',Auth::guard('customer')->user()->id)->first();
            $affiliate_user->balance = $affiliate_user->balance - $request->amount;
            $affiliate_user->save();

            return redirect('user/affiliate/withdraw_request_history')->with('success', __('Affiliate Withdraw Request Successfully'));
        }
        else{
            // flash(translate('Something went wrong'))->error();
            // return back();
        }
    }

    public function affiliate_system()
    {
        SEOTools::setTitle("Affiliate");
        $user = Auth::guard('customer')->user();
        if ($user->referral_code == null) {
            $user->referral_code = substr($user->id.Str::random(), 0, 10);
            $user->save();
        }
        $referral_code = $user->referral_code;

        $query = AffiliateStats::query();
        $query = $query->select(DB::raw('SUM(no_of_clicks) AS count_click, SUM(no_of_order_item) AS count_item, SUM(no_of_delivered) AS count_delivered,  SUM(no_of_cancel) AS count_cancel'));
        $query->where('affiliate_user_id', $user->id)->where('user_id', domain_info('user_id'));

        $affliate_stats = $query->first();

        $affiliate_logs = AffiliateLog::where('user_id', domain_info('user_id'))->where('referred_by_user', $user->id)->paginate(20);
        $affiliate_user = $user->affiliate_user;
        
        return view(base_view().'.account.affiliate_system',compact('referral_code','affliate_stats','affiliate_logs','affiliate_user'));
    }

    public function affiliate_payment_history()
    {
        SEOTools::setTitle("Affiliate");
        $affiliate_user = Auth::guard('customer')->user()->affiliate_user;
        $affiliate_payments = $affiliate_user->affiliate_payments();

        return view(base_view().'.account.affiliate_payment_history', compact('affiliate_payments','affiliate_user'));
    }

    public function affiliate_withdraw_request_history()
    {
        SEOTools::setTitle("Affiliate");
        $affiliate_user = Auth::guard('customer')->user()->affiliate_user;
        $affiliate_withdraw_requests = AffiliateWithdrawRequest::where('user_id', domain_info('user_id'))->where('customer_id', Auth::guard('customer')->user()->id)->orderBy('id', 'desc')->paginate(10);

        return view(base_view().'.account.affiliate_withdraw_request_history',compact('affiliate_withdraw_requests','affiliate_user'));
    }

    public function affiliate_refferal_users()
    {
        SEOTools::setTitle("Affiliate");
        $affiliate_user = Auth::guard('customer')->user()->affiliate_user;
        $refferal_users = Auth::guard('customer')->user()->refferals()->latest()->paginate(10);
        return view(base_view().'.account.affiliate_refferal_users',compact('affiliate_user','refferal_users'));
    }
}
