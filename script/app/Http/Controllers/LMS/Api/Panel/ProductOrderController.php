<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\ProductOrderResource;
use App\Http\Resources\ProductResource;
use App\Models\LMS\Api\ProductOrder;
use App\Models\LMS\Comment;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = apiAuth();

        $query = ProductOrder::where('lms_product_orders.seller_id', $user->id)
            ->where('lms_product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });

        $totalOrders = deepClone($query)->count();
        $pendingOrders = deepClone($query)->where('lms_product_orders.status', ProductOrder::$waitingDelivery)->count();
        $canceledOrders = deepClone($query)->where('lms_product_orders.status', ProductOrder::$canceled)->count();

        $totalSales = deepClone($query)
            ->join('lms_sales', 'lms_sales.product_order_id', 'lms_product_orders.id')
            ->select(DB::raw('(sum(lms_sales.total_amount) - (sum(lms_sales.tax) + sum(lms_sales.commission))) as totalAmount')) // DB::raw("sum(lms_sales.total_amount) as totalAmount")
            ->first();


        $orders = $query->handleFilters()->orderBy('created_at', 'desc')->get();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            ['orders' => ProductOrderResource::collection($orders),
                'total_orders_count' => $totalOrders,
                'pending_orders_count' => $pendingOrders,
                'canceled_orders_count' => $canceledOrders,
                'total_sales' => $totalSales->totalAmount ?? 0,
            ]);

    }

    public function getBuyers()
    {
        $user = apiAuth();

        $query = ProductOrder::where('lms_product_orders.seller_id', $user->id)
            ->where('lms_product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });
        $customerIds = deepClone($query)->pluck('buyer_id')->toArray();
        $customers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($customerIds))
            ->get();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            ['users' => $customers
            ]);

    }

    public function getPurchases()
    {
        $query = ProductOrder::where('lms_product_orders.buyer_id', apiauth()->guard('lms_user')->id)
            ->where('lms_product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->where('type', 'product');
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });

        $totalOrders = deepClone($query)->count();
        $pendingOrders = deepClone($query)->where(function ($query) {
            $query->where('status', ProductOrder::$waitingDelivery)
                ->orWhere('status', ProductOrder::$shipped);
        })->count();
        $canceledOrders = deepClone($query)->where('status', ProductOrder::$canceled)->count();

        $totalPurchase = deepClone($query)
            ->join('lms_sales', 'lms_sales.product_order_id', 'lms_product_orders.id')
            ->select(DB::raw("sum(total_amount) as totalAmount"))
            ->first();

        $orders = $query->handleFilters()->orderBy('created_at', 'desc')
            ->get();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'total_orders_count' => $totalOrders,
                'pending_orders_count' => $pendingOrders,
                'canceled_orders_count' => $canceledOrders,
                'total_purchase_amount' => $totalPurchase->totalAmount ?? 0,
                'orders' => ProductOrderResource::collection($orders),
            ]);

    }

    public function getSellers()
    {
        $query = ProductOrder::where('lms_product_orders.buyer_id', apiauth()->guard('lms_user')->id)
            ->where('lms_product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->where('type', 'product');
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });

        $sellerIds = deepClone($query)->pluck('seller_id')->toArray();
        $sellers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($sellerIds))
            ->get();
    }


}
