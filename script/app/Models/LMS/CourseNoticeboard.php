<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class CourseNoticeboard extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        CourseNoticeboard::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_course_noticeboards';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $colors = ['warning', 'danger', 'neutral', 'info', 'success'];

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function noticeboardStatus()
    {
        return $this->hasOne('App\Models\LMS\CourseNoticeboardStatus', 'noticeboard_id', 'id');
    }

    public function getIcon()
    {
        $icons = [
            'warning' => 'alert-triangle',
            'danger' => 'alert-octagon',
            'neutral' => 'shield',
            'info' => 'message-square',
            'success' => 'check-circle'
        ];

        return $icons[$this->color];
    }
}
