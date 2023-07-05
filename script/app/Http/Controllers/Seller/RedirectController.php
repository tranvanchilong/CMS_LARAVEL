<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Redirect;
use Auth;

class RedirectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $redirect['redirects'] = Redirect::where('user_id', Auth::id())->latest()->paginate(5);
        return view('seller.redirect.index', $redirect);
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
        $redirect = new Redirect;
        $redirect->user_id=Auth::id();
        $redirect->link_check = trim($request->link_check, '/');
  
        $redirect->link_redirect = $request->link_redirect;
        $redirect->save();

        return response()->json(['Add Redirect Successfully !']);
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
        $redirects = Redirect::where('user_id', Auth::id())->findorFail($id);
        return view('seller.redirect.edit',compact('redirects'));
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
        $redirect = Redirect::where('user_id', Auth::id())->find($id);
        $redirect->user_id=Auth::id();
        $redirect->link_check = trim($request->link_check, '/');
        $redirect->link_redirect = $request->link_redirect;
        $redirect->save();

        return redirect('/seller/setting/redirect');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $redirect = Redirect::where('user_id', Auth::id())->findorFail($id);
       
        $redirect->delete();

        return back();
    }
}
