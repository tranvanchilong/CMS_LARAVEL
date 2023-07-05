<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Term extends Model
{
    protected $appends = [
        'brand_id',
        'unit',
        'min_qty',
        'refundable',
        'flash_deal',
        'video_provider',
        'video_url',
        'colors',
        'image',
        'variant_product',
        'thumbnail',
        'free_shipping',
        'attachment',
        'featured_status',
        'meta_title',
        'meta_description',
        'meta_image',
        'request_status',
        'denied_note',
        'shipping_cost',
        'multiply_qty',
        'temp_shipping_cost',
        'is_shipping_cost_updated',
        'reviews_count',
        'price',
        'description',
        'rating'

    ];


    public function getBrandIdAttribute(){
        return $this->brands;
    }

    public function getUnitAttribute(){
		return 'kg';
	}

    public function getMinQtyAttribute(){
		return 1;
	}

    public function getRefundableAttribute(){
		return 1;
	}

    public function getFlashDealAttribute(){
		return null;
	}

    public function getVideoProviderAttribute(){
		return null;
	}

    public function getVideoUrlAttribute(){
		return null;
	}

    public function getColorsAttribute(){
		return null;
	}

    public function getImageAttribute(){
        $data = [];
        foreach($this->medias as $ac){
            array_push($data, $ac->name);
        }
		return $data;
	}

    public function getVariantProductAttribute(){
		return 0;
	}

    public function getThumbnailAttribute(){
		return 'test.jpg';
	}

    public function getFreeShippingAttribute(){
		return 0;
	}

    public function getAttachmentAttribute(){
		return null;
	}

    public function getFeaturedStatusAttribute(){
		return 1;
	}

    public function getMetaTitleAttribute(){
		return null;
	}

    public function getMetaDescriptionAttribute(){
		return null;
	}

    public function getMetaImageAttribute(){
		return "test.jpg";
	}

    public function getRequestStatusAttribute(){
		return 1;
	}

    public function getDeniedNoteAttribute(){
		return null;
	}

    public function getShippingCostAttribute(){
		return 0;
	}

    public function getMultiplyQtyAttribute(){
		return 0;
	}

    public function getTempShippingCostAttribute(){
		return null;
	}

    public function getIsShippingCostUpdatedAttribute(){
		return null;
	}

    public function getReviewsCountAttribute(){
		return 0;
	}

    public function getPriceAttribute(){
		return $this->deal_of_the_day;
	}

    public function getDescriptionAttribute(){
        $f = json_decode($this->content->value);
		return $f->content ?? '';
	}

    public function getRatingAttribute(){
		return $this->ratings;
	}

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function meta()
	{
		return 	$this->hasOne('App\Meta','term_id','id');
	}


	public function categories()
    {
        return $this->belongsToMany('App\Category','postcategories','term_id','category_id')->where('type','category');
    }
    public function brands()
    {
        return $this->belongsToMany('App\Category','postcategories','term_id','category_id')->where('type','brand');
    }

    public function medias()
    {
        return $this->belongsToMany('App\Media','postmedia','term_id','media_id');
    }

    public function product_categories()
    {
         return $this->hasMany('App\Postcategory','term_id')->where('type','product_category')->with('category')->select('id','term_id','category_id','type');
    }

    public function post_categories()
    {
        return $this->hasMany('App\Postcategory');
    }

    public function product_brand()
    {
         return $this->hasOne('App\Postcategory','term_id')->where('type','brand')->with('category')->select('id','term_id','category_id','type');
    }

    public function postcategory()
    {
        return $this->hasMany('App\Postcategory','term_id');
    }
     
    
    public function Productcategory()
    {
        return $this->hasMany('App\Postcategory','term_id')->where('type','product_category');
    }

    public function category() 
    {
        return $this->hasOne('App\Postcategory')->whereHas('category')->with('category');
    }

    public function category_ids() 
    {
        return $this->belongsToMany('App\Category','postcategories')->where('type','category')->select('id',DB::raw("1 as position"));
    }

    public function Brand()
    {
        return $this->hasOne('App\Postcategory','term_id')->where('type','brand');
    }

  	

	public function user()
	{
		return $this->belongsTo('App\User')->select('name','id');
	}

	
	
	public function seo()
	{
		return $this->hasOne('App\Meta','term_id')->where('key','seo');
	}

    public function affiliate()
    {
        return $this->hasOne('App\Meta','term_id')->where('key','affiliate');
    }



	public function content()
	{
		return $this->hasOne('App\Meta','term_id')->where('key','content');
	}
    
    public function excerpt()
    {
        return $this->hasOne('App\Meta','term_id')->where('key','excerpt');
    }

	public function ratings()
    {
        return $this->hasMany('App\Models\Review')->select(DB::raw('avg(rating) total_rating, term_id'));
    }

	public function attributes()
	{
		return $this->hasMany('App\Attribute','term_id')->with('attribute','variation');
	}
	public function attribute()
	{
		return $this->hasOne('App\Attribute','term_id')->with('attribute','variation');
	}

    public function attributes_relation()
    {
        return $this->hasMany('App\Attribute','term_id');
    }

    public function files()
    {
        return $this->hasMany('App\File','term_id');
    }


    public function preview()
    {
        return $this->hasOne('App\Postmedia')->with('media');
    }

    public function order()
    {
        return $this->hasMany('App\Orderitem');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
    
    public function price()
    {
        return $this->hasOne('App\Models\Price')->where('variation_id_code',null);
    }

    public function price_single()
    {
        return $this->hasOne('App\Models\Price')->where('variation_id_code',null);
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price')->where('variation_id_code','!=',null);
    }
    public function price_product($variation=[])
    {
        if(!$variation){
            return $this->hasOne('App\Models\Price')->where('variation_id_code',null)->first();
        }else{
            $var=[];
            foreach($variation as $key => $item){
                array_push($var,(int)$item);
            }
            $price = $this->hasMany('App\Models\Price')->where('variation_id_code','!=',null);
            $price = $price->where('variation_id_code',json_encode($var))->first();
            return $price;
        }
    }

    public function variants_price()
    {
        return $this->hasMany('App\Models\Price');
    }

    public function attr()
    {
        return $this->hasMany('App\Attribute','term_id')->with('attribute','variation');
    }

    public function deal_of_the_day()
    {
        return $this->hasOne('App\Models\Price');
    }

    public function discount()
    {
        return $this->hasMany('App\Models\Price')->select(DB::raw('special_price, term_id'))->where('special_price','!=', 0);
    }

    public function stocks()
    {
        return $this->hasMany('App\Stock','term_id')->where('variation_id_code','!=',null);
    }

    public function stock()
    {
        return $this->hasOne('App\Stock');
    }

    public function variants_stock()
    {
        return $this->hasMany('App\Stock');
    }

    public function stock_single()
    {
        return $this->hasOne('App\Stock')->where('variation_id_code',null);
    }
    
    public function options()
    {
        return $this->hasMany('App\Models\Termoption')->where('type',1)->with('childrenCategories');
    }
    public function termoption()
    {
        return $this->hasMany('App\Models\Termoption')->where('type',0);
    }
	public function bcategories()
    {
        return $this->belongsToMany('App\Category','postcategories','term_id','category_id')->where('type','bcategory');
    }
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }

    public function hide_price_product(){
        return $this->belongsTo('App\Useroption','user_id','user_id')->where('key','hide_price_product');
    }
}
