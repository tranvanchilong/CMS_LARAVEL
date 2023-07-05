<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Region extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Region::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_regions';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $country = 'country';
    static $province = 'province';
    static $city = 'city';
    static $district = 'district';

    static $types = [
        'country',
        'province',
        'city',
        'district',
    ];

    public function country()
    {
        return $this->belongsTo($this, 'country_id', 'id')->where('type', self::$country);
    }

    public function countryProvinces()
    {
        return $this->hasMany($this, 'country_id', 'id')->where('type', self::$province);
    }

    public function countryCities()
    {
        return $this->hasMany($this, 'country_id', 'id')->where('type', self::$city);
    }

    public function provinceCities()
    {
        return $this->hasMany($this, 'province_id', 'id')->where('type', self::$city);
    }

    public function cityDistricts()
    {
        return $this->hasMany($this, 'city_id', 'id')->where('type', self::$district);
    }

    public function province()
    {
        return $this->belongsTo($this, 'province_id', 'id')->where('type', self::$province);
    }

    public function city()
    {
        return $this->belongsTo($this, 'city_id', 'id')->where('type', self::$city);
    }

    public function countryUsers()
    {
        return $this->hasMany('App\Models\LMS\User', 'country_id', 'id');
    }

    public function provinceUsers()
    {
        return $this->hasMany('App\Models\LMS\User', 'province_id', 'id');
    }

    public function cityUsers()
    {
        return $this->hasMany('App\Models\LMS\User', 'city_id', 'id');
    }

    public function districtUsers()
    {
        return $this->hasMany('App\Models\LMS\User', 'district_id', 'id');
    }
}
