<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureSectionElement extends Model
{
    public $timestamps = false;
    protected $table = 'feature_page_section_element';
    protected $fillable = [
        'feature_page_detail_id',
        'title',
        'image',
        'video_url',
        'text',
        'btn_text',
        'btn_url',
        'btn_text_1',
        'btn_url_1',
        'serial_number',
    ];
    
    public function section()
    {
    	return $this->belongsto('App\ProductFeatureDetail','feature_page_detail_id');
    }
    public function scopeElementImage($query)
    {
        return $query->whereNotNull('image');
    }
    public function scopeElementContent($query)
    {
        return $query->whereNull('image');
    }
    public function scopeElementImageTitleNull($query)
    {
        return $query->whereNotNull('image')->where('title','=','')->whereNull('text');
    }
}
