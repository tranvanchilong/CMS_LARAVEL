<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['course_categories'] = Category::where('type','course_category')->where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.course_category.index',$data);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $course_category = new Category;
        $course_category->user_id = auth()->id();
        $course_category->name = $request->name;
        if($request->lang_id){
            $course_category->lang_id = json_encode($request->lang_id);
        }
        $course_category->slug = Str::slug($request->name);
        $course_category->featured = $request->featured;
        $course_category->type = 'course_category';
        $course_category->save();

        return response()->json(['success','Course Category Created']);
    }
    public function edit($id)
    {
        $course_category = Category::find($id);
        return view('seller.course_category.edit', compact('course_category'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $course_category = Category::find($id);
        $course_category->name = $request->name;
        $course_category->lang_id = $request->lang_id;
        $course_category->slug = Str::slug($request->name);
        $course_category->featured = $request->featured;
        $course_category->save();
        return response()->json(['success','Course Category Updated']);
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
                    $course_category = Category::find($id);
                    if($course_category){
                        $course_category->delete();
                    }
                }
            }
        }
        return response()->json('portfolio Category Deleted');
    }
}
