<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Useroption;
use Validator;
use Session;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['instructors'] = Team::where('user_id', auth()->id())->where('type','instructor')->language($lang_id)->orderBy('id', 'DESC')->paginate(20);

        return view('seller.instructor.index',$data);
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
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $instructor = new Team;
        $instructor->user_id = auth()->id();
        if($request->lang_id){
            $instructor->lang_id = json_encode($request->lang_id);
        }
        $instructor->name = $request->name;
        $instructor->rank = $request->rank;
        $instructor->content = $request->content;
        $instructor->featured = $request->featured;
        $instructor->type = "instructor";
        $instructor->facebook = $request->facebook;
        $instructor->twitter = $request->twitter;
        $instructor->instagram = $request->instagram;
        $instructor->linkedin = $request->linkedin;
        $instructor->serial_number = $request->serial_number;

        if ($request->image) {
            $fileName = time().'.'.$extImage;
            $path='uploads/'.auth()->id().'/instructor/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];  
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }    
            $instructor->image = $filenames;
        }

        $instructor->save();

        return response()->json(['success','Instructor Created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $instructor = Team::find($id);
        return view('seller.instructor.edit', compact('instructor'));
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

        $instructor = Team::find($id);
        $instructor->name = $request->name;
        $instructor->lang_id = $request->lang_id;
        $instructor->rank = $request->rank;
        $instructor->content = $request->content;
        $instructor->featured = $request->featured;
        $instructor->facebook = $request->facebook;
        $instructor->twitter = $request->twitter;
        $instructor->instagram = $request->instagram;
        $instructor->linkedin = $request->linkedin;
        $instructor->serial_number = $request->serial_number;

        if ($request->image) {
            @unlink($instructor->image);
            $fileName = time().'.'.$extImage;
            $path='uploads/'.auth()->id().'/instructor/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];  
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }    
            $instructor->image = $filenames;
        }

        $instructor->save();

        return response()->json(['success','Instructor Updated']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $instructor = Team::find($id);
                    if($instructor){
                        @unlink($instructor->image);
                        $instructor->delete();
                    }
                }
            }
        }
        return response()->json('Instructor Deleted');
    }
}
