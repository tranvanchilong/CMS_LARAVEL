<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use Auth;
use Illuminate\Support\Str;
use App\Category;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Auth()->user()->can('blog.list')) {
            abort(401);
        }    

       $blogs=Post::where('is_admin',1)->blog()->latest()->paginate(20);
       return view('admin.blog.index',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('blog.create')) {
            abort(401);
        }
        $bcategory = Category::where('type','bcategory')->where('is_admin',1)->get();
        return view('admin.blog.create',compact('bcategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:100', 
        ]);

        $creat_slug = Str::slug($request->title);
        $check=Post::where('is_admin',1)->blog()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $post=new Post;
        $post->title=$request->title;
        $post->slug=$slug;
        $post->status=$request->status;
        $post->type='blog';
        $post->is_admin=1;
        $post->user_id=Auth::id();
        $post->category_id=$request->category;
        $post->content=$request->content;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;

        if($request->file){
            $fileName = time().'.'.$request->file->extension();  
            $path='uploads/admin/1/blog/'.date('y/m');
            $request->file->move($path, $fileName);
            $name=$path.'/'.$fileName;

            $post->image=$name;
        }
        
        $post->save();

        return redirect('/admin/blog');
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('blog.edit')) {
            abort(401);
        }
        $bcategory = Category::where('type','bcategory')->where('is_admin',1)->get();
        $info=Post::findOrFail($id);   
        return view('admin.blog.edit',compact('info','bcategory'));
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
        $validatedData = $request->validate([
            'title' => 'required|max:255',    
        ]);

        $creat_slug = Str::slug($request->title);
        $check=Post::where('is_admin',1)->blog()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $post= Post::findOrFail($id);
        $post->title=$request->title;
        $post->slug = ($post->slug==$creat_slug ? $creat_slug : $slug);
        $post->status=$request->status;
        $post->category_id=$request->category;
        $post->content=$request->content;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;

        if($request->file){
            if(file_exists($post->image)){
                unlink($post->image);
            }
            $fileName = time().'.'.$request->file->extension();  
            $path='uploads/admin/1/blog/'.date('y/m');
            $request->file->move($path, $fileName);
            $name=$path.'/'.$fileName;

            $post->image=$name;
        }

        $post->save();

        return redirect('/admin/blog');
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
                        unlink($post->image);
                    }
                    $post->delete();   
                }
            }
        }
        return response()->json('Success');
    }
}
