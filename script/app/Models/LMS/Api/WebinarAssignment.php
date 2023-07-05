<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Api\Traits\CheckWebinarItemAccessTrait;
use App\Models\LMS\Sale;
use  App\Models\LMS\WebinarAssignment as Model;
use App\Models\LMS\WebinarAssignmentHistory;
use App\Models\LMS\WebinarAssignmentHistoryMessage;
use Illuminate\Http\Request;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarAssignment extends Model
{
    use CheckWebinarItemAccessTrait;

    public function scopeHandleFilters($query)
    {
        $request = \request();
        $user = apiAuth();
        $from = $request->get('from');
        $to = $request->get('to');
        $webinarId = $request->get('webinar_id');
        $status = $request->get('status');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($webinarId)) {
            $query->where('webinar_id', $webinarId);
        }

        if (!empty($status)) {
            $query->whereHas('assignmentHistory', function ($query) use ($user, $status) {
                $query->where('student_id', $user->id);
                $query->where('status', $status);
            });
        }

        return $query;
    }

    public function getDeadlineTimeAttribute()
    {
        if (!apiAuth()) {
            return null;
        }
        if (!empty($this->deadline)) {
            $sale = Sale::where('buyer_id', apiauth()->guard('lms_user')->id)
                ->where('webinar_id', $this->webinar_id)
                ->whereNull('refund_at')
                ->first();

            return strtotime("+{$this->deadline} days", $sale->created_at);

        }
    }

    public function userAssignmentHistory()
    {
        return $this->assignmentHistory()->where('student_id', apiauth()->guard('lms_user')->id);
    }


    public function getLastSubmissionAttribute()
    {
        if ($this->userAssignmentHistory and $this->userAssignmentHistory->messages) {
            return $this->userAssignmentHistory->messages->where('sender_id', apiauth()->guard('lms_user')->id)->first()->created_at ?? null;
        }
        return null;
    }
    public function attachments()
    {
        return $this->hasMany('App\Models\LMS\Api\WebinarAssignmentAttachment', 'assignment_id', 'id');
    }

    public function getFirstSubmissionAttribute()
    {
        if ($this->userAssignmentHistory and $this->userAssignmentHistory->messages) {
            return $this->userAssignmentHistory->messages->where('sender_id', apiauth()->guard('lms_user')->id)->last()->created_at ?? null;
        }
        return null;

    }

    public function getUsedAttemptsCountAttribute()
    {
        if ($this->userAssignmentHistory and $this->userAssignmentHistory->messages) {
            return $this->userAssignmentHistory->messages->where('sender_id', apiauth()->guard('lms_user')->id)->count() ?? 0;
        }
        return 0;
    }

    public function getAssignmentStatusAttribute()
    {
        if (empty($this->assignmentHistory) or ($this->assignmentHistory->status == \App\Models\LMS\WebinarAssignmentHistory::$notSubmitted)) {

            return 'not_submitted';
        } else {
            return $this->userAssignmentHistory->status;
        }
    }

    public function grades()
    {

        return $this->assignmentHistory()->get()->filter(function ($item) {
            return !is_null($item->grade);
        });

    }

    public function getMinGradeAttribute()
    {
        $grades = $this->grades();
        return $grades->count() ? $grades->min('grade') : null;
    }

    public function getAvgGradeAttribute()
    {
        $grades = $this->grades();
        return $grades->count() ? $grades->avg('grade') : null;
    }

    public function getPendingCountAttribute()
    {
        return $this->instructorAssignmentHistories()->where('status', WebinarAssignmentHistory::$pending)->count();
    }

    public function getPassedCountAttribute()
    {
        return $this->instructorAssignmentHistories()->where('status', WebinarAssignmentHistory::$passed)->count();
    }

    public function getFailedCountAttribute()
    {

        return $this->instructorAssignmentHistories()->where('status', WebinarAssignmentHistory::$notPassed)->count();
    }

    public function getSubmissionsCountAttribute()
    {

        $historyIds = $this->instructorAssignmentHistories->pluck('id')->toArray();

        return WebinarAssignmentHistoryMessage::whereIn('assignment_history_id', $historyIds)
            ->where('sender_id', '!=', $this->creator_id)
            ->count();
        // return $this->instructorAssignmentHistories()->messages()->where('sender_id', '!=', $this->creator_id)->count();
    }

    public function assignmentHistory()
    {
        return $this->hasOne('App\Models\LMS\Api\WebinarAssignmentHistory', 'assignment_id', 'id')
            ->withDefault([
                'student_id' => apiauth()->guard('lms_user')->id,
                'status' => WebinarAssignmentHistory::$notSubmitted
            ]);
    }



}
