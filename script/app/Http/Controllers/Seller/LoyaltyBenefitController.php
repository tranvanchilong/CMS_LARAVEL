<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\LoyaltyBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoyaltyBenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $infos = LoyaltyBenefit::where('user_id', $user_id)->latest()->paginate(20);
        return view('seller.loyalty_rank.benefit.index', compact('infos'));
    }

    public function store(Request $request)
    {
        $rules = [
            'image' => 'required',
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $benefit = new LoyaltyBenefit();
        $benefit->name = $request->name;
        $benefit->content = $request->content;
        if ($request->image) {
            $fileName = time() . '.' . $request->image->extension();
            $path = 'uploads/' . auth()->id() . '/benefit/' . date('y/m') . '/';
            $ext = $request->image->extension();
            $request->image->move($path, $fileName);
            $compress = resizeImage($path . $fileName, $ext, 60, $fileName, $path);
            $filenames = $compress['data']['image'];
            $benefit->image = $filenames;
            if ($ext != 'webp') {
                @unlink($path . '/' . $fileName);
            }
        }
        $benefit->user_id = Auth::id();
        $benefit->save();
        return response()->json(['success', 'Loyalty benefit Created']);
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
        $info = LoyaltyBenefit::find($id);
        return view('seller.loyalty_rank.benefit.edit', compact('info'));
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
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $benefit = LoyaltyBenefit::find($id);
        $benefit->name = $request->name;
        $benefit->content = $request->content;
        if ($request->image) {
            @unlink($benefit->image);
            $fileName = time() . '.' . $request->image->extension();
            $path = 'uploads/' . auth()->id() . '/benefit/' . date('y/m') . '/';
            $ext = $request->image->extension();
            $request->image->move($path, $fileName);
            $compress = resizeImage($path . $fileName, $ext, 60, $fileName, $path);
            $filenames = $compress['data']['image'];
            $benefit->image = $filenames;
            if ($ext != 'webp') {
                @unlink($path . '/' . $fileName);
            }
        }
        $benefit->save();
        return response()->json(['success', 'Loyalty benefit Updated']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->type == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    $benefit = LoyaltyBenefit::where('user_id', Auth::id())->find($id);
                    if ($benefit) {
                        @unlink($benefit->image);
                        $benefit->delete();
                    }
                }
            }
        }
        return response()->json('Success');
    }
}
