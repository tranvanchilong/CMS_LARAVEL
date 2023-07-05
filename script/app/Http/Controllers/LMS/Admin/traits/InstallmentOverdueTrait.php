<?php

namespace App\Http\Controllers\LMS\Admin\traits;


use App\Exports\InstallmentOverdueExport;
use App\Exports\InstallmentOverdueHistoriesExport;
use App\Models\LMS\Accounting;
use App\Models\LMS\InstallmentOrder;
use App\Models\LMS\InstallmentOrderPayment;
use App\Models\LMS\InstallmentStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

trait InstallmentOverdueTrait
{
    public function overdueLists(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_overdue_lists');

        $orders = $this->getOverdueListsQuery($request)
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.overdue_installments'),
            'orders' => $orders
        ];

        return view('lms.admin.financial.installments.overdue_installments', $data);
    }

    private function getOverdueListsQuery(Request $request)
    {
        $time = time();

        $query = InstallmentOrder::query()
            ->join('lms_installments', 'lms_installment_orders.installment_id', 'lms_installments.id')
            ->join('lms_installment_steps', 'lms_installments.id', 'lms_installment_steps.installment_id')
            ->leftJoin('lms_installment_order_payments', 'lms_installment_order_payments.step_id', 'lms_installment_steps.id')
            ->select('lms_installment_orders.*', 'lms_installment_steps.amount', 'lms_installment_steps.amount_type',
                DB::raw('((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at) as overdue_date')
            )
            ->whereRaw("((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at) < {$time}")
            ->where(function ($query) { // Where Doesnt Have payment
                $query->whereRaw("lms_installment_order_payments.id < 1");
                $query->orWhereRaw("lms_installment_order_payments.id is null");
            })
            ->where('lms_installment_orders.status', 'open')
            ->orderBy('overdue_date', 'asc');

        return $query;
    }

    public function overdueListsExportExcel(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_overdue_lists');

        $orders = $this->getOverdueListsQuery($request)->get();

        $export = new InstallmentOverdueExport($orders);
        return Excel::download($export, 'InstallmentOverdue.xlsx');
    }

    public function overdueHistories(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_overdue_lists');

        $orders = $this->getOverdueHistoriesQuery($request)
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.overdue_installments'),
            'orders' => $orders
        ];

        return view('lms.admin.financial.installments.overdue_history', $data);
    }

    private function getOverdueHistoriesQuery(Request $request)
    {
        $time = time();

        $query = InstallmentOrder::query()
            ->join('lms_installments', 'lms_installment_orders.installment_id', 'lms_installments.id')
            ->join('lms_installment_steps', 'lms_installments.id', 'lms_installment_steps.installment_id')
            ->leftJoin('lms_installment_order_payments', 'lms_installment_order_payments.step_id', 'lms_installment_steps.id')
            ->select('lms_installment_orders.*', 'lms_installment_steps.amount', 'lms_installment_steps.amount_type',
                DB::raw('((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at) as overdue_date'),
                DB::raw('lms_installment_order_payments.created_at as paid_at'),
                DB::raw('lms_lms_installment_steps.deadline as deadline')
            )
            ->whereRaw("((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at) < {$time}")
            ->where('lms_installment_orders.status', 'open')
            ->where(function ($query) {
                $query->where(function ($query) { // Where Doesnt Have payment
                    $query->whereRaw("lms_installment_order_payments.id < 1");
                    $query->orWhereRaw("lms_installment_order_payments.id is null");
                });
                $query->orWhereRaw('lms_installment_order_payments.created_at > ((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at)');
            })
            ->orderBy('overdue_date', 'asc');

        return $query;
    }

    public function overdueHistoriesExportExcel(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_overdue_lists');

        $orders = $this->getOverdueHistoriesQuery($request)
            ->paginate(10);

        $export = new InstallmentOverdueHistoriesExport($orders);
        return Excel::download($export, 'InstallmentOverdueHistories.xlsx');
    }
}
