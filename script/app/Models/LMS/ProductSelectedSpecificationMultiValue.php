<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductSelectedSpecificationMultiValue extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductSelectedSpecificationMultiValue::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_product_selected_specification_multi_values';
    public $timestamps = false;
    protected $guarded = ['id'];


    public function selectedSpecification()
    {
        return $this->belongsTo('App\Models\LMS\ProductSelectedSpecification','selected_specification_id','id');
    }

    public function multiValue()
    {
        return $this->belongsTo('App\Models\LMS\ProductSpecificationMultiValue','specification_multi_value_id','id');
    }
}
