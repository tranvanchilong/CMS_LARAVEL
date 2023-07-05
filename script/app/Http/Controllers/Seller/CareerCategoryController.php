<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CareerCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['career_categories'] = Category::where('type','career_category')->where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.career_category.index',$data);
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $career_category = new Category;
        $career_category->user_id = auth()->id();
        $career_category->name = $request->name;
        if($request->lang_id){
            $career_category->lang_id = json_encode($request->lang_id);
        }
        $career_category->slug = Str::slug($request->name);
        $career_category->featured = $request->featured;
        $career_category->type = 'career_category';
        $career_category->save();

        return response()->json(['success','Career Category Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $career_category = Category::find($id);
                    if($career_category){
                        // $career_category->careers->delete()
                        $career_category->delete();
                    }
                }
            }
        }
        return response()->json('Career Category Deleted');
    }

    public function edit($id){
        $career_category = Category::find($id);
        return view('seller.career_category.edit', compact('career_category'));
    }

    public function update($id, Request  $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $career_category = Category::find($id);
        $career_category->name = $request->name;
        $career_category->lang_id = $request->lang_id;
        $career_category->slug = Str::slug($request->name);
        $career_category->featured = $request->featured;
        $career_category->save();

        return response()->json(['success','Career Category Updated']);
    }
}
