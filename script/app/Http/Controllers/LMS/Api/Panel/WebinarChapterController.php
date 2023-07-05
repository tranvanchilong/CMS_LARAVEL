<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\WebinarChapterResource;
use App\Models\LMS\Api\Webinar;
use App\Models\LMS\WebinarChapter;
use Illuminate\Http\Request;

class WebinarChapterController extends Controller
{
    public function index($webinar_id)
    {
        $chapters = WebinarChapter::where('webinar_id', $webinar_id)
            ->where('status', WebinarChapter::$chapterActive)
            ->orderBy('order', 'asc')
            ->with([
                'chapterItems' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ])
            ->get();
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), WebinarChapterResource::collection($chapters));
    }
}
