<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Loyalty;
use App\LoyaltyCustomerPromotion;
use App\LoyaltyPromotion;
use App\LoyaltyRank;
use App\Models\WalletTransactions;
use App\Useroption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoyaltyController extends Controller
{
    public function getLoyaltyRank()
    {
        $ranks = LoyaltyRank::where('user_id', domain_info('user_id'))->latest()->get();
        foreach ($ranks as $item) {
            $content = $item->content;
            if (is_string($content)) {
                $content = json_decode($content, true);
                foreach ($content as &$subitem) {
                    $subitem = json_decode($subitem, true);
                }
            }
            $item->content = $content;
        }
        return response()->json($ranks, 200);
    }

    public function historyTransactionPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 401);
        }

        $loyalty_status = Useroption::where('user_id', domain_info('user_id'))->where('key', 'loyalty_status')->first();
        $loyalty_status = !empty($loyalty_status) ? $loyalty_status->value : null;

        if ($loyalty_status == 1) {
            $user = Auth::user();
            $total_point = number_format($user->total_point, 0, ',', '.');
            $curent_point =  number_format($user->curent_point, 0, ',', '.');
            $wallet_transactio_list = WalletTransactions::where('user_id', domain_info('user_id'))
                ->where('customer_id', $user->id)
                ->where('transaction_type', '=', 'Add Point')
                ->orWhere('transaction_type', '=', 'Remove Point')
                ->orWhere('transaction_type', '=', 'Reward Redemption')
                ->latest()
                ->paginate($request['limit'], ['*'], 'page', $request['offset']);

            return response()->json([
                'limit' => (int)$request->limit,
                'offset' => (int)$request->offset,
                'total_point' => $total_point,
                'curent_point' => $curent_point,
                'total_point_transaction' => $wallet_transactio_list->total(),
                'point_transaction_list' => $wallet_transactio_list->items()
            ], 200);
        } else {
            return response()->json(['message' => 'Access Denied!'], 422);
        }
    }

    public function getLoyaltyRankForUser()
    {
        $loyalty_status = Useroption::where('user_id', domain_info('user_id'))->where('key', 'loyalty_status')->first();
        $loyalty_status = !empty($loyalty_status) ? $loyalty_status->value : null;
        if ($loyalty_status == 1) {
            $loyalty = Loyalty::where('customer_id', Auth::id())->with(['loyaltyRank', 'customer'])->first();
            return response()->json($loyalty, 200);
        } else {
            return response()->json(['message' => 'Access Denied!'], 422);
        }
    }

    public function getPromotionCategories()
    {
        $categories = Category::where('user_id', domain_info('user_id'))->where('type', 'promotion')->get();
        return response()->json($categories, 200);
    }

    public function getPromotionByCategory(Request $request)
    {
        $promotions = LoyaltyPromotion::where('user_id', domain_info('user_id'))
            ->whereDate('end_at', '>=', date('Y-m-d'))
            ->where('category_id', $request->category_id)
            ->get();
        return response()->json($promotions, 200);
    }

    public function getPromotionBySource(Request $request)
    {
        $promotions = LoyaltyPromotion::where('user_id', domain_info('user_id'))
            ->whereDate('end_at', '>=', date('Y-m-d'))
            ->where('source', $request->source)
            ->get();
        return response()->json($promotions, 200);
    }

    public function getPromotionByFeature()
    {
        $promotions = LoyaltyPromotion::where('user_id', domain_info('user_id'))
            ->whereDate('end_at', '>=', date('Y-m-d'))
            ->where('featured', 1)
            ->get();
        return response()->json($promotions, 200);
    }

    public function getPromotions()
    {
        $promotions = LoyaltyPromotion::where('user_id', domain_info('user_id'))
            ->whereDate('end_at', '>=', date('Y-m-d'))
            ->get();
        return response()->json($promotions, 200);
    }

    public function redeemLoyaltyPoints(Request $request)
    {
        $customer = Auth::user();
        $currentDate = Carbon::now();
        $promotion = LoyaltyPromotion::where('user_id', domain_info('user_id'))->find($request->promotion_id);
        if (!isset($promotion)) {
            return response()->json(['message' => 'Not found promotional']);
        }
        if ($customer->curent_point >= $promotion->point) {
            // Đổi promotion
            $redeemLoyalty = new LoyaltyCustomerPromotion();
            $redeemLoyalty->loyalty_promotion_id = $promotion->id;
            $redeemLoyalty->customer_id = Auth::id();
            $redeemLoyalty->redemtion_date = $currentDate->format('Y-m-d');
            $redeemLoyalty->expiration_date = $currentDate->addDays($promotion->expiry)->format('Y-m-d');
            $redeemLoyalty->save();
            // Lưu thông tin giao dịch
            WalletTransactions::create([
                'transaction_type' => 'Reward Redemption',
                'target_id' => $promotion->id,
                'customer_id' => $customer->id,
                'user_id' => domain_info('user_id'),
                'amount' => $promotion->point,
                'balance' => $promotion->point,
                'status' => 1
            ]);
            $redeemLoyalty->customer->curent_point = $customer->curent_point - $promotion->point;
            $redeemLoyalty->customer->save();
            return response()->json(['message' => 'Successful redeem promotion']);
        }
        return response()->json(['message' => 'You don\'t have enough points to redeem this promotion']);
    }

    public function getPromotionForCustomers(Request $request)
    {
        $query = DB::table('loyalty_customer_promotions')
            ->join('customers', 'loyalty_customer_promotions.customer_id', '=', 'customers.id')
            ->join('loyalty_promotions', 'loyalty_customer_promotions.loyalty_promotion_id', '=', 'loyalty_promotions.id')
            ->where('loyalty_customer_promotions.customer_id', Auth::id())
            ->where('loyalty_customer_promotions.expiration_date', '>=', date('Y-m-d'))
            ->select(
                'loyalty_customer_promotions.id',
                'customers.id as customer_id',
                'loyalty_promotions.id as promotion_id',
                'loyalty_promotions.name',
                'loyalty_promotions.code',
                'loyalty_promotions.point',
                'loyalty_customer_promotions.redemtion_date',
                'loyalty_customer_promotions.expiration_date',
                'loyalty_promotions.image',
                'loyalty_promotions.background',
                'loyalty_promotions.description',
                'loyalty_promotions.content',
                'loyalty_promotions.source',
                'loyalty_promotions.type',
                'loyalty_promotions.reduction_rate',
            );
        if ($request->source != null) {
            $query->where('loyalty_promotions.source', $request->source);
        }
        if ($request->type != null) {
            $query->where('loyalty_promotions.type', $request->type);
        }
        if (isset($request['limit']) && isset($request['offset'])) {
            $promotion =  $query->paginate($request['limit'], ['*'], 'page', $request['offset']);
            return response()->json([
                'limit' => (int)$request->limit,
                'offset' => (int)$request->offset,
                'total_item' => $promotion->total(),
                'data' => $promotion->items()
            ], 200);
        }
        $promotion = $query->get();
        return response()->json($promotion);
    }
}
