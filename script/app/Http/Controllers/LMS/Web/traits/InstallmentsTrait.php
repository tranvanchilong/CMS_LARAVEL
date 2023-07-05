<?php

namespace App\Http\Controllers\LMS\Web\traits;

use App\Mixins\Installment\InstallmentPlans;
use App\Models\LMS\InstallmentOrder;
use App\Models\LMS\Product;
use App\Models\LMS\RegistrationPackage;
use App\Models\LMS\Subscribe;
use App\Models\LMS\SubscribeUse;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait InstallmentsTrait
{
    public function getInstallmentsByCourse($slug)
    {
        $user = null;

        if (auth()->guard('lms_user')->check()) {
            $user = auth()->guard('lms_user')->user();
        }


        $contentLimitation = $this->checkContentLimitation($user);
        if ($contentLimitation != "ok") {
            return $contentLimitation;
        }

        $course = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($course)) {
            $isPrivate = $course->private;
            $hasBought = $course->checkUserHasBought($user);

            if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin() or $hasBought)) {
                $isPrivate = false;
            }


            $canSale = ($course->canSale() and !$hasBought);

            if (!$isPrivate and $canSale and !empty($course->price) and $course->price > 0 and getInstallmentsSettings('status')) {
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('courses', $course->id, $course->type, $course->category_id, $course->teacher_id);

                $itemPrice = $course->getPrice();
                $cash = $installments->sum('upfront');
                $plansCount = $installments->count();
                $minimumAmount = 0;

                foreach ($installments as $installment) {
                    if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                        $minimumAmount = $installment->totalPayments($itemPrice);
                    }
                }

                $data = [
                    'pageTitle' => trans('lms/update.select_an_installment_plan'),
                    'overviewTitle' => $course->title,
                    'installments' => $installments,
                    'itemPrice' => $itemPrice,
                    'itemId' => $course->id,
                    'itemType' => 'course',
                    'cash' => $cash,
                    'plansCount' => $plansCount,
                    'minimumAmount' => $minimumAmount,
                ];

                return view('lms.web.default.installment.plans', $data);
            }
        }

        abort(404);
    }

    public function getInstallmentsByProduct(Request $request, $slug)
    {
        $user = null;

        if (auth()->guard('lms_user')->check()) {
            $user = auth()->guard('lms_user')->user();
        }

        $product = Product::where('status', Product::$active)
            ->where('slug', $slug)
            ->first();

        if (!empty($product)) {
            if (!empty($product->price) and $product->price > 0 and getInstallmentsSettings('status')) {
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('store_products', $product->id, $product->type, $product->category_id, $product->creator_id);

                $quantity = $request->get('quantity', 1);
                $itemPrice = $product->getPrice() * $quantity;
                $cash = $installments->sum('upfront');
                $plansCount = $installments->count();
                $minimumAmount = 0;

                foreach ($installments as $installment) {
                    if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                        $minimumAmount = $installment->totalPayments($itemPrice);
                    }
                }

                $data = [
                    'pageTitle' => trans('lms/update.select_an_installment_plan'),
                    'overviewTitle' => $product->title,
                    'installments' => $installments,
                    'itemPrice' => $itemPrice,
                    'itemId' => $product->id,
                    'itemType' => 'product',
                    'cash' => $cash,
                    'plansCount' => $plansCount,
                    'minimumAmount' => $minimumAmount,
                ];

                return view('lms.web.default.installment.plans', $data);
            }
        }

        abort(404);
    }

    public function getInstallmentsByRegistrationPackage($packageId)
    {
        $user = auth()->guard('lms_user')->user();

        $package = RegistrationPackage::where('id', $packageId)
            ->where('status', 'active')
            ->first();

        if (!empty($package) and $package->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
            $installmentPlans = new InstallmentPlans($user);
            $installments = $installmentPlans->getPlans('registration_packages', $package->id);

            $itemPrice = $package->getPrice();
            $cash = $installments->sum('upfront');
            $plansCount = $installments->count();
            $minimumAmount = 0;

            foreach ($installments as $installment) {
                if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                    $minimumAmount = $installment->totalPayments($itemPrice);
                }
            }

            $data = [
                'pageTitle' => trans('lms/update.select_an_installment_plan'),
                'overviewTitle' => $package->title,
                'installments' => $installments,
                'itemPrice' => $itemPrice,
                'itemId' => $package->id,
                'itemType' => 'registration_package',
                'cash' => $cash,
                'plansCount' => $plansCount,
                'minimumAmount' => $minimumAmount,
            ];

            return view('lms.web.default.installment.plans', $data);
        }

        abort(404);
    }

    public function getInstallmentsBySubscribe($subscribeId)
    {
        $user = auth()->guard('lms_user')->user();

        $subscribe = Subscribe::where('id', $subscribeId)->first();

        if (!empty($subscribe) and $subscribe->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
            $installmentPlans = new InstallmentPlans($user);
            $installments = $installmentPlans->getPlans('subscription_packages', $subscribe->id);

            $itemPrice = $subscribe->getPrice();
            $cash = $installments->sum('upfront');
            $plansCount = $installments->count();
            $minimumAmount = 0;

            foreach ($installments as $installment) {
                if ($minimumAmount == 0 or $minimumAmount > $installment->totalPayments($itemPrice)) {
                    $minimumAmount = $installment->totalPayments($itemPrice);
                }
            }

            $data = [
                'pageTitle' => trans('lms/update.select_an_installment_plan'),
                'overviewTitle' => $subscribe->title,
                'installments' => $installments,
                'itemPrice' => $itemPrice,
                'itemId' => $subscribe->id,
                'itemType' => 'subscribe',
                'cash' => $cash,
                'plansCount' => $plansCount,
                'minimumAmount' => $minimumAmount,
            ];

            return view('lms.web.default.installment.plans', $data);
        }

        abort(404);
    }

    public function checkUserHasOverdueInstallment($user = null)
    {
        if (empty($user)) {
            $user = auth()->guard('lms_user')->user();
        }

        $orders = collect();

        if (!empty($user)) {
            $time = time();
            $overdueIntervalDays = !empty(getInstallmentsSettings('overdue_interval_days')) ? getInstallmentsSettings('overdue_interval_days') : 0; // days
            $overdueIntervalDays = $overdueIntervalDays * 86400;
            $time = $time - $overdueIntervalDays;

            $orders = InstallmentOrder::query()
                ->join('lms_installments', 'lms_installment_orders.installment_id', 'lms_installments.id')
                ->join('lms_installment_steps', 'lms_installments.id', 'lms_installment_steps.installment_id')
                ->leftJoin('lms_installment_order_payments', 'lms_installment_order_payments.step_id', 'lms_installment_steps.id')
                ->select('lms_installment_orders.*', 'lms_installment_steps.amount', 'lms_installment_steps.amount_type',
                    DB::raw('((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at) as overdue_date')
                )
                ->where('user_id', $user->id)
                ->whereRaw("((lms_installment_steps.deadline * 86400) + lms_installment_orders.created_at) < {$time}")
                ->where(function ($query) { // Where Doesnt Have payment
                    $query->whereRaw("lms_installment_order_payments.id < 1");
                    $query->orWhereRaw("lms_installment_order_payments.id is null");
                })
                ->where('lms_installment_orders.status', 'open')
                ->get();
        }

        return $orders;
    }

    public function installmentContentLimitation($user, $itemId = null, $itemName = null)
    {
        if (empty($user)) {
            $user = auth()->guard('lms_user')->user();
        }

        $installmentsSettings = getInstallmentsSettings();

        if (!empty($user) and !empty($installmentsSettings['status'])) {
            $overdueInstallmentOrders = $this->checkUserHasOverdueInstallment($user);
            $denied = false;

            if ($overdueInstallmentOrders->isNotEmpty() and $installmentsSettings['disable_all_courses_access_when_user_have_an_overdue_installment']) {
                $denied = true;
            }

            if (!empty($itemId) and !empty($itemName)) {
                $itemOrders = $overdueInstallmentOrders->where($itemName, $itemId);

                if ($itemOrders->isNotEmpty() and $installmentsSettings['disable_course_access_when_user_have_an_overdue_installment']) {
                    $denied = true;
                }

                /*****
                 * Check Subscribe For Items
                 * */
                $subscribeOrders = $overdueInstallmentOrders->whereNotNull('subscribe_id');
                if ($subscribeOrders->isNotEmpty()) {
                    foreach ($subscribeOrders as $subscribeOrder) {
                        $subscribeUse = SubscribeUse::query()->whereNotNull('sale_id')
                            ->where('user_id', $user->id)
                            ->where($itemName, $itemId)
                            ->where('installment_order_id', $subscribeOrder->id)
                            ->first();

                        if (!empty($subscribeUse)) {
                            $denied = true;
                        }
                    }
                }



            }

            if ($denied) {
                $data = [
                    'pageTitle' => trans('lms/update.access_denied'),
                    'pageRobot' => getPageRobotNoIndex(),
                ];

                return view('lms.web.default.course.access_denied', $data);
            }
        }

        return "ok";
    }
}
