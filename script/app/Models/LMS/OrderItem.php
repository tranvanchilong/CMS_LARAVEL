<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class OrderItem extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        OrderItem::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_order_items';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\LMS\Order', 'order_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Bundle', 'bundle_id', 'id');
    }

    public function subscribe()
    {
        return $this->belongsTo('App\Models\LMS\Subscribe', 'subscribe_id', 'id');
    }

    public function promotion()
    {
        return $this->belongsTo('App\Models\LMS\Promotion', 'promotion_id', 'id');
    }

    public function reserveMeeting()
    {
        return $this->belongsTo('App\Models\LMS\ReserveMeeting', 'reserve_meeting_id', 'id');
    }

    public function registrationPackage()
    {
        return $this->belongsTo('App\Models\LMS\RegistrationPackage', 'registration_package_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }

    public function productOrder()
    {
        return $this->belongsTo('App\Models\LMS\ProductOrder', 'product_order_id', 'id');
    }

    public function installmentPayment()
    {
        return $this->belongsTo(InstallmentOrderPayment::class, 'installment_payment_id', 'id');
    }


    public function ticket()
    {
        return $this->belongsTo('App\Models\LMS\Ticket', 'ticket_id', 'id');
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id', 'id');
    }

    public static function getSeller($orderItem)
    {
        $seller = null;

        if (!empty($orderItem->webinar_id) and empty($orderItem->promotion_id)) {
            $seller = $orderItem->webinar->creator_id;
        } elseif (!empty($orderItem->reserve_meeting_id)) {
            $seller = $orderItem->reserveMeeting->meeting->creator_id;
        } elseif (!empty($orderItem->product_id)) {
            $seller = $orderItem->product->creator_id;
        } elseif (!empty($orderItem->bundle_id)) {
            $seller = $orderItem->bundle->creator_id;
        }

        return $seller;
    }

}
