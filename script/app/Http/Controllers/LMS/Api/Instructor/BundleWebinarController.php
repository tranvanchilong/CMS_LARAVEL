<?php

namespace App\Http\Controllers\LMS\Api\Instructor;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\WebinarResource;
use App\Models\LMS\Api\Bundle;
use Illuminate\Http\Request;

class BundleWebinarController extends Controller
{
    public function index($id)
    {

        $user = apiAuth();
        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->with([
                'bundleWebinars' => function ($query) {
                    $query->with([
                        'webinar'
                    ]);
                    $query->orderBy('order', 'asc');
                }
            ])
            ->first();
        if (!$bundle) {
            abort(404);
        }

        //dd($bundle->webinars)
        ;
        $webinars = $bundle->bundleWebinars->map(function ($bundleWebinar){
            return $bundleWebinar->webinar ;
        });

       // dd($webinars) ;

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            ['webinars' => WebinarResource::collection($webinars)]);

    }

}
