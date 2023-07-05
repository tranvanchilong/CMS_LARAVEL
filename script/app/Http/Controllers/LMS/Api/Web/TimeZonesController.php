<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use Illuminate\Http\Request;

class TimeZonesController extends Controller
{
    //
    public function index()
    {

        $list = getListOfTimezones();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),

            $list
        );
    }
}
