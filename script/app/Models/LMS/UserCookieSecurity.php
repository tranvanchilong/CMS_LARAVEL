<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UserCookieSecurity extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UserCookieSecurity::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_users_cookie_security';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $types = ['all', 'customize'];
    static $ALL = 'all';
    static $CUSTOMIZE = 'customize';
}
