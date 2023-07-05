<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth()->user()->can('page.list')) {
            abort(401);
        }    

        $pages=Post::where('is_admin',1)->page()->latest()->paginate(20);
        return view('admin.page.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!Auth()->user()->can('page.create')) {
            abort(401);
        }
        return view('admin.page.create');
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


        $creat_slug=Str::slug($request->title);
        $check=Post::where('is_admin',1)->page()->where('slug',$creat_slug)->count();
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
        $post->type='page';
        $post->is_admin=1;
        $post->user_id=Auth::id();
        $post->content=$request->content;
        $post->excerpt=$request->excerpt;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;
        $post->save();
       
        return redirect('/admin/page');
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('page.edit')) {
            abort(401);
        }
        $info=Post::findOrFail($id);   
        return view('admin.page.edit',compact('info'));
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

        $creat_slug=Str::slug($request->title);
        $check=Post::where('is_admin',1)->page()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }
       
        $post= Post::findOrFail($id);
        $post->title=$request->title;
        $post->status=$request->status;
        $post->slug=($post->slug==$creat_slug ? $creat_slug : $slug);
        $post->content=$request->content;
        $post->excerpt=$request->excerpt;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;
        $post->save();
             
        return redirect('/admin/page');
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

                foreach ($request->ids as $id) {
                    $post=Post::find($id);
                    $post->status=1;
                    $post->save();   
                }
                    
            }
        }
        elseif ($request->status=='trash') {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                    $post=Post::find($id);
                    $post->status=0;
                    $post->save();   
                }
                    
            }
        }
        elseif ($request->status=='delete') {
            if ($request->ids) {
                Post::destroy($request->ids);
            }
        }
        return response()->json('Success');
    }
}
