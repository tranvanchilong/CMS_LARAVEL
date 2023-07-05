<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\DB;
use App\Course;
use App\Team;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Category;
use Image;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['course_categories'] = Category::where('type','course_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $data['courses'] = Course::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        $data['course_instructors'] = Team::where('type','instructor')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();

        return view('seller.course.index', $data);
    }
    public function store(Request $request)
    {
        $rules = [
            // 'image' => 'required',
            'summary' => 'required',
            'title' => 'required|max:255',
            'featured' => 'required',
            // 'current_price' => 'required',
            'category_id' => 'nullable',
            // 'instructor_id' => 'required',
            'serial_number' => 'required',
        ];

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
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $course = new Course;
        $creat_slug = Str::slug($request->title);
        $check=Course::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }
        $course->user_id = auth()->id();
        $course->title = $request->title;
        if($request->lang_id){
            $course->lang_id = json_encode($request->lang_id);
        }

        $link = $request->video_link;

        if (strpos($link, "&") != 0) {
            $custom_link = substr($link, 0, strpos($link, "&"));
            $course->video_link = $custom_link;
        } else {
            $course->video_link = $request->video_link;
        }
        $course->slug = $slug;
        $course->featured = $request->featured;
        $course->summary = $request->summary;
        $course->overview = $request->overview;
        if(empty($request->current_price))
        {$course->current_price = 0;}
        else{
            $course->current_price = $request->current_price;
        }
        $course->previous_price = $request->previous_price;
        $course->duration = $request->duration;
        $course->serial_number = $request->serial_number;
        $course->category_id = $request->category_id;
        $course->type = $request->type;
        $course->instructor_id = $request->instructor_id;

        if ($request->image) {
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';
            $path = 'uploads/' . auth()->id() . '/course/' . date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $course->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            @unlink($filenames);
        }
        else{
            $course->image =Null;
        }

        $course->save();

        return response()->json(['success','Course Created']);
    }
    public function edit($id)
    {
        $course_categories = Category::where('type','course_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $course_instructors = Team::where('type','instructor')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();

        $course = Course::find($id);
        return view('seller.course.edit', compact('course','course_categories','course_instructors'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
            // 'image' => 'required',
            'summary' => 'required',
            'title' => 'required|max:255',
            'featured' => 'required',
            // 'current_price' => 'required',
            'category_id' => 'nullable',
            // 'instructor_id' => 'required',
            'serial_number' => 'required',
        ];
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
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $course = Course::find($id);
        $creat_slug = Str::slug($request->title);
        $check=Course::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $course->title = $request->title;
        if($request->lang_id){
            $course->lang_id = json_encode($request->lang_id);
        }
        $link = $request->video_link;

        if (strpos($link, "&") != 0) {
            $custom_link = substr($link, 0, strpos($link, "&"));
            $course->video_link = $custom_link;
        } else {
            $course->video_link = $request->video_link;
        }
        $course->slug = $slug;
        $course->featured = $request->featured;
        $course->summary = $request->summary;
        $course->overview = $request->overview;
        if(empty($request->current_price))
        {$course->current_price = 0;}
        else{
            $course->current_price = $request->current_price;
        }
        $course->duration = $request->duration;
        $course->previous_price = $request->previous_price;
        $course->serial_number = $request->serial_number;
        $course->category_id = $request->category_id;
        $course->type = $request->type;
        $course->instructor_id = $request->instructor_id;
        if ($request->image) {
            $imageSizes= imageUploadSizes('thumbnail');
            @unlink($course->image);
            $fileName = time().'.webp';
            $path = 'uploads/' . auth()->id() . '/course/' . date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $course->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            @unlink($filenames);
        }

        $course->save();
        return response()->json(['success','Course Updated']);


    }
    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $course = Course::find($id);
                    if($course){
                        $course->delete();
                    }
                }
            }
        }
        return response()->json('Course Deleted');
    }
}
