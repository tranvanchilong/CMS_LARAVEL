<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('lms_user')->user();
        $payouts = Payout::where('user_id', $user->id)
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/financial.payout_request'),
            'payouts' => $payouts,
            'accountCharge' => $user->getAccountingCharge(),
            'readyPayout' => $user->getPayout(),
            'totalIncome' => $user->getIncome(),
        ];

        return view('lms.'. getTemplate() . '.panel.financial.payout', $data);
    }

    public function requestPayout()
    {
        $user = auth()->guard('lms_user')->user();
        $getUserPayout = $user->getPayout();
        $getFinancialSettings = getFinancialSettings();

        if ($getUserPayout < $getFinancialSettings['minimum_payout']) {
            $toastData = [
                'title' => trans('lms/public.request_failed'),
                'msg' => trans('lms/public.income_los_then_minimum_payout'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        if (!empty($user->selectedBank)) {

            Payout::create([
                'user_id' => $user->id,
                'user_selected_bank_id' => $user->selectedBank->id,
                'amount' => $getUserPayout,
                'status' => Payout::$waiting,
                'created_at' => time(),
            ]);

            $notifyOptions = [
                '[payout.amount]' => handlePrice($getUserPayout),
                '[amount]' => handlePrice($getUserPayout),
                '[u.name]' => $user->full_name
            ];

            sendNotification('payout_request', $notifyOptions, $user->id);
            sendNotification('payout_request_admin', $notifyOptions, 1); // for admin
            sendNotification('new_user_payout_request', $notifyOptions, 1); // for admin

            $toastData = [
                'title' => trans('lms/public.request_success'),
                'msg' => trans('lms/update.payout_request_registered_successful_hint'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $toastData = [
            'title' => trans('lms/public.request_failed'),
            'msg' => trans('lms/site.check_identity_settings'),
            'status' => 'error'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
