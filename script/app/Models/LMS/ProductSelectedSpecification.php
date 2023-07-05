<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductSelectedSpecification extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductSelectedSpecification::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_product_selected_specifications';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $inputTypes = ['textarea', 'multi_value'];
    static $Active = 'active';
    static $Inactive = 'inactive';
    static $itemsStatus = ['active', 'inactive'];

    public $translatedAttributes = ['value'];

    public function getValueAttribute()
    {
        return getTranslateAttributeValue($this, 'value');
    }


    public function specification()
    {
        return $this->belongsTo('App\Models\LMS\ProductSpecification', 'product_specification_id', 'id');
    }

    public function selectedMultiValues()
    {
        return $this->hasMany('App\Models\LMS\ProductSelectedSpecificationMultiValue', 'selected_specification_id', 'id');
    }
}
