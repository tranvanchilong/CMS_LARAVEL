<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Categorymeta;
use Image;
use Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $posts=Category::where('user_id',Auth::id())->where('type','slider')->language($lang_id)->with('excerpt')->orderBy('serial_number', 'ASC')->get();

        return view('seller.store.sliders',compact('posts'));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
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
        $rules = [
            'url' => 'required|max:50',
            'title' => 'max:100',
            'btn_text' => 'max:100',
            'file' => 'max:10000|image',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $auth_id=Auth::id();
        $post = new Category;
        
        if($request->file){
            $imageSizes= imageUploadSizes('slider');
            $fileName = time().'.webp';   
            $path='uploads/'.$auth_id.'/'.date('y/m').'/';
            $request->file->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $post->name = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            @unlink($filenames);
        }
        if($request->lang_id){
            $post->lang_id = json_encode($request->lang_id);
        }
        $post->slug=$request->url;
        $post->type='slider';
        $post->user_id=$auth_id;
        $post->serial_number = $request->serial_number;
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
        return response()->json(['success','Slider Created']);
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slider=Category::where('user_id',Auth::id())->where('type','slider')->findorFail($id);
        if (file_exists($slider->name)) {
            unlink($slider->name);
        }
        $slider->delete();

        // return response()->json(['Slider Deleted']);
        return back();
    }
    
    public function edit($id)
    {
        $slider=Category::where('user_id',Auth::id())->where('type','slider')->findorFail($id);
        return back();
    }
    
    public function update($id, Request $request){
        $slider=Category::where('user_id',Auth::id())->where('type','slider')->findorFail($id);

        $limit=user_limit();

         if ($limit['storage'] <= str_replace(',', '', folderSize('uploads/'.Auth::id()))) {
         \Session::flash('error', 'Maximum storage limit exceeded');
         $error['errors']['error']='Maximum storage limit exceeded';
         return response()->json($error,401);
        }

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
        $rules = [
            'url' => 'required|max:50',
            'title' => 'max:100',
            'btn_text' => 'max:100',
            'file' => 'max:10000|image',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        
        $auth_id=Auth::id();
        
        $post = Category::where('user_id',Auth::id())->where('type','slider')->findorFail($id);
        
        if(!$post){
            return response()->json(['ERROR!!! No Update']);
        }
        
        if($request->file){
            $imageSizes= imageUploadSizes('slider');
            @unlink($post->name);
            $fileName = time().'.webp';   
            $path='uploads/'.$auth_id.'/'.date('y/m').'/';
            $request->file->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $post->name = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            @unlink($filenames);
        }
        $post->lang_id = $request->lang_id;
        $post->slug = $request->url;
        $post->serial_number = $request->serial_number;
        $post->save();

        $data['title'] = $request->title ?? '';
        $data['title_2'] = $request->title_2 ?? '';
        $data['title_3'] = $request->title_3 ?? '';
        $data['btn_text'] = $request->btn_text ?? '';

        $meta = Categorymeta::where('category_id', $id)->first();
        $meta->content=json_encode($data);
        $meta->save();
        
        return response()->json(['success','Slider Updated']);
    }
}
