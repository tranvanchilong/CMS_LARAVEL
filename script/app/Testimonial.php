<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function scopeLanguage($query,$language='vi')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
}
