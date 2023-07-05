<?php

namespace App\Http\Controllers\LMS\Auth;

use App\Http\Controllers\LMS\Controller;
use App\Notifications\LMS\SendResetPasswordSMS;
use App\Models\LMS\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your lms_users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('lms.'. getTemplate() . '.auth.forgot_password');
    }

    public function forgot(Request $request)
    {
        if ($this->username() == 'email') {
            $rules = [
                'username' => 'required|email|exists:users,email',
            ];
        } else {
            $rules = [
                'username' => 'required|numeric|exists:users,mobile',
            ];
        }

        if (!empty(getGeneralSecuritySettings('captcha_for_forgot_pass'))) {
            $rules['captcha'] = 'required|captcha';
        }

        $request->validate($rules);

        if ($this->username() == 'email') {
            return $this->getByEmail($request);
        } else {
            return $this->getByMobile($request);
        }
    }

    private function getByEmail(Request $request)
    {
        $email = $request->get('username');
        $token = \Illuminate\Support\Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $generalSettings = getGeneralSettings();
        $emailData = [
            'token' => $token,
            'generalSettings' => $generalSettings,
            'email' => $email
        ];

        Mail::send('web.default.auth.password_verify', $emailData, function ($message) use ($email) {
            $message->from(!empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($email);
            $message->subject('Reset Password Notification');
        });

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => trans('lms/auth.send_email_for_reset_password'),
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (empty($this->username)) {
            $this->username = 'mobile';

            if (preg_match($email_regex, request('username', null))) {
                $this->username = 'email';
            }
        }
        return $this->username;
    }
}
