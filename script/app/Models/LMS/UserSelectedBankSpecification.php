<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UserSelectedBankSpecification extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UserZoomApi::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }
    
    protected $table = "lms_user_selected_bank_specifications";
    public $timestamps = false;

    protected $guarded = ['id'];


    public function bankSpecification()
    {
        return $this->belongsTo('App\Models\LMS\UserBankSpecification', 'user_bank_specification_id', 'id');
    }

}
