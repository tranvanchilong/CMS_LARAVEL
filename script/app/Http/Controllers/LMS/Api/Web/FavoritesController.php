<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Favorite;
use App\Models\LMS\Webinar;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function toggle(Request $request, $id)
    {
        $userId = auth('api')->id();

        $webinar = Webinar::where('id', $id)
            ->where('status', 'active')
            ->first();
        if (!$webinar) {
            abort(404);
        }

        $isFavorite = Favorite::where('webinar_id', $webinar->id)
            ->where('user_id', $userId)
            ->first();

        if (empty($isFavorite)) {
            Favorite::create([
                'user_id' => $userId,
                'webinar_id' => $webinar->id,
                'created_at' => time()
            ]);
            $status = 'favored';
        } else {
            $isFavorite->delete();
            $status = 'unfavored';

        }
        return apiResponse2(1, $status, trans('lms/favorite.' . $status));
    }


}
