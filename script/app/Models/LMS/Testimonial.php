<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class Testimonial extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Testimonial::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_testimonials';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['user_name', 'user_bio', 'comment'];

    public function getUserNameAttribute()
    {
        return getTranslateAttributeValue($this, 'user_name');
    }

    public function getUserBioAttribute()
    {
        return getTranslateAttributeValue($this, 'user_bio');
    }

    public function getCommentAttribute()
    {
        return getTranslateAttributeValue($this, 'comment');
    }
}
