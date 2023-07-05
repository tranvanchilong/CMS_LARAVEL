<?php
namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Api\Controller;
use App\Models\LMS\Api\SupportDepartment ;

class SupportDepartmentsController extends Controller {

    public function index(){

        return SupportDepartment::all()->map(function($department){
            return $department->details ;
        }) ;
    }
}