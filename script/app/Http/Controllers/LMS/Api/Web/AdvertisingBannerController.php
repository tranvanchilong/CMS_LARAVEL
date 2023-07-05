<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use Illuminate\Http\Request;
use App\Models\LMS\AdvertisingBanner;

class AdvertisingBannerController extends Controller
{

    public function list(Request $request)
    {
        $advertisingBanners = AdvertisingBanner::where('published', true)->get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->title,
                'image' => url(get_path_lms().$banner->image),
                'link' => $banner->link,
                'possion' => $banner->position,
            ];


        });

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'count' => $advertisingBanners->count(),
            'advertising_banners' => $advertisingBanners,
        ]);
    }

}
