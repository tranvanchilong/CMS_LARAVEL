<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class AdvertisingBanner extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        AdvertisingBanner::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_advertising_banners';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'image'];


    static $positions = [
        'home1', 'home2', 'course', 'course_sidebar', 'product_show', 'bundle', 'bundle_sidebar', 'upcoming_course', 'upcoming_course_sidebar'
    ];

    static $size = [
        '12' => 'full',
        '6' => '1/2',
        '4' => '1/3',
        '3' => '1/4'
    ];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getImageAttribute()
    {
        return getTranslateAttributeValue($this, 'image');
    }
}
