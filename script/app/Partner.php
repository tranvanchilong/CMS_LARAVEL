<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
}
