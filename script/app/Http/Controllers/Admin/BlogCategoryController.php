<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Categorymeta;
use Auth;
use Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Auth()->user()->can('bcategory.list')) {
            abort(401);
        } 

        $posts=Category::where('type','bcategory')->with('preview')->where('is_admin',1)->latest()->paginate(20);
       return view('admin.bcategory.index',compact('posts'));
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('bcategory.create')) {
            abort(401);
        }
        $bcategory = Category::where('type','bcategory')->where('is_admin',1)->get();
        return view('admin.bcategory.create',compact('bcategory'));
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
            'name' => 'required|max:255',
            'file' => 'image|max:500',
        ]);

        $slug=Str::slug($request->name);
        
        $user_id=Auth::id();

        $category= new Category;
        $category->name=$request->name;
        $category->slug=$slug;
        if ($request->p_id) {
           $category->p_id=$request->p_id;
        }
       
        $category->featured=$request->featured;
        $category->menu_status=$request->menu_status;
        $category->user_id=$user_id;
        $category->type= 'bcategory';
        $category->is_admin=1;
        $category->save();

        if($request->file){

            $fileName = time().'.'.$request->file->extension();  
            $path='uploads/admin/1/bcategory/'.date('y/m');
            $request->file->move($path, $fileName);
            $name=$path.'/'.$fileName;

            $meta= new Categorymeta;
            $meta->category_id =$category->id;
            $meta->type="preview";
            $meta->content=$name;
            $meta->save();

        }

        return response()->json(['Category Created']);
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('bcategory.edit')) {
            abort(401);
        }
        $info= Category::findOrFail($id);
        $bcategory = Category::where('type','bcategory')->where('is_admin',1)->get()->except($info->id);
        return view('admin.bcategory.edit',compact('info','bcategory'));
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

        $validated = $request->validate([
            'name' => 'required|max:255',
            'file' => 'image|max:500',
        ]);
        $user_id=Auth::id();

        $category= Category::with('preview')->findOrFail($id);
        $category->name=$request->name;
       
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
            $path='uploads/admin/1/'.date('y/m');
            $request->file->move($path, $fileName);
            $name=$path.'/'.$fileName;
            $meta =  Categorymeta::where('category_id',$category->id)->where('type','preview')->first();
            if (empty($meta)){
              $meta= new Categorymeta;  
            }
            
            $meta->category_id =$category->id;
            $meta->type="preview";
            $meta->content=$name;
            $meta->save();

        }

        return response()->json(['Category Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->type=='delete') {
            foreach ($request->ids as $key => $row) {
                $id=base64_decode($row);
                $category= Category::where('id',$id)->with('preview')->first();
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
