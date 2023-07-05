<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class DiscountGroup extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        DiscountGroup::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_discount_groups';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function discount()
    {
        return $this->belongsTo('App\Models\LMS\Discount', 'discount_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\LMS\Group', 'group_id', 'id');
    }
}
