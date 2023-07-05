<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Loyalty;
use App\LoyaltyRank;
use App\Models\Customer;
use Illuminate\Http\Request;
use Auth;
use Validator;

class LoyaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $loyalties = Loyalty::where('user_id',  $user_id)->latest()->paginate(20);
        $customers = Customer::where('created_by',  $user_id)->get();
        $ranks = LoyaltyRank::where('user_id',  $user_id)->orderBy('point',  'asc')->get();
        return view('seller.loyalty.index', compact('loyalties', 'customers', 'ranks'));
    }

    public function store(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'loyalty_rank_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $loyalty = new Loyalty();
        $loyalty->customer_id = $request->customer_id;
        $loyalty->loyalty_rank_id = $request->loyalty_rank_id;
        $loyalty->user_id = Auth::id();
        $loyalty->save();
        return response()->json(['success', 'Loyalty Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->action_status == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    Loyalty::destroy($id);
                }
            }
        } else {
            // Update lại rank của customer khi loyalty rank point thay đổi.
            if ($request->ids) {
                $ranks = LoyaltyRank::where('user_id', Auth::id())->orderBy('point', 'desc')->get();
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    $loyalty = Loyalty::where('user_id', Auth::id())->findorFail($id);
                    foreach ($ranks as $rank) {
                        if ($loyalty->customer->total_point >= $rank->point) {
                            $loyalty->update(['loyalty_rank_id' => $rank->id]);
                            break;
                        } else {
                            $loyalty->update(['loyalty_rank_id' => $ranks->last()->id]);
                        }
                    }
                }
            }
        }
        return response()->json('Success');
    }
}
