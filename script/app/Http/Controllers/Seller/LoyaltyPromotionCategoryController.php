<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Categorymeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Str;

class LoyaltyPromotionCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $posts = Category::where('user_id', Auth::id())->where('type', 'promotion')->language($lang_id)->with('preview')->latest()->paginate(20);
        return view('seller.loyalty.promotion_category.index', compact('posts'));
    }

    public function store(Request $request)
    {
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
        $rules = [
            'image' => 'required',
            'name' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $slug = Str::slug($request->name);
        $user_id = Auth::id();
        $category = new Category;
        $category->name = $request->name;
        if ($request->lang_id) {
            $category->lang_id = json_encode($request->lang_id);
        }
        $category->slug = $slug;
        $category->user_id = $user_id;
        $category->type = 'promotion';
        $category->save();

        if ($request->image) {
            $fileName = time().'.'.$extImage;  
            $path = 'uploads/' . $user_id . '/promotion/' . date('y/m').'/';
            $name = $path.'/'.$fileName; 
            $request->image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path);     
            $filenames = $compress['data']['image'];  
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }

            $meta = new Categorymeta;
            $meta->category_id = $category->id;
            $meta->type = "preview";
            $meta->content = $filenames;
            $meta->save();
        }
        return response()->json(['success', 'Category Created']);
    }

    public function edit($id)
    {
        $info = Category::where('user_id', Auth::id())->findOrFail($id);
        return view('seller.loyalty.promotion_category.edit', compact('info'));
    }

    public function update(Request $request, $id)
    {
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
        $rules = [
            'image' => 'required',
            'name' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $category = Category::where('user_id', $user_id)->with('preview')->findOrFail($id);
        $category->name = $request->name;
        $category->save();

        if ($request->image) {
            if (!empty($category->preview)) {
                if (file_exists($category->preview->content)) {
                    unlink($category->preview->content);
                }
            }
            $fileName = time().'.'.$extImage;  
            $path = 'uploads/' . $user_id . '/promotion/' . date('y/m').'/';
            $name = $path.'/'.$fileName; 
            $request->image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path); 
            $filenames = $compress['data']['image'];  

            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
            $meta =  Categorymeta::where('category_id', $category->id)->where('type', 'preview')->first();
            if (empty($meta)) {
                $meta = new Categorymeta;
            }

            $meta->category_id = $category->id;
            $meta->type = "preview";
            $meta->content = $filenames;
            $meta->save();
        }
        return response()->json(['success', 'Category Updated']);
    }

    public function destroy(Request $request)
    {
        if ($request->type == 'delete') {
            foreach ($request->ids as $key => $row) {
                $id = base64_decode($row);
                $category = Category::where('user_id', Auth::id())->where('id', $id)->with('preview')->first();
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
