<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class NavbarButton extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        NavbarButton::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_navbar_buttons';
    public $timestamps = false;
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'url'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getUrlAttribute()
    {
        return getTranslateAttributeValue($this, 'url');
    }


    public function role()
    {
        return $this->belongsTo('App\Models\LMS\Role', 'role_id', 'id');
    }
}
