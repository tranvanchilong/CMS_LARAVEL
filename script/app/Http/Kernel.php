<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthorMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\Subdomain;
use App\Http\Middleware\Customdomain;
use App\Http\Middleware\Domain;
use App\Http\Middleware\ExceptParamDomain;
use App\Http\Middleware\LMS\AdminAuthenticate;
use App\Http\Middleware\LMS\CheckMaintenance;
use App\Http\Middleware\LMS\CheckMobileApp;
use App\Http\Middleware\LMS\Impersonate;
use App\Http\Middleware\LMS\PanelAuthenticate;
use App\Http\Middleware\LMS\Share;
use App\Http\Middleware\LMS\UserNotAccess;
use App\Http\Middleware\LMS\WebAuthenticate;
use App\Http\Middleware\LMS\RedirectIfAuthenticated;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Localization::class,
            \App\Http\Middleware\LMS\UserLocale::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \App\Http\Middleware\LMS\Api\CheckApiKey::class,
            // \App\Http\Middleware\LMS\Api\SetLocale::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'domain' => Domain::class,
        'subdomain' => Subdomain::class,
        'customdomain' => Customdomain::class,
        'admin' => AdminMiddleware::class,
        'author' => AuthorMiddleware::class,
        'seller' => SellerMiddleware::class,
        'customer' => CustomerMiddleware::class,
        'except_param_domain' => ExceptParamDomain::class,
        
        //lms
        'lms_guest' => RedirectIfAuthenticated::class,
        'lms_admin' => AdminAuthenticate::class,
        'panel' => PanelAuthenticate::class,
        'user.not.access' => UserNotAccess::class,
        'web.auth' => WebAuthenticate::class,
        'impersonate' => Impersonate::class,
        'share' => Share::class,
        'check_mobile_app' => CheckMobileApp::class,
        'check_maintenance' => CheckMaintenance::class,
        // api
        // 'api.auth' => \App\Http\Middleware\LMS\Api\Authenticate::class,
        // 'api.guest' => \App\Http\Middleware\LMS\Api\RedirectIfAuthenticated::class,
        // 'api.request.type' => \App\Http\Middleware\LMS\Api\RequestType::class,
        // 'api.identify' => \App\Http\Middleware\LMS\Api\CheckApiKey::class,
        // 'api.level-access' => \App\Http\Middleware\LMS\Api\LevelAccess::class,
    ];
}
