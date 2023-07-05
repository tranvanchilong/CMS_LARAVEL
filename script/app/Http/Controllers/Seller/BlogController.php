<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use Auth;
use Illuminate\Support\Str;
use App\Category;
use Image;
use Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $lang_id =  $request->language;
       $blogs=Post::where('user_id',Auth::id())->blog()->language($lang_id)->latest()->paginate(20);

       return view('seller.blog.index',compact('blogs'));
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bcategory = Category::where('user_id',Auth::id())->where('type','bcategory')->get();
        return view('seller.blog.create',compact('bcategory'));
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
            'file' => 'required',
            'title' => 'required|max:100',
        ];

        if ($request->file) {
            $image = $request->file;
            $allowedExts = allowedExts();
            $extImage = $image->extension();
            $rules['file'] = [
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

        $user_id=Auth::id();

        $creat_slug = Str::slug($request->title);
        $check=Post::where('user_id',$user_id)->blog()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $post=new Post;
        $post->title=$request->title;
        if($request->lang_id){
            $post->lang_id = json_encode($request->lang_id);
        }
        $post->slug=$slug;
        $post->status=$request->status;
        $post->type='blog';
        $post->user_id=$user_id;
        $post->category_id=$request->category;
        $post->content=$request->content;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;
        $post->featured=$request->featured;
        $post->excerpt=$request->excerpt;
        if($request->file){
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';  
            $path='uploads/'.$user_id.'/blog/'.date('y/m').'/';
            $request->file->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $post->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;  
        }

        $post->save();
        return response()->json(['success','Blog Create']);
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bcategory = Category::where('user_id',Auth::id())->where('type','bcategory')->get();
        $info=Post::findOrFail($id);
       return view('seller.blog.edit',compact('info','bcategory'));
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
            'file' => 'required',
            'title' => 'required|max:100',
        ];

        if ($request->file) {
            $image = $request->file;
            $allowedExts = allowedExts();
            $extImage = $image->extension();
            $rules['file'] = [
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

        $user_id=Auth::id();
        
        $creat_slug = Str::slug($request->title);
        $check=Post::where('user_id',$user_id)->blog()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $post= Post::findOrFail($id);
        if($request->save_btn != 1){
            $post->content=$request->content;
            $post->save();
            return back();
        }
        $post->title=$request->title;
        $post->lang_id = $request->lang_id;
        $post->slug = ($post->slug==$creat_slug ? $creat_slug : $slug);
        $post->status=$request->status;
        $post->category_id=$request->category;
        $post->content=$request->content;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;
        $post->featured=$request->featured;
        $post->excerpt=$request->excerpt;

        if($request->file){  
            if(file_exists($post->image)){
                @unlink(ImageThumnail($post->image));
                @unlink($post->image);
            }
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';  
            $path='uploads/'.$user_id.'/blog/'.date('y/m').'/';
            $request->file->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $post->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;
        }

        $post->save();
        return response()->json(['success','Blog Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
          if ($request->status=='publish') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $post=Post::find($id);
                    $post->status=1;
                    $post->save();   
                }
                    
            }
        }
        elseif ($request->status=='trash') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $post=Post::find($id);
                    $post->status=0;
                    $post->save();   
                }
                    
            }
        }
        elseif ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $post=Post::find($id);
                    if(file_exists($post->image)) {
                        unlink(ImageThumnail($post->image));
                        unlink($post->image);
                    }
                    $post->delete();   
                }
            }
        }
        return response()->json('Success');
    }
}
