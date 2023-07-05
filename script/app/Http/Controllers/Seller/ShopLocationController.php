<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Image;

class ShopLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = Location::where('user_id', Auth::id())->latest()->paginate(20);
        return view('seller.location.index', compact('locations'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $location = new Location;
        $location->name = $request->name;
        $location->city = $request->city;
        $location->state = $request->state;
        $location->country = $request->country;
        $location->address = $request->address;
        $location->phone = $request->phone;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->work_time = $request->work_time;
        $location->open_hour = $request->open_hour;
        $location->close_hour = $request->close_hour;
        if ($request->image) {
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';
            $path = 'uploads/'.auth()->id().'/location/'.date('y/m').'/';
            $request->image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $filenames = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            $location->image = $filenames;
        }
        $location->user_id = Auth::id();
        $location->status = $request->status;
        $location->slot = $request->slot;
        $location->save();
        return response()->json(['success', 'Location Created']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $location = Location::find($id);
        return view('seller.location.edit', compact('location'));
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
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $location = Location::find($id);
        $location->name = $request->name;
        $location->city = $request->city;
        $location->state = $request->state;
        $location->country = $request->country;
        $location->address = $request->address;
        $location->phone = $request->phone;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->work_time = $request->work_time;
        $location->open_hour = $request->open_hour;
        $location->close_hour = $request->close_hour;
        if ($request->image) {
            if (file_exists($location->image)) {
                @unlink(ImageThumnail($location->image));
                @unlink($location->image);
            }
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';
            $path = 'uploads/'.auth()->id().'/location/'.date('y/m').'/';
            $request->image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $filenames = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            $location->image = $filenames;
        }
        $location->status = $request->status;
        $location->slot = $request->slot;
        $location->save();
        return response()->json(['success', 'Location Updated']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->action_status == 'publish') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    $location = Location::find($id);
                    $location->status = 1;
                    $location->save();
                }
            }
        } elseif ($request->action_status == 'trash') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    $location = Location::find($id);
                    $location->status = 0;
                    $location->save();
                }
            }
        } elseif ($request->action_status == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id = base64_decode($row);
                    Location::destroy($id);
                }
            }
        }
        return response()->json('Success');
    }

    public function is_default($id, Request $request)
    {
        $is_default = Location::find($id);
        $is_default->is_default = $request->is_default ==1 ? 0 : 1;
        $is_default->save();


        return redirect()->back();
    }
}
