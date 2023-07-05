<?php

namespace App\Http\Controllers\LMS\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Favorite;
use App\Models\LMS\Webinar;

class FavoriteController extends Controller
{
    public function toggle($slug)
    {
        $userId = auth()->guard('lms_user')->id();
        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar)) {

            $isFavorite = Favorite::where('webinar_id', $webinar->id)
                ->where('user_id', $userId)
                ->first();

            if (empty($isFavorite)) {
                Favorite::create([
                    'user_id' => $userId,
                    'webinar_id' => $webinar->id,
                    'created_at' => time()
                ]);
            } else {
                $isFavorite->delete();
            }
        }

        return response()->json([], 200);
    }
}
