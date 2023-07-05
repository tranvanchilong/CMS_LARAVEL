<?php

namespace App\Mixins\Installment;

use App\Models\LMS\Accounting;
use App\Models\LMS\Installment;
use App\Models\LMS\InstallmentOrderPayment;
use App\Models\LMS\Product;
use App\Models\LMS\Webinar;
use Illuminate\Database\Eloquent\Builder;

class InstallmentRefund
{
    public function refundOrder($order)
    {
        $orderPayments = InstallmentOrderPayment::query()
            ->where('installment_order_id', $order->id)
            ->where('status', 'paid')
            ->get();

        if ($orderPayments->isNotEmpty()) {
            foreach ($orderPayments as $payment) {

                // Buyer
                Accounting::create([
                    'user_id' => $order->user_id,
                    'amount' => $payment->amount,
                    'installment_payment_id' => $payment->id,
                    'type' => Accounting::$addiction,
                    'type_account' => Accounting::$asset,
                    'description' => trans('lms/update.installment_refund'),
                    'created_at' => time()
                ]);

                // System
                Accounting::create([
                    'system' => true,
                    'user_id' => $order->user_id,
                    'amount' => $payment->amount,
                    'installment_payment_id' => $payment->id,
                    'type' => Accounting::$deduction,
                    'type_account' => Accounting::$income,
                    'description' => trans('lms/update.installment_refund'),
                    'created_at' => time()
                ]);

                $payment->update([
                    'status' => 'refunded'
                ]);
            }
        }

        return true;
    }
}
