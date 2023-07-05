<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain;
use Auth;
use Hash;

class MaintainanceModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maintainance_mode = Domain::where('user_id', Auth::id())->first();
        return view('seller.settings.maintainance', compact('maintainance_mode'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $maintainance_mode = Domain::where('user_id', Auth::id())->first();

        $maintainance_mode->is_maintainance_mode = $request->maintainance_mode;
   
        $maintainance_mode->maintainance_mode_password = Hash::make($request->secret_password);

        $maintainance_mode->save();

        return response()->json(['Maintanance mode updated successfully!']);
    }

    
}
