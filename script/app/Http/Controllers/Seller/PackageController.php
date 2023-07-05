<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Category;

class packageController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['packages'] = Package::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        $data['packages_categories'] = Category::where('type','package_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        return view('seller.package.index',$data);
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        
        $package = new Package;
        $package->user_id = auth()->id();
        $package->name = $request->name;
        if($request->lang_id){
            $package->lang_id = json_encode($request->lang_id);
        }
        $package->featured = $request->featured;
        $package->package_feature = $request->package_feature;
        $package->not_package_feature = $request->not_package_feature;
        $package->note = $request->note;
        $package->price = $request->price;
        $package->category_id = $request->category_id;
        $package->btn_text = $request->btn_text;
        $package->btn_url = $request->btn_url;
        $package->btn_text_2 = $request->btn_text_2;
        $package->btn_url_2 = $request->btn_url_2;
        $package->meta_description = $request->meta_description;
        $package->meta_keyword = $request->meta_keyword;
        $package->serial_number = $request->serial_number;

        $package->save();

        return response()->json(['success','Package Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $package = Package::find($id);
                    if($package){
                        $package->delete();
                    }
                }
            }
        }
        return response()->json('Package Deleted');
    }

    public function edit($id){
        $package = Package::find($id);
        $packages_categories = Category::where('type','package_category')->where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        return view('seller.package.edit', compact('package','packages_categories'));
    }

    public function update($id, Request  $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
            'serial_number' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $package = Package::find($id);
        $package->name = $request->name;
        $package->lang_id = $request->lang_id;
        $package->featured = $request->featured;
        $package->package_feature = $request->package_feature;
        $package->not_package_feature = $request->not_package_feature;
        $package->note = $request->note;
        $package->price = $request->price;
        $package->category_id = $request->category_id;
        $package->btn_text = $request->btn_text;
        $package->btn_url = $request->btn_url;
        $package->btn_text_2 = $request->btn_text_2;
        $package->btn_url_2 = $request->btn_url_2;
        $package->meta_description = $request->meta_description;
        $package->meta_keyword = $request->meta_keyword;
        $package->serial_number = $request->serial_number;

        $package->save();

        return response()->json(['success','Package Updated']);
    }
}
