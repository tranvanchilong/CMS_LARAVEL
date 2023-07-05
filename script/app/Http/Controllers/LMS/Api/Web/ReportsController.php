<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use Illuminate\Http\Request;
use App\Models\LMS\Api\WebinarReport ;

class ReportsController extends Controller
{
   
   public function index(){

    $reasons=getReportReasons() ;
    return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),$reasons);

   }


}
