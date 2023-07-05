<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarExtraDescription extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarExtraDescription::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_webinar_extra_descriptions';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $types = ['learning_materials', 'company_logos', 'requirements'];
    static $LEARNING_MATERIALS = 'learning_materials';
    static $COMPANY_LOGOS = 'company_logos';
    static $REQUIREMENTS = 'requirements';

    public $translatedAttributes = ['value'];

    public function getValueAttribute()
    {
        return getTranslateAttributeValue($this, 'value');
    }

}
