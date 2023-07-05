<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoyaltyBenefit extends Model
{
    protected $table = 'loyalty_benefits';
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
