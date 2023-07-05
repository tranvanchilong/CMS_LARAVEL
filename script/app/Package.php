<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
    
    public function category() {
        return $this->belongsTo('App\Category','category_id','id');
    }
}
