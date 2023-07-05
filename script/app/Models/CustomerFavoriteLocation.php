<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFavoriteLocation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'customer_favorite_locations';
    protected $primaryKey = 'location_id';
    protected $fillable = [
        'location_id', 'customer_id'
    ];

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public function location()
    {
        return $this->hasOne('App\Location', 'id', 'location_id');
    }
}
