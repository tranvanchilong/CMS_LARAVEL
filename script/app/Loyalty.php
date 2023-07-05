<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    protected $fillable = ['loyalty_rank_id'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }
    public function loyaltyRank()
    {
        return $this->hasOne('App\LoyaltyRank', 'id', 'loyalty_rank_id');
    }
}
