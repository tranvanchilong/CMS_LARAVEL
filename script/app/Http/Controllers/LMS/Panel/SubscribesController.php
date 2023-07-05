<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Controllers\LMS\Web\traits\InstallmentsTrait;
use App\Mixins\Installment\InstallmentPlans;
use App\Models\LMS\Order;
use App\Models\LMS\OrderItem;
use App\Models\LMS\PaymentChannel;
use App\Models\LMS\Sale;
use App\Models\LMS\Setting;
use App\Models\LMS\Subscribe;
use App\Models\LMS\User;
use Illuminate\Http\Request;

class SubscribesController extends Controller
{
    use InstallmentsTrait;

    public function index()
    {
        $user = auth()->guard('lms_user')->user();
        $subscribes = Subscribe::all();

        $installmentPlans = new InstallmentPlans($user);
        foreach ($subscribes as $subscribe) {
            if (getInstallmentsSettings('status') and $user->enable_installments and $subscribe->price > 0) {
                $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);

                $subscribe->has_installment = (!empty($installments) and count($installments));
            }
        }

        $data = [
            'pageTitle' => trans('lms/financial.subscribes'),
            'subscribes' => $subscribes,
            'activeSubscribe' => Subscribe::getActiveSubscribe($user->id),
            'dayOfUse' => Subscribe::getDayOfUse($user->id),
        ];

        return view('lms.'. getTemplate() . '.panel.financial.subscribes', $data);
    }

    public function pay(Request $request)
    {
        $paymentChannels = PaymentChannel::where('status', 'active')->get();

        $subscribe = Subscribe::where('id', $request->input('id'))->first();

        if (empty($subscribe)) {
            $toastData = [
                'msg' => trans('lms/site.subscribe_not_valid'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $user = auth()->guard('lms_user')->user();
        $activeSubscribe = Subscribe::getActiveSubscribe($user->id);

        if ($activeSubscribe) {
            $toastData = [
                'title' => trans('lms/public.request_failed'),
                'msg' => trans('lms/site.you_have_active_subscribe'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $financialSettings = getFinancialSettings();
        $tax = $financialSettings['tax'] ?? 0;

        $amount = $subscribe->getPrice();
        $amount = $amount > 0 ? $amount : 0;

        $taxPrice = $tax ? $amount * $tax / 100 : 0;

        $order = Order::create([
            "user_id" => $user->id,
            "status" => Order::$pending,
            'tax' => $taxPrice,
            'commission' => 0,
            "amount" => $amount,
            "total_amount" => $amount + $taxPrice,
            "created_at" => time(),
        ]);

        $orderItem = OrderItem::updateOrCreate([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'subscribe_id' => $subscribe->id,
        ], [
            'amount' => $order->amount,
            'total_amount' => $amount + $taxPrice,
            'tax' => $tax,
            'tax_price' => $taxPrice,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);

        if ($amount > 0) {

            $razorpay = false;
            foreach ($paymentChannels as $paymentChannel) {
                if ($paymentChannel->class_name == 'Razorpay') {
                    $razorpay = true;
                }
            }

            $data = [
                'pageTitle' => trans('lms/public.checkout_page_title'),
                'paymentChannels' => $paymentChannels,
                'total' => $order->total_amount,
                'order' => $order,
                'count' => 1,
                'userCharge' => $user->getAccountingCharge(),
                'razorpay' => $razorpay
            ];

            return view('lms.'. getTemplate() . '.cart.payment', $data);
        }

        // Handle Free
        Sale::createSales($orderItem, Sale::$credit);

        $toastData = [
            'title' => 'public.request_success',
            'msg' => trans('lms/update.success_pay_msg_for_free_subscribe'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
