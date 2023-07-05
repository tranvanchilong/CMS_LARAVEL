<?php

namespace App\Http\Controllers\LMS\Web\traits;

trait CheckContentLimitationTrait
{
    public function checkContentLimitation($user = null, $coursePage = false)
    {
        if (!empty($user) and !$user->access_content) {
            $data = [
                'pageTitle' => trans('lms/update.not_access_to_content'),
                'pageRobot' => getPageRobotNoIndex(),
                'userNotAccess' => true
            ];

            return view('lms.web.default.course.private_content', $data);
        } elseif (empty($user) and getFeaturesSettings('webinar_private_content_status') and $coursePage) { // user not login
            $data = [
                'pageTitle' => trans('lms/update.private_content'),
                'pageRobot' => getPageRobotNoIndex(),
            ];

            return view('lms.web.default.course.private_content', $data);
        }


        return "ok";
    }
}
