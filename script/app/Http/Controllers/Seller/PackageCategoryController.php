<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Validator;
use Session;
use Illuminate\Support\Str;

class PackageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $package_categorys = Category::where('type','package_category')->where('user_id', auth()->id())->language($lang_id)->orderBy('serial_number', 'ASC')->paginate(20);
        return view('seller.package_category.index', compact('package_categorys'));
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
            'name' => 'required|max:255',
            'featured' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $package_category = new Category;
        $package_category->user_id = auth()->id();
        $package_category->name = $request->name;
        if($request->lang_id){
            $package_category->lang_id = json_encode($request->lang_id);
        }
        $package_category->slug = Str::slug($request->name);
        $package_category->serial_number = $request->serial_number;
        $package_category->featured = $request->featured;
        $package_category->type = 'package_category';
        $package_category->save();

        return response()->json(['success','Package Category Created']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package_category = Category::find($id);
        return view('seller.package_category.edit', compact('package_category'));
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
            'name' => 'required|max:255',
            'featured' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $package_category = Category::find($id);
        $package_category->name = $request->name;
        $package_category->lang_id = $request->lang_id;
        $package_category->slug = Str::slug($request->name);
        $package_category->featured = $request->featured;
        $package_category->serial_number = $request->serial_number;
        $package_category->save();

        return response()->json(['success','Package Category Updated']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $package_category = Category::find($id);
                    if($package_category){
                        $package_category->delete();
                    }
                }
            }
        }
        return response()->json('Package Category Deleted');
    }
}
