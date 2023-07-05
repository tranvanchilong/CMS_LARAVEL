<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cart;
use App\Category;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        try{
            $coupon=Category::where('user_id', domain_info('user_id'))->where('type','coupon')->where('name', $request['code'])->latest()->first();

        }catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
        return response()->json($coupon, 200);

    }

    public function getAllCoupon(Request $request) {
        $coupons = Category::where('user_id', domain_info('user_id'))->where('type', 'coupon')
        ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);;
        return response()->json($coupons, 200);
    }
    public function getCouponExist()
    {
        $coupons = Category::where('user_id', domain_info('user_id'))->where('type', 'coupon')
        ->whereDate('slug', '>=', date('Y-m-d'))
        ->latest()->limit(5)->get();
        $total = count($coupons);
        return response()->json([
            'total_counpon' => $total,
            'data' => $coupons,
        ], 200);
    }
}
