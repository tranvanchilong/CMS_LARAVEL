<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductOrder extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductOrder::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_product_orders';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $status = ['pending', 'waiting_delivery', 'shipped', 'success', 'canceled'];
    static $waitingDelivery = 'waiting_delivery';
    static $shipped = 'shipped';
    static $success = 'success';
    static $canceled = 'canceled';
    static $pending = 'pending';

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo('App\Models\LMS\User', 'seller_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo('App\Models\LMS\User', 'buyer_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\LMS\Sale', 'sale_id', 'id');
    }

    public function gift()
    {
        return $this->belongsTo('App\Models\LMS\Gift', 'gift_id', 'id');
    }
}
