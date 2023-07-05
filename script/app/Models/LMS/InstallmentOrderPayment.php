<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class InstallmentOrderPayment extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        InstallmentOrderPayment::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_installment_order_payments';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function installmentOrder()
    {
        return $this->belongsTo(InstallmentOrder::class, 'installment_order_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function step()
    {
        return $this->belongsTo(InstallmentStep::class, 'step_id', 'id');
    }
}
