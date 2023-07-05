<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Term;
use App\Stock;
use App\Models\Price;
use Cart;
use Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    public function cart()
    {
        $user = Auth::user();
        Cart::restore($user->id);
        Cart::store($user->id);
        $content = Cart::content();
        $data = [];
        foreach ($content as $key => $row) {
            array_push($data,$row);
            
        }
        
        return response()->json(['user' => $user, 'cart' => $data], 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_product' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }

        $user = Auth::user();
        $option=$request->option ?? [];
        $term = Term::with('price','preview')->where('status',1)->where('id', $request->id_product);
        $attribute_full = $term->first()->attributes ?? [];
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
        $variation=[];
        if($request->variation != null){
            if(!is_array($request->variation)){
                $request->variation = json_decode($request->variation);
            }

            foreach($request->variation as $key => $row){
                array_push($variation,$row);
            }

            
            $term=$term->with('attributes',function($q) use ($variation){
             if(count($variation) > 0){
                 return $q->whereIn('variation_id',$variation);
             }
             else{
                   return $q;
             }
             
            });
           
        }
         $term= $term->first();

       
        if(!empty($term)){
            $price=$term->price_product($request->variation);
            if($price == null){
                $price = $term->price;
            }elseif(!Carbon::now()->between($price->starting_date,$price->ending_date)){
                $price->special_price = 0;
            }  
            $price_data = $price;
            $price = $price->price ?? 0;
            $stock = $term->stocks->where('variation_id_code',$variation)->first();

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
 
            
           
            $data = collect();
            $attr = [];
            $stt=0;
            $attributes = $attribute_full->groupBy('category_id');
            if($attributes){
                foreach ($attributes as $key => $attribute) {
                    $var_id_code=[];
                    foreach ($attribute as $key => $value) {
                        $var_id_code[$key]=$value->variation;
                    }
                    $attr[$stt]=$attribute->first()->attribute ?? '';
                    $attr[$stt]['value']=$var_id_code;
                    $stt++;
                }
            }
            $data = (collect($term)->put('attr', $attr));

            if($request->variation != null){
                $attribute = $term->attributes ?? [];
            }
            else{
            $attribute = [];
            }

            Cart::restore($user->id);
            Cart::add($term->id,$term->title, $request->qty,$price,0,['attr' => $data['attr'],'attribute' => $attribute,'options'=>$options,'preview' => $term->preview->media->name ?? asset('uploads/default.png'),'price'=>$price_data]);
            Cart::store($user->id);
        }
       
        $data['count']=Cart::count();
        $data['total']=Cart::total();
        $data['subtotal']=Cart::subtotal();
        $data = [];
        foreach (Cart::content() as $key => $row) {
            array_push($data,$row);
        }
        

        return response()->json($data, 200);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'row_id' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }

        $user = Auth::user();
        Cart::restore($user->id);

        // Tất cả nội dung liên quan đến Cart 
        // Thì viết trong khung này
        // Restore và store

        Cart::remove($request->row_id);
        Cart::store($user->id);
        $content = Cart::content();
        $data = [];
        foreach ($content as $key => $row) {
            array_push($data,$row);
            
        }
        return response()->json(['status' => true, 'message' => 'successfully_removed'], 200);
    }

    public function remove_all_from_cart()
    {
        $user = Auth::user();
        Cart::restore($user->id);
        Cart::destroy();
        Cart::store($user->id);
        $content = Cart::content();
        $data = [];
        foreach ($content as $key => $row) {
            array_push($data,$row);
            
        }
        return response()->json(['status' => true, 'message' => 'successfully_removed_all', 'cart' => $data], 200);
    }

    public function update_cart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'qty' => 'required',
            'row_id' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }
        if(!is_array($request->variation)){
            $request->variation = json_decode($request->variation);
        }

        $user = Auth::user();
        
        Cart::restore($user->id);
        $cart = Cart::get($request->row_id);
        $price = 0;
        
        $variation = $request->variation ?? [];
        $term=Term::with('prices','price_single');
        $term=$term->with('attributes',function($q) use ($variation){
            return $q->whereIn('variation_id',$variation);
        });

        $term=$term->find($cart->id);

        $price=$term->price_product($request->variation);

        if($price == null){
            $price = $term->price;
        }elseif(!Carbon::now()->between($price->starting_date,$price->ending_date)){
            $price->special_price = 0;
        } 
        
        $price_data = $price;
        $price = $price->price ?? 0;
        $stock = $term->stocks->where('variation_id_code',$request->variation)->first();

        $attributes=$term->attributes ?? [];

        $options = $cart->options->merge(['attribute' => $attributes,'price'=>$price_data,'stock'=>$stock]);
        $qty = $request->qty;
        
        Cart::update($request->row_id,['qty' => $qty,'price'=>$price,'options' => $options]);
        
        Cart::store($user->id);
        $content = Cart::content();
        $data = [];

        foreach ($content as $key => $row) {
            array_push($data,$row);

        }
        return response()->json(['status' => true, 'message' => 'Update Successfully', 'cart' => $data], 200);
    }
}
