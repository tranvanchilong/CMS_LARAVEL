<?php

namespace App\Http\Controllers\LMS\Admin\traits;


use App\Exports\InstallmentPurchasesExport;
use App\Models\LMS\InstallmentOrder;
use App\Models\LMS\InstallmentOrderPayment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

trait InstallmentPurchasesTrait
{
    public function purchases()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_purchases');

        $orders = InstallmentOrder::query()
            ->where('status', '!=', 'paying')
            ->orderBy('created_at', 'desc')
            ->with([
                'installment' => function ($query) {
                    $query->with(['steps']);
                    $query->withCount([
                        'steps'
                    ]);
                }
            ])
            ->paginate(10);

        $orders = $this->handlePurchasedOrders($orders);

        $data = [
            'pageTitle' => trans('lms/update.purchases'),
            'orders' => $orders
        ];

        return view('lms.admin.financial.installments.purchases.index', $data);
    }

    private function handlePurchasedOrders($orders)
    {
        foreach ($orders as $order) {
            $overdueOrderInstallments = $this->getOverdueOrderInstallments($order);
            $getUpcomingInstallment = $this->getUpcomingInstallment($order);

            $order->overdue_count = $overdueOrderInstallments['total'];
            $order->overdue_amount = $overdueOrderInstallments['amount'];
            $order->upcoming_date = !empty($getUpcomingInstallment) ? dateTimeFormat((($getUpcomingInstallment->deadline * 86400) + $order->created_at), 'j M Y') : '';

            $lastStep = $order->installment->steps()->orderBy('deadline','desc')->first();

            $order->days_left = 0;

            if (!empty($lastStep)) {
                $dueAt = (($lastStep->deadline * 86400) + $order->created_at);
                $daysLeft = ($dueAt - time()) / 86400;

                if ($daysLeft > 0) {
                    $order->days_left = (int)$daysLeft;
                }
            }
        }

        return $orders;
    }

    private function getOverdueOrderInstallments($order)
    {
        $total = 0;
        $amount = 0;

        $time = time();
        $itemPrice = $order->getItemPrice();

        foreach ($order->installment->steps as $step) {
            $dueAt = ($step->deadline * 86400) + $order->created_at;

            if ($dueAt < $time) {
                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $order->id)
                    ->where('step_id', $step->id)
                    ->where('status', 'paid')
                    ->first();

                if (empty($payment)) {
                    $total += 1;
                    $amount += $step->getPrice($itemPrice);
                }
            }
        }

        return [
            'total' => $total,
            'amount' => $amount,
        ];
    }

    private function getUpcomingInstallment($order)
    {
        $result = null;
        $deadline = 0;

        foreach ($order->installment->steps as $step) {
            $payment = InstallmentOrderPayment::query()
                ->where('installment_order_id', $order->id)
                ->where('step_id', $step->id)
                ->where('status', 'paid')
                ->first();

            if (empty($payment) and ($deadline == 0 or $deadline > $step->deadline)) {
                $deadline = $step->deadline;
                $result = $step;
            }
        }

        return $result;
    }


    public function purchasesExportExcel(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_installments_purchases');

        $orders = InstallmentOrder::query()
            ->where('status', '!=', 'paying')
            ->orderBy('created_at', 'desc')
            ->with([
                'installment' => function ($query) {
                    $query->with(['steps']);
                    $query->withCount([
                        'steps'
                    ]);
                }
            ])
            ->get();

        $orders = $this->handlePurchasedOrders($orders);

        $export = new InstallmentPurchasesExport($orders);
        return Excel::download($export, 'InstallmentPurchases.xlsx');
    }

}
