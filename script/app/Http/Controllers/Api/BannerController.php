<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Category;

class BannerController extends Controller
{
    public function get_banners(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_type' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=> false, 'error'=>$validator->errors()], 401);            
        }
        if($request->banner_type == 'all'){
            $banner = 'banner';
        } elseif($request->banner_type == 'main_banner'){
            $banner = 'banner_ads';
        } elseif($request->banner_type == 'footer_banner'){
            $banner = 'banner_ads_2';
        } elseif($request->banner_type == 'main_section_banner'){
            $banner = 'banner_ads_3';
        }elseif($request->banner_type == 'slider'){
            $banner = 'slider';
        }elseif($request->banner_type == 'offer_ads'){
            $banner = 'offer_ads';
        }

        return Category::where('type','LIKE','%'.$banner.'%')->where('user_id', domain_info('user_id'))->with('excerpt')->get()->map(function($q){
            $data['id']=$q->id;
            $data['photo']=($q->name);
            $data['banner_type']=($q->type);  
            $data['published']=1; 
            $data['url']=asset($q->slug);
            $data['created_at']=($q->created_at);
            $data['updated_at']=($q->updated_at);
            $data['resource_type']="product";
            $data['resource_id']=null;
            $data['product']=null;
            $data['meta']=json_decode($q->excerpt->content ?? '');
            return $data;
        });
    }
}