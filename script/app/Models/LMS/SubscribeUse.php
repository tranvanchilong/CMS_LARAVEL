<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class SubscribeUse extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        SubscribeUse::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_subscribe_uses';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    public function subscribe()
    {
        return $this->belongsTo('App\Models\LMS\Subscribe', 'subscribe_id', 'id');
    }

    public function sale()
    {
        return $this->hasOne('App\Models\LMS\Sale', 'id', 'sale_id');
    }

    public function installmentOrder()
    {
        return $this->belongsTo('App\Models\LMS\InstallmentOrder', 'installment_order_id', 'id');
    }
}
