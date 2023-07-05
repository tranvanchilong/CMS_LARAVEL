<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarAssignmentHistory extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarAssignmentHistory::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_webinar_assignment_history';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $assignmentHistoryStatus = ['pending', 'passed', 'not_passed', 'not_submitted'];
    static $pending = 'pending';
    static $passed = 'passed';
    static $notPassed = 'not_passed';
    static $notSubmitted = 'not_submitted';

    public function instructor()
    {
        return $this->belongsTo('App\Models\LMS\User', 'instructor_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\LMS\User', 'student_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Models\LMS\WebinarAssignment', 'assignment_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\LMS\WebinarAssignmentHistoryMessage', 'assignment_history_id', 'id');
    }
}
