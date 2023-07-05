<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Portfolio;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Category;
use Image;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['portfolio_categories'] = Category::where('type','portfolio_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $data['portfolios'] = Portfolio::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.portfolios.index',$data);
    }

    public function store(Request $request){
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
            'content' => 'required',
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->name);
        $check=Portfolio::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }
        
        $portfolio = new Portfolio;
        $portfolio->user_id = auth()->id();
        $portfolio->name = $request->name;
        $portfolio->description = $request->description;
        if($request->lang_id){
            $portfolio->lang_id = json_encode($request->lang_id);
        }
        $portfolio->slug = $slug;
        $portfolio->featured = $request->featured;
        $portfolio->content = $request->content;
        $portfolio->website_link = $request->website_link;
        $portfolio->category_id = $request->category_id;
        $portfolio->start_date = $request->start_date;
        $portfolio->submission_date = $request->submission_date;
        $portfolio->meta_description = $request->meta_description;
        $portfolio->meta_keyword = $request->meta_keyword;
        $portfolio->serial_number = $request->serial_number;
        if ($request->image) {
            $imageSizes= imageUploadSizes('thumbnail');
            $fileName = time().'.webp';  
            $path = 'uploads/' . auth()->id() . '/portfolio/' . date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $portfolio->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;         
        }

        $portfolio->save();

        return response()->json(['success','Portfolio Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $portfolio = Portfolio::find($id);
                    if($portfolio){
                        @unlink(ImageThumnail($portfolio->image));
                        @unlink($portfolio->image);
                        $portfolio->delete();
                    }
                }
            }
        }
        return response()->json('Portfolio Deleted');
    }

    public function edit($id){
        $portfolio_categories = Category::where('type','portfolio_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        $portfolio = Portfolio::find($id);
        return view('seller.portfolios.edit', compact('portfolio','portfolio_categories'));
    }

    public function update($id, Request  $request){
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
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
            'category_id' => 'nullable',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->name);
        $check=Portfolio::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $portfolio = Portfolio::find($id);
        $portfolio->name = $request->name;
        $portfolio->description = $request->description;
        $portfolio->lang_id = $request->lang_id;
        $portfolio->slug = ($portfolio->slug==$creat_slug ? $creat_slug : $slug);
        $portfolio->featured = $request->featured;
        $portfolio->content = $request->content;
        $portfolio->website_link = $request->website_link;
        $portfolio->category_id = $request->category_id;
        $portfolio->start_date = $request->start_date;
        $portfolio->submission_date = $request->submission_date;
        $portfolio->meta_description = $request->meta_description;
        $portfolio->meta_keyword = $request->meta_keyword;
        $portfolio->serial_number = $request->serial_number;

        if ($request->image) {
            $imageSizes = imageUploadSizes('thumbnail');
            @unlink(ImageThumnail($portfolio->image));
            @unlink($portfolio->image);
            $fileName = time().'.webp';  
            $path = 'uploads/' . auth()->id() . '/portfolio/' . date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $portfolio->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;                          
        }

        $portfolio->save();

        return response()->json(['success','Portfolio Updated']);
    }
}
