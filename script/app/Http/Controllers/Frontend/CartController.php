<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Attribute;
use Cart;
use App\Category;
use App\Term;
use Carbon\Carbon;
use App\Models\Price;
use Illuminate\Support\Facades\Cookie;
use Gloudemans\Shoppingcart\Cart as ShoppingcartCart;

class CartController extends Controller
{
    
    public function add_to_cart(Request $request,$id)
    {
    	$id=request()->route()->parameter('id');
    	$user_id=domain_info('user_id');
    	
        $term=Term::where('user_id',$user_id)->with('attributes','price','preview')->where('id',$id);
        $attribute_full=$term->first()->attributes ?? [];
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
           $price=$term->price_single->price;
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

           $price=$price;
            if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $term->id) {
                $product_referral_code = Cookie::get('product_referral_code') ;
            }   
           Cart::add($term->id,$term->title, $qty,$price,0,['attribute_full'=>$attribute_full,'attribute' => $attributes,'options'=>$options,'product_referral_code' => $product_referral_code ?? '','preview' => $term->preview->media->url ?? asset('uploads/default.png')]);
          
       }
        $data['count']=Cart::count();
    	$data['total']=Cart::total();
    	$data['subtotal']=Cart::subtotal();
    	$data['cart_add']=Cart::content();

    	return response()->json($data);
    }


    public function add_to_wishlist(Request $request,$id){
        $id=request()->route()->parameter('id');
        $user_id=domain_info('user_id');
        
        $term=Term::where('user_id',$user_id)->with('price','preview')->where('id',$id);
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
                    
            Cart::instance('wishlist')->add($term->id,$term->title, $qty,$price,0,['attribute' => $attributes,'options'=>$options,'preview' => $term->preview->media->url ?? asset('uploads/default.png')]);
           
        }
        return Cart::instance('wishlist')->content()->count();
    }

    public function wishlist_remove(){
          $id=request()->route()->parameter('id');
          Cart::instance('wishlist')->remove($id);
          return back();
    }

    public function cart_clear()
    {
        Cart::destroy();
        return back();
    }
    public function getforVariation(Request $request)
    {
        $id=$request->id;
        $user_id=domain_info('user_id');
        $option=$request->option ?? [];
        $term=Term::where('user_id',$user_id)->with('attributes','prices','price','stock')->where('status',1)->where('id',$id);
        $attribute_full=$term->first()->attributes ?? [];
    
        $variation=[];
        if($request->variation != null){
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
        $price = 0;
        $term= $term->first();
        if(!empty($term)){   
            $price = amount_format($term->price_product($variation)->price ?? 0);
            $regular_price = amount_format($term->price_product($variation)->regular_price ?? 0);
           
        }
        
        return response()->json(['price'=>$price,'regular_price'=>$regular_price]);
    }
    public function cart_add(Request $request)
    {
        $id=$request->id;
        $user_id=domain_info('user_id');
        $option=$request->option ?? [];
        $term=Term::where('user_id',$user_id)->with('attributes','prices','price','preview')->where('status',1)->where('id',$id);
        $attribute_full=$term->first()->attributes ?? [];
    
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
        // else{
        //     $error['errors']['error']='Chưa chọn phân loại';
        //     return response()->json($error,201);
        // }

        $term= $term->first();

        if(!empty($term)){   
            $price=$term->price_product($variation)->price ?? 0;   
            
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
            if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $term->id) {
                $product_referral_code = Cookie::get('product_referral_code') ;
            }  
            Cart::add($term->id,$term->title, $request->qty,$price,0,['attribute_full' => $attribute_full,'attribute' => $attributes,'options'=>$options,'product_referral_code' => $product_referral_code ?? '','preview' => $term->preview->media->url ?? asset('uploads/default.png')]);
           
        }
        
        
        $data['count']=Cart::count();
        $data['total']=Cart::total();
        $data['subtotal']=Cart::subtotal();
        $data['cart_add']=Cart::content();

        return response()->json($data);
    }

    public function update_cart(Request $request){
        $cart = Cart::get($request->rowId);
        $term=Term::with('prices','price_single')->find($cart->id);
        $price=$term->price_product($request->variation)->price ?? 0;
        $qty = $request->qty;
        $attribute=$cart->options->attribute_full->whereIn('variation_id',$request->variation);
        $options = $cart->options->merge(['attribute' => $attribute]);
        if ($qty === "0" && $cart) {
            Cart::remove($request->rowId);
            return response()->json('Update Cart Success');
        }
        Cart::update($request->rowId,['qty' => $qty,'price'=>$price,'options' => $options]);
        return response()->json('Update Cart Success');
    }

    public function remove_cart(Request $request){
        Cart::remove($request->id);
        $data['count']=Cart::count();
        $data['total']=Cart::total();
        $data['subtotal']=Cart::subtotal();
        $data['cart_add']=Cart::content();
        return response()->json($data);

    }

    public function cart_remove($id){
        $id=request()->route()->parameter('id');
        Cart::remove($id);
        return back();

    }

    public function apply_coupon(Request $request)
    {

        $validatedData = $request->validate([
            'code' => 'required|max:50',
         ]);
        $user_id=domain_info('user_id');
        $code=Category::where('user_id',$user_id)->where('type','coupon')->where('name',$request->code)->first();
        if (empty($code)) {
           $error['errors']['error']='Coupon Code Not Found.';
           return response()->json($error,404);
        }
        $mydate= Carbon::now()->toDateString();
        if ($code->slug >= $mydate) {
            Cart::setGlobalDiscount($code->featured);

            return response()->json(['Coupon Applied']);
        }

        $error['errors']['error']='Sorry, this coupon is expired';
        return response()->json($error,401);



    }

    public function express(Request $request){
       
        $id=$request->id;
        $user_id=domain_info('user_id');
        $option=$request->option ?? [];
        $term=Term::where('user_id',$user_id)->with('price','preview')->where('status',1)->where('id',$id);
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
        if($request->variation != null){
            
            $variation=[];
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
           
           
            $price=$price;
            // dd($price);       
             Cart::add($term->id,$term->title, $request->qty,$price,0,['attribute' => $attributes,'options'=>$options,'preview' => $term->preview->media->url ?? asset('uploads/default.png')]);
           
        }

       
       return redirect('/checkout');
    }

}
