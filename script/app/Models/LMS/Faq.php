<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class Faq extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Faq::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    
    use Translatable;

    protected $table = 'lms_faqs';
    public $timestamps = false;
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'answer'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getAnswerAttribute()
    {
        return getTranslateAttributeValue($this, 'answer');
    }
}
