<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'careers';

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function category() {
        return $this->belongsTo('App\Category','category_id');
    }
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
}
