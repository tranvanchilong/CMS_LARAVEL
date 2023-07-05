<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductFeature;
use App\ProductFeatureDetail;
use App\ProductFeatureSectionElement;
use Validator;
use Session;
use Image;

class ProductFeatureSectionElementController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'image' => 'nullable',
            'title' => 'required',
            'text' => 'nullable',
            'video_url' => 'nullable',
            'btn_text' => 'nullable',
            'btn_url' => 'nullable|max:255',
            'btn_text_1' => 'nullable',
            'btn_url_1' => 'nullable|max:255',
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
        
        if($request->section_element_id){
            $section_element = ProductFeatureSectionElement::find($request->section_element_id);
            $section_element->updated_at = now();
            $messsage = 'Section Element Created';
        }
        else{
            $section_element = new ProductFeatureSectionElement;
            $section_element->created_at = now();
            $messsage = 'Section Element Updated';
        }
        
        $section_element->title = $request->title;
        $section_element->text = $request->text;
        $section_element->video_url = $request->video_url;
        $section_element->btn_text = $request->btn_text;
        $section_element->btn_url = $request->btn_url;
        $section_element->btn_text_1 = $request->btn_text_1;
        $section_element->btn_url_1 = $request->btn_url_1;

        $feature_page_detail = ProductFeatureDetail::find($request->id);
        
        if ($request->image) {
            if($feature_page_detail->feature_type == 'hero slide 2'){
                $imageSizes= imageUploadSizes('slider');
            }else{
                $imageSizes= imageUploadSizes('thumbnail');
            }
            if($request->section_element_id){
                @unlink($section_element->image);
            }
            $fileName = time().'.webp';   
            $path='uploads/'.auth()->id().'/feature_page/'.date('y/m').'/';
            $image->move($path, $fileName);
            $filenames = $path.$fileName;
            $img = Image::make($filenames);
            $getImageHeigth = imageHeight($img,$imageSizes);
            $img->resize($imageSizes['width'],$getImageHeigth);                 
            $img->save($img->dirname.'/'.$img->filename.$imageSizes['key'].'.'.$img->extension);
            $section_element->image = $img->dirname.'/'.$img->filename.'.'.$img->extension;
            @unlink($filenames);
            
        }
        
        $section_element->feature_page_detail_id = $request->id;
        $section_element->serial_number = $request->serial_number;
        $section_element->save();

        return response()->json(['success',$messsage]);
    }
    public function edit($id)
    {
        $data['section_element'] = ProductFeatureSectionElement::find($id);
        $data['feature'] = ProductFeatureDetail::find($data['section_element']->feature_page_detail_id);
        $data['page'] = ProductFeature::find($data['feature']->feature_page_id);
        return view('seller.product_feature.edit_section_element',$data);
    }
    public function delete($id){
        $section_element = ProductFeatureSectionElement::find($id);
        if($section_element){
            if($section_element->image){
                @unlink($section_element->image);
            }
            $section_element->delete();
        }
        Session::flash('success', 'Section Element Deleted');
        return back();
    }
    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $section_element = ProductFeatureSectionElement::find($id);
                    if($section_element){
                        if($section_element->image){
                            @unlink($section_element->image);
                        }
                        $section_element->delete();
                    }
                }
            }
        }
        return response()->json('Section Element Deleted');
    }
}
