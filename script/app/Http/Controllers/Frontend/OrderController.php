<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Userorder;
use App\Order;
use App\Orderitem;
use App\Useroption;
use App\Ordermeta;
use App\Ordershipping;
use App\Category;
use App\Trasection;
use Cart;
use Hash;
use Session;
use App\Mail\SellerOrderMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Order\Paypal;
use App\Helper\Order\Instamojo;
use App\Helper\Order\Toyyibpay;
use App\Helper\Order\Stripe;
use App\Helper\Order\Mollie;
use App\Helper\Order\Paystack;
use App\Helper\Order\Mercado;
use Cache;
use App\Models\Userplanmeta;
use Str;
use DB;
use App\Getway;
use App\Models\WalletTransactions;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Frontend\AffiliateController;

class OrderController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Cart::count() == 0){
            return back();
        }

        $validated = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|max:20',
        ]);
                // if($request->location){
        //     $validated = $request->validate([
        //         'shipping_mode' => 'required',
        //     ]);
        // }

        if($request->required_location){
            $validated = $request->validate([
                'location' => 'required',
            ]);
        }
        $shop_type=domain_info('shop_type');
        $domain_id=domain_info('domain_id');
        $user_id=domain_info('user_id');
        if($shop_type == 1){
            $validated = $request->validate([
                // 'location' => 'required',
                // 'shipping_mode' => 'required',
                'delivery_address' => 'required|max:100',
                'zip_code' => 'required|max:50',

            ]);
            if($request->required_shipping_mode){
                $validated = $request->validate([
                    'shipping_mode' => 'required',
                ]);
            }
        }
        if($request->create_account == 1){
            $validated = $request->validate([
                'email' => 'required|email|max:100',
                'password' => 'required|min:8',
            ]);
            $check_is_exist=Customer::where('email',$request->email)->where('created_by',domain_info('user_id'))->first();
            if (!empty($check_is_exist)) {
                Session::flash('user_limit','Opps email address already exists');

                return back();
            }

            $user_limit=domain_info('customer_limit',0);

            $total_customers=Customer::where('created_by',$user_id)->count();

            if($user_limit <= $total_customers){
                Session::flash('user_limit','Opps something wrong with registration but you can make order');
                Session::put('registration',false);
                return back();
            }
            else{
                Session::forget('registration');
            }

            $user= new Customer();
            $user->email=$request->email;
            $user->name=$request->name;
            $user->phone=$request->phone;
            $user->password=Hash::make($request->password);
            $user->domain_id=$domain_id;
            $user->created_by=$user_id;
            $user->save();
            Auth::guard('customer')->loginUsingId($user->id);
        }

        $prefix=Useroption::where('user_id',$user_id)->where('key','order_prefix')->first();
        $max_id=Order::max('id');
        if (empty($prefix)) {
            $prefix=$max_id+1;
        }
        else{
            $prefix=$prefix->value.$max_id;
        }

        $shipping_amount=Category::where('user_id',$user_id)->where('type','method')->find($request->shipping_mode);
        if ($request->payment_method == 2) {
            $payment_id=Str::random(10);
        }
        else{
            $payment_id=null;
        }

        DB::beginTransaction();
        try {


            $order=new Order;
            $order->order_no=$prefix;
            if(Auth::guard('customer')->check()){
                $order->customer_id=Auth::guard('customer')->user()->id;
            }

            $order->user_id  =$user_id;
            $order->order_type  =$shop_type;
            $order->payment_status=2;
            $order->status='pending';
            $order->transaction_id =$payment_id;
            $order->category_id =$request->payment_method;
            $order->payment_status=2;
            $order->tax=Cart::tax();
            $order->shipping=$this->calculateWeight(Cart::weight(),$shipping_amount->slug ?? 0);
            $order->total=$this->calculateShipping(Cart::total(),$shipping_amount->slug ?? 0,Cart::weight());
            $order->save();

            $info['name']=$request->name;
            $info['email']=$request->email;
            $info['phone']=$request->phone;
            $info['comment']=$request->comment;
            $info['address']=$request->delivery_address;
            $info['zip_code']=$request->zip_code;
            $info['coupon_discount']=Cart::discount();
            $info['sub_total']=Cart::subtotal();

            $meta=new Ordermeta;
            $meta->order_id=$order->id;
            $meta->key='content';
            $meta->value=json_encode($info);
            $meta->save();

            $items=[];

            foreach (Cart::content() as $key => $row) {
                $options['attribute']= $row->options->attribute;
                $options['options']= $row->options->options;

                $data['order_id']=$order->id;
                $data['term_id']=$row->id;
                $data['info']=json_encode($options);
                $data['qty']=$row->qty;
                $data['amount']=$row->price;
                $data['product_referral_code']=$row->options->product_referral_code;
                array_push($items, $data);
            }

            Orderitem::insert($items);
            if(feature_is_activated('affiliate_status', $user_id)){
                foreach($order->order_item as $order_qty){
                    if($order_qty->product_referral_code){
                        $referred_by_customer = Customer::where('created_by',$user_id)->where('referral_code', $order_qty->product_referral_code)->first();
                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_customer->id, $user_id, 0, $order_qty->qty, 0, 0);
                    }
                }
            }

            if($request->location && $request->shipping_mode){
                $ship['order_id']=$order->id;
                $ship['location_id']=$request->location;
                $ship['shipping_id']=$request->shipping_mode;
                Ordershipping::insert($ship);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        Session::put('order_no',$order->order_no);
        if($request->payment_method != 2){
            $payment_data['ref_id']=$order->id;
            $payment_data['getway_id']=$request->payment_method;
            $payment_data['amount']=$order->total;
            $payment_data['email']=$request->email;
            $payment_data['name']=$request->name;
            $payment_data['phone']=$request->phone;
            $payment_data['billName']='Order No :'.$order->order_no;
            Session::put('customer_order_info',$payment_data);
            Session::put('order_info',$payment_data);

            if($request->payment_method == 5){
                try{
                    return Paypal::make_payment($payment_data);
                }
                catch(Exception $e){
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }

            }
            if($request->payment_method == 3){
                try{
                    return Instamojo::make_payment($payment_data);
                }

                catch(Exception $e){
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }
            }
            if($request->payment_method == 7){
                try{
                    return Toyyibpay::make_payment($payment_data);
                }
                catch(Exception $e){
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }
            }
            if($request->payment_method == 8){
                try{
                    return Mollie::make_payment($payment_data);
                }
                catch(Exception $e){
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }

            }
            if($request->payment_method == 6){
                Session::put('stripe_payment',true);
                return redirect('/payment-with-stripe');
            }
            if($request->payment_method == 4){
                Session::put('razorpay_payment',true);
                return redirect('/payment-with-razorpay');
            }
            if($request->payment_method == 9){
                Session::put('paystack_payment',true);
                return redirect('/payment-with-paystack');
            }
            if($request->payment_method == 10){
                try{
                    return Mercado::make_payment($payment_data);
                }
                catch(Exception $e){
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }
            }
            //VNPay
            if($request->payment_method == 309){
                if(domain_info('user_id')){
                    $vnpay=Getway::with('method')->where('user_id',domain_info('user_id'))->where('category_id',309)->first();
                    $vnpay=json_decode($vnpay->content ?? '');
                }

                $data = [
                    'vnp_TmnCode' => $vnpay->vnpTmnCode,
                    'vnp_HashSecret' => $vnpay->vnpHashSecret,
                    'testMode' => $vnpay->env == 'production' ? false : true,
                    'vnp_TxnRef' => time(),
                    'vnp_OrderType' => 200000,
                    'vnp_OrderInfo' => $order->id,
                    'vnp_Amount' => intval(Cart::subtotal())*100,
                    'vnp_ReturnUrl' => 'https://'.domain_info('domain_name').'/payment/payment-success',
                ];

                $redirectUrl = $this->VNPAY_PAYMENT($data);

                if ($redirectUrl) {
                    return redirect($redirectUrl);
                    // TODO: chuyển khách sang trang VNPay để thanh toán
                }
                else{
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }
            }
            //MoMo
            if($request->payment_method == 311){
                if(domain_info('user_id')){
                    $momo=Getway::with('method')->where('user_id',domain_info('user_id'))->where('category_id',311)->first();
                    $momo=json_decode($momo->content ?? '');
                }
                \Config::set('laravel-omnipay.gateways.MoMoAIO.options.accessKey',$momo->accessKey);
                \Config::set('laravel-omnipay.gateways.MoMoAIO.options.secretKey',$momo->secretKey);
                \Config::set('laravel-omnipay.gateways.MoMoAIO.options.partnerCode',$momo->partnerCode);
                \Config::set('laravel-omnipay.gateways.MoMoAIO.options.testMode',$momo->env == 'production' ? false : true);
                $response = \MoMoAIO::purchase([
                    'amount' => intval(Cart::subtotal()),
                    'returnUrl' => 'https://'.domain_info('domain_name').'/payment/payment-success',
                    'notifyUrl' => 'https://'.domain_info('domain_name'),
                    'orderId' => time(),
                    'requestId' => time(),
                ])->send();
                if ($response->isRedirect()) {
                    $redirectUrl = $response->getRedirectUrl();
                    return redirect($redirectUrl);
                    // TODO: chuyển khách sang trang MoMo để thanh toán
                }
                else{
                    Order::destroy($order->id);
                    return $this->payment_fail();
                }
            }
            //Wallet
            if($request->payment_method == 1145){
                $cartTotal = Cart::total();
                if(Auth::guard('customer')->check()){
                    $customer = Auth::guard('customer')->user();
                    if( $cartTotal > $customer->wallet_balance){
                        Order::destroy($order->id);
                        return back()->with('error', __('Inefficient balance in your wallet to pay for this order!'));
                    }
                    $balance = $customer->wallet_balance - $cartTotal;
                    WalletTransactions::create([
                        'transaction_type' => 'Order Place',
                        'target_id' => $order->id,
                        'customer_id' => $customer->id,
                        'user_id' => $user_id,
                        'amount' => '-'.$cartTotal,
                        'balance' => $balance,
                        'status' => 1
                    ]);
    
                    $customer->wallet_balance = $balance;
                    $customer->save();
                    Cart::destroy();
                    return redirect('/thanks');
                    
                }
                return redirect('/user/login')->with('error', __('Please login when paying with wallet !'));
            }

        }


        try{
            if(Cache::has(domain_info('user_id').'store_email')){
                $store_email=Cache::get(domain_info('user_id').'store_email');
            }
            else{

                $admin=User::findorFail($user_id);
                $store_email=$admin->email;
            }

            $mail_data['store_email']=$store_email;
            $mail_data['order_no']=$prefix;
            $mail_data['base_url']=url('/');
            $mail_data['site_name']=Cache::get(domain_info('user_id').'shop_name',env('APP_NAME'));
            $mail_data['order_url']= url('/seller/order',$order->id);

            if(env('QUEUE_MAIL') == 'on'){

                dispatch(new \App\Jobs\Ordernotification($mail_data));
            }
            else{

                Mail::to($store_email)->send(new SellerOrderMail($mail_data));
            }
        }
        catch(Exception $e){

        }

        Cart::destroy();

        if(Cache::has(domain_info('user_id').'order_receive_method')){
            $method=Cache::get(domain_info('user_id').'order_receive_method');

        }
        else{
            $method="email";
        }

        if($method == 'whatsapp'){
            if(Cache::has(domain_info('user_id').'whatsapp')){
                $whatsapp=json_decode(Cache::get(domain_info('user_id').'whatsapp'));
                $url="https://wa.me/+".$whatsapp->phone_number."?text=My Order No Is ".str_replace('#','',$order->order_no);
                return redirect($url);
            }

        }


        return redirect('/thanks');

    }

    public function payment_success(Request $request){
        //VNPay
        if(isset($request->vnp_ResponseCode)){
            $data= Session::get('order_info');
            if($request->vnp_ResponseCode == '00'){
                $order=Order::findorFail($data['ref_id']);
                $order->transaction_id = $request->vnp_TransactionNo;
                $order->category_id=$data['getway_id'];
                $order->payment_status = 1;
                $order->save();
                if ($order->payment_status == 1 && $order->commission_calculated == 0) {
                    calculateCommissionAffilationClubPoint($order);
                }
                Cart::destroy();

                if(Cache::has(domain_info('user_id').'store_email')){
                    $store_email=Cache::get(domain_info('user_id').'store_email');

                }
                else{

                    $admin=User::findorFail(domain_info('user_id'));
                    $store_email=$admin->email;
                }


                $mail_data['store_email']=$store_email;
                $mail_data['order_no']=$order->order_no;
                $mail_data['base_url']=url('/');
                $mail_data['site_name']=Cache::get(domain_info('user_id').'shop_name',null);
                $mail_data['order_url']= url('/seller/order',$order->id);

                if(Cache::has(domain_info('user_id').'order_receive_method')){
                    $method=Cache::get(domain_info('user_id').'order_receive_method');

                }
                else{
                    $method="email";
                }

                if($method == 'email'){
                    if(env('QUEUE_MAIL') == 'on'){

                        dispatch(new \App\Jobs\Ordernotification($mail_data));
                    }
                    else{

                        Mail::to($store_email)->send(new SellerOrderMail($mail_data));
                    }
                    return redirect('/thanks');
                }
                else{
                    if(Cache::has(domain_info('user_id').'whatsapp')){
                        $whatsapp=json_decode(Cache::get(domain_info('user_id').'whatsapp'));
                        $url="https://wa.me/+".$whatsapp->phone_number."?text=My Order No Is ".str_replace('#','',$order->order_no);
                        return redirect($url);
                    }
                    if(env('QUEUE_MAIL') == 'on'){

                        dispatch(new \App\Jobs\Ordernotification($mail_data));
                    }
                    else{

                        Mail::to($store_email)->send(new SellerOrderMail($mail_data));
                    }
                    return redirect('/thanks');

                }
            }
            else{
                Order::destroy($data['ref_id']);
                return $this->payment_fail();
            }
        }
        //Momo
        if(isset($request->partnerCode)){
            $data= Session::get('order_info');
            if(!$request->errorCode){
                $order=Order::findorFail($data['ref_id']);
                $order->transaction_id = $request->transId;
                $order->category_id=$data['getway_id'];
                $order->payment_status = 1;
                $order->save();
                if ($order->payment_status == 1 && $order->commission_calculated == 0) {
                    calculateCommissionAffilationClubPoint($order);
                }
                Cart::destroy();

                if(Cache::has(domain_info('user_id').'store_email')){
                    $store_email=Cache::get(domain_info('user_id').'store_email');

                }
                else{

                    $admin=User::findorFail(domain_info('user_id'));
                    $store_email=$admin->email;
                }


                $mail_data['store_email']=$store_email;
                $mail_data['order_no']=$order->order_no;
                $mail_data['base_url']=url('/');
                $mail_data['site_name']=Cache::get(domain_info('user_id').'shop_name',null);
                $mail_data['order_url']= url('/seller/order',$order->id);

                if(Cache::has(domain_info('user_id').'order_receive_method')){
                    $method=Cache::get(domain_info('user_id').'order_receive_method');

                }
                else{
                    $method="email";
                }

                if($method == 'email'){
                    if(env('QUEUE_MAIL') == 'on'){

                        dispatch(new \App\Jobs\Ordernotification($mail_data));
                    }
                    else{

                        Mail::to($store_email)->send(new SellerOrderMail($mail_data));
                    }
                    return redirect('/thanks');
                }
                else{
                    if(Cache::has(domain_info('user_id').'whatsapp')){
                        $whatsapp=json_decode(Cache::get(domain_info('user_id').'whatsapp'));
                        $url="https://wa.me/+".$whatsapp->phone_number."?text=My Order No Is ".str_replace('#','',$order->order_no);
                        return redirect($url);
                    }
                    if(env('QUEUE_MAIL') == 'on'){

                        dispatch(new \App\Jobs\Ordernotification($mail_data));
                    }
                    else{

                        Mail::to($store_email)->send(new SellerOrderMail($mail_data));
                    }
                    return redirect('/thanks');

                }
            }
            else{
                Order::destroy($data['ref_id']);
                return $this->payment_fail();
            }
        }
        if (Session::has('customer_payment_info')) {
            $data= Session::get('customer_payment_info');

            $order=Order::findorFail($data['ref_id']);
            $order->transaction_id = $data['payment_id'];
            $order->category_id=$data['getway_id'];
            if (isset($data['payment_status'])) {
                $order->payment_status = $data['payment_status'];
            }
            else{
                $order->payment_status = 1;
            }

            $order->save();
            if ($order->payment_status == 1 && $order->commission_calculated == 0) {
                calculateCommissionAffilationClubPoint($order);
            }
            Session::forget('customer_payment_info');
            Cart::destroy();



            if(Cache::has(domain_info('user_id').'store_email')){
                $store_email=Cache::get(domain_info('user_id').'store_email');

            }
            else{

                $admin=User::findorFail(domain_info('user_id'));
                $store_email=$admin->email;
            }


            $mail_data['store_email']=$store_email;
            $mail_data['order_no']=$order->order_no;
            $mail_data['base_url']=url('/');
            $mail_data['site_name']=Cache::get(domain_info('user_id').'shop_name',null);
            $mail_data['order_url']= url('/seller/order',$order->id);

            if(Cache::has(domain_info('user_id').'order_receive_method')){
                $method=Cache::get(domain_info('user_id').'order_receive_method');

            }
            else{
                $method="email";
            }

            if($method == 'email'){
                if(env('QUEUE_MAIL') == 'on'){

                    dispatch(new \App\Jobs\Ordernotification($mail_data));
                }
                else{

                    Mail::to($store_email)->send(new SellerOrderMail($mail_data));
                }
                return redirect('/thanks');
            }
            else{
                if(Cache::has(domain_info('user_id').'whatsapp')){
                    $whatsapp=json_decode(Cache::get(domain_info('user_id').'whatsapp'));
                    $url="https://wa.me/+".$whatsapp->phone_number."?text=My Order No Is ".str_replace('#','',$order->order_no);
                    return redirect($url);
                }
                if(env('QUEUE_MAIL') == 'on'){

                    dispatch(new \App\Jobs\Ordernotification($mail_data));
                }
                else{

                    Mail::to($store_email)->send(new SellerOrderMail($mail_data));
                }
                return redirect('/thanks');

            }








        }
        abort(404);

    }

    public function payment_fail(){
        Session::flash('payment_fail','Sorry Transaction Failed');

        return redirect('/checkout');
    }


    public function calculateShipping($total,$shipping_amount,$weight)
    {
        $shipping_amount=(float)$shipping_amount;
        $totalAmount=$total;

        $weight_amount=$this->calculateWeight($weight,$shipping_amount);
        $amount=$totalAmount+$weight_amount;

        return $amount;

    }

    public function calculateWeight($weight,$amount)
    {
        return $amount;
    }


    public function api_payment_vnpay(Request $request){
        if(domain_info('user_id')){
            $vnpay=Getway::with('method')->where('user_id',domain_info('user_id'))->where('category_id',309)->first();
            $vnpay=json_decode($vnpay->content ?? '');
        }

        $data = [
            'vnp_TmnCode' => $vnpay->vnpTmnCode,
            'vnp_HashSecret' => $vnpay->vnpHashSecret,
            'testMode' => $vnpay->env == 'production' ? false : true,
            'vnp_TxnRef' => time(),
            'vnp_OrderType' => 250000,
            'vnp_OrderInfo' => $request->order_id,
            'vnp_Amount' => $request->amount*100,
            'vnp_ReturnUrl' => $request->return_url,
        ];

        $redirectUrl = $this->VNPAY_PAYMENT($data);
        return ['status' => true, 'payment_url' => $redirectUrl];
    }

    public function api_payment_momo(Request $request){
        if(domain_info('user_id')){
            $momo=Getway::with('method')->where('user_id',domain_info('user_id'))->where('category_id',311)->first();
            $momo=json_decode($momo->content ?? '');
        }
        \Config::set('laravel-omnipay.gateways.MoMoAIO.options.accessKey',$momo->accessKey);
        \Config::set('laravel-omnipay.gateways.MoMoAIO.options.secretKey',$momo->secretKey);
        \Config::set('laravel-omnipay.gateways.MoMoAIO.options.partnerCode',$momo->partnerCode);
        \Config::set('laravel-omnipay.gateways.MoMoAIO.options.testMode',$momo->env == 'production' ? false : true);

        $response = \MoMoAIO::purchase([
            'amount' => intval($request->amount),
            'returnUrl' => $request->return_url,
            'notifyUrl' => 'https://'.domain_info('domain_name'),
            'orderId' => $request->order_id,
            'requestId' => time(),
        ])->send();

        if ($response->isRedirect()) {
            $redirectUrl = $response->getRedirectUrl();
            return ['status' => true, 'payment_url' => $redirectUrl];
        }

        return ['status' => false];
    }

    public function VNPAY_PAYMENT($data){
        if($data['testMode']){
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        }else{
            $vnp_Url = '';
        }


        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $data['vnp_TmnCode'],
            "vnp_Amount" => $data['vnp_Amount'],
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $data['vnp_OrderInfo'],
            "vnp_OrderType" => $data['vnp_OrderType'],
            "vnp_ReturnUrl" => $data['vnp_ReturnUrl'],
            "vnp_TxnRef" => $data['vnp_TxnRef'],
        ];

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $data['vnp_HashSecret']);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;

    }
}
