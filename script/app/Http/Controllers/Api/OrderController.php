<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Ordershipping;
use App\Useroption;
use App\Ordermeta;
use App\Orderitem;
use App\Category;
use App\Discount;
use App\Loyalty;
use App\LoyaltyCustomerPromotion;
use App\LoyaltyPromotion;
use App\LoyaltyRank;
use App\Order;
use App\Term;
use App\Models\Price;
use Session;
use Cart;
use Auth;
use Str;
use DB;
use App\Models\WalletTransactions;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 401);
        }

        return response()->json(Helper::track_order($request['order_id']), 200);
    }

    public function order_cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 401);
        }
        if ($order = Order::where(['id' => $request->order_id])->with('customer', 'order_content')->where('user_id', domain_info('user_id'))->first()) {
            $order_content = json_decode($order->order_content->value);
            $subTotal = $order_content->sub_total;
            if ($order->status == 'pending') {
                Order::where(['id' => $request->order_id])->update([
                    'status' => 'canceled'
                ]);

                $paymentMethodId = $order->category_id;
                $refundablePaymentMethodsId = Category::where('type','payment_getway')->whereIn('slug',['vnpay', 'momo', 'wallet'])->get();
                $refundablePaymentMethodsId = array_map(function($e){return $e['id'];}, $refundablePaymentMethodsId->toArray());

                if(in_array($paymentMethodId, $refundablePaymentMethodsId)){
                    $balance = $order->customer->wallet_balance + $subTotal;
                    WalletTransactions::create([
                        'transaction_type' => 'Order Canceled',
                        'target_id' => $order->id,
                        'customer_id' => $order->customer->id,
                        'user_id' => $order->customer->created_by,
                        'amount' => '+' . $subTotal,
                        'balance' => $balance,
                        'status' => 1
                    ]);

                    $order->customer->wallet_balance = $balance;
                    $order->customer->save();
                }

                // Loyalty
                $loyalty_status = Useroption::where('user_id', domain_info('user_id'))->where('key', 'loyalty_status')->first()->value;
                if ($loyalty_status == 1) {
                    $loyalty_point = Useroption::where('user_id', domain_info('user_id'))->where('key', 'loyalty_point')->first()->value;
                    $user_loyalty = Loyalty::where('customer_id', $order->customer->id)->first();
                    $increasePoint = $user_loyalty->loyaltyRank->increase_point ?? 0;
                    $point  = $loyalty_point != 0 ? $order->total / $loyalty_point : 0;
                    $curent_loyalty_point =
                        $increasePoint != 0 ? $increasePoint * $point : $point;
                    WalletTransactions::create([
                        'transaction_type' => 'Remove Point',
                        'target_id' => $order->id,
                        'customer_id' => $order->customer->id,
                        'user_id' => domain_info('user_id'),
                        'amount' => '-' . $curent_loyalty_point,
                        'balance' => $balance,
                        'status' => 1
                    ]);
                    $order->customer->total_point = $order->customer->total_point - $curent_loyalty_point;
                    $order->customer->save();
                }

                // Update Loyalty rank
                $ranks = LoyaltyRank::where('user_id', domain_info('user_id'))->orderBy('point', 'asc')->get();
                if ($ranks->isNotEmpty() && isset($user_loyalty)) {
                    foreach ($ranks as $rank) {
                        if ($user_loyalty->customer->total_point >= $rank->point) {
                            $user_loyalty->loyalty_rank_id = $rank->id;
                            $user_loyalty->save();
                            break;
                        }
                    }
                }

                return response()->json(['status' => true, 'message' => 'Order_canceled_successfully!'], 200);
            } else {
                return response()->json(['status' => false, 'message' =>  'Status_not_changable_now'], 302);
            }
        }
        return response()->json(['status' => false, 'message' =>  'Status_not_changable_now'], 302);
    }

    public function place_order(Request $request)
    {
        $user = Auth::user();
        Cart::restore($user->id);

        if (Cart::count() == 0) {
            return response()->json(['message' => 'Not Product']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|max:20',
            'delivery_address' => 'required|max:100',
            'zip_code' => 'required|max:50',
            'shipping_mode' => 'required',
            'payment_method' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 401);
        }

        $prefix = Useroption::where('user_id', $user->created_by)->where('key', 'order_prefix')->first();
        $max_id = Order::max('id');
        if (empty($prefix)) {
            $prefix = $max_id + 1;
        } else {
            $prefix = $prefix->value . $max_id;
        }

        $shipping_amount = Category::where('user_id', $user->created_by)->where('type', 'method')->find($request->shipping_mode);

        if ($request->payment_method == 2) {
            $payment_id = Str::random(10);
        } else {
            $payment_id = null;
        }

        DB::beginTransaction();
        $apply_voucher = $this->applyVoucher($request->code, Cart::subtotal());
        try {
            $order = new Order;
            $order->order_no = $prefix;
            $order->customer_id = $user->id;
            $order->user_id  = $user->created_by;
            $order->order_type  = 1;
            $order->payment_status = 2;
            $order->status = 'pending';
            $order->transaction_id = $payment_id;
            $order->category_id = $request->payment_method;
            $order->payment_status = 2;
            $order->tax = Cart::tax();
            $order->shipping = $this->calculateWeight(Cart::weight(), $shipping_amount->slug ?? 0);
            $order->total = $this->calculateShipping(Cart::total(), $shipping_amount->slug ?? 0, Cart::weight()) - $apply_voucher;
            $order->save();

            $info['name'] = $request->name;
            $info['email'] = $request->email;
            $info['phone'] = $request->phone;
            $info['comment'] = $request->comment;
            $info['address'] = $request->delivery_address;
            $info['zip_code'] = $request->zip_code;
            $info['coupon_discount'] = Cart::discount() + $apply_voucher;
            $info['sub_total'] = Cart::subtotal();

            $meta = new Ordermeta;
            $meta->order_id = $order->id;
            $meta->key = 'content';
            $meta->value = json_encode($info);
            $meta->save();

            $items = [];

            foreach (Cart::content() as $key => $row) {
                $options['attribute'] = $row->options->attribute;
                $options['options'] = $row->options->options;

                $data['order_id'] = $order->id;
                $data['term_id'] = $row->id;
                $data['info'] = json_encode($options);
                $data['qty'] = $row->qty;
                $data['amount'] = $row->price;
                array_push($items, $data);
            }

            Orderitem::insert($items);
            if ($request->location && $request->shipping_mode) {
                $ship['order_id'] = $order->id;
                $ship['location_id'] = $request->location;
                $ship['shipping_id'] = $request->shipping_mode;
                Ordershipping::insert($ship);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }


        if ($request->payment_method != 2) {
            $payment_data['ref_id'] = $order->id;
            $payment_data['getway_id'] = $request->payment_method;
            $payment_data['amount'] = $order->total;
            $payment_data['email'] = $request->email;
            $payment_data['name'] = $request->name;
            $payment_data['phone'] = $request->phone;
            $payment_data['billName'] = 'Order No :' . $order->order_no;
            Session::put('customer_order_info', $payment_data);
            Session::put('order_info', $payment_data);
            //Wallet
            if ($request->payment_method == 1145) {
                $cartTotal = Cart::total();
                if ($cartTotal > $user->wallet_balance) {
                    Order::destroy($order->id);
                    return response()->json(['message' => 'Inefficient balance in your wallet to pay for this order!'], 401);
                }
                $balance = $user->wallet_balance - $cartTotal;
                WalletTransactions::create([
                    'transaction_type' => 'Order Place',
                    'target_id' => $order->id,
                    'customer_id' => $user->id,
                    'user_id' => domain_info('user_id'),
                    'amount' => $cartTotal,
                    'balance' => $balance,
                    'status' => 1
                ]);

                $user->wallet_balance = $balance;
                $user->save();
            }
        }
        // Loyalty
        $loyalty_status = Useroption::where('user_id', domain_info('user_id'))->where('key', 'loyalty_status')->first()->value;
        if ($loyalty_status == 1) {
            $loyalty_point = Useroption::where('user_id', domain_info('user_id'))->where('key', 'loyalty_point')->first()->value;
            $isExistsUser = Loyalty::where('customer_id', $user->id)->first();
            $increasePoint = $isExistsUser->loyaltyRank->increase_point ?? 0;
            $point  = $loyalty_point != 0 ? $order->total / $loyalty_point : 0;
            $curent_loyalty_point =
                $increasePoint != 0 ? $increasePoint * $point : $point;
            WalletTransactions::create([
                'transaction_type' => 'Add Point',
                'target_id' => $order->id,
                'customer_id' => $user->id,
                'user_id' => domain_info('user_id'),
                'amount' => $curent_loyalty_point,
                'balance' => $curent_loyalty_point,
                'status' => 1
            ]);

            $user->total_point = $user->total_point + $curent_loyalty_point;
            $user->save();

            // Update Loyalty rank
            $ranks = LoyaltyRank::where('user_id', domain_info('user_id'))->orderBy('point', 'asc')->get();
            if ($ranks->isNotEmpty()) {
                if (isset($isExistsUser)) {
                    foreach ($ranks as $rank) {
                        if ($isExistsUser->customer->total_point >= $rank->point) {
                            $isExistsUser->loyalty_rank_id = $rank->id;
                            $isExistsUser->save();
                            break;
                        }
                    }
                } else {
                    $loyalty = new Loyalty();
                    $loyalty->user_id = domain_info('user_id');
                    $loyalty->customer_id = $user->id;
                    $loyalty->loyalty_rank_id = $ranks->first()->id;
                    $loyalty->save();
                }
            }
        }
        Cart::destroy();
        Cart::store($user->id);

        return response()->json(['status' => true, 'message' => 'Order_Place_Successfully!'], 200);
    }

    public function store_order(Request $request)
    {
        $total = 0;
        $items = [];
        $shop_type = domain_info('shop_type');
        $domain_id = domain_info('domain_id');
        $user_id = domain_info('user_id');
        $taxs = Useroption::where('user_id', $user_id)->where('key', 'tax')->first()->value;
        foreach ($request->products as $key => $product) {
            $price = Price::where('term_id', $product['id'])->first();
            $term = Term::where('id', $product['id'])->first();
            if (!$price) {
                return ['status' => false, 'error' => 'Not Product'];
            }
            $total += ($price->price * $product['quantity']);
        }
        $tax = $total / $taxs;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|max:20',
            'shipping_mode' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 401);
        }
        if ($shop_type == 1) {
            $validated = $request->validate([
                'delivery_address' => 'required|max:100',
                'zip_code' => 'required|max:50',
            ]);
        }
        $prefix = Useroption::where('user_id', $user_id)->where('key', 'order_prefix')->first();

        $max_id = Order::max('id');
        if (empty($prefix)) {
            $prefix = $max_id + 1;
        } else {
            $prefix = $prefix->value . $max_id;
        }

        $shipping_amount = Category::where('user_id', $user_id)->where('type', 'method')->find($request->shipping_mode);

        if ($request->payment_method == 2) {
            $payment_id = Str::random(10);
        } else {
            $payment_id = null;
        }

        DB::beginTransaction();

        try {
            $order = new Order;
            $order->order_no = $prefix;
            if (Auth::guard('customer')->check()) {
                $order->customer_id = Auth::guard('customer')->user()->id;
            }

            $order->user_id  = $user_id;
            $order->order_type  = $shop_type;
            $order->payment_status = 2;
            $order->status = 'pending';
            $order->transaction_id = $payment_id;
            $order->category_id = $request->payment_method;
            $order->payment_status = 2;
            $order->tax = $tax;
            $order->shipping = $shipping_amount->slug ?? 0;
            $order->total = $total + $order->shipping + $tax;
            $order->save();

            $info['name'] = $request->name;
            $info['email'] = $request->email;
            $info['phone'] = $request->phone;
            $info['comment'] = $request->comment;
            $info['address'] = $request->delivery_address;
            $info['zip_code'] = $request->zip_code;
            $info['coupon_discount'] = 0;
            $info['sub_total'] = $total;

            $meta = new Ordermeta;
            $meta->order_id = $order->id;
            $meta->key = 'content';
            $meta->value = json_encode($info);
            $meta->save();

            $items = [];

            foreach ($request->products as $key => $product) {
                $price = Price::where('term_id', $product['id'])->first();
                $term = Term::where('id', $product['id'])->first();
                $options['attribute'] = [];
                $options['options'] = [];

                $data = [
                    'order_id' => $order->id,
                    'term_id' => $product['id'],
                    'info' => json_encode($options),
                    'qty' => $product['quantity'],
                    'amount' => $price->price
                ];
                $items[] = $data;
            }

            Orderitem::insert($items);
            if ($request->location && $request->shipping_mode) {
                $ship['order_id'] = $order->id;
                $ship['location_id'] = $request->location;
                $ship['shipping_id'] = $request->shipping_mode;
                Ordershipping::insert($ship);
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Order_Place_Successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => 'abc'], 401);
        }
    }

    public function calculateShipping($total, $shipping_amount, $weight)
    {
        $shipping_amount = (float)$shipping_amount;
        $totalAmount = $total;

        $weight_amount = $this->calculateWeight($weight, $shipping_amount);
        $amount = $totalAmount + $weight_amount;

        return $amount;
    }

    public function calculateWeight($weight, $amount)
    {
        return $amount;
    }

    public function applyVoucher($code, $cart_total)
    {
        $promotion = LoyaltyPromotion::where('user_id', domain_info('user_id'))->where('code', $code)->first();
        if (isset($promotion)) {
            $detail = LoyaltyCustomerPromotion::where('loyalty_promotion_id', $promotion->id)->where('customer_id', Auth::id())
                ->where('expiration_date', '>=', date('Y-m-d'))->first();
            if (!isset($detail)) {
                return 0;
            }
            $detail->delete();
            $product = Term::where('user_id', domain_info('user_id'))->find($promotion->term_id);
            if (!isset($product)) {
                return 0;
            }
            return
                $promotion->type == 'percent' ? $product->price->price * ($promotion->reduction_rate / 100)  : $promotion->reduction_rate;
        }
        $discount = Discount::where('user_id', domain_info('user_id'))->where('code', $code)->first();
        if (!isset($discount)) {
            return 0;
        }
        return $discount->discount_type == 'percent' ? $cart_total * ($discount->discount_amount / 100)  : $discount->discount_amount;
    }
}
