<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Ordershipping;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class ShippingMethodController extends Controller
{
    public function get_shipping_method_info(Request $request, $domain)
    {
        try {
            $shipping = Category::where('type','method')->where('user_id', domain_info('user_id'))->get();
            return response()->json($shipping, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function check_shipping_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_id' => 'required'
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }

        $seller_shipping = Ordershipping::where('shipping_id',$request->shipping_id)->with('shipping_method')->first();

        $shipping_type = isset($seller_shipping)==true? $seller_shipping->shipping_method->name:'order_wise';
            
        return response()->json(['shipping_type'=>$shipping_type], 200);
    }

    public function shipping_methods_by_seller($id, $seller_is)
    {
        $seller_is = $seller_is == 'admin' ? 'admin' : 'seller';
        return response()->json(Helper::get_shipping_methods($id, $seller_is), 200);
    }

    public function get_payment_method_info()
    {
        try {
            $cod=Category::with('description','actives_getway')->where('type','payment_getway')->where('featured', 1)->whereHas('actives_getway',function($q){
                return $q->where('status',1);
            })->get();
            return response()->json($cod, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }
}
