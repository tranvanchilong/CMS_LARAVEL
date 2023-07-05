<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use App\Models\User;
use App\Models\Userplanmeta;
use App\Models\Customer;
use Hash;
use App\Order;
use App\Booking;
use Cache;
use App\Useroption;
use App\Models\WalletTransactions;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Support\Facades\Mail;
use Session;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Crypt;
use App\ExchangeRate;
use App\Getway;
use App\Http\Controllers\Frontend\AffiliateController;
use App\AffiliateStats;
use App\AffiliateUser;
use App\AffiliateOption;
use App\AffiliateLog;
use App\Redirect;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        if(env('MULTILEVEL_CUSTOMER_REGISTER') != true || url('/') == env('APP_URL')){
            abort(404);
        }
    }

    public function login(){

        $social_login = Useroption::where('user_id', domain_info('user_id'))->where('key','social_login')->first();
        $social_login=json_decode($social_login->value ?? '');

        if(Auth::check() == true){
            Auth::logout();
        }
        if(Auth::guard('customer')->check() == true){

            return redirect('/user/dashboard');
        }
        if(Cache::has(domain_info('user_id').'seo')){
            $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
        }
        else{
            $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
            $seo=json_decode($data->value ?? '');
        }
        if(!empty($seo)){
            JsonLdMulti::setTitle('Login - '.$seo->title ?? env('APP_NAME'));
            JsonLdMulti::setDescription($seo->description ?? null);
            JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

            SEOMeta::setTitle('Login - '.$seo->title ?? env('APP_NAME'));
            SEOMeta::setDescription($seo->description ?? null);
            SEOMeta::addKeyword($seo->tags ?? null);

            SEOTools::setTitle('Login - '.$seo->title ?? env('APP_NAME'));
            SEOTools::setDescription($seo->description ?? null);
            SEOTools::setCanonical($seo->canonical ?? url('/'));
            SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
            SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
            SEOTools::twitter()->setTitle('Login - '.$seo->title ?? env('APP_NAME'));
            SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
            SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
        }

        return view(base_view().'.account.login',compact('social_login'));
    }

    public function logout(){
        if(Auth::guard('customer')->check()){
            Auth::guard('customer')->logout();
            return redirect('/');
        }
        return redirect('/');
    }


    public function register(Request $request){
        $social_login = Useroption::where('user_id', domain_info('user_id'))->where('key','social_login')->first();
        $social_login=json_decode($social_login->value ?? '');

        if(Auth::check()){
            Auth::logout();
        }
        if(Auth::guard('customer')->check()){
            return redirect('/user/dashboard');
        }
        // check refferral_code on url if exist add cookie  
        if ($request->has('referral_code') && feature_is_activated('affiliate_status', domain_info('user_id'))) {
            try {
                $cookie_minute = 15;
                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_customer = Customer::where('created_by', domain_info('user_id'))->where('referral_code', $request->referral_code)->first();
                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_customer->id,domain_info('user_id'), 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }

        if(Cache::has(domain_info('user_id').'seo')){
            $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
        }
        else{
            $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
            $seo=json_decode($data->value ?? '');
        }
        if(!empty($seo)){
            JsonLdMulti::setTitle('Register - '.$seo->title ?? env('APP_NAME'));
            JsonLdMulti::setDescription($seo->description ?? null);
            JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

            SEOMeta::setTitle('Register - '.$seo->title ?? env('APP_NAME'));
            SEOMeta::setDescription($seo->description ?? null);
            SEOMeta::addKeyword($seo->tags ?? null);

            SEOTools::setTitle('Register - '.$seo->title ?? env('APP_NAME'));
            SEOTools::setDescription($seo->description ?? null);
            SEOTools::setCanonical($seo->canonical ?? url('/'));
            SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
            SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
            SEOTools::twitter()->setTitle('Register - '.$seo->title ?? env('APP_NAME'));
            SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
            SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
        }
        return view(base_view().'.account.register',compact('social_login'));
    }

    public function settings(){
        SEOTools::setTitle('Settings');
        return view(base_view().'.account.account');
    }

    public function settings_update(Request $request){
        $user_id = domain_info('user_id');

        $validatedData = $request->validate([
            'phone' => [
                'required',
                'max:20',
                Rule::unique('customers')->where(function ($query) use ($user_id) {
                    return $query->where('created_by', $user_id);
                })->ignore(Auth::guard('customer')->user()->id)
            ],
            'email' => [
                'required',
                'max:50',
                'email',
                Rule::unique('customers')->where(function ($query) use ($user_id) {
                    return $query->where('created_by', $user_id);
                })->ignore(Auth::guard('customer')->user()->id)
            ],
            'name' =>  'required|max:50',
        ]);

        if ($request->password) {
            $validatedData = $request->validate([
                'password' => ['required', 'min:8', 'confirmed'],
            ]);
        }

        $user=Customer::find(Auth::guard('customer')->user()->id);
        $user->name=$request->name;
        $user->phone=$request->phone;
        $user->email=$request->email;
        if ($request->password) {
            $check=Hash::check($request->password_current,Auth::guard('customer')->user()->password);
            if ($check==true) {
                $user->password= Hash::make($request->password);
            }
            else{
                $returnData['errors']['password']=array(0=>"Enter Valid Password");
                $returnData['message']="given data was invalid.";
                return response()->json($returnData, 401);
            }
        }
        $user->save();

        return response()->json(['Profile Updated Successfully']);
    }

    public function orders(){
        SEOTools::setTitle('Orders');
        $orders=Order::where('customer_id',Auth::guard('customer')->user()->id)->where('user_id',domain_info('user_id'))->with('payment_method')->latest()->paginate(20);
        return view(base_view().'.account.orders',compact('orders'));
    }

    public function order_view($id){

        $id=request()->route()->parameter('id');
        $info=Order::where('customer_id',Auth::guard('customer')->user()->id)->where('user_id',domain_info('user_id'))->with('order_item_with_file','order_content','shipping_info','payment_method')->findorFail($id);
        $order_content=json_decode($info->order_content->value);
        SEOTools::setTitle('Order No '.$info->order_no);
        return view(base_view().'.account.order_view',compact('info','order_content'));
    }
    
    public function bookings(){
        SEOTools::setTitle('Bookings');
        $bookings=Booking::where('customer_id',Auth::guard('customer')->user()->id)->where('user_id',domain_info('user_id'))->latest()->paginate(20);
        return view(base_view().'.account.bookings',compact('bookings'));
    }

    public function booking_view($id){

        $id=request()->route()->parameter('id');
        $info=Booking::where('customer_id',Auth::guard('customer')->user()->id)->where('user_id',domain_info('user_id'))->findorFail($id);
        SEOTools::setTitle('Booking No '.$info->booking_no);
       return view(base_view().'.account.booking_view',compact('info'));
    }

    public function register_user(Request $request){
        $domain_id=domain_info('domain_id');
        $user_id=domain_info('user_id');
        
        $validated = $request->validate([
            'email' => [
                'required',
                'max:50',
                'email',
                Rule::unique('customers')->where(function ($query) use ($user_id) {
                    return $query->where('created_by', $user_id);
                })
            ],
            'phone' => [
                'required',
                'max:20',
                Rule::unique('customers')->where(function ($query) use ($user_id) {
                    return $query->where('created_by', $user_id);
                })
            ],
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:8',
        ]);


        $user_limit=domain_info('customer_limit',0);
        $total_customers=Customer::where('created_by',$user_id)->count();

        if($user_limit <= $total_customers){
            \Session::flash('user_limit','Opps something wrong please contact with us..!!');
            return back();

        }
        $check=Customer::where([['created_by',$user_id],['email',$request->email]])->first();
        $checkPhone=Customer::where([['created_by',$user_id],['phone',$request->phone]])->first();
        if(!empty($check)){
            \Session::flash('user_limit','Opps the email address already exists...!!');
            return back();
        }elseif(!empty($checkPhone)){
            \Session::flash('user_limit','Opps the phone number already exists...!!');
            return back();
        }
        $user= new Customer();
        $user->email=$request->email;
        $user->name=$request->name;
        $user->phone=$request->phone;
        $user->password=Hash::make($request->password);
        $user->domain_id=$domain_id;
        $user->created_by=$user_id;
        if(Cookie::has('referral_code')){
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = Customer::where('referral_code', $referral_code)->first();
            $user->referred_by = $referred_by_user->id;
            $user->save();
            if($referred_by_user != null){
                $levels = AffiliateOption::where('user_id', domain_info('user_id'))->where('type','like', '%'.'level'.'%')->orderBy('type','asc')->get();
                $customer_parent = $user;
                $amount = AffiliateOption::where('user_id', domain_info('user_id'))->where('type', 'user_registration')->first();
                if($amount){
                    $amount = $amount->percentage;
                }else{
                    $amount = 0;
                }
                // Affiliate log
                $affiliate_log                      = new AffiliateLog;
                $affiliate_log->user_id             = domain_info('user_id');
                $affiliate_log->customer_id         = $user->id;
                $affiliate_log->referred_by_user    = $referred_by_user->id;
                $affiliate_log->amount              = $amount;
                $affiliate_log->affiliate_type      = 'user_registration';
                $affiliate_log->save();

                if(count($levels) == 0){
                    $affiliate_user = $referred_by_user->affiliate_user;
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
        }else{
            $user->save();
        }
        $affilateUser = new AffiliateUser();
        $affilateUser->customer_id=$user->id;
        $affilateUser->user_id=domain_info('user_id');
        $affilateUser->save();

        // Auth::guard('customer')->loginUsingId($user->id);
        $dataSendMail = [
            'link' =>  route('user.verify_email', Crypt::encryptString($user->id))
        ];

        //send email verify
        Mail::to($user->email)->send(new VerifyEmail($dataSendMail));
        Session::flash('success', 'Please verify your email');
        return redirect('/user/login');

    }

    public function dashboard(){
        if(Auth::guard('customer')->check()){
            SEOTools::setTitle('Dashboard');
            return view(base_view().'.account.dashboard');
        }
        return redirect('/user/login');
    }

    public function wallet(){
        if(Auth::guard('customer')->check()){
            SEOTools::setTitle('Wallet');
            $wallet_status= Useroption::where('user_id',domain_info('user_id'))->where('key','wallet_status')->first()->value ?? '';
            $total_wallet_balance = Auth::guard('customer')->user()->wallet_balance ?? '0';
            $wallet_transactio_list = WalletTransactions::where('user_id',domain_info('user_id'))->where('customer_id', Auth::guard('customer')->user()->id)->latest()->paginate(10);
            $currency = Useroption::where('key','currency')->where('user_id',domain_info('user_id'))->first();
            $exchange_rate = ExchangeRate::where('code',(json_decode($currency->value)->currency_name ?? ''))->first();
            $deposit_method = Getway::where('user_id',domain_info('user_id'))->where('category_id', 2908)->first();
            $getways=  Getway::where('user_id',domain_info('user_id'))->where('status_add_money',1)->whereHas('method', function ($method) {
                $method->where('featured', 1);
            })->get();
            $binance = json_decode($getway->content ?? '');
            $contract_address = $binance->contract_address ?? '';
            $receiver_address = $binance->receiver_address ?? '';
            if($wallet_status == 1){
                return view(base_view().'.account.wallet', compact('total_wallet_balance','wallet_transactio_list','exchange_rate','contract_address','receiver_address','getways','deposit_method'));
            }
            return redirect('/user/login')->with('success', __('Access Denied'));
        }

    }

    public function deposit_method(Request $request)
    {
        if(Auth::guard('customer')->check()){
            SEOTools::setTitle('Deposit Method');
            $getways=  Getway::where('user_id',domain_info('user_id'))->where('status_add_money',1)->whereHas('method', function ($method) {
                $method->where('featured', 1);
            })->get();
            $deposit_method = Getway::where('user_id',domain_info('user_id'))->where('category_id', 2908)->first();
            if($deposit_method->status_add_money == 1){
                return view(base_view().'.account.connect_metamark', compact('getways','deposit_method'));
            }
            return redirect('/user/wallet')->with('error', __('Metamask Access Denied'));
        }
    }

    public function wallet_add(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $customer->wallet_balance = $request->amount;
        $customer->save();
        return back();
    }

    public function wallet_qr(Request $request)
    {
        if(Auth::guard('customer')->check()){
            SEOTools::setTitle('Wallet');
            $exchange_rate= Useroption::where('user_id',domain_info('user_id'))->where('key','exchange_rate')->first()->value ?? '';
            $total_wallet_balance = Auth::guard('customer')->user()->wallet_balance ?? '0';
            return view(base_view().'.account.qr_wallet', compact('total_wallet_balance','exchange_rate'));
        }
        return redirect('/user/login')->with('success', __('Access Denied'));

    }

    public function notification(){
        if(Auth::guard('customer')->check()){
            SEOTools::setTitle('All Notification');
            $notification = \App\Models\Notifications::where('user_id', domain_info('user_id'))->where('status',1)->get();
            return view(base_view().'.account.notification',compact('notification'));
        }
        return redirect('/user/login');
    }

    public function verifyEmail(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $user = Customer::where('id', $id)->first();
        if($user){
            if($user->email_verified_at == null){
                $user->email_verified_at = now();
                $user->save();
            }

            return redirect('/user/login')->with('success', __('Email verify successfully'));
        }

        abort(404);
    }

    public function deposit(Request $request)
    {
        if(!$request->amount || $request->amount <= 0 || !$request->transaction){
            return redirect()->back()->with('error', __('Deposit Failed'));
        }

        $check = WalletTransactions::where('blockchain_transaction', $request->transaction)->first();
        if($check){
            return redirect()->back()->with('success', __('Deposit successfully'));
        }

        //  CHECK BINANCE

        $customer = Auth::guard('customer')->user();
        $user = domain_info('user_id');
        $amount = $request->amount;
//        $balance = $customer->wallet_balance + $amount;
        WalletTransactions::create([
            'transaction_type' => 'Add Money',
            'customer_id' => $customer->id,
            'user_id' => $user,
            'amount' => $amount,
//            'balance' => $balance,
            'status' => 0,
            'blockchain_transaction' => $request->transaction
        ]);

//        $customer->wallet_balance = $balance;
//        $customer->save();

        return redirect()->back()->with('success', __('Deposit successfully'));
    }

    protected function loginFromMyDi4l(Request $request){
        $user = $this->verifyDi4l($request->only(['token', 'secret']));
        if(!$user){
            return redirect('/login')->with('error', 'Error');
        }
        Auth::logout();
        Auth::loginUsingId($user->id, true);
        return redirect('/seller/dashboard');
    }

    protected function loginFromAdmin(Request $request){
        $email = $request->email;
        $token = $request->token;
        $secret = $request->secret;
        $user = User::where('email', $email)->first();
        if(!$user){
            return redirect('/login')->with('error', 'Email not found');
        }

        if(!$this->verifyDi4lSellAdmin(['email' => $email, 'token' => $token, 'secret' => $secret])){
            return redirect('/login')->with('error', 'Error');
        }

        if(Auth::check()){
            Auth::logout();
        }
        Auth::loginUsingId($user->id, true);

        return redirect('/seller/dashboard')->with('error', 'Email not found');
    }

    public function checkRedirect($page_name)
    {
      $checkRedirect = Redirect::where('user_id', domain_info('user_id'))->where('link_check', $page_name)->first();

      if(!$checkRedirect || !$checkRedirect->link_redirect){
        abort(404);
      }else{
        return redirect($checkRedirect->link_redirect);
      }
    }
  
    

}
