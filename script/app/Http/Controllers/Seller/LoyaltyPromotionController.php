<?php

namespace App\Http\Controllers\Seller;

use App\Category;
use App\Http\Controllers\Controller;
use App\LoyaltyPromotion;
use App\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoyaltyPromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $data['info_categories'] = Category::where('type', 'promotion')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $data['products'] = Term::where('user_id', $user_id)->where('type', 'product')->where('status', 1)->get();
        $data['infos'] = LoyaltyPromotion::where('user_id', auth()->id())->orderBy('id', 'DESC')->paginate(20);
        return view('seller.loyalty.promotion.index', $data);
    }

    public function store(Request $request)
    {
        if ($request->image) {
            $image = $request->image;
            $allowedExts = allowedExts();
            $extImage = $image->extension();
            $rules['image'] = [
                function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                    if (!in_array($extImage, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg, svg image is allowed");
                    }
                }
            ];
        }
        $rules = [
            'image' => 'required',
            'name' => 'required|max:255',
            'code' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'point' => 'required',
            'expiry' => 'required',
            'reduction_rate' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $promotion = new LoyaltyPromotion();
        $promotion->name = $request->name;
        $promotion->code = $request->code;
        $promotion->category_id = $request->category_id;
        $promotion->start_at = $request->start_at;
        $promotion->end_at = $request->end_at;
        $promotion->point = $request->point;
        $promotion->expiry = $request->expiry;
        $promotion->content = $request->content;
        $promotion->description = $request->description;
        $promotion->featured = $request->featured;
        $promotion->source = $request->source;
        $promotion->term_id = $request->term_id;
        $promotion->type = $request->type;
        $promotion->reduction_rate = $request->reduction_rate;
        $promotion->user_id = $user_id;
        if ($request->image) {
            $fileName = time().'.'.$extImage;
            $path = 'uploads/' . $user_id . '/promotion/' . date('y/m').'/';
            $request->image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path);
            $filenames = $compress['data']['image'];
            $promotion->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }
        if ($request->background) {
            $fileName = time().'.'.$request->background->extension();
            $path = 'uploads/' . $user_id . '/promotion_background/' . date('y/m').'/';
            $ext = $request->background->extension();
            $request->background->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path);
            $filenames = $compress['data']['image'];
            $promotion->background = $filenames;
            if($ext != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }
        $promotion->save();

        return response()->json(['success', 'Promotion Created']);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $info = LoyaltyPromotion::where('user_id', Auth::id())->findOrFail($id);
        $info_categories =
            Category::where('type', 'promotion')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $products
            = Term::where('user_id', Auth::id())->where('type', 'product')->where('status', 1)->get();
        return view('seller.loyalty.promotion.edit', compact('info', 'info_categories', 'products'));
    }

    public function update(Request $request, $id)
    {
        if ($request->image) {
            $image = $request->image;
            $allowedExts = allowedExts();
            $extImage = $image->extension();
            $rules['image'] = [
                function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                    if (!in_array($extImage, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg, svg image is allowed");
                    }
                }
            ];
        }
        $rules = [
            'image' => 'required',
            'name' => 'required|max:255',
            'code' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'point' => 'required',
            'expiry' => 'required',
            'reduction_rate' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $promotion = LoyaltyPromotion::where('user_id', $user_id)->findOrFail($id);
        $promotion->name = $request->name;
        $promotion->code = $request->code;
        $promotion->category_id = $request->category_id;
        $promotion->start_at = $request->start_at;
        $promotion->end_at = $request->end_at;
        $promotion->expiry = $request->expiry;
        $promotion->point = $request->point;
        $promotion->content = $request->content;
        $promotion->description = $request->description;
        $promotion->featured = $request->featured;
        $promotion->source = $request->source;
        $promotion->term_id = $request->term_id;
        $promotion->type = $request->type;
        $promotion->reduction_rate = $request->reduction_rate;
        if ($request->image) {
            @unlink($promotion->image);
            $fileName = time().'.'.$extImage;
            $path = 'uploads/' . $user_id . '/promotion/' . date('y/m').'/';
            $request->image->move($path, $fileName);
            $compress = resizeImage($path.$fileName,$extImage,60,$fileName,$path);
            $filenames = $compress['data']['image'];
            $promotion->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }
        if ($request->background) {
            @unlink($promotion->background);
            $fileName = time().'.'.$request->background->extension();
            $path = 'uploads/' . $user_id . '/promotion_background/' . date('y/m').'/';
            $ext = $request->background->extension();
            $request->background->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path);
            $filenames = $compress['data']['image'];
            $promotion->background = $filenames;
            if($ext != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }
        $promotion->save();

        return response()->json(['success', 'Promotion Updated']);
    }

    public function destroy(Request $request)
    {
        if ($request->status == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    $info = LoyaltyPromotion::find($id);
                    if ($info) {
                        @unlink($info->image);
                        @unlink($info->background);
                        $info->delete();
                    }
                }
            }
        }
        return response()->json(['Loyalty Promotion Deleted']);
    }
}
