<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductSpecification extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductSpecification::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_product_specifications';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $inputTypes = ['textarea', 'multi_value'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    public function categories()
    {
        return $this->hasMany('App\Models\LMS\ProductSpecificationCategory', 'specification_id', 'id');
    }

    public function multiValues()
    {
        return $this->hasMany('App\Models\LMS\ProductSpecificationMultiValue', 'specification_id', 'id');
    }

    public function createName()
    {
        return str_replace(' ', '_', $this->title);
    }
}
