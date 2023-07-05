<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Price;
use App\Term;
use App\Category;
use DB;

class FlashDealController extends Controller
{
    public function get_flash_deal()
    {
        try {
            $term = Term::where('type','product')->where('user_id', domain_info('user_id'))->pluck('id')->toArray();
            
            $flash_deals = Price::select(
                'id', 
                DB::raw("'' as title"),
                'starting_date as start_date',
                'ending_date as end_date',
                DB::raw("1 as status"),
                DB::raw("0 as featured"),
                DB::raw("null as background_color"),
                DB::raw("null as text_color"),
                DB::raw("'test.jpg' as banner"),
                DB::raw("'' as slug"),
                'created_at',
                'updated_at',
                'term_id as product_id',
                DB::raw("'flash_deal' as deal_type")
            )->whereNotNull('special_price')
                ->whereDate('starting_date', '<=', date('Y-m-d'))
                ->whereDate('ending_date', '>=', date('Y-m-d'))->whereIn('term_id', $term)->first();
            return response()->json($flash_deals, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function get_products($domain, $id)
    {
        $flash_deals = Price::where(['term_id' => $id])->whereNotNull('special_price')
        ->pluck('term_id')->toArray();

        $flash_deals_product = Term::select('id',DB::raw("'admin' as added_by"),'user_id','title','slug',DB::raw("'kg' as unit"))->with(['variants_price','category_ids'])->whereIn('id', $flash_deals)->get();

        $now = strtotime(date('Y-m-d'));
		foreach($flash_deals_product as $key=>$product){
            if(
                ($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
                ($product->price->ending_date && $now > strtotime($product->price->ending_date))
            ){
                $flash_deals_product[$key]->price->special_price = 0;
            }   
        }
        $data=collect();
        foreach ($flash_deals_product as $row) {
            $attr = [];
            $stt=0;
            $attributes = $row->attributes->groupBy('category_id');
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
            
            $data = $data->push(collect($row)->put('attr', $attr));
        }

        return response()->json($data, 200);
    }

    public function flash_deal_feature($domain)
    {
        try {
            $term = Term::where('type','product')->where('user_id', domain_info('user_id'))->pluck('id')->toArray();
            $flash_deals = Price::where('special_price', '>=', 100000)
                ->whereDate('starting_date', '<=', date('Y-m-d'))
                ->whereDate('ending_date', '>=', date('Y-m-d'))->select('id','term_id as product_id','price','regular_price','special_price','price_type','starting_date','ending_date','created_at','updated_at')->with('product')->whereIn('term_id', $term)->get();
            return response()->json($flash_deals, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function get_deal_of_the_day_product(Request $request)
    {
        $deal_of_the_day = Helper::get_deal_of_day_product($request['limit'], $request['offset']);
        return response()->json($deal_of_the_day, 200);
        
    }
}
