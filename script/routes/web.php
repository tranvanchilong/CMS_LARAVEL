<?php

use Illuminate\Support\Facades\Route;

Route::get('/check', 'FrontendController@check');
Route::get('/pwa', function () {
    return redirect('/');
});



// Match my own domain
Route::group(['domain' => env('APP_URL')], function ($domain) {
    Route::get('/', 'FrontendController@welcome');
    Route::get('/page/{slug}', 'FrontendController@page');
    Route::get('/about', 'FrontendController@about');
    Route::get('/service', 'FrontendController@service');
    Route::get('/priceing', '   @priceing')->name('front.priceing');
    Route::get('/template', 'FrontendController@template')->name('front.template');
    Route::get('/make-translate', 'FrontendController@translate')->name('translate');
    Route::get('/contact', 'FrontendController@contact');
    Route::get('/blog', 'FrontendController@blog_list');
    Route::get('/blog-detail/{slug}', 'FrontendController@blog_detail')->name('blog-detail');
    Route::post('/sent-mail', 'FrontendController@send_mail')->name('send_mail');
    Route::get('merchant/register', 'FrontendController@register_view')->name('merchant.form');
    Route::post('seller-register', 'FrontendController@register')->name('merchant.register-make');

    Route::group(['prefix' => 'cron'], function () {
        Route::get('/make-expire-order', 'CronController@makeExpireAbleCustomer');
        Route::get('/make-alert-before-expire-plan', 'CronController@send_mail_to_will_expire_plan_soon');
        Route::get('/reset_product_price', 'CronController@reset_product_price');
        Route::get('/exchange_rates', 'CronController@exchange_rates');
        Route::get('/check-status-binance-transaction', 'CronController@checkStatusBinanceTransaction');
    });


    Route::group(['as' => 'merchant.', 'prefix' => 'merchant', 'middleware' => ['auth']], function () {
        Route::get('/dashboard', 'FrontendController@dashboard')->name('dashboard');
        Route::get('/make-payment/{id}', 'FrontendController@make_payment')->name('make_payment');
        Route::get('/plan', 'FrontendController@plans')->name('plan');
        Route::get('/profile', 'FrontendController@settings')->name('profile.settings');

        Route::post('/make-charge/{id}', 'FrontendController@make_charge')->name('make_payment_charge');
        Route::get('/payment-success', 'FrontendController@success')->name('payment.success');
        Route::get('/payment-fail', 'FrontendController@fail')->name('payment.fail');
        Route::get('/instamojo', '\App\Helper\Subscription\Instamojo@status')->name('instamojo.fallback');
        Route::get('/paypal', '\App\Helper\Subscription\Paypal@status')->name('paypal.fallback');
        Route::get('/toyyibpay', '\App\Helper\Subscription\Toyyibpay@status')->name('toyyibpay.fallback');
        Route::get('/payment-with/razorpay', '\App\Helper\Subscription\Razorpay@razorpay_view');
        Route::get('/payment/mollie', '\App\Helper\Subscription\Mollie@status');
        Route::get('/payment/mercado', '\App\Helper\Subscription\Mercado@status');
        Route::post('/razorpay/status', '\App\Helper\Subscription\Razorpay@status');
        Route::post('/paystack/status', '\App\Helper\Subscription\Paystack@status');
    });

    // Route::group(['namespace' => 'Frontend'], function () {
    //     Route::get('product/{slug}/{id}', 'FrontendController@detail');
    // });

    Route::get('/sitemap.xml', function () {
        return response(file_get_contents(base_path('sitemap.xml')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    });

    Route::get('/verify-login-domain', 'Controller@verifyLoginDomain');
    Route::get('/verify-login', 'Di4lController@verifyLogin');

    Route::get('/get-domain-by-email', 'FrontendController@getDomainByEmail');
    Route::get('/register-from-my-di4l', 'FrontendController@registerFromMyDi4l');
});




// Match a subdomain of my domain
Route::group(['domain' => '{subdomain}.' . env('APP_PROTOCOLESS_URL'), 'middleware' => ['domain', 'share']], function () {



    Route::group(['namespace' => 'Frontend'], function () {
        Route::get('/', 'FrontendController@index');
        Route::get(permalink_type('fp').'/{slug}', 'FrontendController@feature_page');
        Route::get('product/{slug}/{id}', 'FrontendController@detail');
        Route::get('/'.permalink_type('shop').'', 'FrontendController@shop');
        Route::get('/cart', 'FrontendController@cart');
        Route::get('/wishlist', 'FrontendController@wishlist');
        Route::get('/wishlist/remove/{id}', 'CartController@wishlist_remove');
        Route::get('/getforVariation', 'CartController@getforVariation');
        Route::get('/category/{slug}/{id}', 'FrontendController@category');
        Route::get('/'.permalink_type('blog').'/category/{slug}', 'FrontendController@blogCategory');
        Route::get('/brand/{slug}/{id}', 'FrontendController@brand');
        Route::get('/trending', 'FrontendController@trending');
        Route::get('/best-sales', 'FrontendController@best_seles');
        Route::get('/add_to_cart/{id}', 'CartController@add_to_cart');
        Route::get('/add_to_wishlist/{id}', 'CartController@add_to_wishlist');
        Route::post('/addtocart', 'CartController@cart_add');
        Route::post('/update_cart', 'CartController@update_cart');
        Route::get('/remove_cart', 'CartController@remove_cart');
        Route::get('/cart_remove/{id}', 'CartController@cart_remove');
        Route::get('/cart-clear', 'CartController@cart_clear');
        Route::post('apply_coupon', 'CartController@apply_coupon');

        Route::get('/get_home_page_products', 'FrontendController@home_page_products');
        Route::post('make_order', 'OrderController@store');
        Route::get('/express', 'CartController@express');
        Route::get('/get_ralated_product_with_latest_post', 'FrontendController@get_ralated_product_with_latest_post');
        Route::get('/get_category_with_product/{limit}', 'FrontendController@get_category_with_product');
        Route::get('/get_brand_with_product/{limit}', 'FrontendController@get_brand_with_product');
        Route::get('/get_featured_category', 'FrontendController@get_featured_category');
        Route::get('/get_featured_brand', 'FrontendController@get_featured_brand');
        Route::get('/get_category', 'FrontendController@get_category');
        Route::get('/get_brand', 'FrontendController@get_brand');
        Route::get('/get_products', 'FrontendController@get_products');
        Route::get('/get_latest_products', 'FrontendController@get_latest_products');
        Route::get('/get_shop_products', 'FrontendController@get_shop_products');
        Route::get('/get_slider', 'FrontendController@get_slider');
        Route::get('/get_bump_adds', 'FrontendController@get_bump_adds');
        Route::get('/get_banner_adds', 'FrontendController@get_banner_adds');
        Route::get('/get_menu_category', 'FrontendController@get_menu_category');
        Route::get('/get_trending_products', 'FrontendController@get_trending_products');
        Route::get('/get_best_selling_product', 'FrontendController@get_best_selling_product');
        Route::get('/get_ralated_products', 'FrontendController@get_ralated_products');
        Route::get('/get_offerable_products', 'FrontendController@get_offerable_products');
        Route::get('/product_search', 'FrontendController@product_search');
        Route::get('/get_featured_attributes', 'FrontendController@get_featured_attributes');
        Route::get('/get_random_products/{limit}', 'FrontendController@get_random_products');
        Route::get('/get_shop_attributes', 'FrontendController@get_shop_attributes');
        Route::get('/'.permalink_type('checkout').'', 'FrontendController@checkout');
        Route::post('/make-review/{id}', 'ReviewController@store')->middleware('throttle:1,1');
        Route::get('/get_reviews/{id}', 'FrontendController@get_reviews');
        Route::post('/make-review-course/{id}', 'ReviewCourseController@store')->middleware('throttle:1,1');
        // Route::get('/get_reviews_course/{id}', 'FrontendController@get_reviews');
        Route::get('/'.permalink_type('thanks').'', 'FrontendController@thanks');
        Route::get('/make_local', 'FrontendController@make_local');
        Route::get('/sitemap.xml', 'FrontendController@sitemap');
        Route::get('/'.permalink_type('contact-us').'', 'FrontendController@contact');
        Route::get('/'.permalink_type('page').'/{slug}', 'FrontendController@page');
        Route::get('/'.permalink_type('blog').'', 'FrontendController@blog_list');
        Route::get('/'.permalink_type('blog_detail').'/{slug}', 'FrontendController@blog_detail');
        Route::get('/'.permalink_type('service').'', 'FrontendController@service_list');
        Route::get('/'.permalink_type('service_detail').'/{slug}', 'FrontendController@service_detail');
        Route::get('/'.permalink_type('portfolio').'', 'FrontendController@portfolio_list');
        Route::get('/'.permalink_type('portfolio_detail').'/{slug}', 'FrontendController@portfolio_detail');
        Route::get('/'.permalink_type('career').'', 'FrontendController@career_list');
        Route::get('/'.permalink_type('career_detail').'/{slug}', 'FrontendController@career_detail');
        Route::get('/'.permalink_type('team').'', 'FrontendController@team_list');
        Route::get('/'.permalink_type('team_detail').'/{id}', 'FrontendController@team_detail');
        Route::get('/'.permalink_type('instructor').'', 'FrontendController@instructor_list');
        Route::get('/'.permalink_type('instructor').'/{id}', 'FrontendController@instructor_detail');
        Route::get('/'.permalink_type('package').'', 'FrontendController@package_list');
        Route::get('/'.permalink_type('faq').'', 'FrontendController@faqs');
        Route::get('/'.permalink_type('testimonial').'', 'FrontendController@testimonial');
        Route::get('/'.permalink_type('partner').'', 'FrontendController@partner');
        Route::post('/send-contact', 'FrontendController@sendMailContact');
        Route::get('/refresh-captcha', 'FrontendController@refreshCaptcha');
        Route::get('/template', 'FrontendController@gallery');
        Route::get('/'.permalink_type('gallery').'', 'FrontendController@gallery');
        Route::get('/'.permalink_type('booking').'', 'FrontendController@booking');
        Route::post('/booking', 'FrontendController@store_booking');
        Route::any('/maintenance', 'FrontendController@maintenance');
        Route::get('/'.permalink_type('knowledge').'', 'FrontendController@guide_list');
        Route::get('/'.permalink_type('knowledge').'/{slug}', 'FrontendController@guide_detail');


        Route::group(['prefix' => 'user'], function () {
            Route::get('/login', 'UserController@login')->middleware('guest');
            Route::get('/register', 'UserController@register')->middleware('guest');
            Route::post('/register-user', 'UserController@register_user')->middleware('guest');
            Route::post('/logout', 'UserController@logout')->middleware('guest');
            Route::get('/dashboard', 'UserController@dashboard')->middleware('customer');
            Route::get('/wallet', 'UserController@wallet')->middleware('customer');
            Route::get('/wallet/add', 'UserController@wallet_qr')->middleware('customer');
            Route::post('/wallet', 'UserController@wallet_add')->middleware('customer');
            Route::get('/deposit_metamask', 'UserController@deposit_method')->middleware('customer');
            Route::post('/deposit', 'UserController@deposit')->middleware('customer');
            Route::get('/all-notification', 'UserController@notification')->middleware('customer');
            Route::get('/orders', 'UserController@orders')->middleware('customer');
            Route::get('/order/view/{id}', 'UserController@order_view')->middleware('customer');
            Route::get('/bookings', 'UserController@bookings')->middleware('customer');
            Route::get('/booking/view/{id}', 'UserController@booking_view')->middleware('customer');
            Route::get('/download', 'UserController@download')->middleware('customer');
            Route::get('/settings', 'UserController@settings')->middleware('customer');
            Route::post('/settings/update', 'UserController@settings_update')->middleware('customer');
            Route::group(['prefix' => 'affiliate'], function () {
                Route::get('/affiliate_system', 'AffiliateController@affiliate_system')->middleware('customer');
                Route::get('/payment_history', 'AffiliateController@affiliate_payment_history')->middleware('customer');
                Route::get('/withdraw_request_history', 'AffiliateController@affiliate_withdraw_request_history')->middleware('customer');
                Route::post('/withdraw_request/store', 'AffiliateController@withdraw_request_store')->middleware('customer');
                Route::get('/payment/settings', 'AffiliateController@payment_settings')->middleware('customer');
                Route::post('/payment/settings/store', 'AffiliateController@payment_settings_store')->middleware('customer');
                Route::get('/refferal_users', 'AffiliateController@affiliate_refferal_users')->middleware('customer');
            });
        });


        //payment getway routes only
        Route::get('/payment/payment-success', 'OrderController@payment_success');
        Route::get('/payment/payment-fail', 'OrderController@payment_fail');
        Route::get('/payment/paypal', '\App\Helper\Order\Paypal@status');
        Route::get('/payment/instamojo', '\App\Helper\Order\Instamojo@status');
        Route::get('/payment/toyyibpay', '\App\Helper\Order\Toyyibpay@status');
        Route::get('/payment/mercado', '\App\Helper\Order\Mercado@status');
        Route::get('/payment/mollie', '\App\Helper\Order\Mollie@status');
        Route::get('/payment-with-stripe', '\App\Helper\Order\Stripe@view');
        Route::post('/payement/stripe', '\App\Helper\Order\Stripe@status');
        Route::get('/payment-with-razorpay', '\App\Helper\Order\Razorpay@view');
        Route::post('/payement/razorpay', '\App\Helper\Order\Razorpay@status');

        Route::get('/payment-with-paystack', '\App\Helper\Order\Paystack@view');
        Route::post('/payement/paystack', '\App\Helper\Order\Paystack@status');

        Route::get('/payment-with-vnpay', 'OrderController@api_payment_vnpay');
        Route::get('/payment-with-momo', 'OrderController@api_payment_momo');

        Route::get('/login-from-mydi4lvn', 'UserController@loginFromMyDi4l');
        Route::get('/login-from-admin', 'UserController@loginFromAdmin');

    });
    Route::group(['prefix' => 'lms','namespace' => 'LMS','middleware' => 'except_param_domain'], function () {
        require __DIR__ . '/lms/panel.php';
        require __DIR__ . '/lms/web.php';
        require __DIR__ . '/lms/admin.php';
    });
});


// Match any other domains
Route::group(['domain' => '{domain}', 'middleware' => ['domain', 'customdomain', 'share']], function () {


    Route::group(['namespace' => 'Frontend'], function () {
        Route::get('/', 'FrontendController@index');
        Route::get(permalink_type('fp').'/{slug}', 'FrontendController@feature_page');
        Route::get('product/{slug}/{id}', 'FrontendController@detail');
        Route::get('/'.permalink_type('shop').'', 'FrontendController@shop');
        Route::get('/cart', 'FrontendController@cart');
        Route::get('/wishlist', 'FrontendController@wishlist');
        Route::get('/wishlist/remove/{id}', 'CartController@wishlist_remove');
        Route::get('/getforVariation', 'CartController@getforVariation');
        Route::get('/category/{slug}/{id}', 'FrontendController@category');
        Route::get('/'.permalink_type('blog').'/category/{slug}', 'FrontendController@blogCategory');
        Route::get('/brand/{slug}/{id}', 'FrontendController@brand');
        Route::get('/trending', 'FrontendController@trending');
        Route::get('/best-sales', 'FrontendController@best_seles');
        Route::get('/add_to_cart/{id}', 'CartController@add_to_cart');
        Route::get('/add_to_wishlist/{id}', 'CartController@add_to_wishlist');
        Route::post('/addtocart', 'CartController@cart_add');
        Route::post('/update_cart', 'CartController@update_cart');
        Route::get('/remove_cart', 'CartController@remove_cart');
        Route::get('/cart_remove/{id}', 'CartController@cart_remove');
        Route::get('/cart-clear', 'CartController@cart_clear');
        Route::post('apply_coupon', 'CartController@apply_coupon');

        Route::get('/get_home_page_products', 'FrontendController@home_page_products');
        Route::post('make_order', 'OrderController@store');
        Route::get('/express', 'CartController@express');
        Route::get('/get_ralated_product_with_latest_post', 'FrontendController@get_ralated_product_with_latest_post');
        Route::get('/get_category_with_product/{limit}', 'FrontendController@get_category_with_product');
        Route::get('/get_brand_with_product/{limit}', 'FrontendController@get_brand_with_product');
        Route::get('/get_featured_category', 'FrontendController@get_featured_category');
        Route::get('/get_featured_brand', 'FrontendController@get_featured_brand');
        Route::get('/get_category', 'FrontendController@get_category');
        Route::get('/get_brand', 'FrontendController@get_brand');
        Route::get('/get_products', 'FrontendController@get_products');
        Route::get('/get_latest_products', 'FrontendController@get_latest_products');
        Route::get('/get_shop_products', 'FrontendController@get_shop_products');
        Route::get('/get_slider', 'FrontendController@get_slider');
        Route::get('/get_bump_adds', 'FrontendController@get_bump_adds');
        Route::get('/get_banner_adds', 'FrontendController@get_banner_adds');
        Route::get('/get_menu_category', 'FrontendController@get_menu_category');
        Route::get('/get_trending_products', 'FrontendController@get_trending_products');
        Route::get('/get_best_selling_product', 'FrontendController@get_best_selling_product');
        Route::get('/get_ralated_products', 'FrontendController@get_ralated_products');
        Route::get('/get_offerable_products', 'FrontendController@get_offerable_products');
        Route::get('/product_search', 'FrontendController@product_search');
        Route::get('/get_featured_attributes', 'FrontendController@get_featured_attributes');
        Route::get('/get_random_products/{limit}', 'FrontendController@get_random_products');
        Route::get('/get_shop_attributes', 'FrontendController@get_shop_attributes');
        Route::get('/'.permalink_type('checkout').'', 'FrontendController@checkout');
        Route::post('/make-review/{id}', 'ReviewController@store')->middleware('throttle:1,1');
        Route::get('/get_reviews/{id}', 'FrontendController@get_reviews');
        Route::post('/make-review-course/{id}', 'ReviewCourseController@store')->middleware('throttle:1,1');
        Route::get('/'.permalink_type('thanks').'', 'FrontendController@thanks');
        Route::get('/make_local', 'FrontendController@make_local');
        Route::get('/sitemap.xml', 'FrontendController@sitemap');
        Route::get('/'.permalink_type('contact_us').'', 'FrontendController@contact');
        Route::get('/'.permalink_type('page').'/{slug}', 'FrontendController@page');
        Route::get('/'.permalink_type('blog').'', 'FrontendController@blog_list');
        Route::get('/'.permalink_type('blog_detail').'/{slug}', 'FrontendController@blog_detail');
        Route::get('/'.permalink_type('service').'', 'FrontendController@service_list');
        Route::get('/'.permalink_type('service_detail').'/{slug}', 'FrontendController@service_detail');
        Route::get('/'.permalink_type('portfolio').'', 'FrontendController@portfolio_list');
        Route::get('/'.permalink_type('portfolio_detail').'/{slug}', 'FrontendController@portfolio_detail');
        Route::get('/'.permalink_type('course').'', 'FrontendController@course_list');
        Route::get('/'.permalink_type('course').'/{slug}', 'FrontendController@course_detail');
        Route::get('/'.permalink_type('career').'', 'FrontendController@career_list');
        Route::get('/'.permalink_type('career_detail').'/{slug}', 'FrontendController@career_detail');
        Route::get('/'.permalink_type('team').'', 'FrontendController@team_list');
        Route::get('/'.permalink_type('team_detail').'/{id}', 'FrontendController@team_detail');
        Route::get('/'.permalink_type('instructor').'', 'FrontendController@instructor_list');
        Route::get('/'.permalink_type('instructor').'/{id}', 'FrontendController@instructor_detail');
        Route::get('/'.permalink_type('package').'', 'FrontendController@package_list');
        Route::get('/'.permalink_type('faq').'', 'FrontendController@faqs');
        Route::get('/'.permalink_type('testimonial').'', 'FrontendController@testimonial');
        Route::get('/'.permalink_type('partner').'', 'FrontendController@partner');
        Route::post('/send-contact', 'FrontendController@sendMailContact');
        Route::get('/refresh-captcha', 'FrontendController@refreshCaptcha');
        Route::get('/template', 'FrontendController@gallery');
        Route::get('/'.permalink_type('gallery').'', 'FrontendController@gallery');
        Route::get('/'.permalink_type('booking').'', 'FrontendController@booking');
        Route::post('/booking', 'FrontendController@store_booking');
        Route::any('/maintenance', 'FrontendController@maintenance');
        Route::get('/'.permalink_type('knowledge').'', 'FrontendController@guide_list');
        Route::get('/'.permalink_type('knowledge').'/{slug}', 'FrontendController@guide_detail');


        Route::group(['prefix' => 'user'], function () {
            Route::get('/login', 'UserController@login')->middleware('guest');
            Route::get('/register', 'UserController@register')->middleware('guest');
            Route::post('/register-user', 'UserController@register_user')->middleware('guest');
            Route::post('/logout', 'UserController@logout')->middleware('guest');
            Route::get('/dashboard', 'UserController@dashboard')->middleware('customer');
            Route::get('/wallet', 'UserController@wallet')->middleware('customer');
            Route::get('/wallet/add', 'UserController@wallet_qr')->middleware('customer');
            Route::post('/wallet', 'UserController@wallet_add')->middleware('customer');
            Route::get('/deposit_metamask', 'UserController@deposit_method')->middleware('customer');
            Route::post('/deposit', 'UserController@deposit')->middleware('customer');
            Route::get('/all-notification', 'UserController@notification')->middleware('customer');
            Route::get('/orders', 'UserController@orders')->middleware('customer');
            Route::get('/order/view/{id}', 'UserController@order_view')->middleware('customer');
            Route::get('/bookings', 'UserController@bookings')->middleware('customer');
            Route::get('/booking/view/{id}', 'UserController@booking_view')->middleware('customer');
            Route::get('/download', 'UserController@download')->middleware('customer');
            Route::get('/settings', 'UserController@settings')->middleware('customer');
            Route::post('/settings/update', 'UserController@settings_update')->middleware('customer');
            Route::group(['prefix' => 'affiliate'], function () {
                Route::get('/affiliate_system', 'AffiliateController@affiliate_system')->middleware('customer');
                Route::get('/payment_history', 'AffiliateController@affiliate_payment_history')->middleware('customer');
                Route::get('/withdraw_request_history', 'AffiliateController@affiliate_withdraw_request_history')->middleware('customer');
                Route::post('/withdraw_request/store', 'AffiliateController@withdraw_request_store')->middleware('customer');
                Route::get('/payment/settings', 'AffiliateController@payment_settings')->middleware('customer');
                Route::post('/payment/settings/store', 'AffiliateController@payment_settings_store')->middleware('customer');
            });
        });


        //payment getway routes only
        Route::get('/payment/payment-success', 'OrderController@payment_success');
        Route::get('/payment/payment-fail', 'OrderController@payment_fail');
        Route::get('/payment/paypal', '\App\Helper\Order\Paypal@status');
        Route::get('/payment/instamojo', '\App\Helper\Order\Instamojo@status');
        Route::get('/payment/toyyibpay', '\App\Helper\Order\Toyyibpay@status');
        Route::get('/payment/mercado', '\App\Helper\Order\Mercado@status');
        Route::get('/payment/mollie', '\App\Helper\Order\Mollie@status');
        Route::get('/payment-with-stripe', '\App\Helper\Order\Stripe@view');
        Route::post('/payement/stripe', '\App\Helper\Order\Stripe@status');
        Route::get('/payment-with-razorpay', '\App\Helper\Order\Razorpay@view');
        Route::post('/payement/razorpay', '\App\Helper\Order\Razorpay@status');

        Route::get('/payment-with-paystack', '\App\Helper\Order\Paystack@view');
        Route::post('/payement/paystack', '\App\Helper\Order\Paystack@status');

        Route::get('/payment-with-vnpay', 'OrderController@api_payment_vnpay');
        Route::get('/payment-with-momo', 'OrderController@api_payment_momo');

        Route::get('/login-from-mydi4lvn', 'UserController@loginFromMyDi4l');
        Route::get('/login-from-admin', 'UserController@loginFromAdmin');


    });
    Route::group(['prefix' => 'lms','namespace' => 'LMS','middleware' => 'except_param_domain'], function () {
        require __DIR__ . '/lms/panel.php';
        require __DIR__ . '/lms/web.php';
        require __DIR__ . '/lms/admin.php';
    });
});

Auth::routes();

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::get('dashboard/static', 'AdminController@staticData')->name('dashboard.static');
    Route::get('dashboard/perfomance/{period}', 'AdminController@perfomance')->name('dashboard.perfomance');
    Route::get('dashboard/order_statics/{month}', 'AdminController@order_statics');
    Route::get('dashboard/visitors/{day}', 'AdminController@google_analytics');

    Route::resource('category', 'CategoryController');
    Route::post('categoryss/destroy', 'CategoryController@destroy')->name('categorie.destroys');



    Route::get('/location/countries', 'CategoryController@countries')->name('country.index');
    Route::get('/location/countries/create', 'CategoryController@countryCreate')->name('country.create');
    Route::get('/location/cities', 'CategoryController@cities')->name('city.index');
    Route::get('/location/cities/create', 'CategoryController@cityCreate')->name('city.create');

    //role management
    //roles
    Route::resource('role', 'RoleController');
    Route::post('roles/destroy', 'RoleController@destroy')->name('roles.destroy');
    //users
    Route::resource('users', 'AdminController');
    Route::post('/userss/destroy', 'AdminController@destroy')->name('users.destroys');

    Route::resource('plan', 'PlanController');
    Route::post('plans/destroy', 'PlanController@destroy')->name('plans.destroys');

    Route::resource('domain', 'DomainController');
    Route::post('domains/destroy', 'DomainController@destroy')->name('domains.destroys');

    Route::resource('order', 'OrderController');
    Route::post('orders/destroy', 'OrderController@destroy')->name('orders.destroys');
    Route::get('order/invoice/{id}', 'OrderController@invoice')->name('order.invoice');

    Route::resource('customer', 'CustomerController');
    Route::post('customer/feature_template', 'CustomerController@feature_template')->name('customer.feature_template');
    Route::post('customer/template_enable', 'CustomerController@template_enable')->name('customer.template_enable');
    Route::get('customer/plan/{id}', 'CustomerController@planview')->name('customer.planedit');
    Route::put('customer/planupdate/{id}', 'CustomerController@updateplaninfo')->name('customer.updateplaninfo');
    Route::post('customers/destroy', 'CustomerController@destroy')->name('customers.destroys');
    Route::get('customers/default/{id}', 'CustomerController@default')->name('customer.default');
    Route::get('customers/login/{id}', 'CustomerController@login_seller')->name('customer.login_seller');
    Route::get('customer/{id}/email_status', 'CustomerController@emailStatus')->name('customer.emailStatus');

    Route::resource('page', 'PageController');
    Route::post('pages/destroy', 'PageController@destroy')->name('pages.destroys');

    Route::resource('blog', 'BlogController');
    Route::post('blogs/destroy', 'BlogController@destroy')->name('blogs.destroys');
    Route::resource('bcategory', 'BlogCategoryController');
    Route::post('bcategories/destroy', 'BlogCategoryController@destroy')->name('bcategories.destroys');

    Route::get('report', 'ReportController@index')->name('report');
    Route::resource('language', 'LanguageController');
    Route::get('languages/delete/{id}', 'LanguageController@destroy')->name('languages.delete');
    Route::post('languages/setActiveLanuguage', 'LanguageController@setActiveLanuguage')->name('languages.active');
    Route::post('languages/add_key', 'LanguageController@add_key')->name('language.add_key');

    Route::resource('payment-geteway', 'PaymentController');
    Route::resource('settings', 'SettingController');
    Route::resource('email', 'EmailController');

    Route::resource('emailtemplate', 'EmailtemplateController');

    Route::resource('marketing', 'MarketingController');

    Route::resource('template', 'TemplateController');
    Route::get('templates/delete/{id}', 'TemplateController@destroy')->name('templates.delete');


    Route::get('site-settings', 'SiteController@site_settings')->name('site.settings');

    Route::resource('contact', 'ContactListController');

    Route::get('contact/remove/{id}', 'ContactListController@destroy')->name('contact.destroys');

    Route::get('system-environment', 'SiteController@system_environment_view')->name('site.environment');
    Route::post('site_settings_update', 'SiteController@site_settings_update')->name('site_settings.update');

    Route::post('env_update', 'SiteController@env_update')->name('env.update');


    Route::resource('gallery', 'GalleryController');

    Route::resource('appearance', 'FrontendController');

    Route::post('gallery/destroyes', 'GalleryController@destroy')->name('galleries.destroys');
    Route::resource('menu', 'MenuController');
    Route::post('menus/delete', 'MenuController@destroy')->name('menues.destroy');
    Route::post('menus/MenuNodeStore', 'MenuController@MenuNodeStore')->name('menus.MenuNodeStore');
    Route::resource('seo', 'SeoController');

    Route::resource('cron', 'CronController');

    Route::get('/profile', 'AdminController@settings')->name('profile.settings');
});

Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'Admin', 'middleware' => ['auth', 'author']], function () {
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
});

Route::post('user_profile_update', 'Seller\SettingController@profile_update')->name('my.profile.update');

Route::group(['as' => 'seller.', 'prefix' => 'seller', 'namespace' => 'Seller', 'middleware' => ['auth', 'seller']], function () {

    Route::get('di4l-analytics', 'Di4lController@analytics')->name('analytics');

    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('dashboard/static', 'DashboardController@staticData')->name('dashboard.static');
    Route::get('dashboard/perfomance/{period}', 'DashboardController@perfomance')->name('dashboard.perfomance');
    Route::get('dashboard/order_statics/{month}', 'DashboardController@order_statics');
    Route::get('dashboard/visitors/{day}', 'DashboardController@google_analytics');
    Route::get('set-lang', 'DashboardController@set_lang')->name('set_lang');

    Route::resource('category', 'CategoryController');
    Route::post('categoryss/destroy', 'CategoryController@destroy')->name('categorie.destroys');

    Route::resource('brand', 'BrandController');
    Route::post('brands/destroy', 'BrandController@destroy')->name('brands.destroys');

    Route::resource('attribute', 'AttributeController');
    Route::post('attributes/destroy', 'AttributeController@destroy')->name('attributes.destroy');

    Route::resource('attribute-term', 'ChildattributeController');
    Route::post('attributes-terms/destroy', 'ChildattributeController@destroy')->name('attributes-terms.destroy');
    Route::resource('ads', 'AdsController');
    Route::get('ad/brand', 'AdsController@brand')->name('ads.brand');
    Route::get('ad/remove/{id}', 'AdsController@destroy')->name('ad.destroy');

    Route::get('email-newsletter', 'AdsController@email_newsletter')->name('email.newsletter');

    Route::resource('product', 'ProductController');
    Route::get('product/{id}/{type}', 'ProductController@edit')->name('product.config');
    Route::get('products/{status}', 'ProductController@index')->name('product.list');
    Route::post('products/destroy', 'ProductController@destroy')->name('products.destroys');
    Route::post('products/seo/{id}', 'ProductController@seo')->name('products.seo');
    Route::post('products/import', 'ProductController@import')->name('products.import');
    Route::put('products/price/{id}', 'ProductController@price')->name('products.price');
    Route::put('products/price_single/{id}', 'ProductController@price_single')->name('products.price_single');
    Route::post('products/variation/{id}', 'ProductController@variation')->name('product.variation');
    Route::post('products/store_group/{id}', 'ProductController@store_group')->name('product.store_group');
    Route::put('products/stock/{id}', 'ProductController@stock')->name('products.stock');
    Route::put('products/stock_single/{id}', 'ProductController@stock_single')->name('products.stock_single');
    Route::post('products/add_row', 'ProductController@add_row')->name('product.add_row');
    Route::post('products/option_update/{id}', 'ProductController@option_update')->name('product.option_update');
    Route::post('products/option_delete', 'ProductController@option_delete')->name('product.option_delete');
    Route::post('products/row_update', 'ProductController@row_update')->name('product.row_update');

    Route::resource('blog', 'BlogController');
    Route::post('blogs/destroy', 'BlogController@destroy')->name('blogs.destroys');
    Route::resource('bcategory', 'BlogCategoryController');
    Route::post('bcategories/destroy', 'BlogCategoryController@destroy')->name('bcategories.destroys');

    Route::resource('varient', 'VarientController');
    Route::post('varients/destroy', 'VarientController@destroy')->name('variants.destroy');

    Route::resource('media', 'ProductmediaController');
    Route::post('medias/destroy', 'ProductmediaController@destroy')->name('medias.destroy');

    Route::resource('file', 'FileController');
    Route::post('files/update', 'FileController@update')->name('files.update');
    Route::post('files/destroy', 'FileController@destroy')->name('files.destroy');

    Route::resource('inventory', 'InventoryController');

    Route::resource('location', 'LocationController');
    Route::post('locations/destroy', 'LocationController@destroy')->name('locations.destroy');

    Route::resource('shipping', 'ShippingController');
    Route::post('shippings/destroy', 'ShippingController@destroy')->name('shippings.destroy');

    Route::resource('coupon', 'CouponController');
    Route::post('coupons/destroy', 'CouponController@destroy')->name('coupons.destroy');

    Route::resource('marketing', 'MarketingController');

    Route::resource('settings', 'SettingController');

    Route::resource('payment', 'GetwayController');

    Route::resource('transection', 'TransectionController');

    Route::get('report', 'ReportController@index')->name('report.index');

    //Banner Topbar

    Route::get('top-banner', 'MenuController@show_top_banner')->name('banner.show');
    Route::post('top-banner/store', 'MenuController@store_top_banner')->name('top_banner.store');
    //Permalink
    Route::get('permalinks', 'MenuController@permalinks')->name('permalink.show');
    Route::post('permalinks/update', 'MenuController@permalinksUpdate')->name('permalinks.update');

    Route::group(['prefix' => 'setting'], function () {
        Route::resource('seo', 'SeoController');
        Route::resource('theme', 'ThemeController');
        Route::resource('template', 'TemplateController');
        Route::resource('menu', 'MenuController');
        Route::post('menu/destroy', 'MenuController@destroy')->name('menu.destroys');
        Route::resource('page', 'PageController');
        Route::post('pages/destroy', 'PageController@destroy')->name('pages.destroys');
        Route::resource('slider', 'SliderController');
        //shop Location
        Route::resource('shop-location', 'ShopLocationController');
        Route::post('shop-location/destroy', 'ShopLocationController@destroy')->name('shop-location.destroys');
        Route::get('shop-location/{id}/default', 'ShopLocationController@is_default')->name('shop-location.is_default');
        //Redirect
        Route::resource('redirect', 'RedirectController');
        Route::get('redirect/remove/{id}', 'RedirectController@destroy')->name('redirect.destroys');
        //MaintainanceMode
        Route::resource('maintainance', 'MaintainanceModeController');
        //Logo & Favicon
        Route::get('logo-favicon', 'SettingController@logo_favicon')->name('logo_favicon.show');
        Route::post('logo-favicon', 'SettingController@update_logo_favicon')->name('logo_favicon.update');
        //Gen token sync shop my-di4sell
        Route::get('token-shop', 'SettingController@viewTokenPage')->name('pasteTokenMyDi4Sell.show');
        Route::post('token-shop', 'SettingController@saveTokenMyDi4sell')->name('save_token_mydi4sell.update');
        //booking
        Route::get('booking', 'SettingController@booking')->name('booking_setting.show');
        Route::post('booking', 'SettingController@booking_update')->name('booking_setting.update');
        //loyalty
        Route::get('loyalty', 'SettingController@loyalty')->name('loyalty_setting.show');
        Route::post('loyalty', 'SettingController@loyalty_update')->name('loyalty_setting.update');
    });

    Route::resource('customer', 'CustomerController');
    Route::get('customer/login/{id}', 'CustomerController@login')->name('customer.login');
    Route::post('customers/destroys', 'CustomerController@destroy')->name('customers.destroys');
    Route::get('user', 'CustomerController@user');
    Route::post('customers/{id}/add-money', 'CustomerController@addMoney')->name('customers.addMoney');

    Route::resource('order', 'OrderController');
    Route::resource('email', 'EmailController');
    Route::resource('review', 'ReviewController');
    Route::post('reviews/destroy', 'ReviewController@destroy')->name('reviews.destroys');
    Route::resource('review_course', 'ReviewCourseController');
    Route::post('review_course/destroy', 'ReviewCourseController@destroy')->name('review_course.destroys');
    Route::get('order/cart/remove/{id}', 'OrderController@cartRemove')->name('cart.remove');
    Route::get('orders/{type}', 'OrderController@index')->name('orders.status');
    Route::get('orders/customer/checkout', 'OrderController@checkout')->name('checkout');
    Route::get('orders/invoice/{id}', 'OrderController@invoice')->name('invoice');
    Route::post('orders/destroys', 'OrderController@destroy')->name('orders.destroys');
    Route::post('orders/apply_coupon', 'OrderController@apply_coupon')->name('orders.apply_coupon');
    Route::post('orders/make_order', 'OrderController@make_order')->name('orders.make_order');
    Route::post('orders/fulfillment', 'OrderController@destroy')->name('orders.method');
    Route::get('/make-payment/{id}', 'PlanController@make_payment')->name('make_payment');
    Route::get('plan-renew', 'PlanController@renew');

    Route::get('/settings', 'SettingController@settings_view')->name('seller.settings');
    Route::post('/profile_update', 'SettingController@profile_update')->name('profile.update');
    Route::post('/mail-config', 'SettingController@store_mailConfig')->name('mail_config.update');
    Route::post('/social-login', 'SettingController@storeSocialLogin')->name('social_login.update');
    Route::post('/contact-page', 'SettingController@storeContactPage')->name('contact_page.update');
    Route::post('/make-charge/{id}', 'PlanController@make_charge')->name('make_payment_charge');

    Route::get('/support', 'SettingController@support_view')->name('support');
    Route::resource('notifications', 'NotificationController');
    Route::get('notifications/remove/{id}', 'NotificationController@destroy')->name('notifications.destroys');
    Route::resource('contactlists', 'ContactListController');
    Route::get('contactlists/remove/{id}', 'ContactListController@destroy')->name('contactlists.destroys');
    Route::get('contactlists/position_contact/{position}', 'ContactListController@position_contact')->name('contactlists.position_contact');
    Route::post('contactlists/status_contact', 'ContactListController@status_contact')->name('contactlists.status_contact');
    Route::post('contactlists/icon', 'ContactListController@icon_update')->name('contactlists.icon_update');


    //payment methods
    Route::get('/payment-success', 'PlanController@success')->name('payment.success');
    Route::get('/payment-fail', 'PlanController@fail')->name('payment.fail');
    Route::get('/instamojo', '\App\Helper\Subscription\Instamojo@status')->name('instamojo.fallback');
    Route::get('/paypal', '\App\Helper\Subscription\Paypal@status')->name('paypal.fallback');
    Route::get('/toyyibpay', '\App\Helper\Subscription\Toyyibpay@status')->name('toyyibpay.fallback');
    Route::get('/payment-with/razorpay', '\App\Helper\Subscription\Razorpay@razorpay_view');
    Route::get('/payment_with_mollie', '\App\Helper\Subscription\Mollie@status');
    Route::post('/razorpay/status', '\App\Helper\Subscription\Razorpay@status')->name('razorpay.status');
    Route::post('/paystack/status', '\App\Helper\Subscription\Paystack@status');
    Route::get('/payment_with_mercado', '\App\Helper\Subscription\Mercado@status');

    //feature page
    Route::get('/feature-page', 'ProductFeatureController@index')->name('feature_page.index');
    Route::post('/feature-page/add', 'ProductFeatureController@store')->name('feature_page.store');
    Route::get('/feature-page/{id}/edit', 'ProductFeatureController@edit')->name('feature_page.edit');
    Route::post('/feature-page/{id}/edit', 'ProductFeatureController@update')->name('feature_page.update');
    Route::get('/feature-page/{id}/delete', 'ProductFeatureController@delete')->name('feature_page.delete');
    Route::post('feature-page', 'ProductFeatureController@destroy')->name('feature_page.destroys');

    Route::get('/feature-page/{id}/homepage', 'ProductFeatureController@setHomePage')->name('feature_page.homepage');

    //Section
    Route::get('/feature-page/{id}/detail', 'ProductFeatureController@detail')->name('feature_page.detail');
    Route::post('/feature-page/{id}/detail/create', 'ProductFeatureController@detail_store')->name('feature_page.detail.store');
    Route::get('/feature-page/detail/{id}/edit', 'ProductFeatureController@detail_edit')->name('feature_page.detail.edit');
    Route::post('/feature-page/detail/{id}/edit', 'ProductFeatureController@detail_update')->name('feature_page.detail.update');
    Route::get('/feature-page/detail/{feature_id}/delete', 'ProductFeatureController@detail_delete')->name('feature_page.detail.delete');
    Route::post('feature-page/detail', 'ProductFeatureController@detail_destroy')->name('feature_page.detail.destroys');
    Route::get('/feature-page/detail/{id}/hide_title/{hide_title}', 'ProductFeatureController@hide_title')->name('feature_page.detail.hide_title');

    // Element
    Route::post('/feature-page/section-element/store', 'ProductFeatureSectionElementController@store')->name('feature_page.section_element.store');
    Route::get('/feature-page/section-element/{id}/edit', 'ProductFeatureSectionElementController@edit')->name('feature_page.section_element.edit');
    Route::post('/feature-page/section-element/update', 'ProductFeatureSectionElementController@update')->name('feature_page.section_element.update');
    Route::get('/feature-page/section-element/{id}/delete', 'ProductFeatureSectionElementController@delete')->name('feature_page.section_element.delete');
    Route::post('feature-page/section-element', 'ProductFeatureSectionElementController@destroy')->name('feature_page.section_element.destroys');

    // Import Landing Page
    Route::post('/feature-page/{id}', 'ProductFeatureController@importLandingPage')->name('feature_page.import_page');

    Route::resource('service', 'ServiceController');
    Route::post('service/destroy', 'ServiceController@destroy')->name('service.destroys');

    Route::resource('portfolio_category', 'PortfolioCategoryController');
    Route::post('portfolio_category/destroy', 'PortfolioCategoryController@destroy')->name('portfolio_category.destroys');
    Route::resource('portfolio', 'PortfolioController');
    Route::post('portfolio/destroy', 'PortfolioController@destroy')->name('portfolio.destroys');
    Route::resource('course', 'CourseController');
    Route::post('course/destroy', 'CourseController@destroy')->name('course.destroys');
    Route::resource('course_category', 'CourseCategoryController');
    Route::post('course_category/destroy', 'CourseCategoryController@destroy')->name('course_category.destroys');

    Route::get('/course/{id?}/modules', 'ModuleController@index')->name('course.module.index');
    Route::post('/modules/store', 'ModuleController@store')->name('course.module.store');
    Route::get('/modules/{id?}/edit', 'ModuleController@edit')->name('course.module.edit');
    Route::put('/modules/update/{id?}', 'ModuleController@update')->name('course.module.update');
    Route::post('/modules/destroy', 'ModuleController@destroy')->name('course.module.destroys');

    Route::get('/module/{id?}/lessons', 'LessonController@index')->name('module.lesson.index');
    Route::post('/lessons/store', 'LessonController@store')->name('module.lesson.store');
    Route::get('/lessons/{id?}/edit', 'LessonController@edit')->name('module.lesson.edit');
    Route::put('/lessons/update/{id?}', 'LessonController@update')->name('module.lesson.update');
    Route::post('/lessons/destroy', 'LessonController@destroy')->name('module.lesson.destroys');




    Route::resource('faq', 'FaqController');
    Route::post('faq/destroy', 'FaqController@destroy')->name('faq.destroys');

    Route::resource('career_category', 'CareerCategoryController');
    Route::post('career_category/destroy', 'CareerCategoryController@destroy')->name('career_category.destroys');
    Route::resource('career', 'CareerController');
    Route::post('career/destroy', 'CareerController@destroy')->name('career.destroys');

    Route::resource('team', 'TeamController');
    Route::post('team/destroy', 'TeamController@destroy')->name('team.destroys');

    Route::resource('instructor', 'InstructorController');
    Route::post('instructor/destroy', 'InstructorController@destroy')->name('instructor.destroys');

    Route::resource('testimonial', 'TestimonialController');
    Route::post('testimonial/destroy', 'TestimonialController@destroy')->name('testimonial.destroys');

    Route::resource('package_category', 'PackageCategoryController');
    Route::post('package_category/destroy', 'PackageCategoryController@destroy')->name('package_category.destroys');
    Route::resource('package', 'PackageController');
    Route::post('package/destroy', 'PackageController@destroy')->name('package.destroys');

    Route::resource('partner', 'PartnerController');
    Route::post('partner/destroy', 'PartnerController@destroy')->name('partner.destroys');

    Route::resource('gallery_category', 'GalleryCategoryController');
    Route::post('gallery_category/destroy', 'GalleryCategoryController@destroy')->name('gallery_category.destroys');
    Route::resource('gallery', 'GalleryController');
    Route::post('gallery/destroy', 'GalleryController@destroy')->name('gallery.destroys');

    Route::resource('booking', 'BookingController');
    Route::get('bookings/{type}', 'BookingController@index')->name('bookings.status');
    Route::post('booking/destroy', 'BookingController@destroy')->name('booking.destroys');

    Route::resource('booking-category', 'BookingCategoryController');
    Route::post('booking-category/destroy', 'BookingCategoryController@destroy')->name('booking-category.destroys');

    Route::resource('booking-service', 'BookingServiceController');
    Route::post('booking-service/destroy', 'BookingServiceController@destroy')->name('booking-service.destroys');

    Route::get('cache-clear', 'SettingController@cache_clear')->name('cache_clear');

    Route::resource('loyalty-rank', 'LoyaltyRankController');
    Route::post('loyalty-rank/destroy', 'LoyaltyRankController@destroy')->name('loyalty-rank.destroys');

    Route::resource('loyalty', 'LoyaltyController');
    Route::post('loyalty/destroy', 'LoyaltyController@destroy')->name('loyalty.destroys');

    Route::resource('guide_category', 'GuideCategoryController');
    Route::post('guide_category/destroy', 'GuideCategoryController@destroy')->name('guide_category.destroys');
    Route::resource('guide', 'GuideController');
    Route::post('guide/destroy', 'GuideController@destroy')->name('guide.destroys');

    Route::resource('discount', 'DiscountController');
    Route::post('discount/destroy', 'DiscountController@destroy')->name('discount.destroys');

    Route::resource('loyalty-promotion', 'LoyaltyPromotionController');
    Route::post('loyalty-promotion/destroy', 'LoyaltyPromotionController@destroy')->name('loyalty-promotion.destroys');

    Route::resource('loyalty-promotion-category', 'LoyaltyPromotionCategoryController');
    Route::post('loyalty-promotion-category/destroy', 'LoyaltyPromotionCategoryController@destroy')->name('loyalty-promotion-category.destroys');

    Route::resource('loyalty-benefit', 'LoyaltyBenefitController');
    Route::post('loyalty-benefit/destroy', 'LoyaltyBenefitController@destroy')->name('loyalty-benefit.destroys');

    Route::group(['prefix' => 'affiliate'], function () {
        Route::get('', 'AffiliateController@index')->name('affiliate.index');
        Route::post('/affiliate_option_store', 'AffiliateController@affiliate_option_store')->name('affiliate.store');

        Route::get('/configs', 'AffiliateController@configs')->name('affiliate.configs');
        Route::post('/configs/store', 'AffiliateController@config_store')->name('affiliate.configs.store');

        Route::get('/users', 'AffiliateController@users')->name('affiliate.users');
        Route::get('/verification/{id}', 'AffiliateController@show_verification_request')->name('affiliate_users.show_verification_request');

        Route::post('/approved', 'AffiliateController@updateApproved')->name('affiliate_user.approved');

        Route::post('/payment_modal', 'AffiliateController@payment_modal')->name('affiliate_user.payment_modal');
        Route::post('/pay/store', 'AffiliateController@payment_store')->name('affiliate_user.payment_store');

        Route::get('/payments/show/{id}', 'AffiliateController@payment_history')->name('affiliate_user.payment_history');
        Route::get('/refferal/users', 'AffiliateController@refferal_users')->name('refferals.users');

        // Affiliate Withdraw Request
        Route::get('/withdraw_requests', 'AffiliateController@affiliate_withdraw_requests')->name('affiliate.withdraw_requests');
        Route::post('/affiliate_withdraw_modal', 'AffiliateController@affiliate_withdraw_modal')->name('affiliate_withdraw_modal');
        Route::post('/withdraw_request/payment_store', 'AffiliateController@withdraw_request_payment_store')->name('withdraw_request.payment_store');
        Route::post('/withdraw_request/reject/{id}', 'AffiliateController@reject_withdraw_request')->name('affiliate.withdraw_request.reject');

        Route::get('/logs', 'AffiliateController@affiliate_logs_admin')->name('affiliate.logs.admin');
    });
});

// Route::post('seller-register', 'FrontendController@register')->name('merchant.register-make');

// Route::group(['as' => 'merchant.', 'prefix' => 'merchant', 'middleware' => ['auth']], function () {
//     Route::get('/dashboard', 'FrontendController@dashboard')->name('dashboard');
//     Route::get('/make-payment/{id}', 'FrontendController@make_payment')->name('make_payment');
//     Route::get('/plan', 'FrontendController@plans')->name('plan');
//     Route::get('/profile', 'FrontendController@settings')->name('profile.settings');

//     Route::post('/make-charge/{id}', 'FrontendController@make_charge')->name('make_payment_charge');
//     Route::get('/payment-success', 'FrontendController@success')->name('payment.success');
//     Route::get('/payment-fail', 'FrontendController@fail')->name('payment.fail');
//     Route::get('/instamojo', '\App\Helper\Subscription\Instamojo@status')->name('instamojo.fallback');
//     Route::get('/paypal', '\App\Helper\Subscription\Paypal@status')->name('paypal.fallback');
//     Route::get('/toyyibpay', '\App\Helper\Subscription\Toyyibpay@status')->name('toyyibpay.fallback');
//     Route::get('/payment-with/razorpay', '\App\Helper\Subscription\Razorpay@razorpay_view');
//     Route::get('/payment/mollie', '\App\Helper\Subscription\Mollie@status');
//     Route::post('/razorpay/status', '\App\Helper\Subscription\Razorpay@status');
//     Route::post('/paystack/status', '\App\Helper\Subscription\Paystack@status');
//     Route::get('/payment/mercado', '\App\Helper\Subscription\Mercado@status');
// });


Route::post('/customers/attempt', 'Frontend\UserController@userLogin')->name('customer.login');
Route::post('/customer/login', 'Customer\LoginController@login')->middleware('guest');
//Social Login
Route::get('/login/redirect-google', 'Customer\SocialAuthController@redirectToProvider')->middleware('guest');
Route::get('/customer/google/callback', 'Customer\SocialAuthController@handleProviderCallback')->middleware('guest');
Route::get('/user/password/reset', 'Customer\ForgotPasswordController@showLinkRequestForm')->middleware('guest');
Route::post('/user/password/email', 'Customer\ForgotPasswordController@sendResetOtp')->middleware('throttle:5,5');
Route::get('/verify-email/{id}', 'Frontend\UserController@verifyEmail')->middleware('guest')->name('user.verify_email');
Route::get('email/{id}', 'Seller\EmailController@verify_email')->name('email.verify_email');
Route::post('email/verify', 'Seller\EmailController@verify')->name('email.verify');
//Reset Password Routes
Route::get('/user/password/otp', 'Customer\ResetPasswordController@otp')->middleware('guest');
Route::post('/user/password/reset', 'Customer\ResetPasswordController@resetPassword')->middleware('throttle:5,5');
//Redirect
Route::any('{page_name}', 'Frontend\UserController@checkRedirect')->middleware('domain');

