<?php

namespace App\Policies;

use App\Models\LMS\CourseForum;
use App\Models\LMS\Api\CourseForumAnswer;
use App\Models\LMS\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseForumAnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function pin(User $user, CourseForumAnswer $courseForumAnswer)
    {
        return $courseForumAnswer->course_forum->webinar->isOwner($user->id);
    }

    public function resolve(User $user, CourseForumAnswer $courseForumAnswer)
    {
        return ($courseForumAnswer->course_forum->webinar->isOwner($user->id)  or $courseForumAnswer->course_forum->user_id==$user->id );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\LMS\User $user
     * @param \App\Models\LMS\CourseForum $courseForum
     * @return mixed
     */
    public function update(User $user,CourseForumAnswer $courseForumAnswer)
    {

        return  $courseForumAnswer->user_id==$user->id ;
    }



}
