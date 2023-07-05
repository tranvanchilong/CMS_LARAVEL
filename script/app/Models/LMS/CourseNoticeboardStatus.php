<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class CourseNoticeboardStatus extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        CourseNoticeboardStatus::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_course_noticeboard_status';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];//
}
