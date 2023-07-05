<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Useroption;
use Validator;
use Session;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index(Request $request)
    { 
        $lang_id =  $request->language;
        $data['teams'] = Team::where('user_id', auth()->id())->where('type','team')->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        
        return view('seller.team.index',$data);
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
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $team = new Team;
        $team->user_id = auth()->id();
        if($request->lang_id){
            $team->lang_id = json_encode($request->lang_id);
        }
        $team->name = $request->name;
        $team->rank = $request->rank;
        $team->content = $request->content;
        $team->featured = $request->featured;
        $team->type = "team";
        $team->facebook = $request->facebook;
        $team->twitter = $request->twitter;
        $team->instagram = $request->instagram;
        $team->linkedin = $request->linkedin;
        $team->serial_number = $request->serial_number;

        if ($request->image) {
            $fileName = time().'.'.$extImage;  
            $path='uploads/'.auth()->id().'/team/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            $team->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }

        $team->save();

        return response()->json(['success','Team Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $team = Team::find($id);
                    if($team){
                        @unlink($team->image);
                        $team->delete();
                    }
                }
            }
        }
        return response()->json('Team Deleted');
    }

    public function edit($id){
        $team = Team::find($id);
        return view('seller.team.edit', compact('team'));
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

        $team = Team::find($id);
        $team->name = $request->name;
        $team->lang_id = $request->lang_id;
        $team->rank = $request->rank;
        $team->content = $request->content;
        $team->featured = $request->featured;
        $team->facebook = $request->facebook;
        $team->twitter = $request->twitter;
        $team->instagram = $request->instagram;
        $team->linkedin = $request->linkedin;
        $team->serial_number = $request->serial_number;

        if ($request->image) {
            @unlink($team->image);
            $fileName = time().'.'.$extImage;  
            $path='uploads/'.auth()->id().'/team/'.date('y/m').'/';
            $image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            $team->image = $filenames;
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }

        $team->save();

        return response()->json(['success','Team Updated']);
    }
}
