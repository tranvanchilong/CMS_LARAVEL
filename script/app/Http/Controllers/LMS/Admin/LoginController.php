<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/lms/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('lms_guest')->except('logout');
    }

    public function showLoginForm()
    {
        $data = [
            'pageTitle' => trans('lms/auth.login'),
        ];


        return view('lms.admin.auth.login', $data);
    }

    /**
     * Check either username or email.
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Validate the user login.
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|email|exists:lms_users,email,status,active',
                'password' => 'required|min:4',
            ]
        );
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $request->session()->put('login_error', trans('lms/auth.failed'));
        throw ValidationException::withMessages(
            [
                'error' => [trans('lms/auth.failed')],
            ]
        );
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:lms_users,email,status,active',
            'password' => 'required|min:4',
        ];

        if (!empty(getGeneralSecuritySettings('captcha_for_admin_login'))) {
            $rules['captcha'] = 'required|captcha';
        }

        // validate the form data
        $this->validate($request, $rules);

        if (Auth::guard('lms_user')->attempt(['email' => $request->email, 'password' => $request->password, 'domain_id' => domain_info('domain_id')], $request->remember)) {
            return Redirect::to('/lms/admin');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'password' => 'Wrong password or this account not approved yet.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('lms_user')->logout();
        return redirect('/lms/admin/login');
    }
}
