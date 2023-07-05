<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Api\Bundle;
use App\Models\LMS\RewardAccounting;
use App\Models\LMS\Sale;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function buyWithPoint($id)
    {
        $user = apiAuth();

        $bundle = Bundle::where('id', $id)
            ->where('status', 'active')
            ->first();
        if (!$bundle) {
            abort(404);
        }

        if (empty($bundle->points)) {
            return apiResponse2(0, 'no_points', trans('lms/update.can_not_buy_this_bundle_with_point'));
        }

        if ($user->getRewardPoints() < $bundle->points) {
            return apiResponse2(0, 'no_enough_points', trans('lms/update.you_have_no_enough_points_for_this_bundle'));

        }

        $checkCourseForSale = $bundle->checkWebinarForSale($user);

        if ($checkCourseForSale != 'ok') {
            return $checkCourseForSale;
        }

        Sale::create([
            'buyer_id' => $user->id,
            'seller_id' => $bundle->creator_id,
            'bundle_id' => $bundle->id,
            'type' => Sale::$bundle,
            'payment_method' => Sale::$credit,
            'amount' => 0,
            'total_amount' => 0,
            'created_at' => time(),
        ]);

        RewardAccounting::makeRewardAccounting($user->id, $bundle->points, 'withdraw', null, false, RewardAccounting::DEDUCTION);

        return apiResponse2(1, 'paid', trans('lms/update.success_pay_bundle_with_point_msg'));
    }

    public function free(Request $request, $id)
    {
        $user = apiAuth();

        $bundle = Bundle::where('id', $id)
            ->where('status', 'active')
            ->first();
        if (!$bundle) {
            abort(404);
        }

        $checkCourseForSale = $bundle->checkWebinarForSale($user);

        if ($checkCourseForSale != 'ok') {
            return $checkCourseForSale;
        }

        if (!empty($bundle->price) and $bundle->price > 0) {
            return apiResponse2(0, 'not_free', trans('lms/update.bundle_not_free'));
        }

        Sale::create([
            'buyer_id' => $user->id,
            'seller_id' => $bundle->creator_id,
            'bundle_id' => $bundle->id,
            'type' => Sale::$bundle,
            'payment_method' => Sale::$credit,
            'amount' => 0,
            'total_amount' => 0,
            'created_at' => time(),
        ]);
        return apiResponse2(1, 'enrolled', trans('lms/cart.success_pay_msg_for_free_course'));

    }

}
