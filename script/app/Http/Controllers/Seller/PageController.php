<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Post;
use Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $posts=Post::where('user_id',Auth::id())->page()->language($lang_id)->paginate(20);
        return view('seller.store.page.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('seller.store.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
          ]);

        $creat_slug = Str::slug($request->title);
        $check=Post::where('user_id', auth()->id())->page()->where('slug',$creat_slug)->count();
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
        $post->user_id=Auth::id();
        $post->status=1;
        $post->type='page';
        $post->slug=$slug;
        $post->content=$request->content;
        $post->excerpt=$request->excerpt;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;
        $post->save();

        return redirect('/seller/setting/page');
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
        $info=Post::findOrFail($id);
        return view('seller.store.page.edit',compact('info'));
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
        $request->validate([
            'title' => 'required',
        ]);

        $creat_slug = Str::slug($request->slug);
        $check=Post::where('user_id', auth()->id())->page()->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $post=Post::findOrFail($id);
        $post->title=$request->title;
        $post->lang_id = $request->lang_id;
        $post->slug=($post->slug==$creat_slug ? $creat_slug : $slug);
        $post->content=$request->content;
        $post->excerpt=$request->excerpt;
        $post->meta_description=$request->meta_description;
        $post->meta_keyword=$request->meta_keyword;
        $post->save();
        
        return response()->json(['Page Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $auth_id=Auth::id();
        if ($request->method=='delete') {
            if ($request->ids) {
                Post::destroy($request->ids);
            }
            return response()->json(['Page Deleted']);
        }
    }
}
