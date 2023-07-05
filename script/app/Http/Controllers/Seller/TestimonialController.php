<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Testimonial;
use Validator;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['testimonials'] = Testimonial::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.testimonial.index',$data);
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
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $testimonial = new Testimonial;
        $testimonial->user_id = auth()->id();
        $testimonial->name = $request->name;
        if($request->lang_id){
            $testimonial->lang_id = json_encode($request->lang_id);
        }
        $testimonial->rank = $request->rank;
        $testimonial->featured = $request->featured;
        $testimonial->content = $request->text;
        $testimonial->serial_number = $request->serial_number;

        if ($request->image) {
            $fileName = time().'.'.$extImage;  
            $path='uploads/'.auth()->id().'/testimonial/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            $testimonial->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }

        $testimonial->save();

        return response()->json(['success','Testimonial Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $testimonial = Testimonial::find($id);
                    if($testimonial){
                        @unlink($testimonial->image);
                        $testimonial->delete();
                    }
                }
            }
        }
        return response()->json('Testimonial Deleted');
    }

    public function edit($id){
        $testimonial = Testimonial::find($id);
        return view('seller.testimonial.edit', compact('testimonial'));
    }

    public function show($id){
        
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
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $testimonial = Testimonial::find($id);
        $testimonial->name = $request->name;
        $testimonial->lang_id = $request->lang_id;
        $testimonial->rank = $request->rank;
        $testimonial->featured = $request->featured;
        $testimonial->content = $request->text;
        $testimonial->serial_number = $request->serial_number;

        if ($request->image) {
            @unlink($testimonial->image);
            $fileName = time().'.'.$extImage;  
            $path='uploads/'.auth()->id().'/testimonial/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            $testimonial->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }

        $testimonial->save();

        return response()->json(['success','Testimonial Updated']);
    }
}
