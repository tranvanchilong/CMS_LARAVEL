<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Category;
use Image;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['guide_categories'] = Category::where('type','guide_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $data['guides'] = Post::where('user_id', auth()->id())->guide()->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.guides.index',$data);
    }

    public function store(Request $request){

        $rules = [
            'image' => 'required',
            'content' => 'required',
            'title' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
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

        $creat_slug = Str::slug($request->title);
        $check=Post::where('user_id', auth()->id())->guide()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }
        
        $guide = new Post;
        if($request->lang_id){
            $guide->lang_id = json_encode($request->lang_id);
        }
        
        $guide->title=$request->title;
        $guide->slug=$slug;
        $guide->type='guide';
        $guide->featured = $request->featured;
        $guide->user_id = auth()->id();
        $guide->category_id=$request->category_id;
        $guide->content=$request->content;
        $guide->meta_description=$request->meta_description;
        $guide->meta_keyword=$request->meta_keyword;
        $guide->serial_number = $request->serial_number;
        if($request->image){
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp'; 
            $path='uploads/'.auth()->id().'/guide/'.date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $guide->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;
        }
        $guide->save();

        return response()->json(['success','Guide Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $guide = Post::find($id);
                    if($guide){
                        unlink(ImageThumnail($guide->image));
                        unlink($guide->image);
                        $guide->delete();
                    }
                }
            }
        }
        return response()->json('Guide Deleted');
    }

    public function edit($id){
        $guide_categories = Category::where('type','guide_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $guide = Post::find($id);
        return view('seller.guides.edit', compact('guide','guide_categories'));
    }

    public function update($id, Request  $request){
        $rules = [
            'title' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
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

        $creat_slug = Str::slug($request->title);
        $check=Post::where('user_id', auth()->id())->guide()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $guide = Post::find($id);
        $guide->title=$request->title;
        $guide->lang_id = $request->lang_id;
        $guide->slug = ($guide->slug==$creat_slug ? $creat_slug : $slug);
        $guide->featured = $request->featured;
        $guide->category_id=$request->category_id;
        $guide->content=$request->content;
        $guide->meta_description=$request->meta_description;
        $guide->meta_keyword=$request->meta_keyword;
        $guide->serial_number = $request->serial_number;
        
        if($request->image){
            $imageSizes= imageUploadSizes('thumbnail');
            if(file_exists($guide->image)){
                unlink(ImageThumnail($guide->image));
                unlink($guide->image);
            }
            $fileName = time().'.webp'; 
            $path='uploads/'.auth()->id().'/guide/'.date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $guide->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;
        }

        $guide->save();

        return response()->json(['success','Guide Updated']);
    }
}
