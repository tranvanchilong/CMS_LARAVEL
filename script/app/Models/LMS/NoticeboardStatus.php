<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class NoticeboardStatus extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        NoticeboardStatus::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_noticeboards_status';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];//
}
