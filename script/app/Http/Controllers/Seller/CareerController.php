<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Career;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Category;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['career_categories'] = Category::where('type','career_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $data['careers'] = Career::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.career.index',$data);
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
            'summary' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $creat_slug = Str::slug($request->name);
        $check=Career::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $career = new Career;
        $career->user_id = auth()->id();
        $career->name = $request->name;
        if($request->lang_id){
            $career->lang_id = json_encode($request->lang_id);
        }
        $career->slug = $slug;
        $career->featured = $request->featured;
        $career->summary = $request->summary;
        $career->salary = $request->salary;
        $career->category_id = $request->category_id;
        $career->content = $request->content;
        $career->meta_description = $request->meta_description;
        $career->meta_keyword = $request->meta_keyword;
        $career->serial_number = $request->serial_number;
        $career->save();

        return response()->json(['success','Career Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $career = Career::find($id);
                    if($career){
                        $career->delete();
                    }
                }
            }
        }
        return response()->json('career Deleted');
    }

    public function edit($id){
        $career_categories = Category::where('type','career_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $career = Career::find($id);
        return view('seller.career.edit', compact('career','career_categories'));
    }

    public function update($id, Request  $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
            'summary' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->name);
        $check=Career::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }
        
        $career = Career::find($id);
        $career->name = $request->name;
        $career->lang_id = $request->lang_id;
        $career->slug = ($career->slug==$creat_slug ? $creat_slug : $slug);
        $career->featured = $request->featured;
        $career->summary = $request->summary;
        $career->salary = $request->salary;
        $career->category_id = $request->category_id;
        $career->content = $request->content;
        $career->meta_description = $request->meta_description;
        $career->meta_keyword = $request->meta_keyword;
        $career->serial_number = $request->serial_number;
        $career->save();

        return response()->json(['success','Career Updated']);
    }
}
