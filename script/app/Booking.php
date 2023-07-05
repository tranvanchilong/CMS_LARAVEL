<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Booking Status
     * 1 - new, 2 - confirm, 3-complete, 4-cancel
     */
    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public function locations()
    {
        return $this->hasOne('App\Location', 'id', 'location_id');
    }

    public function services()
    {
        return $this->hasOne('App\Service', 'id', 'service_id');
    }

    public function category_services()
    {
        return $this->hasOne('App\Category', 'id', 'category_service_id');
    }
}
