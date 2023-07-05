<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function bookings()
    {
        return $this->hasMany('App\Booking', 'location_id', 'id');
    }

    public function getAddress()
    {
        return $this->address . ', ' . $this->state . ', ' . $this->state . ', ' . $this->city . ', ' . $this->country;
    }
    public function favorite()
    {
        return $this->hasMany('App\CustomerFavoriteLocation', 'location_id', 'id');
    }
}
