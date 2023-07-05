<?php

namespace App\Models\LMS;

use App\Mixins\RegistrationPackage\UserPackage;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class RegistrationPackage extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        RegistrationPackage::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_registration_packages';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\LMS\Sale','registration_package_id','id');
    }

    public function activeSpecialOffer()
    {
        $activeSpecialOffer = SpecialOffer::where('registration_package_id', $this->id)
            ->where('status', SpecialOffer::$active)
            ->where('from_date', '<', time())
            ->where('to_date', '>', time())
            ->first();

        return $activeSpecialOffer ?? false;
    }

    public function getPrice()
    {
        $price = $this->price;

        $specialOffer = $this->activeSpecialOffer();
        if (!empty($specialOffer)) {
            $price = $price - ($price * $specialOffer->percent / 100);
        }

        return $price;
    }
}
