<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class SessionRemind extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        SessionRemind::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $table = 'lms_session_reminds';
    protected $dateFormat = 'U';
}
