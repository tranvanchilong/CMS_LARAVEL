<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class DiscountCategory extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        DiscountCategory::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_discount_categories';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function discount()
    {
        return $this->belongsTo('App\Models\LMS\Discount', 'discount_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\LMS\Category', 'category_id', 'id');
    }
}
