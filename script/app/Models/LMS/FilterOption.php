<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class FilterOption extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        FilterOption::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_filter_options';
    public $timestamps = false;
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }
}
