<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\ReserveMeeting as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ReserveMeeting extends Model
{
    public function getDetailsAttribute()
    {
        $time_exploded = explode('-', $this->meetingTime->time);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'link' => $this->link,
            'user_paid_amount' => $this->user_paid_amount,
            'discount' => $this->discount,
            'amount' => $this->paid_amount,
            'date' => $this->date,
            'day' => $this->meetingTime->day_label,
            'time' => [
                'start' => $time_exploded[0],
                'end' => $time_exploded[1],
            ],
            'student_count'=>$this->student_count,
            'description'=>$this->description ,
            'meeting' => $this->meeting->details,
            'user' => $this->meeting->creator->brief,

        ];
    }

    public function getUserPaidAmountAttribute()
    {

        return ($this->sale && $this->sale->total_amount && $this->sale->total_amount > 0) ? $this->sale->total_amount : 0;

    }

    public function meetingTime()
    {
        return $this->belongsTo('App\Models\LMS\MeetingTime', 'meeting_time_id', 'id');
    }

    public function meeting()
    {
        return $this->belongsTo('App\Models\LMS\Api\Meeting', 'meeting_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\LMS\Api\Sale', 'sale_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'user_id', 'id');
    }


}
