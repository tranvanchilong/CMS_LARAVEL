<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarQuizzes;
use App\Models\LMS\Quiz;
use Illuminate\Http\Request;
use Validator;

class WebinarQuizController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->guard('lms_user')->user();
        $data = $request->get('ajax')['new'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'quiz_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $quiz = Quiz::where('id', $data['quiz_id'])
                ->where('creator_id', $user->id)
                ->where('webinar_id', null)
                ->first();

            if (!empty($quiz)) {
                $quiz->webinar_id = $data['webinar_id'];
                $quiz->save();

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();
        $data = $request->get('ajax')[$id];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'quiz_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $quiz = Quiz::where('id', $data['quiz_id'])
                ->where('creator_id', $user->id)
                ->where('webinar_id', null)
                ->first();

            if (!empty($quiz)) {
                $quiz->webinar_id = $data['webinar_id'];
                $quiz->save();

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }
}
