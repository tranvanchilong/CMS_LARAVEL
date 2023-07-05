<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';
    protected $guarded = [];
    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
}
