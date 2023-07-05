<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class FeatureWebinar extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        FeatureWebinar::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    
    use Translatable;

    protected $dateFormat = 'U';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $table = 'lms_feature_webinars';

    static $pages = ['categories', 'home', 'home_categories'];

    public $translatedAttributes = ['description'];

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }
}
