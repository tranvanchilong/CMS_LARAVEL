<?php

namespace App\Models\LMS;

use App\Mixins\Cart\CartItemInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\LMS\Scopes\ScopeDomain;

class Cart extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Cart::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = "lms_cart";

    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Bundle', 'bundle_id', 'id');
    }

    public function reserveMeeting()
    {
        return $this->belongsTo('App\Models\LMS\ReserveMeeting', 'reserve_meeting_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\LMS\Ticket', 'ticket_id', 'id');
    }

    public function productOrder()
    {
        return $this->belongsTo('App\Models\LMS\ProductOrder', 'product_order_id', 'id');
    }

    public function subscribe()
    {
        return $this->belongsTo('App\Models\LMS\Subscribe', 'subscribe_id', 'id');
    }

    public function promotion()
    {
        return $this->belongsTo('App\Models\LMS\Promotion', 'promotion_id', 'id');
    }

    public function installmentPayment()
    {
        return $this->belongsTo(InstallmentOrderPayment::class, 'installment_payment_id', 'id');
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id', 'id');
    }

    public static function emptyCart($userId)
    {
        Cart::where('creator_id', $userId)->delete();
    }

    public static function getCartsTotalPrice($carts)
    {
        $totalPrice = 0;

        if (!empty($carts) and count($carts)) {
            foreach ($carts as $cart) {
                if ((!empty($cart->ticket_id) or !empty($cart->special_offer_id)) and !empty($cart->webinar)) {
                    $totalPrice += $cart->webinar->price - $cart->webinar->getDiscount($cart->ticket);
                } else if (!empty($cart->webinar_id) and !empty($cart->webinar)) {
                    $totalPrice += $cart->webinar->price;
                } else if (!empty($cart->bundle_id) and !empty($cart->bundle)) {
                    $totalPrice += $cart->bundle->price;
                } else if (!empty($cart->reserve_meeting_id) and !empty($cart->reserveMeeting)) {
                    $totalPrice += $cart->reserveMeeting->paid_amount;
                } else if (!empty($cart->product_order_id) and !empty($cart->productOrder) and !empty($cart->productOrder->product)) {
                    $product = $cart->productOrder->product;

                    $totalPrice += (($product->price * $cart->productOrder->quantity) - $product->getDiscountPrice());
                }
            }
        }

        return $totalPrice;
    }

    public function getItemInfo()
    {
        if (empty($this->itemInfo)) {
            $cartItemInfo = new CartItemInfo();

            $this->itemInfo = $cartItemInfo->getItemInfo($this);
        }

        return $this->itemInfo;
    }
}
