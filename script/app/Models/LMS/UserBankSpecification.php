<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UserBankSpecification extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UserBankSpecification::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = "lms_user_bank_specifications";
    public $timestamps = false;

    protected $guarded = ['id'];

    public $translatedAttributes = ['name'];

    public function getNameAttribute()
    {
        return getTranslateAttributeValue($this, 'name');
    }
}
