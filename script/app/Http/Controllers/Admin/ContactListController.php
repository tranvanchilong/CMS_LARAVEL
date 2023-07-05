<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactLists;

class ContactListController extends Controller
{

    public function index() {
        if (Auth::user()->role_id !== 1) {
            return abort(401);
        }

        $auth_id=Auth::id();
        $contact = ContactLists::where('user_id', $auth_id)->paginate(5);
    
        return view('admin.settings.contact_list', compact('contact'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role_id !== 1) {
            return abort(401);
        }

        $validated = $request->validate([
            'url' => 'required',
            'file' => 'required|max:500|image'
        ]);

        $auth_id=Auth::id();

        $contact = new ContactLists;
        $contact->url = $request->url;
        
        $contact->user_id=$auth_id;
        $fileName = time().'.'.$request->file->extension();  
        $path='uploads/'.$auth_id.'/'.date('y/m');
        $request->file->move($path, $fileName);
        $name=$path.'/'.$fileName;
        $contact->image = $name;
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
        if (Auth::user()->role_id !== 1) {
            return abort(401);
        }

        $contacts = ContactLists::findorFail($id);
        return view('admin.settings.edit_contact_list',compact('contacts'));
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
        if (Auth::user()->role_id !== 1) {
            return abort(401);
        }

        $validated = $request->validate([
            'url' => 'required',
        ]);
        $auth_id=Auth::id();

        $contact = ContactLists::find($id);
        $contact->url = $request->url;
        $contact->serial_number = $request->serial_number;
        $contact->is_show_float_content = $request->is_show_float_content;
        $contact->is_show_topbar = $request->is_show_topbar;
        if($request->file) {
            $fileName = time().'.'.$request->file->extension();  
            $path='uploads/'.$auth_id.'/'.date('y/m');
            $request->file->move($path, $fileName);
            $name=$path.'/'.$fileName;
            $contact->image = $name;
            $contact->save();
        }
        $contact->save();
        return redirect('admin/contact');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->role_id !== 1) {
            return abort(401);
        }

        $contact = ContactLists::findorFail($id);
        if (file_exists($contact->image)){
            unlink($contact->image);
        }
        $contact->delete();

        return back();
    }
}
