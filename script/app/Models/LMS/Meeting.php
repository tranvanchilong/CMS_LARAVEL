<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Meeting extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Meeting::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_meetings';

    protected $guarded = ['id'];

    public function teacher()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function meetingTimes()
    {
        return $this->hasMany('App\Models\LMS\MeetingTime', 'meeting_id', 'id');
    }

    public function getTimezone()
    {
        $timezone = getGeneralSettings('default_time_zone');

        $user = $this->creator;

        if (!empty($user) and !empty($user->timezone)) {
            $timezone = $user->timezone;
        }

        return $timezone;
    }
}
