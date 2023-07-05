<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductDiscount extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductDiscount::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_product_discounts';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function getRemainingTimes()
    {
        $current_time = time();
        $date = $this->end_date;
        $difference = $date - $current_time;

        return time2string($difference);
    }

    public function discountRemain()
    {
        $count = $this->count;

        $orderItems = ProductOrder::where('discount_id', $this->id)
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            })
            ->count();


        return ($count > 0) ? $count - $orderItems : 0;
    }
}
