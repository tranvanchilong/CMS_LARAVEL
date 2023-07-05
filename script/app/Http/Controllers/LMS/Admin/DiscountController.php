<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Discount;
use App\Models\LMS\DiscountBundle;
use App\Models\LMS\DiscountCategory;
use App\Models\LMS\DiscountCourse;
use App\Models\LMS\DiscountGroup;
use App\Models\LMS\DiscountUser;
use App\Models\LMS\Group;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_discount_codes_list');

        $query = Discount::query();

        $query = $this->filters($query, $request);

        $discounts = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.discount_codes_title'),
            'discounts' => $discounts,
        ];

        return view('lms.admin.financial.discount.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');
        $user_ids = $request->get('user_ids', []);
        $sort = $request->get('sort');


        $query = fromAndToDateFilter($from, $to, $query, 'expired_at');


        if (!empty($user_ids) and count($user_ids)) {
            $discountIds = DiscountUser::whereIn('user_id', $user_ids)->pluck('discount_id');

            $query = $query->whereIn('id', $discountIds);
        }

        if (isset($search)) {
            $query = $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'percent_asc':
                    $query->orderBy('percent', 'asc');
                    break;
                case 'percent_desc':
                    $query->orderBy('percent', 'desc');
                    break;
                case 'amount_asc':
                    $query->orderBy('amount', 'asc');
                    break;
                case 'amount_desc':
                    $query->orderBy('amount', 'desc');
                    break;
                case 'usable_time_asc':
                    $query->orderBy('count', 'asc');
                    break;
                case 'usable_time_desc':
                    $query->orderBy('count', 'desc');
                    break;
                case 'usable_time_remain_asc':
                    $query->leftJoin('lms_order_items', 'lms_discounts.id', '=', 'lms_order_items.discount_id')
                        ->select('lms_discounts.*', 'lms_order_items.order_id', DB::raw('(lms_discounts.count - count(lms_order_items.order_id)) as remain_count'))
                        ->leftJoin('lms_orders', 'lms_orders.id', '=', 'lms_order_items.order_id')
                        ->where(function ($query) {
                            $query->whereNull('order_id')
                                ->orWhere('lms_orders.status', 'paid');
                        })
                        ->groupBy('lms_order_items.order_id')
                        ->orderBy('remain_count', 'asc');
                    break;
                case 'usable_time_remain_desc':
                    $query->leftJoin('lms_order_items', 'lms_discounts.id', '=', 'lms_order_items.discount_id')
                        ->select('lms_discounts.*', 'lms_order_items.order_id', DB::raw('(lms_discounts.count - count(lms_order_items.order_id)) as remain_count'))
                        ->leftJoin('lms_orders', 'lms_orders.id', '=', 'lms_order_items.order_id')
                        ->where(function ($query) {
                            $query->whereNull('order_id')
                                ->orWhere('lms_orders.status', 'paid');
                        })
                        ->groupBy('lms_order_items.order_id')
                        ->orderBy('remain_count', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'expire_at_asc':
                    $query->orderBy('expired_at', 'asc');
                    break;
                case 'expire_at_desc':
                    $query->orderBy('expired_at', 'desc');
                    break;
            }
        }

        return $query;
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_discount_codes_create');

        $userGroups = Group::orderBy('created_at', 'desc')->where('status', 'active')->get();

        $data = [
            'pageTitle' => trans('lms/admin/main.new_discount_title'),
            'userGroups' => $userGroups,
        ];

        return view('lms.admin.financial.discount.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_discount_codes_create');

        $this->validate($request, [
            'title' => 'required',
            'discount_type' => 'required|in:' . implode(',', Discount::$discountTypes),
            'source' => 'required|in:' . implode(',', Discount::$discountSource),
            'code' => 'required|unique:lms_discounts',
            'user_id' => 'nullable',
            'percent' => 'nullable',
            'amount' => 'nullable',
            'count' => 'nullable',
            'expired_at' => 'required',
        ]);

        $data = $request->all();

        $user_id = $data['user_id'] ?? [];

        $discountType = 'all_users';
        if (!empty($user_id)) {
            $discountType = 'special_users';
        }

        $expiredAt = convertTimeToUTCzone($data['expired_at'], getTimezone());

        $discount = Discount::create([
            'creator_id' => auth()->guard('lms_user')->id(),
            'title' => $data['title'],
            'discount_type' => $data['discount_type'],
            'source' => $data['source'],
            'code' => $data['code'],
            'percent' => (!empty($data['percent']) and $data['percent'] > 0) ? $data['percent'] : 0,
            'amount' => $data['amount'],
            'max_amount' => $data['max_amount'],
            'minimum_order' => $data['minimum_order'],
            'count' => (!empty($data['count']) and $data['count'] > 0) ? $data['count'] : 1,
            'user_type' => $discountType,
            'product_type' => $data['product_type'] ?? null,
            'for_first_purchase' => $data['for_first_purchase'],
            'status' => 'active',
            'expired_at' => $expiredAt->getTimestamp(),
            'created_at' => time(),
        ]);

        $this->handleRelationItems($discount, $data);

        return redirect('/lms/admin/financial/discounts');
    }

    private function handleRelationItems($discount, $data)
    {
        $user_id = $data['user_id'] ?? [];
        $coursesIds = $data['webinar_ids'] ?? [];
        $bundlesIds = $data['bundle_ids'] ?? [];
        $categoriesIds = $data['category_ids'] ?? [];
        $groupsIds = $data['group_ids'] ?? [];

        if (!empty($user_id)) {
            DiscountUser::create([
                'discount_id' => $discount->id,
                'user_id' => $user_id,
                'created_at' => time(),
            ]);
        }

        if (!empty($coursesIds) and count($coursesIds)) {
            foreach ($coursesIds as $coursesId) {
                DiscountCourse::create([
                    'discount_id' => $discount->id,
                    'course_id' => $coursesId,
                    'created_at' => time(),
                ]);
            }
        }

        if (!empty($bundlesIds) and count($bundlesIds)) {
            foreach ($bundlesIds as $bundlesId) {
                DiscountBundle::create([
                    'discount_id' => $discount->id,
                    'bundle_id' => $bundlesId,
                    'created_at' => time(),
                ]);
            }
        }

        if (!empty($categoriesIds) and count($categoriesIds)) {
            foreach ($categoriesIds as $categoryId) {
                DiscountCategory::create([
                    'discount_id' => $discount->id,
                    'category_id' => $categoryId,
                    'created_at' => time(),
                ]);
            }
        }

        if (!empty($groupsIds) and count($groupsIds)) {
            foreach ($groupsIds as $groupsId) {
                DiscountGroup::create([
                    'discount_id' => $discount->id,
                    'group_id' => $groupsId,
                    'created_at' => time(),
                ]);
            }
        }
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_discount_codes_edit');

        $discount = Discount::findOrFail($id);
        $userDiscounts = DiscountUser::where('discount_id', $id)->get();
        $userGroups = Group::orderBy('created_at', 'desc')->where('status', 'active')->get();

        $discountGroupIds = [];
        if (!empty($discount->discountGroups)) {
            $discountGroupIds = $discount->discountGroups->pluck('group_id')->toArray();
        }


        $data = [
            'pageTitle' => trans('lms/admin/main.edit_discount_title'),
            'discount' => $discount,
            'userDiscounts' => $userDiscounts,
            'userGroups' => $userGroups,
            'discountGroupIds' => $discountGroupIds,
        ];

        return view('lms.admin.financial.discount.new', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_discount_codes_edit');

        $discount = Discount::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'discount_type' => 'required|in:' . implode(',', Discount::$discountTypes),
            'source' => 'required|in:' . implode(',', Discount::$discountSource),
            'code' => 'required|unique:lms_discounts,code,' . $discount->id,
            'user_id' => 'nullable',
            'percent' => 'nullable',
            'amount' => 'nullable',
            'count' => 'nullable',
            'expired_at' => 'required',
        ]);

        $data = $request->all();
        $user_id = $data['user_id'] ?? [];

        $discountType = 'all_users';
        if (!empty($user_id)) {
            $discountType = 'special_users';
        }

        $expiredAt = convertTimeToUTCzone($data['expired_at'], getTimezone());

        $discount->update([
            'title' => $data['title'],
            'discount_type' => $data['discount_type'],
            'source' => $data['source'],
            'code' => $data['code'],
            'percent' => (!empty($data['percent']) and $data['percent'] > 0) ? $data['percent'] : 0,
            'amount' => $data['amount'],
            'max_amount' => $data['max_amount'],
            'minimum_order' => $data['minimum_order'],
            'count' => (!empty($data['count']) and $data['count'] > 0) ? $data['count'] : 1,
            'user_type' => $discountType,
            'product_type' => $data['product_type'] ?? null,
            'for_first_purchase' => $data['for_first_purchase'],
            'status' => 'active',
            'expired_at' => $expiredAt->getTimestamp(),
        ]);

        DiscountUser::where('discount_id', $discount->id)->delete();

        DiscountCourse::where('discount_id', $discount->id)->delete();

        DiscountBundle::where('discount_id', $discount->id)->delete();

        DiscountCategory::where('discount_id', $discount->id)->delete();

        DiscountGroup::where('discount_id', $discount->id)->delete();

        $this->handleRelationItems($discount, $data);

        return redirect('/lms/admin/financial/discounts');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_discount_codes_delete');

        Discount::find($id)->delete();

        return redirect('/lms/admin/financial/discounts');
    }
}
