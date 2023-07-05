<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class SpecialOffer extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        SpecialOffer::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_special_offers';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public static $active = 'active';
    public static $inactive = 'inactive';

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Bundle', 'bundle_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }

    public function subscribe()
    {
        return $this->belongsTo('App\Models\LMS\Subscribe', 'subscribe_id', 'id');
    }

    public function registrationPackage()
    {
        return $this->belongsTo('App\Models\LMS\RegistrationPackage', 'registration_package_id', 'id');
    }

    public function getRemainingTimes()
    {
        $current_time = time();
        $date = $this->to_date;
        $difference = $date - $current_time;

        return time2string($difference);
    }
}
