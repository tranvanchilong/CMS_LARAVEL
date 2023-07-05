<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Discount;
use App\LoyaltyCustomerPromotion;
use App\LoyaltyPromotion;
use App\Term;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function apply(Request $request)
    {
        $user = Auth::user();
        Cart::restore($user->id);
        if (Cart::count() == 0) {
            return response()->json(['message' => 'Not Product']);
        }
        Cart::store($user->id);

        $promotion = LoyaltyPromotion::where('user_id', domain_info('user_id'))->where('code', $request->code)->first();
        if (isset($promotion)) {
            $detail = LoyaltyCustomerPromotion::where('loyalty_promotion_id', $promotion->id)
                ->where('customer_id', Auth::id())
                ->where('expiration_date', '>=', date('Y-m-d'))->first();
            if (!isset($detail)) {
                return response()->json(['message' => 'You are not eligible to use this promotion']);
            }
            $product_id = $promotion->term_id;
            if ($this->searchProductInCart($product_id) == false) {
                return response()->json(['message' => 'Not found Product.']);
            }
            $product = Term::where('user_id', domain_info('user_id'))->find($promotion->term_id);
            $product_discount = $promotion->type == 'percent' ? $product->price->price * ($promotion->reduction_rate / 100)  : $promotion->reduction_rate;
            return response()->json([
                'message' => $promotion->name,
                'discount' => number_format($product_discount, 0, ',', '.'),
            ]);
        } else {
            $discount = Discount::where('user_id', domain_info('user_id'))->where('code', $request->input('code'))->first();
            if (!isset($discount)) {
                return response()->json(['message' => 'not found code'], 403);
            }
            if($discount->term_id == null && $discount->shipping_id == null && $discount->order_amount == 0 && $discount->order_price == 0){
                return response()->json(['message' => 'You have not selected a discount type'], 403);
            }

            $price_discount = $discount->discount_type == 'percent' ? Cart::subtotal() * ($discount->discount_amount / 100)  : $discount->discount_amount;
            // Khuyến mãi khi mua sản phẩm kièm số lượng
            if ($discount->term_id != null && $discount->order_amount != 0) {
                $product_id = $discount->term_id;
                if ($this->searchProductInCart($product_id) == false) {
                    return response()->json(['message' => 'Not found Product']);
                }
                $qty_item = Cart::content()->whereIn('id', $discount->term_id)->first()->qty;
                if ($qty_item < $discount->order_amount) {
                    return response()->json(['message' => 'The number of products is not enough']);
                }
                $product = Term::where('user_id', domain_info('user_id'))->find($discount->term_id);
                $product_discount = ($discount->discount_type == 'percent' ? $product->price->price * ($discount->discount_amount / 100)  : $discount->discount_amount) * $qty_item;
                return response()->json([
                    'message' => 'Discount Product',
                    'discount' => number_format($product_discount, 0, ',', '.'),
                ]);
            }
            // Miễn Phí shipping
            if ($discount->shipping_id != null) {
                $shipping = Category::where('user_id', domain_info('user_id'))->where('type', 'method')->find($discount->shipping_id);
                if (isset($shipping)) {
                    return response()->json([
                        'message' => 'Free Shipping',
                        'discount' => number_format($shipping->slug, 0, ',', '.'),
                    ]);
                }
            }
            //Khuyến mãi khi mua x sản phẩm
            if ($discount->order_amount != 0) {
                if ($discount->order_amount <= Cart::count()) {
                    return response()->json([
                        'message' => 'Discount Order Amount',
                        'discount' => number_format($price_discount, 0, ',', '.'),
                    ]);
                }
                return response()->json([
                    'message' => 'Voucher applies when buying ' . $discount->order_amount . ' or more products',
                ]);
            }
            //Khuyến mãi khi tổng đơn hơn x tiền
            if ($discount->order_price != 0) {
                if ($discount->order_price <= Cart::subtotal()) {
                    return response()->json([
                        'message' => 'Discount Order Price',
                        'discount' => number_format($price_discount, 0, ',', '.'),
                    ]);
                }
                return response()->json([
                    'message' => 'Voucher applies to orders of ' . number_format($discount->order_price, 0, ',', '.') . ' or more',
                ]);
            }
            // Khuyến mãi theo sản phảm
            if ($discount->term_id != null) {
                $product_id = $discount->term_id;
                if ($this->searchProductInCart($product_id) == false) {
                    return response()->json(['message' => 'Not found Product']);
                }
                $product = Term::where('user_id', domain_info('user_id'))->find($discount->term_id);
                $item = Cart::content()->whereIn('id', $product->id)->first()->qty ?? 0;
                $product_discount = ($discount->discount_type == 'percent' ? $product->price->price * ($discount->discount_amount / 100)  : $discount->discount_amount) * $item;
                return response()->json([
                    'message' => 'Discount Product',
                    'discount' => number_format($product_discount, 0, ',', '.'),
                ]);
            }
        }
        return response()->json(['message' => 'No promotional']);
    }

    public function getAllDiscount(Request $request)
    {
        $coupons = Discount::where('user_id', domain_info('user_id'))
            ->whereDate('end_at', '>=', date('Y-m-d'))
            ->latest()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);;
        return response()->json($coupons, 200);
    }
    public function getDiscountExist()
    {
        $coupons = Discount::where('user_id', domain_info('user_id'))
            ->whereDate('end_at', '>=', date('Y-m-d'))
            ->latest()
            ->limit(5)->get();
        $total = count($coupons);
        return response()->json([
            'total_counpon' => $total,
            'data' => $coupons,
        ], 200);
    }
    protected function searchProductInCart($product_id)
    {
        $user = Auth::user();
        Cart::restore($user->id);
        Cart::store($user->id);
        $cart = Cart::content();
        
        $cartItem = $cart->search(function ($cartItem, $id) use ($product_id) {
            return $cartItem->id === $product_id;
        });
        
        if ($cartItem) {
            return true;
        }
        return false;
    }
}
