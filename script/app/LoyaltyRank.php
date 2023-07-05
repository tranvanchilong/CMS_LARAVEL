<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoyaltyRank extends Model
{

    protected $casts = [
        'content' => 'array'
    ];

    public function getContentAttribute($value)
    {
        return json_decode($value, true);
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function loyalties()
    {
        return $this->hasMany('App\Loyalty', 'loyalty_rank_id', 'id');
    }
}
