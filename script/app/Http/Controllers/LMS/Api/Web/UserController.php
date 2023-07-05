<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;

use App\Models\LMS\Api\Meeting;
use App\Models\LMS\Newsletter;
use App\Models\LMS\Api\ReserveMeeting;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\UserOccupation;
use App\Models\LMS\Api\Webinar;
use App\Models\LMS\Api\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LMS\Api\Setting;
use Exception;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function profile(Request $request, $id)
    {
        $user = User::where('id', $id)
            ->whereIn('role_name', [Role::$organization, Role::$teacher, Role::$user])
            ->first();
        if (!$user) {
            abort(404);
        }
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'user' => $user->details
        ]);

    }

    public function instructors(Request $request)
    {
        $providers = $this->handleProviders($request, [Role::$teacher]);

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $providers);

    }

    public function consultations(Request $request)
    {
        $providers = $this->handleProviders($request, [Role::$teacher, Role::$organization], true);
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $providers);


    }

    public function organizations(Request $request)
    {
        $providers = $this->handleProviders($request, [Role::$organization]);

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $providers);


    }

    public function providers(Request $request)
    {
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'instructors' => $this->instructors($request),
            'organizations' => $this->organizations($request),
            'consultations' => $this->consultations($request),
        ]);

    }

    public function handleProviders(Request $request, $role, $has_meeting = false)
    {
        $query = User::whereIn('role_name', $role)
            //->where('verified', true)
            ->where('lms_users.status', 'active')
            ->where(function ($query) {
                $query->where('lms_users.ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('lms_users.ban_end_at')
                            ->orWhere('lms_users.ban_end_at', '<', time());
                    });
            });

        if ($has_meeting) {
            $query->whereHas('meeting');
        }

        $users = $this->filterProviders($request, deepClone($query), $role)
            ->get()
            ->map(function ($user) {
                return $user->brief;
            });

        return [
            'count' => $users->count(),
            'users' => $users,
        ];

    }

    private function filterProviders($request, $query, $role)
    {
        $categories = $request->get('categories', null);
        $sort = $request->get('sort', null);
        $availableForMeetings = $request->get('available_for_meetings', null);
        $hasFreeMeetings = $request->get('free_meetings', null);
        $withDiscount = $request->get('discount', null);
        $search = $request->get('search', null);
        $organization_id = $request->get('organization', null);
        $downloadable = $request->get('downloadable', null);

        if ($downloadable) {
            $query->whereHas('webinars', function ($qu) {
                return $qu->where('downloadable', 1);
            });
        }
        if (!empty($categories) and is_array($categories)) {
            $userIds = UserOccupation::whereIn('category_id', $categories)->pluck('user_id')->toArray();

            $query->whereIn('lms_users.id', $userIds);
        }
        if ($organization_id) {
            $query->where('organ_id', $organization_id);
        }

        if (!empty($sort) and $sort == 'top_rate') {
            $query = $this->getBestRateUsers($query, $role);
        }

        if (!empty($sort) and $sort == 'top_sale') {
            $query = $this->getTopSalesUsers($query, $role);
        }

        if (!empty($availableForMeetings) and $availableForMeetings == 1) {
            $hasMeetings = DB::table('lms_meetings')
                ->where('lms_meetings.disabled', 0)
                ->join('lms_meeting_times', 'lms_meetings.id', '=', 'lms_meeting_times.meeting_id')
                ->select('lms_meetings.creator_id', DB::raw('count(meeting_id) as counts'))
                ->groupBy('creator_id')
                ->orderBy('counts', 'desc')
                ->get();

            $hasMeetingsInstructorsIds = [];
            if (!empty($hasMeetings)) {
                $hasMeetingsInstructorsIds = $hasMeetings->pluck('creator_id')->toArray();
            }

            $query->whereIn('lms_users.id', $hasMeetingsInstructorsIds);
        }

        if (!empty($hasFreeMeetings) and $hasFreeMeetings == 1) {
            $freeMeetingsIds = Meeting::where('disabled', 0)
                ->where(function ($query) {
                    $query->whereNull('amount')->orWhere('amount', '0');
                })->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('lms_users.id', $freeMeetingsIds);
        }

        if (!empty($withDiscount) and $withDiscount == 1) {
            $withDiscountMeetingsIds = Meeting::where('disabled', 0)
                ->whereNotNull('discount')
                ->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('lms_users.id', $withDiscountMeetingsIds);
        }

        if (!empty($search)) {
            $query->where(function ($qu) use ($search) {
                $qu->where('lms_users.full_name', 'like', "%$search%")
                    ->orWhere('lms_users.email', 'like', "%$search%")
                    ->orWhere('lms_users.mobile', 'like', "%$search%");
            });
        }

        return $query;
    }

    private function getBestRateUsers($query, $role)
    {
        $query->leftJoin('lms_webinars', function ($join) use ($role) {
            if ($role == Role::$organization) {
                $join->on('lms_users.id', '=', 'lms_webinars.creator_id');
            } else {
                $join->on('lms_users.id', '=', 'lms_webinars.teacher_id');
            }

            $join->where('lms_webinars.status', 'active');
        })->leftJoin('lms_webinar_reviews', function ($join) {
            $join->on('lms_webinars.id', '=', 'lms_webinar_reviews.webinar_id');
            $join->where('lms_webinar_reviews.status', 'active');
        })
            ->whereNotNull('rates')
            ->select('lms_users.*', DB::raw('avg(rates) as rates'))
            ->orderBy('rates', 'desc');

        if ($role == Role::$organization) {
            $query->groupBy('lms_webinars.creator_id');
        } else {
            $query->groupBy('lms_webinars.teacher_id');
        }

        return $query;
    }

    private function getTopSalesUsers($query, $role)
    {
        $query->leftJoin('lms_sales', function ($join) {
            $join->on('lms_users.id', '=', 'lms_sales.seller_id')
                ->whereNull('refund_at');
        })
            ->whereNotNull('lms_sales.seller_id')
            ->select('lms_users.*', 'lms_sales.seller_id', DB::raw('count(lms_sales.seller_id) as counts'))
            ->groupBy('lms_sales.seller_id')
            ->orderBy('counts', 'desc');

        return $query;
    }


    public function sendMessage(Request $request, $id)
    {

        $user = User::find($id);
        abort_unless($user, 404);
        if (!$user->public_message) {
            return apiResponse2(0, 'disabled_public_message', trans('lms/api.user.disabled_public_message'));
        }

        validateParam($request->all(), [
            'title' => 'required|string',
            'email' => 'required|email',
            'description' => 'required|string',
            //    'captcha' => 'required|captcha',
        ]);
        $data = $request->all();

        $mail = [
            'title' => $data['title'],
            'message' => trans('lms/site.you_have_message_from', ['email' => $data['email']]) . "\n" . $data['description'],
        ];

        try {
            Mail::to($user->email)->send(new \App\Mail\SendNotifications($mail));


            return apiResponse2(1, 'email_sent', trans('lms/api.user.email_sent'));

        } catch (Exception $e) {

            return apiResponse2(0, 'email_error', $e->getMessage());


        }


    }


    public function makeNewsletter(Request $request)
    {
        validateParam($request->all(), [
            'email' => 'required|string|email|max:255|unique:newsletters,email'
        ]);

        $data = $request->all();
        $user_id = null;
        $email = $data['email'];
        if (auth()->guard('lms_user')->check()) {
            $user = auth()->guard('lms_user')->user();

            if ($user->email == $email) {
                $user_id = $user->id;

                $user->update([
                    'newsletter' => true,
                ]);
            }
        }

        Newsletter::create([
            'user_id' => $user_id,
            'email' => $email,
            'created_at' => time()
        ]);

        return apiResponse2('1', 'subscribed_newsletter', 'email subscribed in newsletter successfully.');


    }


    public function availableTimes(Request $request, $id)
    {
        $date = $request->input('date');

        $day_label = $request->input('day_label');

        $timestamp = strtotime($date);

        //  dd($timestamp);
        $user = User::where('id', $id)
            ->whereIn('role_name', [Role::$teacher, Role::$organization])
            ->where('status', 'active')
            ->first();

        if (!$user) {
            abort(404);
        }

        $meeting = Meeting::where('creator_id', $user->id)->first();

        $meetingTimes = [];

        if (!empty($meeting->meetingTimes)) {
            foreach ($meeting->meetingTimes->groupBy('day_label') as $day => $meetingTime) {

                foreach ($meetingTime as $time) {
                    $can_reserve = true;

                 $explodetime = explode('-', $time->time);

                     $secondTime = dateTimeFormat(strtotime($explodetime['0']), 'H') * 3600 + dateTimeFormat(strtotime($explodetime['0']), 'i') * 60;

                    $reserveMeeting = ReserveMeeting::where('meeting_time_id', $time->id)
                        ->where('day', dateTimeFormat($timestamp, 'Y-m-d'))
                        ->where('meeting_time_id', $time->id)
                        ->first();

                    if ($reserveMeeting && ($reserveMeeting->locked_at || $reserveMeeting->reserved_at)) {
                        $can_reserve = false;
                    }

                        if ($timestamp + $secondTime < time()) {
                           $can_reserve = false;
                       }
                    // $time_explode = explode('-', $time->time);
                    // Carbon::parse($time_explode[0]);

                    $user = apiAuth();
                    $userReservedMeeting = null;
                    if ($user) {
                        $userReservedMeeting = ReserveMeeting::where('user_id', $user->id)
                            ->where('meeting_id', $meeting->id)->where('meeting_time_id',
                                $time->id
                            )
                            ->first();
                    }


                    $meetingTimes[$day]["times"][] =
                        [
                            "id" => $time->id,
                            "time" => $time->time,
                            "can_reserve" => $can_reserve,
                            "description" => $time->description,
                            'meeting_type'=>$time->meeting_type ,
                            'meeting' => $time->meeting->details,
                            'auth_reservation' => $userReservedMeeting

                        ];
                }
            }
        }

        //  return $meetingTimes ;
        $array = [];;
        foreach ($meetingTimes as $day => $time) {
            if ($day == strtolower(date('l', $timestamp))) // if ($day == $day_label) {
            {
                $array = $time['times'];

            }
        }

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'count' => count($array),
            'times' => $array
        ]);

    }


}
