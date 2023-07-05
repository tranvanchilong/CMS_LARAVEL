<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Module;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LessonController extends Controller
{
    public function index($id){
        $module = Module::findOrFail($id);
        $lessons = Lesson::where('module_id',$module->id)->get();
        return view('seller.lesson.index', compact('module', 'lessons'));
    }
    public function store(Request $request)
    {
        if ($request->video == 1) {
            $request->video_link = NULL;
        } elseif ($request->video == 2) {
            $request->video_file = NULL;
        }

        // $videoFile = $request->file('video_file');
        $videoLink = $request->video_link;
        // $videoFile = $request->video_file;
        // $videoExts = array('mp4');
        // $extVideo = pathinfo($videoFile, PATHINFO_EXTENSION);

        $rules = [
        'name' => 'required',
        'duration' => 'required'
        ];
        // if ($request->filled('video_file')) {
        //     $rules['video_file'] = [
        //         function ($attribute, $value, $fail) use ($extVideo, $videoExts) {
        //             if (!in_array($extVideo, $videoExts)) {
        //                 return $fail("Only mp4 video is allowed");
        //             }
        //         }
        //     ];
        // }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $lesson = new Lesson;
        $lesson->module_id = $request->module_id;
        $lesson->name = $request->name;
        $lesson->video_link = $request->video_link;

        $lesson->duration = $request->duration;

        // if ($request->filled('video_file')) {
        //     $videoFileName = uniqid() .'.'. $extVideo;
        //     $directory = 'assets/front/video/lesson_videos/';
        //     @mkdir($directory, 0775, true);
        //     @copy($videoFile, $directory . $videoFileName);
        // } else {
        //     $videoFileName = null;
        // }
        // $lesson->video_file = $videoFileName;

        
        $lesson->save();

        return response()->json(['success','Lesson Created']);
    }
    public function edit($id){
        $lesson = Lesson::find($id);
        return view('seller.lesson.edit', compact('lesson'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
        'name' => 'required',
        'duration' => 'required'
        ];
    
        $videoLink = $request->video_link;

        $rules = [
        'name' => 'required',
        'duration' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $lesson = Lesson::find($id);
        $lesson->name = $request->name;
        $lesson->video_link = $request->video_link;

        $lesson->duration = $request->duration;
        $lesson->save();

        return response()->json(['success','Lesson Created']);
    }
    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $lesson = Lesson::find($id);
                    if($lesson){
                        $lesson->delete();
                    }
                }
            }
        }
        return response()->json('Lesson Deleted');
    }

}
