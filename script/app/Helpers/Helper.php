<?php
namespace App\Helpers;
use Cache;
use CURLFile;
use App\Menu;
use Session;
use DB;
use App\Term;
use App\Order;
use App\Category;
use App\Useroption;
use App\Models\Notifications;
use Auth;
use App\Post;

class Helper
{
    public static $domain;
	public static $full_domain;
	public static $autoload_static_data;
	public static $position;
	public static function domain($domain,$full_domain)
	{

		Helper::$domain=$domain;
		Helper::$full_domain=$full_domain;
		$domain_info=domain_info();

		if ($full_domain==env('APP_URL') || $full_domain==env('APP_URL_WITHOUT_WWW')) {
			return true;
		}
		if ($domain==env('APP_PROTOCOLESS_URL') || str_replace('www.','',$domain)==env('APP_PROTOCOLESS_URL')) {
			return true;
		}



		$domain=str_replace('www.','',$domain);
		Helper::$domain=$domain;
		if (!Cache::has(Helper::$domain)) {

			$value = Cache::remember(Helper::$domain, 300,function () {
				$data=\App\Domain::where('domain',Helper::$domain)->where('status',1)->with('theme')->first();
				if (empty($data)) {
					die();
				}

				$info['domain_id']=$data->id;
				$info['user_id']=$data->user_id;
				$info['domain_name']= Helper::$domain;
				$info['full_domain']= Helper::$full_domain;
				$info['view_path']=$data->theme->src_path;
				$info['asset_path']=$data->theme->asset_path;
				$info['shop_type']=$data->shop_type;
				$info['plan']=json_decode($data->data);
				return $info;
			});
		}

	}

	public static function Optimize($path)
	{
		$file = $path;
		$image_array=explode('/', $file);
		$image_name=end($image_array);
		if (file_exists($path)) {


		$mime = mime_content_type($file);
		$info = pathinfo($file);
		$name = $info['basename'];
		$output = new CURLFile($file, $mime, $name);
		$data = array(
			"files" => $output,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.resmush.it/?qlty=80');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$result = curl_error($ch);
		}
		curl_close ($ch);

		$res=json_decode($result);
		$file=file_get_contents($res->dest);
		\File::put($path,$file);
	  }
	}

	public static function autoload_site_data(){
		if(!Cache::has(domain_info('user_id').'autoload_loaded')){
			$autoload_data=\App\Useroption::where('user_id',domain_info('user_id'))->where('status',1)->where('key','!=','currency')->get();

			if(count($autoload_data) > 0){
				Cache::remember(domain_info('user_id').'autoload_loaded',300,function(){
					return true;
				});
			}
			foreach($autoload_data as $autoload){
				if($autoload->key == 'local'){
					Session::put('locale',$autoload->value);
				}
				else{
					Helper::$autoload_static_data=$autoload->value;
					Cache::remember(domain_info('user_id').$autoload->key, 300,function () {
						return Helper::$autoload_static_data;
					});
				}
	        }

	    }
	}

	public static function autoload_main_site_data(){

		if(!Cache::has('site_info')){
			$site_info=\App\Option::where('key','company_info')->first();
			if(!empty($site_info)){
				Helper::$autoload_static_data=json_decode($site_info->value);
				Cache::remember('site_info', 300,function () {
					return Helper::$autoload_static_data;

				});
			}
		}

		if(!Cache::has('marketing_tool')){
			$marketing_tool=\App\Option::where('key','marketing_tool')->first();
			if(!empty($marketing_tool)){
				Helper::$autoload_static_data=json_decode($marketing_tool->value);
				Cache::remember('marketing_tool', 300,function () {
					return Helper::$autoload_static_data;

				});
			}
		}

		if(!Cache::has('active_languages')){
			$marketing_tool=\App\Option::where('key','active_languages')->first();
			if(!empty($marketing_tool)){
				Helper::$autoload_static_data=json_decode($marketing_tool->value);
				Cache::remember('active_languages', 300,function () {
					return Helper::$autoload_static_data;

				});
			}
		}



	}




	public static function menu_query($menu_position){
		// Helper::$position=$menu_position;
		// return $menus=cache()->remember($menu_position.'menu'.domain_info('user_id'), 300, function () {
		// 	$user_id=domain_info('user_id');
		// 	$menus=Menu::where('position',Helper::$position)->where('user_id',$user_id)->language(language_active())->first();
		// 	return $menus=json_decode($menus->data ?? '');
		// });
		Helper::$position=$menu_position;
		$user_id=domain_info('user_id');
		$menus=Menu::where('position',Helper::$position)->where('user_id',$user_id)->language(language_active())->first();
		$menus = json_decode($menus->data ?? '');
		return $menus;
	}
	public static function menu_query_with_name($menu_position){
		// Helper::$position=$menu_position;
		// return $menus=cache()->remember($menu_position.'menu'.domain_info('user_id'), 300, function () {
		// 	$user_id=domain_info('user_id');
		// 	$menus=Menu::where('position',Helper::$position)->where('user_id',$user_id)->language(language_active())->first();
		// 	$data['data'] = json_decode($menus->data ?? '');
		// 	$data['name'] = $menus->name ?? '';
		// 	return $data;
		// });
		Helper::$position=$menu_position;
		$user_id=domain_info('user_id');
		$menus=Menu::where('position',Helper::$position)->where('user_id',$user_id)->language(language_active())->first();
		$data['data'] = json_decode($menus->data ?? '');
		$data['name'] = $menus->name ?? '';
		return $data;
	}


	public static function test()
    {
    	\Webmozart\Assert\Assert::Asst();
        \Laravel\Sanctum\Sanctum::test();
    }

	public static function get_products_all(){
        $products=Term::where('type','product')->where('user_id', domain_info('user_id'))->where('status','1')->with('prices','preview','category','options','stocks','affiliate','discount')->get();
		$now = strtotime(date('Y-m-d'));
		foreach($products as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$products[$key]->price->special_price = 0;
			}
		}
		$data=collect();
        foreach ($products as $row) {
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
        return [
            'status' => true,
            'products' => $data
        ];
    }
    public static function get_all_product_active(){
        $products=Term::where('type','product')->where('user_id', domain_info('user_id'))->where('status','1')->with('stocks','stock_single','price_single', 'prices')->withCount('order')->get();
		$now = strtotime(date('Y-m-d'));
        $productInfo = array();
        // $data = array();
		foreach($products as $key=>$product){
            $skus = array();
            // if(count($product->stocks)>1)
            // {
            //     foreach($product->stocks as $indexStock=>$sku)
            //     {
            //         array_push($skus, [
            //             'sku' => $sku->sku,
            //             'term_id' =>$sku->term_id,
            //             'variation_id_code' => json_encode($sku->variation_id_code),
            //             'stock_info'=>[
            //                 'quantity'=>$sku->stock_qty,
            //         ],
            //             'price_info'=>[
            //                 'price' =>$product->prices[$indexStock]->price,
            //                 'regular_price' =>$product->prices[$indexStock]->regular_price,
            //                 'special_price'=>$product->prices[$indexStock]->special_price,
            //                 // 'sku' => $prices->variants_price[$index]->sku
            //         ],]);
            //     }
            // }

            // else{
            //     array_push($skus, [
            //         'sku' =>$product->stock_single->sku,
            //         'term_id' =>$product->stock_single->term_id,
            //         'variation_id_code' => json_encode($product->stock_single->variation_id_code),
            //         'stock_info'=>[
            //             'quantity'=>$product->stock_single->stock_qty,
            //     ],
            //         'price_info'=>[
            //             'price' =>$product->price_single->price,
            //             'regular_price' =>$product->price_single->regular_price,
            //             'special_price'=>$product->price_single->special_price,
            //     ],
            // ]);
            // }
            foreach($product->stocks as $indexStock=>$stock)
            {
                array_push($skus, [
                    'sku' => $stock->sku,
                    'variation_id_code' => json_encode($stock->variation_id_code),
                    'term_id' =>$stock->term_id,
                    'stock_info'=>[[
                        'quantity'=>$stock->stock_qty,
                ]],
                    'price_info'=>[
                        'price' =>$product->prices[$indexStock]->price,
                        'regular_price' =>$product->prices[$indexStock]->regular_price,
                        'special_price'=>$product->prices[$indexStock]->special_price,
                        // 'sku' => $prices->variants_price[$index]->sku
                ],
            ]);
            }

            if(empty($skus)){
                array_push($skus, [
                    'sku' => $product->stock_single->sku,
                    'variation_id_code' => json_encode($product->stock_single->variation_id_code),
                    'term_id' =>$product->stock_single->term_id,
                    'stock_info'=>[[
                        'quantity'=>$product->stock_single->stock_qty,
                ]],
                    'price_info'=>[
                        'price' =>$product->price_single->price,
                        'regular_price' =>$product->price_single->regular_price,
                        'special_price'=>$product->price_single->special_price,
                        // 'sku' => $prices->variants_price[$index]->sku
                ],
            ]);
            }
            array_push($productInfo,[
                'id' => $product->id,
                "title"=> $product->title,
                "slug"=> $product->slug,
                "user_id"=> $product->user_id,
                "image" => $product->image,
                "skus" => $skus,
                "status" => $product->status,
                "brand_id" =>$product->brand_id
            ]);
        }

        return [
            'status' => true,
            'products' => $productInfo
        ];
    }

	public static function get_latest_product($limit=10, $offset=1){
        $latest_product=Term::active()->where('type','product')->where('user_id', domain_info('user_id'))->select('id','title','slug','price_status')->with('preview','category','options','affiliate')->latest()->take($limit)->get();
		$now = strtotime(date('Y-m-d'));
		foreach($latest_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$latest_product[$key]->price->special_price = 0;
			}
		}
		
        return [
            'status' => true,
            'total_size' => $latest_product->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $latest_product
        ];
    }

	public static function get_featured_product($limit=10, $offset=1){

       	$featured_product=Term::where('status',1)->where('type','product')->where('featured',1)->where('user_id', domain_info('user_id'))->with('prices','preview','attributes','category','options','stock','affiliate','discount')->withCount('reviews')->latest()->take($limit)->get();
       	$now = strtotime(date('Y-m-d'));
		foreach($featured_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$featured_product[$key]->price->special_price = 0;
			}
		}
		$data = collect();
        foreach ($featured_product as $row) {
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
       	return [
            'status' => true,
            'total_size' => $featured_product->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $data
        ];
	}

	public static function get_deal_of_day_product($limit=10, $offset=1){

		$featured_product=Term::where('status',1)->where('type','product')->where('user_id', domain_info('user_id'))->with('prices','preview','attributes','category','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
		$now = strtotime(date('Y-m-d'));
		foreach($featured_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$featured_product[$key]->price->special_price = 0;
			}
		}
		$data=collect();
        foreach ($featured_product as $row) {
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
		return [
		 'status' => true,
		 'total_size' => $featured_product->count(),
		 'limit' => (integer)$limit,
		 'offset' => (integer)$offset,
		 'products' => $data
	 ];
 }

	public static function get_best_selling($limit=10, $offset=1){

		$best_sell_product = Term::where('status',1)->where('type','product')->where('featured',2)->where('user_id', domain_info('user_id'))->with('preview','attributes','category','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
		$now = strtotime(date('Y-m-d'));
		foreach($best_sell_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$best_sell_product[$key]->price->special_price = 0;
			}
		}
		$data=collect();
        foreach ($best_sell_product as $row) {
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
		return [
            'status' => true,
            'total_size' => $best_sell_product->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $data
        ];
	}

	public static function get_new_latest_product($limit=10, $offset=1){
		$new_latest_product = Term::where('status',1)->where('type','product')->where('featured', 0)->where('user_id', domain_info('user_id'))->with('preview','attributes','category','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
		$now = strtotime(date('Y-m-d'));
		foreach($new_latest_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$new_latest_product[$key]->price->special_price = 0;
			}
		}
		$data=collect();
        foreach ($new_latest_product as $row) {
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
		return [
            'status' => true,
            'total_size' => $new_latest_product->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $data
        ];
	}

	public static function get_top_rated_product($limit=10, $offset=1){
		$top_rated_product = Term::where('status',1)->where('type','product')->where('user_id', domain_info('user_id'))->orderBy('reviews_count', 'DESC')->withCount('reviews')->latest()->take($limit)->get();
		$now = strtotime(date('Y-m-d'));
		foreach($top_rated_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$top_rated_product[$key]->price->special_price = 0;
			}
		}
		$data=collect();
        foreach ($top_rated_product as $row) {
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
		return [
            'status' => true,
            'total_size' => $top_rated_product->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $data
        ];
	}

	public static function get_discounted_product($limit=10, $offset=1){
		$top_rated_product = Term::where('status',1)->where('type','product')->where('user_id', domain_info('user_id'))->with(['discount'])->withCount('reviews')->latest()->take($limit)->get();
		$now = strtotime(date('Y-m-d'));
		foreach($top_rated_product as $key=>$product){
			if(
				($product->price->starting_date && $now < strtotime($product->price->starting_date)) ||
				($product->price->ending_date && $now > strtotime($product->price->ending_date))
			){
				$top_rated_product[$key]->price->special_price = 0;
			}
		}
		$data=collect();
        foreach ($top_rated_product as $row) {
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
		return [
            'status' => true,
            'total_size' => $top_rated_product->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $data
        ];
	}

	public static function track_order($order_id)
    {
        $order = Order::where(['id' => $order_id])->where('user_id', domain_info('user_id'))->with('customer')->withCount('order_items')->first();

        return $order;
    }

	public static function get_shipping_methods($seller_id, $type)
    {
        if ($type == 'admin') {
            return Category::where('type', 'method')->where(['is_admin' => 1])->get();
        } else {
            return Category::where('type', 'method')->where(['id' => $seller_id, 'is_admin' => $type])->get();
        }
    }

	public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }

	public static function send_push_notif_to_topic($data)
	{
		$key_firebase = Useroption::where('user_id', Auth::id())->where(['key' => 'push_firebase' ])->first()->value;

		$url = "https://fcm.googleapis.com/fcm/send";
		$header = ["authorization: key=" . $key_firebase . "",
            "content-type: application/json",
        ];

        $image = asset($data->image);
        $postdata = '{
            "to" : "/topics/shiva",
            "data" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $image . '",
                "is_read": 0
              },
              "notification" : {
                "title":"' . $data->title . '",
                "body" : "' . $data->description . '",
                "image" : "' . $image . '",

                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';
		$headers = array(
			'Content-Type:application/json',
			'Authorization:key=' . $key_firebase
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
	}

	public static function order_status_update_message($status)
    {
        if ($status == 'pending') {
            $data = '{"status":"1","message":"Order Awaiting processing"}';
        } elseif ($status == 'processing') {
            $data = $data = '{"status":"1","message":"Order Processing"}';
        } elseif ($status == 'ready-for-pickup') {
            $data = $data = '{"status":"1","message":"Order Ready for pickup"}';
        } elseif ($status == 'completed') {
            $data = $data = '{"status":"1","message":"Order Completed"}';
        } elseif ($status == 'archived') {
            $data = '{"status":"1","message":"Order Archived"}';
        } elseif ($status == 'canceled') {
            $data = '{"status":"1","message":"Order Canceled"}';
        } else {
            $data = '{"status":"0","message":""}';
        }

        $res = json_decode($data, true);

        if ($res['status'] == 0) {
            return 0;
        }
        return $res['message'];
    }

	public static function send_push_notif_to_device($fcm_token, $data)
	{
		$key_firebase = Useroption::where('user_id', Auth::id())->where(['key' => 'push_firebase' ])->first()->value;

		$url = "https://fcm.googleapis.com/fcm/send";
		$header = ["authorization: key=" . $key_firebase . "",
            "content-type: application/json",
        ];

		if (isset($data['order_no']) == false) {
            $data['order_no'] = null;
        }

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_no":"' . $data['order_no'] . '",
                "is_read": 0,
                "notification_type" : "2"
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_no":"' . $data['order_no'] . '",
                "title_loc_key":"' . $data['order_no'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default",
				"notification_type" : "2"
              }
        }';
		$headers = array(
			'Content-Type:application/json',
			'Authorization:key=' . $key_firebase
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
	}

	public static function get_blogs_latest($limit=10, $offset=1)
	{
		$blogs = Post::where('user_id',domain_info('user_id'))->where('status',1)->blog()->with('bcategory')->latest()->take($limit)->get();

		return [
            'status' => true,
            'total_size' => $blogs->count(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'blog' => $blogs
        ];
	}

    public static function createImg($image, $name, $type, $size, $c_type, $level,$fileName, $path) {
        $im_name = $fileName;
        $path=$path;
        $im_output = $path.$im_name;
        $im_ex = explode('.', $im_output); // get file extension

        // create image
        if($type == 'image/jpeg'){
            $im = imagecreatefromjpeg($image); // create image from jpeg
        }
        elseif($type == 'image/gif'){
           $im = imagecreatefromgif($image);
        }
        elseif($type == 'image/png'){
            $im=imagecreatefrompng($image);
        }
        elseif($type == 'image/webp'){
            $im=imagecreatefromwebp($image);
        }
        else{
           $im = imagecreatefromjpeg($image);
        }

        // compree image
        if($c_type){
            $im_name = str_replace(end($im_ex), 'jpg', $im_name);
            $im_name = str_replace(end($im_ex), 'png', $im_name);
            $im_name = str_replace(end($im_ex), 'gif', $im_name);
            $im_name = str_replace(end($im_ex), 'jpeg', $im_name); // replace file extension
            $im_output = str_replace(end($im_ex), 'webp', $im_output); // replace file extension

            if(!empty($level)){
                imagewebp($im, $im_output, 60); // if level = 2 then quality = 80%
            }else{
                imagewebp($im, $im_output, 100); // default quality = 100% (no compression)
            }
            $im_type = 'image/webp';
            // image destroy
            imagedestroy($im);
        }
        else{

        }



        // output original image & compressed image
        $im_size = filesize($im_output);
        $info = array(
                'name' => $im_name,
                'image' => $im_output,
                'type' => $im_type,
                'size' => $im_size
        );
        return $info;
    }



}


 ?>
