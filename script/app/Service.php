<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function scopeLanguage($query, $language = '')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id', 'LIKE', '%' . $language . '%');
    }
    public function bookings()
    {
        return $this->hasMany('App\Booking', 'service_id', 'id');
    }
    public function categories()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }
}
