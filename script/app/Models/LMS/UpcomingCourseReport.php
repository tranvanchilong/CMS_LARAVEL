<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UpcomingCourseReport extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UpcomingCourseReport::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_upcoming_course_reports';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function upcomingCourse()
    {
        return $this->belongsTo('App\Models\LMS\UpcomingCourse', 'upcoming_course_id', 'id');
    }
}
