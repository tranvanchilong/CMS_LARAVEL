<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\CourseForum;
use App\Models\LMS\CourseForumAnswer as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class CourseForumAnswer extends Model
{
    //
    public function course_forum()
    {
       return $this->belongsTo(CourseForum::class, 'forum_id');
    }
}
