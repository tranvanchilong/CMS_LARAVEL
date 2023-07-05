<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Validator;
use Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PortfolioCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['portfolio_categories'] = Category::where('type','portfolio_category')->where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.portfolio_category.index',$data);
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $portfolio_category = new Category;
        $portfolio_category->user_id = auth()->id();
        $portfolio_category->name = $request->name;
        if($request->lang_id){
            $portfolio_category->lang_id = json_encode($request->lang_id);
        }
        $portfolio_category->slug = Str::slug($request->name);
        $portfolio_category->featured = $request->featured;
        $portfolio_category->type = 'portfolio_category';
        $portfolio_category->save();

        return response()->json(['success','portfolio Category Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $portfolio_category = Category::find($id);
                    if($portfolio_category){
                        // if($portfolio_category->portfolios->count()>0){
                        //     foreach ($portfolio_category->portfolios as $portfolio){
                        //         if($portfolio->image){
                        //             @unlink($portfolio->image);
                        //         }
                        //         $portfolio->delete();
                        //     }
                        // }
                        $portfolio_category->delete();
                    }
                }
            }
        }
        return response()->json('portfolio Category Deleted');
    }

    public function edit($id){
        $portfolio_category = Category::find($id);
        return view('seller.portfolio_category.edit', compact('portfolio_category'));
    }

    public function update($id, Request  $request){
        $rules = [
            'name' => 'required|max:255',
            'featured' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $portfolio_category = Category::find($id);
        $portfolio_category->name = $request->name;
        $portfolio_category->lang_id = $request->lang_id;
        $portfolio_category->slug = Str::slug($request->name);
        $portfolio_category->featured = $request->featured;
        $portfolio_category->save();

        return response()->json(['success','portfolio Category Updated']);
    }
}
