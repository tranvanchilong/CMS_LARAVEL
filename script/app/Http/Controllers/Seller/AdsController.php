<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Categorymeta;
use App\Useroption;
use Auth;
use Image;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $posts=Category::where('type','offer_ads')->where('user_id',Auth::id())->language($lang_id)->with('excerpt')->latest()->get();
        $type = 'offer_ads';
        return view('seller.ads.offer',compact('posts', 'type'));
    }
    
    public function brand(Request $request)
    {
        $lang_id =  $request->language;
        $posts=Category::where('type','brand_ads')->where('user_id',Auth::id())->language($lang_id)->with('excerpt')->latest()->get();
        $type = 'brand_ads';
        return view('seller.ads.brand',compact('posts', 'type'));
    }


    public function email_newsletter()
    {
        $posts=Category::where('type','newsletter')->where('user_id',Auth::id())->latest()->get();
        return view('seller.ads.newsletter',compact('posts'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|max:1000|image'
        ]);


        $limit=user_limit();
        $posts_count=\App\Term::where('user_id',Auth::id())->count();
        if ($limit['product_limit'] <= $posts_count) {
           \Session::flash('error', 'Maximum posts limit exceeded');
           $error['errors']['error']='Maximum posts limit exceeded';
           return response()->json($error,401);
        }

        if ($limit['storage'] <= str_replace(',', '', folderSize('uploads/'.Auth::id()))) {
           \Session::flash('error', 'Maximum storage limit exceeded');
           $error['errors']['error']='Maximum storage limit exceeded';
           return response()->json($error,401);
        }
        if($request->file){
            $auth_id=Auth::id();
            $fileName = time().'.'.$request->file->extension();   
        $fileName = time().'.'.$request->file->extension();   
            $fileName = time().'.'.$request->file->extension();   
        $fileName = time().'.'.$request->file->extension();   
            $fileName = time().'.'.$request->file->extension();   
            $path ='uploads/'.$auth_id.'/'.date('y/m').'/';
            $ext = $request->file->extension();
            $request->file->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            if($ext != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }
        
        
        if($request->type == 'offer_ads'){
            $type = 'offer_ads';  
        }elseif ($request->type == 'banner_ads' || $request->type == 'banner_ads_2' || $request->type == 'banner_ads_3') {
            $type = $request->type;
        }else{
            $type = 'brand_ads';
        }

        $post=new Category;
        $post->name = $filenames;
        if($request->lang_id){
            $post->lang_id = json_encode($request->lang_id);
        }
        $post->slug=$request->url;
        $post->type=$type;
        $post->user_id=$auth_id;
        $post->save();
        
        $data['title']=$request->title ?? '';
        $data['title_2']=$request->title_2 ?? '';
        $data['title_3']=$request->title_3 ?? '';
        $data['btn_text']=$request->btn_text;

        $meta=new Categorymeta;
        $meta->category_id=$post->id;
        $meta->type="excerpt";
        $meta->content=json_encode($data);
        $meta->save();

        return response()->json(['Ads Created']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $lang_id =  $request->language;
        if($request->type){
            $type = [$request->type];
        }else{
            $type = ['banner_ads', 'banner_ads_2', 'banner_ads_3'];
        }
        $posts = Category::whereIn('type', $type)->where('user_id',Auth::id())->language($lang_id)->with('excerpt')->latest()->get();
        
        return view('seller.ads.banner',compact('posts'));
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request){
        $limit=user_limit();
        $posts_count=\App\Term::where('user_id',Auth::id())->count();
        if ($limit['product_limit'] <= $posts_count) {
         \Session::flash('error', 'Maximum posts limit exceeded');
         $error['errors']['error']='Maximum posts limit exceeded';
         return response()->json($error,401);
        }

         if ($limit['storage'] <= str_replace(',', '', folderSize('uploads/'.Auth::id()))) {
         \Session::flash('error', 'Maximum storage limit exceeded');
         $error['errors']['error']='Maximum storage limit exceeded';
         return response()->json($error,401);
        }

        $validatedData = $request->validate([
            'file' => 'max:1000|image',
        ]);
        
        $auth_id=Auth::id();
        
        $post = Category::where('user_id',Auth::id())->findorFail($id);
        
        if(!$post){
            return response()->json(['ERROR!!! No Update']);
        }
        
        if($request->file){
            @unlink($post->name);
            $fileName = time().'.'.$request->file->extension();   
            $path ='uploads/'.$auth_id.'/'.date('y/m').'/';
            $ext = $request->file->extension();
            $request->file->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path); 
            $filenames = $compress['data']['image'];   
            if($ext != 'webp'){
                @unlink($path.'/'.$fileName);
            }
            $post->name = $filenames;
        }
        $post->lang_id = $request->lang_id;
        $post->slug = $request->url;
        $post->save();

        $data['title'] = $request->title ?? '';
        $data['title_2'] = $request->title_2 ?? '';
        $data['title_3'] = $request->title_3 ?? '';
        $data['btn_text'] = $request->btn_text ?? '';

        $meta = Categorymeta::where('category_id', $id)->first();
        if(!$meta){
            $meta=new Categorymeta;
            $meta->category_id= $id;
            $meta->type="excerpt";
        }
        $meta->content=json_encode($data);
        $meta->save();
        
        return response()->json(['Banner Updated']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $category= Category::where('user_id',Auth::id())->findorFail($id);
        
        if (file_exists($category->name)){
            unlink($category->name);
        }
        $category->delete();
        Categorymeta::where('category_id', $category->id)->delete();
        return back();
    }
}
