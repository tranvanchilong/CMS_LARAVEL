<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Api\Subscribe;
use Illuminate\Http\Request;

class SubscribesController extends Controller
{
    public function list(Request $request)
    {
        $user = auth('api')->user();
        $subscribes = Subscribe::all()->map(function ($subscribe) {
            return $subscribe->details ;
        });
        $data = [
            'count' => $subscribes->count(),
            'subscribes' => $subscribes,
            'subscribed' =>$user? Subscribe::getActiveSubscribe($user->id):null,
            'dayOfUse' =>$user? Subscribe::getDayOfUse($user->id):null,
        ];
        return apiResponse2(1, 'retrieved', trans('lms/public.retrieved'), $data);
    }
}
