<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Api\Objects\WebinarObj;
use App\Models\LMS\Api\FeatureWebinar;
use Illuminate\Http\Request;

class FeatureWebinarController
{
    public function index(Request $request){

        $webinars=FeatureWebinar::whereIn('page', ['home', 'home_categories'])
        ->where('status', 'publish') 
        ->handleFilters()
        ->get()->map(function ($item) {
            return $item->webinar->brief;
        });

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $webinars);

    }
   
    
}
