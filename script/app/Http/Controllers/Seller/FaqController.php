<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Faq;
use Validator;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $lang_id =  $request->language;
        $data['faqs'] = Faq::where('user_id', auth()->id())->language($lang_id)->orderBy('id', 'DESC')->paginate(20);
        return view('seller.faqs.index',$data);
    }

    public function store(Request $request){
        $rules = [
            'question' => 'required|max:255',
            'answer' => 'required',
            'featured' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $faq = new Faq;
        $faq->user_id = auth()->id();
        if($request->lang_id){
            $faq->lang_id = json_encode($request->lang_id);
        }
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->featured = $request->featured;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        return response()->json(['success','Faq Created']);
    }

    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $faq = Faq::find($id);
                    if($faq){
                        $faq->delete();
                    }
                }
            }
        }
        return response()->json('Faq Deleted');
    }

    public function edit($id){
        $faq = Faq::find($id);
        return view('seller.faqs.edit', compact('faq'));
    }

    public function update($id, Request  $request){
        $rules = [
            'question' => 'required|max:255',
            'answer' => 'required',
            'featured' => 'required',
            'serial_number' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $faq = Faq::find($id);
        $faq->lang_id = $request->lang_id;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->featured = $request->featured;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        return response()->json(['success','Faq Updated']);
    }
}
