<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	public $timestamps = true;
	
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
    public function feature_page()
    {
        return $this->belongsTo('App\ProductFeature','fp_id');
    }
}
