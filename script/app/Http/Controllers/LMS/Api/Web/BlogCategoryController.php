<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use Illuminate\Http\Request;
use App\Models\LMS\Api\BlogCategory ;

class BlogCategoryController extends Controller
{
    //

    public function index(){

        $categories=BlogCategory::all()->map(function($category){
            return $category->details ;
        }) ;
   return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),$categories);
    }
}
