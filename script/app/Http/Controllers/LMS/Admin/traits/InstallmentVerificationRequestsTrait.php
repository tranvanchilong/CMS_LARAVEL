<?php

namespace App\Http\Controllers\LMS\Admin\traits;


use App\Exports\InstallmentVerifiedUsersExport;
use App\Models\LMS\Accounting;
use App\Models\LMS\InstallmentOrder;
use App\Models\LMS\InstallmentOrderPayment;
use App\Models\LMS\InstallmentStep;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

trait InstallmentVerificationRequestsTrait
{
    public function verificationRequests()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_verification_requests');

        $orders = InstallmentOrder::query()->select('*', DB::raw("case
            when status = 'pending_verification' then 'a'
            when status = 'open' then 'b'
            when status = 'rejected' then 'c'
            when status = 'canceled' then 'd'
            when status = 'refunded' then 'e'
            end as status_order
        "))
            ->orderBy('status_order', 'asc')
            ->whereHas('installment', function ($query) {
                $query->where('verification', true);
            })
            ->with([
                'installment' => function ($query) {
                    $query->with(['steps']);
                    $query->withCount([
                        'steps'
                    ]);
                }
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.verification_requests'),
            'orders' => $orders
        ];

        return view('lms.admin.financial.installments.verification_requests', $data);
    }

    public function verifiedUsers()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_verified_users');

        $users = User::query()->where('installment_approval', true)
            ->paginate(10);

        $users = $this->handleVerifiedUsers($users);

        $data = [
            'pageTitle' => trans('lms/update.verified_users'),
            'users' => $users
        ];

        return view('lms.admin.financial.installments.verified_users', $data);
    }

    private function handleVerifiedUsers($users)
    {
        foreach ($users as $user) {
            $orders = InstallmentOrder::query()->where('user_id', $user->id)
                ->where('status', 'open')
                ->get();

            $totalAmount = 0;
            $unpaidStepsCount = 0;
            $unpaidStepsAmount = 0;
            $overdueCount = 0;
            $overdueAmount = 0;

            foreach ($orders as $order) {
                $itemPrice = $order->getItemPrice();

                $installment = $order->installment;

                $totalAmount += $installment->totalPayments($itemPrice);

                $steps = InstallmentStep::query()
                    ->where('installment_id', $installment->id)
                    ->whereDoesntHave('orderPayment')
                    ->get();

                $unpaidStepsCount = $steps->count();

                foreach ($steps as $step) {
                    $stepAmount = $step->getPrice($itemPrice);
                    $unpaidStepsAmount += $stepAmount;

                    if (($step->deadline * 86400) + $order->created_at < time()) {
                        $overdueCount += 1;
                        $overdueAmount += $stepAmount;
                    }
                }
            }

            $user->totalAmount = $totalAmount;
            $user->unpaidStepsCount = $unpaidStepsCount;
            $user->unpaidStepsAmount = $unpaidStepsAmount;
            $user->overdueCount = $overdueCount;
            $user->overdueAmount = $overdueAmount;
        }

        return $users;
    }

    public function verifiedUsersExportExcel(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_verified_users');

        $users = User::query()->where('installment_approval', true)
            ->get();

        $users = $this->handleVerifiedUsers($users);

        $export = new InstallmentVerifiedUsersExport($users);
        return Excel::download($export, 'verifiedUsers.xlsx');
    }
}
