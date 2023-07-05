<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;
use Validator;
use Session;
use Image;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingServiceController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['services'] = Service::where('user_id', auth()->id())->language($lang_id)->where('type', 'service_booking')->orderBy('id', 'DESC')->paginate(20);
        return view('seller.booking_service.index', $data);
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
            'featured' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->name);
        $check = Service::where('user_id', auth()->id())->where('slug', $creat_slug)->count();
        if ($check > 0) {
            $slug = $creat_slug . '-' . rand(1, 80);
        } else {
            $slug = $creat_slug;
        }

        $service = new Service;
        $service->user_id = auth()->id();
        $service->name = $request->name;
        if ($request->lang_id) {
            $service->lang_id = json_encode($request->lang_id);
        }
        $service->slug = $slug;
        $service->featured = $request->featured;
        $service->serial_number = $request->serial_number;
        $service->type = 'service_booking';
        $service->category_id = $request->category_id;
        if ($request->image) {
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';
            $path = 'uploads/'.auth()->id().'/service/'.date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $filenames = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            $service->image = $filenames;
        }

        $service->save();

        return response()->json(['success', 'Service Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    $service = Service::find($id);
                    if (file_exists($service->image)) {
                        @unlink(ImageThumnail($service->image));
                        @unlink($service->image);
                        $service->delete();
                    }
                }
            }
        }
        return response()->json('Service Deleted');
    }

    public function edit($id)
    {
        $service = Service::find($id);
        return view('seller.booking_service.edit', compact('service'));
    }

    public function update($id, Request  $request)
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
            'featured' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->name);
        $check = Service::where('user_id', auth()->id())->where('slug', $creat_slug)->count();
        if ($check > 0) {
            $slug = $creat_slug . '-' . rand(1, 80);
        } else {
            $slug = $creat_slug;
        }

        $service = Service::find($id);
        $service->name = $request->name;
        $service->lang_id = $request->lang_id;
        $service->slug = ($service->slug == $creat_slug ? $creat_slug : $slug);
        $service->featured = $request->featured;
        $service->serial_number = $request->serial_number;
        $service->category_id = $request->category_id;
        if ($request->image) {
            if (file_exists($service->image)) {
                @unlink(ImageThumnail($service->image));
                @unlink($service->image);
            }
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';
            $path = 'uploads/'.auth()->id().'/service/'.date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $filenames = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            $service->image = $filenames;
        }

        $service->save();
        return response()->json(['success', 'Service Updated']);
    }
}
