<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\WalletTransactions;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Userplan;
use App\Models\Userplanmeta;
use App\Models\Price;
use App\Category;
use App\Domain;
use App\Option;
use App\Plan;
use Carbon\Carbon;
use App\Mail\Sendmailtowillexpire;
use App\Mail\Planexpired;
use App\Mail\Planrenew;
use Illuminate\Support\Facades\Mail;
use Artisan;
use Illuminate\Support\Facades\Http;
use App\ExchangeRate;
class CronController extends Controller
{


    public function makeExpireAbleCustomer()
    {

          $tenants=Domain::where('will_expire','<' ,Carbon::today())->where('status',1)->with('orderwithplan','user')->get();
         Userplan::where('will_expire','<' ,Carbon::today())->where('status',1)->update(array('status' => 3)); //expired
         Domain::where('will_expire','<' ,Carbon::today())->where('status',1)->update(array('status' => 3));

         $option = Option::where('key','cron_info')->first();
         $cron_option = json_decode($option->value);

         $trial_tenants=[];
         $expireable_tenants=[];
         $users=[];
        foreach($tenants as $row){
            $plan=$row->orderwithplan->plan;
            array_push($users, $row->user_id);
            if (!empty($plan)) {
                if($row->is_trial == 1){
                   $order_info['email']=$row->user->email;
                   $order_info['name']=$row->user->name;
                   $order_info['plan_name']=$plan->name;
                   $order_info['tenant_id']=$row->domain;
                   $order_info['will_expire']=$row->will_expire;
                   array_push($trial_tenants, $order_info);

               }
               else{

                   $order_info['email']=$row->user->email;
                   $order_info['name']=$row->user->name;
                   $order_info['plan_name']=$plan->name;
                   $order_info['tenant_id']=$row->domain;
                   $order_info['will_expire']=$row->will_expire;
                   $order_info['amount']=$plan->price;
                   $order_info['plan_name']=$plan->name;
                   array_push($expireable_tenants, $order_info);
               }
            }
         }

         //User::whereIn('id',$users)->update(array('status' => 2));


         $this->expiredTenant($trial_tenants,$cron_option->trial_expired_message);
         $this->expiredTenant($expireable_tenants,$cron_option->expire_message);


         return "success";






    }

    //send notification for the expired tenant owner
     public function expiredTenant($data,$massege)
     {
         foreach($data ?? []  as $row){

                $references['Store']=$row['tenant_id'];
                $references['Plan name']=$row['plan_name'];
                $references['Date of expire']=$row['will_expire'];

                $maildata=[

                    'subject'=>'['.strtoupper(env('APP_NAME')).'] - Subscription Expired For '.$row['tenant_id'],
                    'message'=>$massege,
                    'references'=>json_encode($references),
                    'name'=>$row['name'],
                    'email'=>$row['email'],
                ];



                 Mail::to($row['email'])->send(new Planrenew($maildata));

        }
     }





    public function reset_product_price(){
        $start=Price::where('starting_date','<=',date('Y-m-d'))->where('special_price','!=',null)->get();
        foreach($start as $row){

            if($row->price_type == 1){
                $price=$row->regular_price-$row->special_price;
            }
            else{
                $percent= $row->regular_price * $row->special_price / 100;
                $price= $row->regular_price-$percent;
                $price=str_replace(',','',number_format($price,2));
            }

            $new_price=Price::find($row->id);
            $new_price->price=$price;
            $new_price->save();

        }
        $ending_date=Price::where('ending_date','<=',now())->get();
        foreach($ending_date as $row){
            $price=Price::find($row->id);
            $price->price=$price->regular_price;
            $price->special_price=null;
            $price->price_type=1;
            $price->starting_date=null;
            $price->ending_date=null;
            $price->save();
        }
        return response()->json('success');
    }


    public function index()
    {

        if (!Auth()->user()->can('cron_job.control')) {
            return abort(401);
        }
        $option=Option::where('key','cron_info')->first();
        $info=json_decode($option->value);
       return view('admin.cron.index',compact('info'));
    }

    public function make_expirable_user()
    {
        Artisan::call('make:make_expirable_user');
        return "done";
    }

    public function send_mail_to_will_expire_plan_soon()
    {
         $cron_info=Option::where('key','cron_info')->first();
         $cron_info=json_decode($cron_info->value);
         $days=$cron_info->send_mail_to_will_expire_within_days;

         $date= Carbon::now()->addDays($days)->format('Y-m-d');

         $tenants=\App\Domain::where([['status',1],['will_expire','<',$date],['will_expire','!=',Carbon::now()->format('Y-m-d')]])->with('orderwithplan','user')->get();


         $expireable_tenants=[];

         foreach($tenants as $row){
            $plan=$row->orderwithplan->plan;

            if (!empty($plan)) {
                if($row->is_trial == 0){
                   $order_info['email']=$row->user->email;
                   $order_info['name']=$row->user->name;
                   $order_info['plan_name']=$plan->name;
                   $order_info['will_expire']=$row->will_expire;
                   $order_info['amount']=$plan->price;
                   $order_info['plan_name']=$plan->name;
                   $order_info['tenant_id']=$row->domain;
                   array_push($expireable_tenants, $order_info);

               }

            }
         }


         $this->expireSoon($expireable_tenants,$cron_info->alert_message);

         return "success";
    }

      //send notification mail before expire the order
    public function expireSoon($data,$message)
    {
        foreach($data ?? []  as $row){

                $references['Store']=$row['tenant_id'];
                $references['Plan name']=$row['plan_name'];
                $references['Last date of due']=$row['will_expire'];
                $references['Amount']=number_format($row['amount'],2);

                $maildata=[

                    'subject'=>'['.strtoupper(env('APP_NAME')).'] - Upcoming Subscription Renewal Notice',
                    'message'=>$message,
                    'references'=>json_encode($references),
                    'name'=>$row['name'],
                    'email'=>$row['email'],
                ];



                Mail::to($row['email'])->send(new Planrenew($maildata));

        }
    }

    //exchange_rates
    public function exchange_rates()
    {

        $api_shiva = Http::get('https://api.pancakeswap.info/api/v2/tokens/0xb0157556d288f89b6c61cd8ce45723b8d44b810e');
        $data_shiva = json_decode($api_shiva);
        $api_data = Http::get('https://api.exchangerate.host/latest?base=USD');
        $data = json_decode($api_data);

        $crypto = array("BTC", "ETH");
        $currency = array("USD", "EUR", "VND");
        foreach ($data->rates as $key => $value) {
            if (in_array($key, $crypto)) {
                $exchange_rates = ExchangeRate::updateOrCreate([
                    'code' => $key,
                ],
                [
                    'name' => $key,
                    'type' => 'crypto',
                    'rate' => (1/$value)*(1/$data_shiva->data->price)
                ]);
            }
            if (in_array($key, $currency)) {
                $exchange_rates = ExchangeRate::updateOrCreate([
                    'code' => $key,
                ],
                [
                    'name' => $key,
                    'type' => 'currency',
                    'rate' => (1/$value)*(1/$data_shiva->data->price)
                ]);
            }
        }
        return "success";
    }

    public function checkStatusBinanceTransaction(){
        $result = [];
        $wallet = WalletTransactions::where('status', 0)->whereNotNull('blockchain_transaction')->orderby('id', 'asc')->limit(5)->get();
        if($wallet){
            foreach($wallet as $w){
                try{
                    $txhash = $w->blockchain_transaction;

                    $customer = Customer::where('id', $w->customer_id)->first();
                    $wallet_balance = $customer->wallet_balance ? $customer->wallet_balance : 0;
                    $amount = $w->amount;
                    $balance = $wallet_balance + $amount;

                    $client = new Client();
                    $res = $client->request('GET', 'https://api.bscscan.com/api', ['query' => [
                        'module' => 'transaction',
                        'action' => 'gettxreceiptstatus',
                        'txhash' => $txhash,
                        'apikey' => env('BSCAN_API_TOKEN')
                    ]]);
                    $data = json_decode($res->getBody());
                    if($data->result->status == "1") {
                        $new_wallet = WalletTransactions::where('id', $w->id)->first();
                        $new_wallet->balance = $balance;
                        $new_wallet->status = 1;
                        $new_wallet->save();

                        $customer->wallet_balance = $balance;
                        $customer->save();

                        $result[] = $new_wallet;
                    }else if($data->result->status == "0"){
                        $new_wallet = WalletTransactions::where('id', $w->id)->first();
                        $new_wallet->status = 2;
                        $new_wallet->save();
                        $result[] = $new_wallet;
                    }
                }catch (BadResponseException $exception){
                    return $exception->getMessage();
                }
            }
        }
        return $result;
    }
}
