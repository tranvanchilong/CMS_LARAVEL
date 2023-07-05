<?php

namespace App\Http\Controllers\LMS\Web;

use App\Http\Controllers\LMS\Controller;
use Illuminate\Http\Request;

class MobileAppController extends Controller
{
    public function index()
    {
        /*if (empty(getFeaturesSettings('mobile_app_status')) or !getFeaturesSettings('mobile_app_status')) {
            return redirect('/lms/');
        }*/


        $data = [
            'pageTitle' => trans('lms/update.download_mobile_app_and_enjoy'),
            'pageRobot' => getPageRobotNoIndex()
        ];

        return view('lms.web.default.mobile_app.index', $data);
    }
}
