<?php

namespace App\Http\Controllers\LMS\Admin\Store;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Group;
use App\Models\LMS\GroupUser;
use App\Models\LMS\Product;
use App\Models\LMS\ProductOrder;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellersController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_products_sellers');

        $query = User::query()
            ->whereHas('products');

        $users = $this->handleFilters($request, $query)
            ->with([
                'products'
            ])
            ->withCount([
                'products as virtual_products_count' => function ($query) {
                    $query->where('type', Product::$virtual);
                },
                'products as physical_products_count' => function ($query) {
                    $query->where('type', Product::$physical);
                },
                'productOrdersAsSeller as pending_orders_count' => function ($query) {
                    $query->whereNotNull('sale_id');
                    $query->where('status', ProductOrder::$waitingDelivery);
                }
            ])->paginate(10);

        $userGroups = Group::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($users as $user) {
            $user->total_sales = $this->getTotalSales($user);
            $user->virtual_products_sales = $this->getTotalSales($user, Product::$virtual);
            $user->physical_products_sales = $this->getTotalSales($user, Product::$physical);
        }


        $data = [
            'pageTitle' => trans('lms/update.products_sellers'),
            'users' => $users,
            'userGroups' => $userGroups,
        ];

        return view('lms.admin.store.sellers.index', $data);
    }

    private function getTotalSales($user, $productType = null)
    {
        $types = [Product::$virtual, Product::$physical];

        if ($productType == Product::$virtual) {
            $types = [Product::$virtual];
        } else if ($productType == Product::$physical) {
            $types = [Product::$physical];
        }

        $productIds = $user->products->whereIn('type', $types)->pluck('id')->toArray();

        $sale = ProductOrder::whereIn('product_id', $productIds)
            ->join('lms_sales', 'lms_sales.product_order_id', 'lms_product_orders.id')
            ->select(DB::raw("sum(amount) as total"))
            ->whereNull('lms_sales.refund_at')
            ->whereNotNull('lms_product_orders.sale_id')
            ->first();

        return $sale->total ?? 0;
    }

    private function handleFilters(Request $request, $query)
    {
        $full_name = $request->get('full_name');
        $group_id = $request->get('group_id');
        $role_name = $request->get('role_name');

        if (!empty($full_name)) {
            $query->where('full_name', 'like', "%$full_name%");
        }

        if (!empty($group_id)) {
            $userIds = GroupUser::where('group_id', $group_id)->pluck('user_id')->toArray();

            $query->whereIn('id', $userIds);
        }

        if (!empty($role_name)) {
            $query->where('role_name', $role_name);
        }

        return $query;
    }
}
