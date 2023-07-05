<?php

namespace App\Http\Controllers\Seller;

use App\Category;
use App\Discount;
use App\Http\Controllers\Controller;
use App\LoyaltyBenefit;
use App\LoyaltyRank;
use App\Term;
use Illuminate\Http\Request;
use Auth;
use Validator;

class LoyaltyRankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $loyaltyRanks = LoyaltyRank::where('user_id', $user_id)->latest()->paginate(20);
        $products = Term::where('user_id', $user_id)->where('type', 'product')->where('status', 1)->get();
        $categories = Category::where('user_id', $user_id)->where('type', 'category')->get();
        $benefits = LoyaltyBenefit::where('user_id', $user_id)->get(['id','image', 'name', 'content']);
        $discounts = Discount::where('user_id', $user_id)->get();
        return view('seller.loyalty_rank.index', compact('loyaltyRanks', 'products', 'discounts', 'categories', 'benefits'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'point' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $loyalty = new LoyaltyRank();
        $loyalty->name = $request->name;
        $loyalty->point = $request->point;
        $loyalty->term_id = $request->term_id;
        $loyalty->category_id = $request->category_id;
        $loyalty->discount_id = $request->discount_id;
        $loyalty->increase_point = $request->increase_point;
        if ($request->benefit) {
            $loyalty->content = json_encode($request->benefit);
        }
        if ($request->image) {
            $fileName = time() . '.' . $request->image->extension();
            $path = 'uploads/' . auth()->id() . '/loyalty/' . date('y/m') . '/';
            $ext = $request->image->extension();
            $request->image->move($path, $fileName);
            $compress = resizeImage($path . $fileName, $ext, 60, $fileName, $path);
            $filenames = $compress['data']['image'];
            $loyalty->image = $filenames;
            if ($ext != 'webp') {
                @unlink($path . '/' . $fileName);
            }
        }
        $loyalty->user_id = Auth::id();
        $loyalty->save();
        return response()->json(['success', 'Loyalty Rank Created']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Auth::id();
        $loyaltyRank = LoyaltyRank::find($id);
        $products = Term::where('user_id', $user_id)->where('type', 'product')->where('status', 1)->get();
        $categories = Category::where('user_id', $user_id)->where('type', 'category')->get();
        $discounts = Discount::where('user_id', $user_id)->get();
        $benefits = LoyaltyBenefit::where('user_id', $user_id)->get(['id', 'image', 'name','content']);
        return view('seller.loyalty_rank.edit', compact('loyaltyRank', 'products', 'discounts', 'categories', 'benefits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'point' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $loyalty = LoyaltyRank::find($id);
        $loyalty->name = $request->name;
        $loyalty->point = $request->point;
        $loyalty->term_id = $request->term_id;
        $loyalty->category_id = $request->category_id;
        $loyalty->discount_id = $request->discount_id;
        $loyalty->increase_point = $request->increase_point;
        if ($request->benefit) {
            $loyalty->content = json_encode($request->benefit);
        }
        if ($request->image) {
            @unlink($loyalty->image);
            $fileName = time() . '.' . $request->image->extension();
            $path = 'uploads/' . auth()->id() . '/loyalty/' . date('y/m') . '/';
            $ext = $request->image->extension();
            $request->image->move($path, $fileName);
            $compress = resizeImage($path . $fileName, $ext, 60, $fileName, $path);
            $filenames = $compress['data']['image'];
            $loyalty->image = $filenames;
            if ($ext != 'webp') {
                @unlink($path . '/' . $fileName);
            }
        }
        $loyalty->save();
        return response()->json(['success', 'Loyalty Rank Updated']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->action_status == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    LoyaltyRank::destroy($id);
                }
            }
        }
        return response()->json('Success');
    }
}
