<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Follow extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Follow::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public static $requested = "requested";
    public static $accepted = "accepted";
    public static $rejected = "rejected";
    
    public $timestamps = false;
    protected $table = 'lms_follows';

    protected $guarded = ['id'];

}
