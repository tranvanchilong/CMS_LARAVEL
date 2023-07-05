<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Api\Controller;
use App\Http\Controllers\LMS\Api\Web\WebinarController;
use App\Http\Controllers\LMS\Api\Panel\PaymentsController;
use App\Models\LMS\Api\Accounting;
use App\Models\LMS\OfflinePayment;
use App\Models\LMS\Order;
use App\Models\LMS\OrderItem;
use App\Models\LMS\PaymentChannel;
use App\PaymentChannels\ChannelManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class AccountingsController extends Controller
{
    public function summary()
    {
        $user = apiAuth();
        $accountings = Accounting::where('user_id', $user->id)
            ->where('system', false)
            ->where('tax', false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($accounting) {
                return $accounting->details ;
            });


        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'balance'=> $user->getAccountingBalance() ,
            'history'=>$accountings
        ]);

    }

    public function listOfflinePayment()
    {
        $user = apiAuth();
        $offlinePayments = OfflinePayment::where('user_id', $user->id)->orderBy('created_at', 'desc')->get()
            ->map(function ($offlinePayment) {
                return [
                    'amount' => $offlinePayment->amount,
                    'bank' => $offlinePayment->bank,
                    'reference_number' => $offlinePayment->reference_number,
                    'status' => $offlinePayment->status,
                    'created_at' => $offlinePayment->created_at,
                    'pay_date' => $offlinePayment->pay_date,
                ];

            });
        return apiResponse2(1, 'retrieved', trans('lms/public.retrieved'), $offlinePayments);

    }

    public function platformBankAccounts(){

        $accounts=[] ;
        foreach(getOfflineBankSettings() as $account){

            if(isset($account['image'])){
                $account['image']=url( $account['image']) ;
            }
            $accounts[]=$account ;
        }

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),[
            'accounts'=>$accounts
        ]);

    }

    public function accountTypes(){

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),[
            'accounts_type'=> getOfflineBanksTitle()
        ]);
    }







}
