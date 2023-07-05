<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Categorymeta;
use Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $templates = Category::where('type', 'gallery')->where('user_id',Auth::id())->language($lang_id)->with('excerpt')->orderBy('serial_number', 'asc')->paginate(20);
        $gallerys_categories = Category::where('type','gallery_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        return view('seller.gallery.index',compact('templates','gallerys_categories'));
    }

    public function store(Request $request){
        $type = 'gallery';

        $validated = $request->validate([
            'file' => 'required',
            'file.*' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ]);

        $auth_id = Auth::id();
        $image =[];
        if($files = $request->file('file')){
            foreach($files as $file){
                $fileName = time().rand(1,100).'.'.$file->extension();
                $path='uploads/'.$auth_id.'/'.date('y/m').'/';
                $ext = $file->extension();
                $file->move($path, $fileName);
                $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path); 
                $filenames = $compress['data']['image'];  
                if($ext != 'webp'){
                    @unlink($path.'/'.$fileName);
                }          
                $image[] = $filenames;
            }           
        }
        // $fileName = time().'.'.$request->file->extension();  
        // $path='uploads/'.$auth_id.'/'.date('y/m');
        // $request->file->move($path, $fileName);
        $data = $request->only(['title', 'button_text_1', 'button_link_1', 'button_text_2', 'button_link_2', 'status']);
        $data['image'] = $image;

        
        $category=new Category;
        $category->name = $data['title'];
        if($request->lang_id){
            $category->lang_id = json_encode($request->lang_id);
        }
        $category->slug = Str::slug($data['title']);
        $category->type = $type;
        $category->serial_number = $request->serial_number;
        $category->p_id = $request->category_id;
        $category->user_id = $auth_id;
        $category->save();

        $meta= new Categorymeta;
        $meta->category_id = $category->id;
        $meta->type = "excerpt";
        $meta->content = json_encode($data);
        $meta->save();
        
        return response()->json(['success','Add Gallery Successfully']);
    }

    public function edit($id){
        $template = Category::where('type', 'gallery')->where('user_id', Auth::id())->where('id', $id)->with('excerpt')->first();
        $gallery_category = Category::where('type','gallery_category')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        return view('seller.gallery.edit',compact('template','gallery_category'));
    }

    public function update($id, Request $request){
        
        $type = 'gallery';

        $validated = $request->validate([
            'status' => 'required',
            'serial_number' => 'required'
        ]);

        $data = $request->only(['title', 'button_text_1', 'button_link_1', 'button_text_2', 'button_link_2', 'status']);
 
        $category = Category::where('type', 'gallery')->where('user_id',Auth::id())->where('id', $id)->first();
        $meta= Categorymeta::where('category_id', $id)->first();
    
        $image= [];       
        if($request->file){
            foreach($request->file as $files){
                $data_old = json_decode($meta->content);
                @unlink($data_old->image[0]);
                $auth_id = Auth::id();
                $fileName = time().rand(1,100).'.'.$files->extension();
                $path='uploads/'.$auth_id.'/'.date('y/m').'/';
                $ext = $files->extension();
                $files->move($path, $fileName);
                $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path);      
                $filenames = $compress['data']['image'];  
                if($ext != 'webp'){
                    @unlink($path.'/'.$fileName);
                }          
                $image[] = $filenames;
            }
            $data['image'] = $image; 
        }else{
            $data_old = json_decode($meta->content);
            $data['image'] = $data_old->image;
        }      
        $category->name = $data['title'];
        if($request->lang_id){
            $category->lang_id = json_encode($request->lang_id);
        }       
        $category->slug = Str::slug($data['title']);
        $category->serial_number = $request->serial_number;
        $category->p_id = $request->category_id;
        $category->save();

        $meta->content = json_encode($data);
        $meta->save();
        
        return response()->json(['success','Update Gallery Successfully']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $category = Category::where('type', 'gallery')->where('user_id',Auth::id())->where('id', $id);
                    if($category){
                        $category->delete();
                        Categorymeta::where('category_id', $id)->delete();
                    }
                }
            }
        }
        return response()->json('Gallery Deleted');
    }
}
   