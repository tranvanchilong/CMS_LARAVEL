<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $table = 'feature_page';
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'meta_description',
        'meta_keyword',
        'status',
        'header_status',
        'footer_status'
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function sections()
    {
    	return $this->hasMany('App\ProductFeatureDetail','feature_page_id');
    }
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
    public function menu()
    {
        return $this->hasOne('App\Menu','fp_id');
    }
}
