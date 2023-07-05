<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Validator;
use Session;
use Auth;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $gallery_categorys = Category::where('type','gallery_category')->where('user_id', Auth::id())->language($lang_id)->orderBy('serial_number', 'ASC')->paginate(20);
        return view('seller.gallery_category.index', compact('gallery_categorys'));
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
            'name' => 'required|max:255',
            'featured' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $gallery_category = new Category;
        $gallery_category->user_id = Auth::id();
        $gallery_category->name = $request->name;
        if($request->lang_id){
            $gallery_category->lang_id = json_encode($request->lang_id);
        }
        $gallery_category->slug = Str::slug($request->name);
        $gallery_category->featured = $request->featured;
        $gallery_category->serial_number = $request->serial_number;
        $gallery_category->type = 'gallery_category';
        $gallery_category->save();

        return response()->json(['success','Gallery Category Created']);
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
        $gallery_category = Category::find($id);
        return view('seller.gallery_category.edit', compact('gallery_category'));
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

        $gallery_category = Category::find($id);
        $gallery_category->name = $request->name;
        $gallery_category->lang_id = $request->lang_id;
        $gallery_category->slug = Str::slug($request->name);
        $gallery_category->featured = $request->featured;
        $gallery_category->serial_number = $request->serial_number;
        $gallery_category->save();

        return response()->json(['success','Gallery Category Updated']);
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
                    $gallery_category = Category::find($id);
                    if($gallery_category){
                        $gallery_category->delete();
                    }
                }
            }
        }
        return response()->json('Gallery Category Deleted');
    }
}
