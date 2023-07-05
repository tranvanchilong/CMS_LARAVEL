<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\AdminVerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Auth;

class EmailController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $real_time = strtotime(date('Y-m-d'));
        $create_time = strtotime(date_format(Auth::user()->created_at, 'Y-m-d'));
        $count_day = abs($create_time - $real_time);
        $count_day = ($count_day/(60*60*24));
        if($count_day <= 1){
            if($user){
                if($user->email_verified_at == null){
                    $user->email_verified_at = now();
                    $user->email_verified = 1;
                    $user->save();
                }
                $user->email_verified = 1;
                $user->save();
                return redirect('/seller/dashboard');
            }
        }

        return view('seller.email.verify_email', compact('count_day'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('id', $request->id)->where('role_id', 3)->where('status', 1)->first();
        $user->is_check_send_mail = 1;
        $dataSendMail = [
            'link' =>  route('email.verify_email', Crypt::encryptString($user->id))
        ];
        
        //send email verify
        Mail::to($user->email)->send(new AdminVerifyEmail($dataSendMail));
        $user->save();
        return response()->json(['Please verify your email']); 
    } 

    public function verify(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        if($user){
            if($user->email_verified_at == null){
                $user->email_verified_at = now();
                $user->email_verified = 1;
                $user->save();
            }
            $user->email_verified = 1;
            $user->save();
            return redirect('/seller/dashboard');
        }
    }

    public function verify_email(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $user = User::where('id', $id)->first();

        if($user){
            if($user->email_verified_at == null){
                $user->email_verified_at = now();
                $user->email_verified = 1;
                $user->save();
            }
            $user->email_verified = 1;
            $user->save();
            \Session::flash('success', 'Email verify successfully');
            return redirect('/seller/dashboard');
        }

        abort(404);
    }
}
