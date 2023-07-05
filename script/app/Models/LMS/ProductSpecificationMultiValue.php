<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductSpecificationMultiValue extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductSpecificationMultiValue::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_product_specification_multi_values';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function createName()
    {
        return str_replace(' ', '_', $this->title);
    }
}
