<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Partner;
use Validator;
use Session;
use Illuminate\Support\Str;

class partnerController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['partners'] = Partner::where('user_id', auth()->id())->orderBy('id', 'DESC')->language($lang_id)->paginate(20);
        return view('seller.partner.index',$data);
    }

    public function store(Request $request){
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
            'name' => 'nullable|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $partner = new Partner;
        $partner->user_id = auth()->id();
        $partner->name = $request->name;
        if($request->lang_id){
            $partner->lang_id = json_encode($request->lang_id);
        }
        $partner->url = $request->url;
        $partner->featured = $request->featured;
        $partner->serial_number = $request->serial_number;

        if ($request->image) {
            $fileName = time().'.'.$extImage;  
            $path='uploads/'.auth()->id().'/partner/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            $partner->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
            
        }

        $partner->save();

        return response()->json(['success','Partner Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $partner = Partner::find($id);
                    if($partner){
                        @unlink($partner->image);
                        $partner->delete();
                    }
                }
            }
        }
        return response()->json('Partner Deleted');
    }

    public function edit($id){
        $partner = Partner::find($id);
        return view('seller.partner.edit', compact('partner'));
    }

    public function update($id, Request  $request){
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
            'name' => 'nullable|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $partner = Partner::find($id);
        $partner->name = $request->name;
        $partner->lang_id = $request->lang_id;
        $partner->url = $request->url;
        $partner->featured = $request->featured;
        $partner->serial_number = $request->serial_number;

        if ($request->image) {
            @unlink($partner->image);
            $fileName = time().'.'.$extImage;  
            $path='uploads/'.auth()->id().'/partner/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            $partner->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
            
        }

        $partner->save();

        return response()->json(['success','Partner Updated']);
    }
}
