<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class DepositMethodController extends Controller
{
    public function get_deposit_method_info()
    {
        try {
            $deposit_method = Category::with('actives_deposit')->where('type','payment_getway')->where('featured', 1)->whereHas('actives_deposit',function($q){
                return $q->where('status_add_money',1);
            })->get();
            return response()->json($deposit_method, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }
}
