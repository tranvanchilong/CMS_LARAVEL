<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Categorymeta;
use Auth;
use Str;
use Image;
use Validator;

class BookingCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $posts = Category::where('user_id', Auth::id())->where('type', 'booking')->language($lang_id)->with('preview')->latest()->paginate(20);
        return view('seller.booking_category.index', compact('posts'));
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
            'featured' => 'required',
            'serial_number' => 'required'
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
        $category->featured = $request->featured;
        $category->serial_number = $request->serial_number;
        $category->user_id = $user_id;
        $category->type = 'booking';
        $category->save();

        if ($request->image) {
            $imageSizes= imageUploadSizes('thumbnail');
            $path='uploads/'.$user_id.'/scategory/'.date('y/m').'/';
            $fileName = time().'.webp';  
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $filenames = $img->dirname.'/'.$img->filename.'.'.$img->extension;   

            $meta = new Categorymeta;
            $meta->category_id = $category->id;
            $meta->type = "preview";
            $meta->content = $filenames;
            $meta->save();
        }
        return response()->json(['success', 'Booking Category Created']);
    }

    public function edit($id)
    {
        $info = Category::where('user_id', Auth::id())->findOrFail($id);
        return view('seller.booking_category.edit', compact('info'));
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
            'featured' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();

        $category = Category::where('user_id', $user_id)->with('preview')->findOrFail($id);
        $category->name = $request->name;
        $category->lang_id = $request->lang_id;
        $category->featured = $request->featured;
        $category->serial_number = $request->serial_number;
        $category->save();

        if ($request->image) {
            if (!empty($category->preview)) {
                if (file_exists($category->preview->content)) {
                    @unlink(ImageThumnail($category->preview->content));
                    @unlink($category->preview->content);
                }
            }
            $imageSizes= imageUploadSizes('thumbnail');
            $path='uploads/'.$user_id.'/scategory/'.date('y/m').'/';
            $fileName = time().'.webp';  
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $filenames = $img->dirname.'/'.$img->filename.'.'.$img->extension;   
            $meta =  Categorymeta::where('category_id', $category->id)->where('type', 'preview')->first();
            if (empty($meta)) {
                $meta = new Categorymeta;
            }

            $meta->category_id = $category->id;
            $meta->type = "preview";
            $meta->content = $filenames;
            $meta->save();
        }
        return response()->json(['success', 'Booking Category Updated']);
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
                            unlink(ImageThumnail($category->preview->content));
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
