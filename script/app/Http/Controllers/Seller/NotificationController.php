<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Useroption;
use App\Helpers\Helper;
use Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notifications::where('user_id', Auth::id())->latest()->paginate(10);
        return view('seller.notification.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|max:500|image'
        ]);
        $auth_id=Auth::id();

        $notification = new Notifications;
        $notification->title = $request->title;
        $notification->description = $request->description;
        
        $notification->user_id=$auth_id;
        $fileName = time().'.'.$request->file->extension();  
        $path='uploads/'.$auth_id.'/'.date('y/m');
        $request->file->move($path, $fileName);
        $name=$path.'/'.$fileName;
        $notification->image = $name;
        $notification->status = 1;
        $notification->save();

       
        Helper::send_push_notif_to_topic($notification);
        
        
        return response()->json(['Send Notification Successfully !']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notices = Notifications::where('user_id', Auth::id())->findorFail($id);
        return view('seller.notification.edit',compact('notices'));
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
            'title' => 'required',
            'description' => 'required',
        ]);

        $auth_id=Auth::id();

        $notification = Notifications::where('user_id', $auth_id)->find($id);
        $notification->title = $request->title;
        $notification->description = $request->description;
        if($request->file){
            $fileName = time().'.'.$request->file->extension();  
            $path='uploads/'.$auth_id.'/'.date('y/m');
            $request->file->move($path, $fileName);
            $name=$path.'/'.$fileName;

            $notification->image = $name;
            $notification->save(); 
        }
        $notification->save(); 

        return redirect('/seller/notifications');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notifications::where('user_id', Auth::id())->findorFail($id);
        if (file_exists($notification->image)){
            unlink($notification->image);
        }
        $notification->delete();

        return back();
    }
}
