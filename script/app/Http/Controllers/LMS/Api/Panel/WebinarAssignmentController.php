<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\WebinarAssignmentResource;
use App\Models\LMS\Api\File;
use App\Models\LMS\Api\WebinarAssignment;
use App\Models\LMS\WebinarChapter;
use Illuminate\Http\Request;

class WebinarAssignmentController extends Controller
{
    public function show($id)
    {
        $assignmnet = WebinarAssignment::where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)->first();
        abort_unless($assignmnet,404);
        if ($error = $assignmnet->canViewError()) {
            //       return $this->failure($error, 403, 403);
        }
        $resource = new WebinarAssignmentResource($assignmnet);
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $resource);
    }
}
