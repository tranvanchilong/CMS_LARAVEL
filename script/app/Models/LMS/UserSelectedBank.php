<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UserSelectedBank extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UserSelectedBank::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = "lms_user_selected_banks";
    public $timestamps = false;

    protected $guarded = ['id'];


    public function bank()
    {
        return $this->belongsTo('App\Models\LMS\UserBank', 'user_bank_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function specifications()
    {
        return $this->hasMany('App\Models\LMS\UserSelectedBankSpecification', 'user_selected_bank_id', 'id');
    }
}
