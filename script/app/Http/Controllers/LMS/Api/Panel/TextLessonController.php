<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\TextLessonResource;
use App\Models\LMS\Api\TextLesson;
use App\Models\LMS\WebinarChapter;
use Illuminate\Http\Request;

class TextLessonController extends Controller
{
    public function show($id)
    {
        $textLesson = TextLesson::where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)->first();
        abort_unless($textLesson, 404);

        if ($error = $textLesson->canViewError()) {
            //       return $this->failure($error, 403, 403);
        }
        $resource = new TextLessonResource($textLesson);
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $resource);
    }
}
