<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarAssignmentHistoryMessage extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarAssignmentHistoryMessage::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_webinar_assignment_history_messages';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function sender()
    {
        return $this->belongsTo('App\Models\LMS\User', 'sender_id', 'id');
    }

    public function getDownloadUrl($assignmentId)
    {
        return "/course/assignment/{$assignmentId}/history/{$this->assignment_history_id}/message/{$this->id}/downloadAttach";
    }
}
