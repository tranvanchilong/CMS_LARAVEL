<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Accounting;
use App\Models\LMS\BecomeInstructor;
use App\Models\LMS\Order;
use App\Models\LMS\RegistrationPackage;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;

class BecomeInstructorController extends Controller
{
    public function index($page)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_become_instructors_list');

        if ($page == 'organizations') {
            $role = Role::$organization;
        } else {
            $role = Role::$teacher;
        }

        $query = BecomeInstructor::where('role', $role);

        if (getRegistrationPackagesGeneralSettings('force_user_to_select_a_package')) {
            $query->whereNotNull('package_id');
        }

        $becomeInstructors = $query->with(['user', 'registrationPackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.become_instructors_list'),
            'becomeInstructors' => $becomeInstructors
        ];

        return view('lms.admin.users.become_instructors.lists', $data);
    }

    public function reject($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_become_instructors_reject');

        $becomeInstructors = BecomeInstructor::findOrFail($id);

        $this->handleRefundPackage($becomeInstructors);

        $becomeInstructors->update([
            'status' => 'reject'
        ]);

        // Send Notification
        $becomeInstructors->sendNotificationToUser('reject');

        return redirect()->back();
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_become_instructors_delete');

        $becomeInstructors = BecomeInstructor::findOrFail($id);

        $this->handleRefundPackage($becomeInstructors);

        $becomeInstructors->delete();

        return redirect()->back();
    }

    private function handleRefundPackage($becomeInstructors)
    {
        if (!empty($becomeInstructors->package_id)) {
            $sale = Sale::where('buyer_id', $becomeInstructors->user_id)
                ->where('type', Sale::$registrationPackage)
                ->where('registration_package_id', $becomeInstructors->package_id)
                ->whereNull('refund_at')
                ->first();

            if (!empty($sale)) {
                if (!empty($sale->total_amount)) {

                    Accounting::refundAccounting($sale);
                }

                $sale->update(['refund_at' => time()]);
            }
        }
    }
}
