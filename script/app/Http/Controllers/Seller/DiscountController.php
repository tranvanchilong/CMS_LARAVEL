<?php

namespace App\Http\Controllers\Seller;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Discount;
use App\Term;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $posts = Discount::where('user_id',  $user_id)->latest()->paginate(20);
        $products
            = Term::where('user_id',  $user_id)->where('type', 'product')->where('status', 1)->get();
        $shippings =
            Category::where('type', 'method')->where('user_id',  $user_id)->orderBy('id', 'DESC')->get();
        return view('seller.discount.index', compact('posts', 'products', 'shippings'));
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
            'name' => 'required|max:255',
            'code' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $discount = new Discount();
        $discount->name = $request->name;
        $discount->code = $request->code;
        $discount->start_at = $request->start_at;
        $discount->end_at = $request->end_at;
        $discount->discount_type = $request->discount_type;
        $discount->discount_amount = $request->discount_amount;
        $discount->shipping_id = $request->shipping_id;
        $discount->term_id = $request->term_id;
        $discount->order_amount = $request->order_amount;
        $discount->order_price = $request->order_price;
        $discount->content = $request->content;
        $discount->status = $request->status;
        $discount->user_id = $user_id;
        if ($request->image) {
            $fileName = time() . '.' . $extImage;
            $path = 'uploads/' . $user_id . '/discount/' . date('y/m') . '/';
            $request->image->move($path, $fileName);
            $compress = resizeImage($path . $fileName, $extImage, 60, $fileName, $path);
            $filenames = $compress['data']['image'];
            if ($extImage != 'webp') {
                @unlink($path . '/' . $fileName);
            }
            $discount->image = $filenames;
        }
        $discount->save();
        return response()->json(['success', 'Discount Created']);
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user_id = Auth::id();
        $info = Discount::where('user_id', $user_id)->findOrFail($id);
        $products
            = Term::where('user_id',  $user_id)->where('type', 'product')->where('status', 1)->get();
        $shippings =
            Category::where('type', 'method')->where('user_id',  $user_id)->orderBy('id', 'DESC')->get();

        return view('seller.discount.edit', compact('info', 'products', 'shippings'));
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
            'name' => 'required|max:255',
            'code' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $discount = Discount::where('user_id', $user_id)->findOrFail($id);
        $discount->name = $request->name;
        $discount->code = $request->code;
        $discount->start_at = $request->start_at;
        $discount->end_at = $request->end_at;
        $discount->discount_type = $request->discount_type;
        $discount->discount_amount = $request->discount_amount;
        $discount->shipping_id = $request->shipping_id;
        $discount->term_id = $request->term_id;
        $discount->order_amount = $request->order_amount;
        $discount->order_price = $request->order_price;
        $discount->content = $request->content;
        $discount->status = $request->status;
        if ($request->image) {
            @unlink($discount->image);
            $fileName = time() . '.' . $extImage;
            $path = 'uploads/' . $user_id . '/discount/' . date('y/m') . '/';
            $request->image->move($path, $fileName);
            $compress = resizeImage($path . $fileName, $extImage, 60, $fileName, $path);
            $filenames = $compress['data']['image'];
            if ($extImage != 'webp') {
                @unlink($path . '/' . $fileName);
            }
            $discount->image = $filenames;
        }
        $discount->save();
        return response()->json(['success', 'Discount Updated']);
    }

    public function destroy(Request $request)
    {
        if ($request->type == 'delete') {
            foreach ($request->ids as $key => $row) {
                $id = base64_decode($row);
                $discount = Discount::where('user_id', Auth::id())->where('id', $id)->first();
                if ($discount) {
                    @unlink($discount->image);
                    $discount->delete();
                }
            }
        }

        return response()->json(['Discount Deleted']);
    }
}
