<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Term;
use App\Models\Customer;
use App\Orderitem;
use App\Category;
use App\Domain;
use App\Order;
use App\Stock;
use App\User;
use App\Models\Price;
use Carbon\Carbon;
use App\Models\Review;
use App\Helpers\Helper;
use Cart;
use Auth;
use DB;

class ProductController extends Controller

{
    public $cats;

    public function get_latest_products(Request $request){
        $latest_product = Helper::get_latest_product($request['limit'], $request['offset']);
        return response()->json($latest_product, 200);
    }

    public function get_top_rated_products(Request $request){
        $top_rated_product = Helper::get_top_rated_product($request['limit'], $request['offset']);
        return response()->json($top_rated_product, 200);
    }

    public function get_new_latest_products(Request $request){
        $new_latest_product = Helper::get_new_latest_product($request['limit'], $request['offset']);
        return response()->json($new_latest_product, 200);
    }

    public function get_featured_products(Request $request){
        $featured_product = Helper::get_featured_product($request['limit'], $request['offset']);
        return response()->json($featured_product, 200);
    }

    public function get_best_sellings(Request $request){
        $best_sell_product = Helper::get_best_selling($request['limit'], $request['offset']);
        return response()->json($best_sell_product, 200);
    }

    public function get_discounted_products(Request $request){
        $discounted_product = Helper::get_discounted_product($request['limit'], $request['offset']);
        return response()->json($discounted_product, 200);
    }

    public function get_product($domain, $slug, $id){

        $product_detail = Term::where('type','product')->select('id','title','slug','user_id','featured','status','price_status')->with(['variants_price','attributes','options'])->where(['slug' => $slug])->where('user_id', domain_info('user_id'))->findorFail($id);

        $now = strtotime(date('Y-m-d'));
			if(
				($product_detail->price->starting_date && $now < strtotime($product_detail->price->starting_date)) ||
				($product_detail->price->ending_date && $now > strtotime($product_detail->price->ending_date))
			){
				$product_detail->price->special_price = 0;
			}
        $attr = [];
        $stt=0;
        $attributes = $product_detail->attributes->groupBy('category_id');
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

        $product_detail = collect($product_detail)->put('attr', $attr);
        return response()->json($product_detail, 200);
    }

    public function get_product_reviews($doamin, $id){

        $reviews = Review::where(['term_id' => $id])->where('user_id', domain_info('user_id'))->get();

        $data=[];
    	foreach($reviews as $review){
    		array_push($data,$review);
    	}

        return response()->json($data, 200);
    }

    public function get_home_categories(){
        $categories = Category::select(
            'id',
            'name',
            'slug',
        )->where('type','category')->where('menu_status',1)->where('user_id', domain_info('user_id'))->with(['products'])->take(1)->get();

        return response()->json($categories, 200);
    }

    public function get_searched_products(Request $request,$limit=10, $offset=1)
    {

        if($request->name){
            $posts=Term::where('status',1)->where('type','product')->where('title','LIKE','%'.$request->name.'%')->where('user_id', domain_info('user_id'))->select('id','title','slug')->latest()->paginate(30);
        }else{
            $posts=Term::where('status',1)->where('type','product')->where('user_id', domain_info('user_id'))->select('id','title','slug')->latest()->paginate(30);
        }

        $data=collect();
        foreach ($posts as $row) {
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
        return response()->json( [
            'total_size' => $posts->total(),
            'products' => $data
        ], 200);
    }

    public function get_related_products(Request $request, $domain, $product_id)
    {
        $user_id=domain_info('user_id');

    	$this->cats=$request->categories ?? [];
    	$avg=Review::where('term_id',$request->term)->avg('rating');
    	$ratting_count=Review::where('term_id',$request->term)->count();
    	$avg=(int)$avg;
    	$related=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->whereHas('post_categories',function($q){
            $q->whereIn('category_id',$this->cats);
        })->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take(20)->get();

    	 $data['related_products']=$this->related_products();
    	 $data['ratting_count']=$ratting_count;
    	 $data['ratting_avg']=$avg;

    	 return response()->json($data);
    }

    public function social_share_link($domain, $product_slug)
    {
        $product = Term::where('type','product')->with('preview')->where('user_id', domain_info('user_id'))->where('slug', $product_slug)->first();
        $link =  url('/product/'.$product->slug.'/'.$product->id);
        try {
            return response()->json($link, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function get_shipping_method(){
        $methods = Category::where('type','method')->where('user_id', domain_info('user_id'))->get();
        return response()->json($methods, 200);
    }

    public function submit_product_review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'term_id' => 'required',
            'comment' => 'required',
            'rating' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);
        }
        $customer = Auth::user();
        $customer = Customer::where('id', $customer->id)->where('created_by', domain_info('user_id'))->first();
        $review = new Review;
        $review->user_id = $customer->created_by;
        $review->term_id = $request->term_id;
        $review->rating = $request->rating;
        $review->name = $request->name;
        $review->email = $request->email;
        $review->comment = $request->comment;

        $review->save();

        return response()->json(['message' => 'successfully review submitted!', 'data' =>$review], 200);
    }

    public function counter($domain, $id)
    {
        try {
            $term = Term::where('id', $id)->where('user_id', domain_info('user_id'))->withCount('order')->first();
            $countOrder = Orderitem::where('term_id', $term->id)->count();
            $countWishlist = Review::where('user_id', domain_info('user_id'))->where('term_id', $term->id)->count();
            return response()->json(['order_count' => $countOrder, 'wishlist_count' => $countWishlist], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function related_products($limit=4)
    {
       $user_id=domain_info('user_id');
       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
       return $posts;

    }

    public function get_attributes()
    {
      $user_id=domain_info('user_id');
      $posts=Category::where('user_id',$user_id)->where('type','parent_attribute')->where('featured',1)->with('value')->get();

      return $posts;
    }

    public function get_products_all(Request $request){
        $products = Helper::get_products_all();
        return response()->json($products, 200);
    }
    public function get_all_product_active(Request $request){
        $products = Helper::get_all_product_active();
        return response()->json($products, 200);
    }
    public function get_details_product(Request $request)
    {
        $product_detail = Term::where('type','product')->with(['attr','attributes','stocks','prices','stock_single','price_single'])->where('user_id', domain_info('user_id'))->find($request->id);
        if(empty($product_detail))
        return response()->json( [
            'data' => [],
        ], 200);

        $stocks=Term::with('stocks','stock_single')->where('user_id', domain_info('user_id'))->find($request->id);
        $prices=Term::with('prices','price_single')->where('user_id',domain_info('user_id'))->find($request->id);

        $data = array();
        $skus = array();
        $index = 0;
        foreach($stocks->stocks as $stock)
        {
            array_push($skus, [
                'sku' => $stock->sku,
                'variation_id_code' => json_encode($stock->variation_id_code),
                'term_id' =>$stock->term_id,
                'status' =>$product_detail->status,
                'brand_id' =>$product_detail->brand_id,
                'stock_info'=>[[
                    'quantity'=>$stock->stock_qty,
            ]],
                'price_info'=>[
                    'price' =>$prices->prices[$index]->price,
                    'regular_price' =>$prices->prices[$index]->regular_price,
                    'special_price'=>$prices->prices[$index]->special_price,
                    // 'sku' => $prices->variants_price[$index]->sku
            ],
        ]);
        $index++;
        }

        if(empty($skus)){
            array_push($skus, [
                'sku' => $stocks->stock_single->sku,
                'variation_id_code' => json_encode($stocks->stock_single->variation_id_code),
                'term_id' =>$stocks->stock_single->term_id,
                'status' =>$product_detail->status,
                'brand_id' =>$product_detail->brand_id,
                'stock_info'=>[[
                    'quantity'=>$stocks->stock_single->stock_qty,
            ]],
                'price_info'=>[
                    'price' =>$prices->price_single->price,
                    'regular_price' =>$prices->price_single->regular_price,
                    'special_price'=>$prices->price_single->special_price,
                    // 'sku' => $prices->variants_price[$index]->sku
            ],
        ]);
        }
        $brand=array();
        if(!isset($product_detail->brand_id))
        {
            array_push($brand,[
                "name" => $product_detail->brand_id[0]->name,
                "image" => $product_detail->brand_id[0]->image
            ]);
        }



        return response()->json( [
            'data' => [
                "id"=> $product_detail->id,
            "title"=>$product_detail->title,
            "slug"=>$product_detail->slug,
            "image" => $product_detail->image,
            "user_id"=>$product_detail->user_id,
            "brand_id" =>$brand,
            "skus" => $skus
            ],
        ], 200);
    }
    public function updateProduct(Request $request)
    {
        $sku = $request->sku;
        $token = $request->token;
        $term_id = $request->term_id;
        $email = $request->email;
        $quantity = $request->quantity;
        $priceRequest = $request->price;
        $checkToken = User::where('email', $email)->where('shop_sync_token', $token)->get();

        if($checkToken)
        {
            Stock::where('term_id',$term_id)->where('sku',$sku)->update(['stock_qty'=>$quantity]);
            $priceInfo = Price::where('term_id',$term_id)->where('sku',$sku)->first();
            if($priceInfo['special_price_start'] <= Carbon::now()->format('Y-m-d') && $priceInfo['special_price'] != null){
                if($priceInfo['special_price'] != null){
                    if($priceInfo['price_type'] == 1){
                        $price=$priceRequest - $priceInfo['special_price'];
                    }else{
                        $percent = $priceRequest * $priceInfo['special_price'] / 100;
                        $price = $priceRequest - $percent;
                        $price =str_replace(',','',number_format($price,2));
                    }
                }else{
                    $price = $priceRequest;
                }
            }else{
                $price = $priceRequest;
            }
            $data['price']=$price;
            $data['regular_price']=$priceRequest;
            $data['special_price']=$priceInfo['special_price'];
            $data['price_type']=$priceInfo['price_type'];
            $data['starting_date']=$priceInfo['special_price_start'];
            $data['ending_date']=$priceInfo['special_price_end'];
            $data['created_at']=Carbon::now();
            Price::where('term_id',$term_id)->where('sku',$sku)->update($data);
            return response()->json( [
                'data' => [],
            ], 200);
        }

        return response()->json( [
            'data' => 'fail',
        ], 200);
    }
}
