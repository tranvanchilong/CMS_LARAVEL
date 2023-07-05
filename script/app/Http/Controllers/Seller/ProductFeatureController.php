<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductFeature;
use App\ProductFeatureDetail;
use App\ProductFeatureSectionElement;
use Validator;
use Session;
use App\Models\User;
use App\Domain;
use Cache;
use DB;
use Auth;
use Image;
use Illuminate\Support\Str;

class ProductFeatureController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['pages'] = ProductFeature::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        $data['import_page'] = User::where('role_id',3)->where('status',1)->where(function($query){
            return $query->whereHas('user_domain',function($q){
                return $q->where('featured',1);
            })->WhereHas('home_page',function($q){
                return $q->where('is_home_page',1);
            });
        })
        ->with('user_domain','home_page')->get()->sortBy('user_domain.serial_number');

        return view('seller.product_feature.index',$data);
    }

    public function store(Request $request){
        $rules = [
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'status' => 'required',
            'title' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->title);
        $check=ProductFeature::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $page = new ProductFeature;
        $page->user_id = auth()->id();
        $page->title = $request->title;
        if($request->lang_id){
            $page->lang_id = json_encode($request->lang_id);
        }
        $page->slug = $slug;
        $page->meta_description = $request->meta_description;
        $page->meta_keyword = $request->meta_keyword;
        $page->status = $request->status;
        $page->save();

        return response()->json(['success','Landing Page Created']);
    }

    public function delete($id){
        $page = ProductFeature::find($id);
            
                foreach ($page->sections as $section){
                    
                        foreach ($section->section_elements as $section_element){
                            if($section_element->image){
                                @unlink($section_element->image);
                            }
                            $section_element->delete();
                        }
                        $section->delete();
                    
                }
                $page->delete();
                Session::flash('success', 'Landing Page Deleted');
                return redirect()->back();
            
            Session::flash('error', 'Landing Page Not Deleted');
            return redirect()->back();
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $page = ProductFeature::find($id);
                    if($page->sections->count() == 0){
                        foreach ($page->sections as $section){
                            if($section->section_elements->count()>0){
                                foreach ($section->section_elements as $section_element){
                                    if($section_element->image){
                                        @unlink($section_element->image);
                                    }
                                    $section_element->delete();
                                }
                                $section->delete();
                            }
                        }
                        $page->delete();
                        return response()->json('Landing Page Deleted');
                    }
                }
            }
        }
        \Session::flash('error', 'Landing Page Not Deleted');
        $error['errors']['error']='Landing Page Not Deleted';
        return response()->json($error,401);
    }

    public function edit($id){
        $page = ProductFeature::find($id);
        return view('seller.product_feature.edit', compact('page'));
    }

    public function update($id, Request  $request){
        $rules = [
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'status' => 'required',
            'title' => 'required|max:255',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $creat_slug = Str::slug($request->slug);
        $check=ProductFeature::where('user_id', auth()->id())->where('slug',$creat_slug)->count();
        if ($check > 0) {
            $slug=$creat_slug.'-'.rand(1,80);
        }
        else{
            $slug=$creat_slug;
        }

        $page = ProductFeature::find($id);
        $page->user_id = auth()->id();
        $page->title = $request->title;
        $page->lang_id = $request->lang_id;
        $page->slug = ($page->slug==$creat_slug ? $creat_slug : $slug);
        $page->meta_description = $request->meta_description;
        $page->status = $request->status;
        $page->header_status = $request->header_status;
        $page->footer_status = $request->footer_status;
        $page->save();

        return response()->json(['success','Landing Page Updated']);
    }

    public function setHomePage($id, Request  $request){
        $page = ProductFeature::find($id);
        if($page->lang_id==null){
            ProductFeature::where('user_id', auth()->id())->update(['is_home_page' => 0]);
        }
        else{
            ProductFeature::where('user_id', auth()->id())->where('lang_id',$page->lang_id)->update(['is_home_page' => 0]);
        }
        
        $page->is_home_page = $request->is_home_page ==1 ? 0 : 1;
        $page->save();

        return redirect()->route('seller.feature_page.index');
    }

    public function detail($id, Request $request)
    {
        $data['page'] = ProductFeature::find($id);
        $data['list_feature'] = ProductFeatureDetail::where('feature_page_id', $id)->orderBy('serial_number','asc')->paginate(20);
        
        return view('seller.product_feature.detail',$data);
    }

    public function detail_store ($id, Request $request){
        $rules = [
            'feature_title' => 'required',
            'feature_subtitle' => 'nullable',
            'feature_position' => 'required',
            'feature_status' => 'required',
            'feature_type' => 'required',
            'data_type' => 'required',
            'category' => 'nullable',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $page = new ProductFeatureDetail;
        $page->feature_page_id = $id;
        $page->feature_title = $request->feature_title;
        $page->feature_subtitle = $request->feature_subtitle;
        $page->feature_position = $request->feature_position;
        $page->background_color = $request->background_color;
        $page->feature_status = $request->feature_status;
        $page->feature_type = $request->feature_type;
        $page->data_type = $request->data_type;
        $page->category = $request->category;
        $page->btn_text = $request->btn_text;
        $page->btn_url = $request->btn_url;
        $page->serial_number = $request->serial_number;
        
        $page->save();

        $redirect = route('seller.feature_page.detail.edit',$page->id);
        return response()->json(['success','Page Section Created',$redirect]);
    }


    public function detail_edit ($id, Request $request){
        $feature = ProductFeatureDetail::find($id);
        $page = ProductFeature::find($feature->feature_page_id);
        if(in_array($feature->feature_type,['intro','intro 2','feature list 2','feature list 3','feature list 4','feature list 5',
        'faq','faq 2'])){
            $section_element = ProductFeatureSectionElement::where('feature_page_detail_id',$id)->elementContent()->orderBy('serial_number','asc')->get();
        }else{
            $section_element = ProductFeatureSectionElement::where('feature_page_detail_id',$id)->orderBy('serial_number','asc')->get();
        }
        $section_element_image = ProductFeatureSectionElement::where('feature_page_detail_id',$id)->elementImage()->first();
        return view('seller.product_feature.detail_edit',compact('feature','page','section_element','section_element_image'));
    }

    public function detail_update ($id, Request $request){
        $rules = [
            'image' => 'nullable',
            'feature_title' => 'required',
            'feature_subtitle' => 'nullable',
            'feature_position' => 'required',
            'feature_status' => 'required',
            'feature_type' => 'required',
            'data_type' => 'required',
            'category' => 'nullable',
            'serial_number' => 'required|integer',
        ];

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

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $page = ProductFeatureDetail::find($id);
        $page->feature_title = $request->feature_title;
        $page->feature_subtitle = $request->feature_subtitle;
        $page->feature_position = $request->feature_position;
        $page->background_color = $request->background_color;
        $page->feature_status = $request->feature_status;
        $page->feature_type = $request->feature_type;
        $page->data_type = $request->data_type;
        $page->category = $request->category;
        $page->btn_text = $request->btn_text;
        $page->btn_url = $request->btn_url;
        $page->serial_number = $request->serial_number;  
        
        if ($request->image) {
            $imageSizes= imageUploadSizes('section');
            @unlink($page->image); 
            $fileName = time().'.webp';  
            $path='uploads/'.auth()->id().'/feature_page/'.date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $names = $img->dirname.'/'.$img->filename.'.'.$img->extension; 
            @unlink($filenames);

            $page->image = $names;
        }

        $page->save();

        return response()->json(['success','Page Section Updated']);
    }
    
    public function detail_delete($id){
        $section = ProductFeatureDetail::find($id);
        if($section){
            if($section->section_elements->count()>0){
                foreach ($section->section_elements as $section_element){
                    if($section_element->image){
                        @unlink($section_element->image);
                    }
                    $section_element->delete();
                }
            }
            $section->delete();
        }
        Session::flash('success', 'Page Section Deleted');
        return back();
    }
    public function detail_destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $section = ProductFeatureDetail::find($id);
                    if($section){
                        if($section->section_elements->count()>0){
                            foreach ($section->section_elements as $section_element){
                                if($section_element->image){
                                    @unlink($section_element->image);
                                }
                                $section_element->delete();
                            }
                        }
                        $section->delete();
                    }
                }
            }
        }
        return response()->json('Page Section Deleted');
    }

    public function hide_title($id, $hide)
    {
        $page = ProductFeatureDetail::find($id);
        $page->hide_title = $hide;
        $page->save();

        Session::flash('success', 'Page Section Updated');
        return redirect()->back();
    }

    public function importLandingPage(Request $request, $id)
    {
        $domain = Domain::where('id', $id)->where('template_enable', 1)->first();

        if(!$domain){
            \Session::flash('error', 'Landing page not found !');
            return back();
        }
        DB::beginTransaction();
        try {
            if($request->import){
                $this->cloneLandingPage($domain->user_id);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            \Session::flash('warning', 'Something went wrong !');
            return back();
        }

        Cache::forget(get_host());
        \Session::flash('success', 'Import Landing Page successfully');
        return back();
    }

    public function cloneLandingPage($user_domain_id)
    {
        $user = Auth::user();
        $user_template = User::find($user_domain_id);
        //file
        $path_file_old = 'uploads/'.$user_template->id;
        $path_file_new = 'uploads/'.$user->id;
        
        //feature_page
        $feature_pages = ProductFeature::where('user_id',$user_template->id)->where('is_home_page',1)->get();
        $old_feature_page_id = $feature_pages->pluck('id')->toArray();
        $new_feature_page_id = [];
        if($feature_pages){
            foreach($feature_pages as $feature_page){
                $new_feature_page = $feature_page->replicate();
                $new_feature_page->user_id = $user->id;
                $new_feature_page->save();
                array_push($new_feature_page_id,$new_feature_page->id);
            }
            $final_feature_page_id = array_combine($old_feature_page_id,$new_feature_page_id);
        }

        //section
        $sections = ProductFeatureDetail::whereIn('feature_page_id',$old_feature_page_id)->get();
        $old_section_id = $sections->pluck('id')->toArray();
        $new_section_id = [];
        if($sections){
            foreach($sections as $section){
                $new_section = $section->replicate();
                $new_section->image=str_replace($path_file_old,$path_file_new,$section->image);
                if($section->image){
                    copy($section->image,$new_section->image);
                }
                $new_section->feature_page_id = $final_feature_page_id[$section->feature_page_id] ?? null;
                $new_section->save();
                array_push($new_section_id,$new_section->id);
            }
            $final_section_id = array_combine($old_section_id,$new_section_id);
        }

        //section_element
        $section_elements = ProductFeatureSectionElement::whereIn('feature_page_detail_id',$old_section_id)->get();
        if($section_elements){
            foreach($section_elements as $section_element){
                $new_section_element = $section_element->replicate();
                $new_section_element->image=str_replace($path_file_old,$path_file_new,$section_element->image);
                if($section_element->image){
                    copy($section_element->image,$new_section_element->image);
                }
                $new_section_element->feature_page_detail_id = $final_section_id[$section_element->feature_page_detail_id] ?? null;
                $new_section_element->save();
            }
        }
    }
}
