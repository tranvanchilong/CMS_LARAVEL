<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuideCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['guide_categories'] = Category::where('type','guide_category')->where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.guide_category.index',$data);
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

        $guide_category = new Category;
        $guide_category->user_id = auth()->id();
        $guide_category->name = $request->name;
        if($request->lang_id){
            $guide_category->lang_id = json_encode($request->lang_id);
        }
        $guide_category->slug = Str::slug($request->name);
        $guide_category->featured = $request->featured;
        $guide_category->type = 'guide_category';
        $guide_category->save();

        return response()->json(['success','Guide Category Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $guide_category = Category::find($id);
                    if($guide_category){
                        // if($guide_category->guides->count()>0){
                        //     foreach ($guide_category->guides as $guide){
                        //         if($guide->image){
                        //             @unlink($guide->image);
                        //         }
                        //         $guide->delete();
                        //     }
                        // }
                        $guide_category->delete();
                    }
                }
            }
        }
        return response()->json('Guide Category Deleted');
    }

    public function edit($id){
        $guide_category = Category::find($id);
        return view('seller.guide_category.edit', compact('guide_category'));
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

        $guide_category = Category::find($id);
        $guide_category->name = $request->name;
        $guide_category->lang_id = $request->lang_id;
        $guide_category->slug = Str::slug($request->name);
        $guide_category->featured = $request->featured;
        $guide_category->save();

        return response()->json(['success','Guide Category Updated']);
    }
}
