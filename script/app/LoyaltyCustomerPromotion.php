<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCustomerPromotion extends Model
{
    protected $table = 'loyalty_customer_promotions';

    public function loyalty_promotion()
    {
        return $this->hasOne('App\LoyaltyPromotion', 'id', 'loyalty_promotion_id');
    }
    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }
}
