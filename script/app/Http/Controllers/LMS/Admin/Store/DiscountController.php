<?php

namespace App\Http\Controllers\LMS\Admin\Store;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Product;
use App\Models\LMS\ProductDiscount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_discounts');

        $query = ProductDiscount::query();

        $discounts = $this->filters($query, $request)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        $data = [
            'pageTitle' => trans('lms/admin/main.discounts'),
            'discounts' => $discounts,
        ];

        $product_ids = $request->get('product_ids');
        if (!empty($product_ids)) {
            $data['products'] = Product::select('id')
                ->whereIn('id', $product_ids)
                ->get();
        }

        return view('lms.admin.store.discounts.lists', $data);
    }

    private function filters($query, $request)
    {
        $name = $request->get('name');
        $from = $request->get('from');
        $to = $request->get('to');
        $sort = $request->get('sort');
        $product_ids = $request->get('product_ids');
        $status = $request->get('status');

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($from)) {
            $from = strtotime($from);
            $query->where('start_date', '>=', $from);
        }

        if (!empty($to)) {
            $to = strtotime($to);
            $query->where('end_date', '<', $to);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'percent_asc':
                    $query->orderBy('percent', 'asc');
                    break;
                case 'percent_desc':
                    $query->orderBy('percent', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'expire_at_asc':
                    $query->orderBy('end_date', 'asc');
                    break;
                case 'expire_at_desc':
                    $query->orderBy('end_date', 'desc');
                    break;
            }
        }

        if (!empty($product_ids)) {
            $query->whereIn('product_id', $product_ids);
        }

        if (!empty($status) and in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_discounts_create');

        $data = [
            'pageTitle' => trans('lms/admin/main.create'),
        ];

        return view('lms.admin.store.discounts.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_discounts_create');

        $this->validate($request, [
            'product_id' => 'required',
            'percent' => 'required',
            'status' => 'nullable|in:active,inactive',
            'count' => 'nullable|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $data = $request->all();

        $product = Product::findOrFail($data["product_id"]);

        $activeDiscountForProduct = $product->getActiveDiscount();

        if ($activeDiscountForProduct) {
            $toastData = [
                'title' => trans('lms/public.request_failed'),
                'msg' => trans('lms/update.this_product_has_an_active_discount'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $startDate = convertTimeToUTCzone($data['start_date'], getTimezone());
        $endDate = convertTimeToUTCzone($data['end_date'], getTimezone());

        ProductDiscount::create([
            'creator_id' => auth()->guard('lms_user')->id(),
            'name' => $data["name"],
            'product_id' => $data["product_id"],
            'percent' => $data["percent"],
            'status' => $data["status"],
            'count' => $data["count"] ?? null,
            'created_at' => time(),
            'start_date' => $startDate->getTimestamp(),
            'end_date' => $endDate->getTimestamp(),
        ]);

        return redirect('/lms/admin/store/discounts');
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_edit');

        $discount = ProductDiscount::findOrFail($id);

        $data = [
            'pageTitle' => trans('lms/admin/main.edit'),
            'discount' => $discount,
        ];

        return view('lms.admin.store.discounts.new', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_discounts_create');

        $this->validate($request, [
            'product_id' => 'required',
            'percent' => 'required',
            'status' => 'nullable|in:active,inactive',
            'count' => 'nullable|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $discount = ProductDiscount::findOrfail($id);

        $data = $request->all();

        $startDate = convertTimeToUTCzone($data['start_date'], getTimezone());
        $endDate = convertTimeToUTCzone($data['end_date'], getTimezone());

        $discount->update([
            'creator_id' => auth()->guard('lms_user')->id(),
            'name' => $data["name"],
            'product_id' => $data["product_id"],
            'percent' => $data["percent"],
            'status' => $data["status"],
            'count' => $data["count"] ?? null,
            'created_at' => time(),
            'start_date' => $startDate->getTimestamp(),
            'end_date' => $endDate->getTimestamp(),
        ]);

        return redirect('/lms/admin/store/discounts');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_discounts_delete');

        ProductDiscount::findOrfail($id)->delete();

        return redirect()->back();
    }
}
