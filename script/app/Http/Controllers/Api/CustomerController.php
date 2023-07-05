<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Order;
use App\Term;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use Cart;

class CustomerController extends Controller
{
    public function info(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function update_profile(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }
        $customer = Auth::user();

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }

        $userDetails = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ];

         Customer::where(['id' => $customer->id])->update($userDetails);

        return response()->json(['message' =>'Successfully updated !', 'data' =>$userDetails], 200);
    }

    public function get_order_list(Request $request)
    {
        $user = Auth::user();
        $orders=Order::where(['customer_id' => $user->id])->with('payment_method')->latest()->paginate(20);
        return response()->json(['total_size' => $orders->count(),'order' =>$orders], 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }
        $customer = Auth::user();
        $info=Order::where(['id' => $request['order_id']])->where('user_id', domain_info('user_id'))->with('order_item','customer','order_content','shipping_info','getway','payment_method')->get();
        $data = [];
        foreach($info as $key=>$product){
            foreach($product->order_item as $key=>$products){

                // return $products->term->attributes;
                $variation = json_decode($products->info);

                foreach($variation->attribute as $key=>$var){
                    array_push($data, $var);
                }
                $products->term->attribute = $data;
            }

        }
        return response()->json(['order_detail' => $info,'term' => $data ], 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }
        $customer = Auth::user();
        DB::table('customers')->where('id', $customer->id)->where('created_by', domain_info('user_id'))->update([
            'cm_firebase_token' => $request['cm_firebase_token'],
        ]);

        return response()->json(['message' => ('Firebase Token Successfully updated!')], 200);
    }

    public function remove_account(Request $request,$domain,$id)
    {
        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }
        $userDetails = [
            'status' => 0,
        ];
        Customer::where(['id' => $id])->update($userDetails);

        return response()->json(['message' =>'Successfully removed !', 'data' =>$userDetails], 200);
    }

    public function wish_list(Request $request)
    {
        $user = Auth::user();
        Cart::instance('wishlist')->restore($user->id);
        Cart::instance('wishlist')->store($user->id);
        $wishlist = Cart::instance('wishlist')->content();
        $data = [];
        foreach ($wishlist as $key => $row) {
            array_push($data,$row);

        }
        return response()->json( [ 'wishlist' => $data], 200);
    }

    public function add_to_wishlist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_product' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }

        $user = Auth::user();
        $term=Term::with('price','preview')->where('id',$request->id_product);
        if($request->option != null){
         $term=$term->with('termoption',function($q) use ($option){
             if(count($option) > 0){
             return $q->whereIn('id',$option);
             }
             else{
                 return $q;
             }
             });
        }
        if($request->variation){
         $term=$term->with('attributes',function($q) use ($variation){
             if(count($variation) > 0){
              return $q->whereIn('id',$variation);
             }
             else{
                 return $q;
             }

         });
        }
        $term= $term->first();
        if(!empty($term)){
            $price=$term->price->price;
            if($request->option != null){
             foreach($term->termoption ?? [] as $row){
                 if($row->amount_type == 1){
                  $price= $price+$row->amount;
                 }
                 else{
                  $percent= $price * $row->amount / 100;
                  $price= $price+$percent;
                 }
             }
             $options=$term->termoption;
            }
            else{
             $options= [];
            }

            if($request->variation != null){
             $attributes=$term->attributes ?? [];
            }
            else{
             $attributes= [];
            }
            $qty=$request->qty ?? 1;

            $price=$price*$qty;
            Cart::instance('wishlist')->restore($user->id);
            Cart::instance('wishlist')->add($term->id,$term->title, $qty,$price,0,['attribute' => $attributes,'options'=>$options,'preview' => $term->preview->media->name ?? asset('uploads/default.png'),'slug' => $term->slug]);
            Cart::instance('wishlist')->store($user->id);
        }

        $wishlist = Cart::instance('wishlist')->content();
        $data = [];
        foreach ($wishlist as $key => $row) {
            array_push($data,$row);

        }

        return response()->json(['status' => 'Add Wish List Successfully', 'wishlist' => $data], 200);
    }

    public function remove_from_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'row_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }

        $user = Auth::user();
        Cart::instance('wishlist')->restore($user->id);

        // Tất cả nội dung liên quan đến Cart
        // Thì viết trong khung này
        // Restore và store

        Cart::instance('wishlist')->remove($request->row_id);
        Cart::instance('wishlist')->store($user->id);
        $content = Cart::instance('wishlist')->content();
        $data = [];
        foreach ($content as $key => $row) {
            array_push($data,$row);

        }
        return response()->json(['status' => 'successfully_removed', 'wishlist' => $data], 200);
    }
    public function updateToken(Request $request)
    {
        $data = User::where('email',  $request->email)->update(['shop_sync_token' => $request->value]);
        return response()->json( [
            'data' => $data,
        ], 200);
    }

}
