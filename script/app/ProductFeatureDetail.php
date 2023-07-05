<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureDetail extends Model
{
    public $timestamps = false;
    protected $table = 'feature_page_detail';
    protected $fillable = [
        'image',
        'feature_page_id',
        'feature_title',
        'feature_subtitle',
        'feature_position',
        'background_color',
        'feature_status',
        'feature_type',
        'hide_title',
        'data_type',
        'category',
        'serial_number',
    ];
    public function section_elements()
    {
    	return $this->hasMany('App\ProductFeatureSectionElement','feature_page_detail_id')->orderBy('serial_number','ASC');
    }
    public function section_elements_blog_old()
    {
    	return $this->hasMany('App\ProductFeatureSectionElement','feature_page_detail_id')->orderBy('serial_number','ASC')->limit(2);
    }
    public function section_elements_blog_new()
    {
    	return $this->hasMany('App\ProductFeatureSectionElement','feature_page_detail_id')->orderBy('serial_number','DESC')->limit(2);;
    }
    public function section_elements_content()
    {
    	return $this->hasMany('App\ProductFeatureSectionElement','feature_page_detail_id')->elementContent();
    }
    public function section_elements_image()
    {
    	return $this->hasMany('App\ProductFeatureSectionElement','feature_page_detail_id')->elementImage();
    }
    public function section_elements_image_title_null()
    {
    	return $this->hasMany('App\ProductFeatureSectionElement','feature_page_detail_id')->elementImageTitleNull();
    }
    public function page_feature()
    {
    	return $this->belongsto('App\ProductFeature','feature_page_id');
    }
}
