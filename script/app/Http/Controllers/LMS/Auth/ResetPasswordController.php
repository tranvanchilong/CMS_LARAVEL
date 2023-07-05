<?php

namespace App\Http\Controllers\LMS\Auth;

use App\Http\Controllers\LMS\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\LMS\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token)
    {
        $updatePassword = DB::table('lms_password_resets')
            ->where(['email' => $request->email, 'token' => $token])
            ->first();

        if (!empty($updatePassword)) {
            return view('lms.'. getTemplate() . '.auth.reset_password', ['token' => $token]);
        }

        abort(404);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:lms_users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        $data = $request->all();

        $updatePassword = DB::table('lms_password_resets')
            ->where(['email' => $data['email'], 'token' => $data['token']])
            ->first();

        if (!empty($updatePassword)) {
            $user = User::where('email', $data['email'])
                ->update(['password' => Hash::make($data['password'])]);

            DB::table('lms_password_resets')->where(['email' => $data['email']])->delete();

            $toastData = [
                'title' => trans('lms/public.request_success'),
                'msg' => trans('lms/auth.reset_password_success'),
                'status' => 'success'
            ];
            return redirect('/lms/login')->with(['toast' => $toastData]);
        }

        $toastData = [
            'title' => trans('lms/public.request_failed'),
            'msg' => trans('lms/auth.reset_password_token_invalid'),
            'status' => 'error'
        ];
        return back()->withInput()->with(['toast' => $toastData]);
    }
}
