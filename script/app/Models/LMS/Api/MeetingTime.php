<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\MeetingTime as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class MeetingTime extends Model
{
    //
    public function meeting()
    {
        return $this->belongsTo('App\Models\LMS\Api\Meeting', 'meeting_id', 'id');
    }
}
