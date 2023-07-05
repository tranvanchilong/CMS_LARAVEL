<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Auth;
use Str;
use App\Models\Userplanmeta;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index()
    {
        $posts=Category::where('user_id',Auth::id())->where('type','city')->latest()->paginate(20);
        return view('seller.shipping.location.index',compact('posts'));
    }

    public function create()
    {
       return view('seller.shipping.location.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name|max:50',
        ], [
            'name.unique' => 'Location already exists',
        ]);
        $post = new Category;
        $post->name=$request->name;
        $post->user_id =Auth::id();
        $post->slug=Str::slug($request->name);
        $post->type="city";
        $post->save();

        return response()->json(['Location Created Successfully']);
    }

    public function edit($id)
    {
        $info=Category::where('user_id',Auth::id())->findorFail($id);
        return view('seller.shipping.location.edit',compact('info'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'max:50',
                Rule::unique('categories')->ignore($id),
            ],
        ], [
            'name.unique' => 'Location already exists',
        ]);
       $post = Category::where('user_id',Auth::id())->findorFail($id);
       $post->name=$request->name;
       $post->save();

       return response()->json(['Location Updated Successfully']);
    }

    public function destroy(Request $request)
    {
        $auth_id=Auth::id();
        if ($request->method=='delete') {
           foreach ($request->ids as $key => $id) {
               $post = Category::where('user_id',$auth_id)->findorFail($id);
               $post->delete();
           }
        }

        return response()->json(['Success']);
    }
}
