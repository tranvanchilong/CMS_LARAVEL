<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class MeetingTime extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        MeetingTime::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public static $open = "open";
    public static $finished = "finished";

    public static $saturday = "saturday";
    public static $sunday = "sunday";
    public static $monday = "monday";
    public static $tuesday = "tuesday";
    public static $wednesday = "wednesday";
    public static $thursday = "thursday";
    public static $friday = "friday";
    public static $days = ["saturday", "sunday", "monday", "tuesday", "wednesday", "thursday", "friday"];

    public $timestamps = false;
    protected $table = 'lms_meeting_times';

    protected $guarded = ['id'];

    public function meeting()
    {
        return $this->belongsTo('App\Models\LMS\Meeting', 'meeting_id', 'id');
    }
}
