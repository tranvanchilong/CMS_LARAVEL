<?php

namespace App\Http\Controllers\LMS\Api\Instructor;

use App\Http\Controllers\LMS\Api\Controller;
use App\Models\LMS\Meeting;
use App\Models\LMS\MeetingTime;
use App\Models\LMS\Quiz;
use App\Models\LMS\ReserveMeeting;
use App\Models\LMS\Role;
use App\Models\LMS\User;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\LMS\Api\Panel\ReserveMeetingsController;

class ReserveMeetingController extends Controller
{
    public function createLink(Request $request)
    {
        validateParam($request->all(), [
            'link' => 'required|url',
            'reserved_meeting_id' => 'required|exists:reserve_meetings,id',
            //  'password'=>
        ]);

        $user = apiAuth();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');

        $link = $request->input('link');
        $ReserveMeeting = ReserveMeeting::where('id', $request->input('reserved_meeting_id'))
            ->whereIn('meeting_id', $meetingIds)
            ->first();

        if (!empty($ReserveMeeting) and !empty($ReserveMeeting->meeting)) {
            $ReserveMeeting->update([
                'link' => $link,
                'password' => $request->input('password') ?? null,
                'status' => ReserveMeeting::$open,
            ]);

            $notifyOptions = [
                '[link]' => $link,
                '[instructor.name]' => $ReserveMeeting->meeting->creator->full_name,
                '[time.date]' => $ReserveMeeting->day,
            ];
            sendNotification('new_appointment_link', $notifyOptions, $ReserveMeeting->user_id);

            return apiResponse2(1, 'stored', trans('lms/api.public.stored'));
        }


    }

    public function requests(Request $request)
    {
        $controller = new ReserveMeetingsController();
        return $controller->requests( $request);
    }
}
