<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Controllers\LMS\Panel\AgoraController;
use App\Http\Resources\SessionResource;
use App\Models\LMS\AgoraHistory;
use App\Models\LMS\Api\WebinarChapter;
use App\Models\LMS\File;
use App\Models\LMS\Sale;
use App\Models\LMS\Api\Session;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function show($id)
    {
        $session = Session::where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)->first();
        abort_unless($session, 404);
        if ($error = $session->canViewError()) {
            //       return $this->failure($error, 403, 403);
        }
        $resource = new SessionResource($session);
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $resource);
    }

    public function BigBlueButton(Request $request)
    {

        $session_id = $request->input('session_id');
        $user = User::find($request->input('user_id'));
        Auth::guard('lms_user')->login($user);

        return redirect(url('panel/sessions/' . $session_id . '/joinToBigBlueButton'));

    }

    public function agora(Request $request)
    {

        $session_id = $request->input('session_id');
        $user = User::find($request->input('user_id'));
        Auth::guard('lms_user')->login($user);

        return redirect(url('panel/sessions/' . $session_id . '/joinToAgora'));
    }
}
