<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Exports\ConsultantsExport;
use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Group;
use App\Models\LMS\Meeting;
use App\Models\LMS\ReserveMeeting;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ConsultantsController extends Controller
{
    public function index(Request $request, $exportExcel = false)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_consultants_lists');

        $query = User::whereIn('role_name', [Role::$teacher, Role::$organization])
            ->join('lms_meetings', 'lms_meetings.creator_id', '=', 'lms_users.id')
            ->select('lms_users.*', 'lms_meetings.amount', 'lms_meetings.discount', 'lms_meetings.disabled')
            ->groupBy('lms_users.id');

        $totalConsultants = User::whereHas('meeting')->get();

        $availableConsultants = User::whereHas('meeting', function ($query) {
            $query->where('disabled', false);
        })->count();

        $unavailableConsultants = User::whereHas('meeting', function ($query) {
            $query->where('disabled', true);
        })->count();

        $consultantsWithoutAppointment = 0;
        foreach ($totalConsultants as $consultant) {
            $checkConsultantsMeetingSale = Sale::whereNull('refund_at')
                ->where('seller_id', $consultant->id)
                ->whereNotNull('meeting_id')
                ->count();

            if ($checkConsultantsMeetingSale < 1) {
                $consultantsWithoutAppointment += 1;
            }
        }

        $organizations = User::select('id', 'full_name', 'created_at')
            ->where('role_name', Role::$organization)
            ->orderBy('created_at', 'desc')
            ->get();

        $userGroups = Group::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $query = $this->filters($query, $request);

        if ($exportExcel) {
            return $query->with([
                'userGroup'
            ])->get();
        }

        $consultants = $query->with([
            'userGroup'
        ])->paginate(10);

        $consultants = $this->addUsersExtraInfo($consultants);

        $data = [
            'pageTitle' => trans('lms/admin/main.consultants_list_title'),
            'totalConsultants' => count($totalConsultants),
            'availableConsultants' => $availableConsultants,
            'unavailableConsultants' => $unavailableConsultants,
            'consultantsWithoutAppointment' => $consultantsWithoutAppointment,
            'organizations' => $organizations,
            'userGroups' => $userGroups,
            'consultants' => $consultants,
        ];

        return view('lms.admin.consultants.lists', $data);
    }

    private function addUsersExtraInfo($users)
    {
        foreach ($users as $user) {
            $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
            $reserveMeetingsQuery = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where(function ($query) {
                    $query->whereHas('sale', function ($query) {
                        $query->whereNull('refund_at');
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('status', ['canceled']);
                        $query->whereHas('sale');
                    });
                });

            $user->meetingsSalesCount = deepClone($reserveMeetingsQuery)->count();
            $user->meetingsSalesSum = deepClone($reserveMeetingsQuery)->sum('paid_amount');
            $user->pendingAppointments = deepClone($reserveMeetingsQuery)->where('status', 'pending')->count();


            /*$reserveMeetings = deepClone($reserveMeetingsQuery)->get();

            $totalIncome = 0;
            foreach ($reserveMeetings as $reserveMeeting) {
                $sale = $reserveMeeting->sale;

                $totalIncome += $sale->total_amount - ($sale->tax + $sale->commission);
            }

            $user->totalIncome = $totalIncome;*/
        }

        return $users;
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $search = $request->get('search', null);
        $sort = $request->get('sort', null);
        $organization_id = $request->get('organization_id', null);
        $group_id = $request->get('group_id', null);
        $disabled = $request->get('disabled', null);

        $query = fromAndToDateFilter($from, $to, $query, 'lms_users.created_at');

        if (!empty($search)) {
            $query->where('lms_users.full_name', 'like', "%$search%");
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'appointments_asc':
                    $query->orderBy('sales_count', 'asc');
                    break;
                case 'appointments_desc':
                    $query->orderBy('sales_count', 'desc');
                    break;
                case 'appointments_income_asc':
                    $query->orderBy('totalIncome', 'asc');
                    break;
                case 'appointments_income_desc':
                    $query->orderBy('totalIncome', 'desc');
                    break;
                case 'pending_appointments_asc':
                    $query->orderBy('pendingAppointments', 'asc');
                    break;
                case 'pending_appointments_desc':
                    $query->orderBy('pendingAppointments', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('lms_users.created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('lms_users.created_at', 'desc');
                    break;
            }
        }

        if (!empty($organization_id)) {
            $query->where('organ_id', $organization_id);
        }

        if (!empty($group_id)) {
            $query->where('group_id', $group_id);
        }

        if (isset($disabled)) {
            $query->where('disabled', ($disabled == '1') ? 1 : 0);
        }

        return $query;
    }

    public function exportExcel(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_consultants_export_excel');

        $consultants = $this->index($request, true);

        $exports = new ConsultantsExport($consultants);

        return Excel::download($exports, 'consultants.xlsx');
    }
}
