<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use DB;

class CategoryController extends Controller
{
    public function get_categories()
    {
        try {
            $category = Category::select(
                'id', 
                'name',
                'slug',
                DB::raw("0 as parent_id"),
                DB::raw("0 as position"),
                'created_at',
                'updated_at',
                'menu_status as home_status',
                DB::raw("0 as priority")
            )
            ->where('type','category')->where('user_id', domain_info('user_id'))->with('childrenCategories')->get();
            return $category;
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products($domain, $id)
    {
        try {
            $category= Category::where('type','category')->where('user_id', domain_info('user_id'))->with('product')->findOrFail($id);
            
            return response()->json([$category], 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }

    }
}
