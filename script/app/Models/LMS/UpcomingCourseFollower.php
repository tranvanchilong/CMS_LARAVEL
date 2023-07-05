<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UpcomingCourseFollower extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UpcomingCourseFollower::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_upcoming_course_followers';
    public $timestamps = false;

    protected $guarded = ['id'];


    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }
}
