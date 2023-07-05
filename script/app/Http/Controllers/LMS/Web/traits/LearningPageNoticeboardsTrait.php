<?php

namespace App\Http\Controllers\LMS\Web\traits;

use App\Models\LMS\CourseNoticeboard;
use App\Models\LMS\CourseNoticeboardStatus;

trait LearningPageNoticeboardsTrait
{
    public function noticeboards($slug)
    {
        $user = auth()->guard('lms_user')->user();

        $course = $this->getCourse($slug, $user, 'noticeboards');

        if ($course == 'not_access') {
            abort(404);
        }

        if ($course->creator_id != $user->id and $course->teacher_id != $user->id and !$user->isAdmin()) {
            $unReadCourseNoticeboards = CourseNoticeboard::where('webinar_id', $course->id)
                ->whereDoesntHave('noticeboardStatus', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();

            foreach ($unReadCourseNoticeboards as $noticeboard) {
                CourseNoticeboardStatus::create([
                    'user_id' => $user->id,
                    'noticeboard_id' => $noticeboard->id,
                    'seen_at' => time()
                ]);
            }
        }

        $data = [
            'pageTitle' => $course->title,
            'pageDescription' => $course->seo_description,
            'course' => $course,
            'noticeboards' => true,
            'dontAllowLoadFirstContent' => true,
            'user' => $user,
        ];

        return view('lms.web.default.course.learningPage.index', $data);
    }
}
