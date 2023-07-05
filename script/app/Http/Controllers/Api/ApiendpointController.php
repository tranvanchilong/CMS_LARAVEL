<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userplan;
use App\Plan;
use App\Models\User;
use App\Option;
use App\Domain;
use Carbon\Carbon;



class ApiendpointController extends Controller
{
    public function getPlan(){
        $plans = Plan::all();
        return response()->json($plans); 
    }

    public function create_order(Request $request){
        // $data = $request->all();
        // return Carbon::createFromFormat('Y-m-d', $request->start_date)->setTime(0, 0, 0);
        $user=User::where('email',$request->email)->where('role_id',3)->first();
        if (empty($user)) {
            $msg['errors']['user']='User Not Found';
            return response()->json($msg,401);
        }
        
        $plan=Plan::findorFail($request->plan);
            
        $max_order=Userplan::max('id');
        $order_prefix=Option::where('key','order_prefix')->first();
        $tax=Option::where('key','tax')->first();
        $tax= ($plan->price / 100) * $tax->value;
        $order_no = $order_prefix->value.$max_order;

        $order=new Userplan;
        $order->order_no=$order_no;
        $order->amount=$plan->price;
        $order->tax=$tax;
        $order->trx=$request->transition_id;
        $order->will_expire=$request->end_date;
        $order->created_at=Carbon::createFromFormat('Y-m-d', $request->start_date)->setTime(0, 0, 0);
        $order->user_id=$user->id;
        $order->plan_id=$plan->id;
        $order->category_id=$request->payment_method;
        $order->payment_status = 1;
        $order->status=1;
        $order->save();
        
        $dom=Domain::where('user_id',$user->id)->first();
        $dom->data=$plan->data;
        $dom->userplan_id=$order->id;
        $dom->will_expire=$request->end_date;
        $dom->is_trial=0;
        $dom->save();

        $dom->orderlog()->create(['userplan_id'=>$order->id,'domain_id'=>$dom->id]);

        if ($request->notification_status == 'yes'){
            $data['info']=Userplan::with('plan_info','category','user')->find($order->id);
            $data['comment']=$request->content;
            $data['to_vendor']='vendor';
            if(env('QUEUE_MAIL') == 'on'){
             dispatch(new \App\Jobs\SendInvoiceEmail($data));
            }
            else{
             Mail::to($user->email)->send(new SubscriptionMail($data));
            }
         }

        return response()->json(['Order Created Successfully']);
    }
}