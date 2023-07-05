<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use DB;

class BrandController extends Controller
{
    public function get_brands()
    {
        try {
            $brands = Category::select(
                'id', 
                'name',
                'created_at',
                'updated_at',
                'menu_status as status',
                DB::raw("1 as brand_products_count")
            )->where('type','brand')->where('user_id', domain_info('user_id'))->get();
            
            return response()->json($brands, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products($domain, $brand_id)
    {
        try {
            $brands = Category::where('type','brand')->where('user_id', domain_info('user_id'))->with('product')->findOrFail($brand_id);

            return response()->json([$brands],200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }

    }
}
