<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Accounting;
use App\Models\LMS\Order;
use App\Models\LMS\OrderItem;
use App\Models\LMS\PaymentChannel;
use App\Models\LMS\Promotion;
use App\Models\LMS\Sale;
use App\Models\LMS\Webinar;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function promotions()
    {
        $user = auth()->guard('lms_user')->user();
        $webinars = Webinar::select('id', 'creator_id', 'teacher_id')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $promotions = Promotion::orderBy('created_at', 'desc')->get();

        $promotionSales = Sale::where('buyer_id', $user->id)
            ->where('type', Sale::$promotion)
            ->orderBy('created_at', 'desc')
            ->whereNull('refund_at')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/panel.promotions'),
            'promotionSales' => $promotionSales,
            'promotions' => $promotions,
            'webinars' => $webinars
        ];

        return view('lms.'. getTemplate() . '.panel.marketing.promotions', $data);
    }


    public function payPromotion(Request $request)
    {
        $user = auth()->guard('lms_user')->user();
        $data = $request->all();
        $paymentChannels = PaymentChannel::where('status', 'active')->get();

        $promotion = Promotion::where('id', $data['promotion_id'])->first();

        if (!empty($promotion)) {
            $webinar = Webinar::where('id', $data['webinar_id'])
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->where('status', 'active')
                ->first();

            if (!empty($webinar)) {
                $financialSettings = getFinancialSettings();
                //$commission = $financialSettings['commission'] ?? 0;
                $tax = $financialSettings['tax'] ?? 0;

                $amount = (!empty($promotion->price) and $promotion->price > 0) ? $promotion->price : 0;

                $taxPrice = $tax ? $amount * $tax / 100 : 0;
                //$commissionPrice = $commission ? $amount * $commission / 100 : 0;

                $order = Order::create([
                    "user_id" => $user->id,
                    "status" => Order::$pending,
                    'tax' => $taxPrice,
                    'commission' => 0,
                    "amount" => $promotion->price,
                    "total_amount" => $amount + $taxPrice,
                    "created_at" => time(),
                ]);

                $orderItem = OrderItem::updateOrCreate([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'webinar_id' => $webinar->id,
                    'promotion_id' => $promotion->id,
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
                    'title' => trans('lms/public.request_success'),
                    'msg' => trans('lms/update.success_pay_msg_for_free_promotion'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }

    private function handleFreePromotion($orderItem)
    {

    }
}
