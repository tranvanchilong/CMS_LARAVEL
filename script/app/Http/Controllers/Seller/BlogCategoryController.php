<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Categorymeta;
use Auth;
use Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $posts=Category::where('user_id',Auth::id())->where('type','bcategory')->language($lang_id)->with('preview')->latest()->paginate(20);
       return view('seller.bcategory.index',compact('posts'));
      
    }

    public function create()
    {
        $bcategory = Category::where('user_id',Auth::id())->where('type','bcategory')->get();
        return view('seller.bcategory.create',compact('bcategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'file' => 'image|max:500',
        ]);

        $slug=Str::slug($request->name);
        
        $user_id=Auth::id();

        $category= new Category;
        $category->name=$request->name;
        if($request->lang_id){
            $category->lang_id = json_encode($request->lang_id);
        }
        $category->slug=$slug;
        if ($request->p_id) {
           $category->p_id=$request->p_id;
        }
       
        $category->featured=$request->featured;
        $category->menu_status=$request->menu_status;
        $category->user_id=$user_id;
        $category->type= 'bcategory';
        $category->save();

        if($request->file){
            $fileName = time().'.'.$request->file->extension();  
            $ext= $request->file->extension();
            $path='uploads/'.$user_id.'/bcategory/'.date('y/m').'/';
            $request->file->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path); 
            $filenames = $compress['data']['image'];
            if($ext != 'webp'){
                @unlink($path.'/'.$fileName);
            }
            $meta= new Categorymeta;
            $meta->category_id =$category->id;
            $meta->type="preview";
            $meta->content = $filenames;
            $meta->save();
        }

        return response()->json(['Category Created']);
    }

    public function edit($id)
    {
        $info= Category::where('user_id',Auth::id())->findOrFail($id);
        return view('seller.bcategory.edit',compact('info'));
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'file' => 'image|max:500',
        ]);
        $user_id=Auth::id();
        $slug=Str::slug($request->name);
        $category= Category::where('user_id',$user_id)->with('preview')->findOrFail($id);
        $category->name=$request->name;
        $category->lang_id = $request->lang_id;
        $category->slug=$slug;
        if ($request->p_id) {
           $category->p_id=$request->p_id;
        }
        else{
            $category->p_id=null;
        }
       
        $category->featured=$request->featured;
        $category->menu_status=$request->menu_status;
        $category->type= 'bcategory';
        $category->save();

        if($request->file){
            if(!empty($category->preview)){
                if(file_exists($category->preview->content)){
                    unlink($category->preview->content);
                }
            }

            $fileName = time().'.'.$request->file->extension();  
            $ext= $request->file->extension();
            $path='uploads/'.$user_id.'/bcategory/'.date('y/m').'/';
            if(substr($request->file->getMimeType(), 0, 5) == 'image' &&  $ext != 'ico') {
                $request->file->move($path, $fileName);
                $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path); 
                $filenames = $compress['data']['image'];
                if($ext != 'webp'){
                    @unlink($path.'/'.$fileName);
                }
            }
            $meta =  Categorymeta::where('category_id',$category->id)->where('type','preview')->first();
            if (empty($meta)){
              $meta= new Categorymeta;  
            }
            
            $meta->category_id =$category->id;
            $meta->type="preview";
            $meta->content = $filenames;
            $meta->save();

        }

        return response()->json(['Category Updated']);
    }
    
    public function destroy(Request $request)
    {
        if ($request->type=='delete') {
            foreach ($request->ids as $key => $row) {
                $id=base64_decode($row);
                $category= Category::where('user_id',Auth::id())->where('id',$id)->with('preview')->first();
                if (!empty($category->preview)) {
                    if (!empty($category->preview->content)) {
                        if (file_exists($category->preview->content)) {
                            unlink($category->preview->content);
                        }
                    }
                }
                $category->delete();
            }
        }

        return response()->json(['Category Deleted']);
    }
}
