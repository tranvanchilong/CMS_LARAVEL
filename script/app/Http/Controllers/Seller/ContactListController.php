<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactLists;
use App\Useroption;
use App\Domain;
use Auth;
use Session;

class ContactListController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required',
            'serial_number' => 'required',
            'file' => 'required|max:500|image'
        ]);
        $auth_id=Auth::id();

        $contact = new ContactLists;
        $contact->url = $request->url;
        
        $contact->user_id=$auth_id;
        $fileName = time().'.'.$request->file->extension();  
        $path = 'uploads/'.$auth_id.'/'.date('y/m').'/';
        $ext = $request->file->extension();
        $request->file->move($path, $fileName);
        $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path);      
        $contact->image = $compress['data']['image'];  
        if($ext != 'webp'){
            @unlink($path.'/'.$fileName);
        }

        $contact->serial_number = $request->serial_number;
        $contact->is_show_float_content = $request->is_show_float_content;
        $contact->is_show_topbar = $request->is_show_topbar;
        $contact->save();

        
        
        return response()->json(['Add Contact List Successfully !']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contacts = ContactLists::where('user_id', Auth::id())->findorFail($id);
        return view('seller.settings.contact.edit',compact('contacts'));
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
            'url' => 'required',
            'serial_number' => 'required',
            'file' => 'required|max:500|image'
        ]);
        $auth_id=Auth::id();

        $contact = ContactLists::where('user_id', $auth_id)->find($id);
        $contact->url = $request->url;
        $contact->serial_number = $request->serial_number;
        $contact->is_show_float_content = $request->is_show_float_content;
        $contact->is_show_topbar = $request->is_show_topbar;
        if($request->file){
            @unlink($contact->image);
            $fileName = time().'.'.$request->file->extension();  
            $path = 'uploads/'.$auth_id.'/'.date('y/m').'/';
            $ext = $request->file->extension();
            $request->file->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$ext,60,$fileName,$path); 
            $contact->image = $compress['data']['image'];  
            if($ext != 'webp'){
                @unlink($path.'/'.$fileName);
            }
            $contact->save();
        }
        $contact->save();
        return redirect('/seller/settings/contact-list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = ContactLists::where('user_id', Auth::id())->findorFail($id);
        if (file_exists($contact->image)){
            unlink($contact->image);
        }
        $contact->delete();

        return back();
    }

    public function position_contact($position)
    {
        $contact_list= Useroption::where('user_id',Auth::id())->where('key','contact_list')->first();
        if (empty($contact_list)) {
            $contact_list=new Useroption;
        }
        $contact_list->key = 'contact_list';
        $contact_list->value = $position;
        $contact_list->user_id = Auth::id();
        $contact_list->save();
        Session::flash('success', 'Contact List Update Position Successfully');
        return redirect()->back();
    }

    public function status_contact(Request $request)
    {
        $contact_list_status= Domain::where('user_id',Auth::id())->first();
       
        $contact_list_status->top_bar_contact_status = $request->top_bar_contact_status;
        $contact_list_status->float_contact_status = $request->float_contact_status;
        $contact_list_status->user_id = Auth::id();
        $contact_list_status->save();
        Session::flash('success', 'Contact List Update Position Successfully');
        return redirect()->back();
    }

    public function icon_update(Request $request)
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
        $icon_contact= Useroption::where('user_id',Auth::id())->where('key','icon_contact')->first();
        if (empty($icon_contact)) {
            $icon_contact=new Useroption;
        }
        $icon_contact->key = 'icon_contact';
        if ($request->image) {
            @unlink($icon_contact->value);
            $fileName = time().'.'.$extImage;  
            $path = 'uploads/' . auth()->id() . '/icon_contact/' . date('y/m').'/';
            $request->image->move($path, $fileName);
            $compress= resizeImage($path.$fileName,$extImage,60,$fileName,$path);       
            $icon_contact->value = $compress['data']['image'];  
            if($extImage != 'webp'){
                @unlink($path.'/'.$fileName);
            }
        }
        $icon_contact->user_id = Auth::id();
        $icon_contact->save();
        Session::flash('success', 'Icon Update Successfully');
        return redirect()->back();

    }


}
