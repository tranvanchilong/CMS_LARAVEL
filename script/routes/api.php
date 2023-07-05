<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'domain' => '{domain}', 'middleware' => ['domain', 'customdomain']], function () {
    Route::post('/update-token-sync','CustomerController@updateToken');

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@get_categories');
        Route::get('products/{category_id}', 'CategoryController@get_products');
    });

    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', 'BrandController@get_brands');
        Route::get('products/{brand_id}', 'BrandController@get_products');
    });

    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('register/check', 'AuthController@checkRegister');
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        Route::post('forgot-password', 'ForgotPassword@sendResetOtp')->middleware('throttle:5,5');
        Route::post('verify-otp', 'ForgotPassword@resetPassword');
        Route::any('social-login', 'SocialAuthController@social_login');

        Route::any('login-from-mydi4lvn', 'AuthController@loginFromMyDi4l');
    });

    Route::group(['prefix' => 'auth-v2', 'namespace' => 'Auth\V2'], function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
    });
    Route::group(['prefix' => 'products'], function () {
        Route::get('get_all_product_active' ,'ProductController@get_all_product_active');
        Route::get('get_details_product' ,'ProductController@get_details_product');
        Route::put('update_stock_price', 'ProductController@updateProduct');
        Route::get('get_products_all', 'ProductController@get_products_all');
        Route::get('latest', 'ProductController@get_latest_products');
        Route::get('new-latest', 'ProductController@get_new_latest_products');
        Route::get('featured', 'ProductController@get_featured_products');
        Route::get('top-rated', 'ProductController@get_top_rated_products');
        Route::any('search', 'ProductController@get_searched_products');
        Route::get('details/{slug}/{id}', 'ProductController@get_product');
        Route::get('related-products/{product_id}', 'ProductController@get_related_products');
        Route::get('reviews/{product_id}', 'ProductController@get_product_reviews');
        Route::get('rating/{product_id}', 'ProductController@get_product_rating');
        Route::get('counter/{product_id}', 'ProductController@counter');
        Route::get('shipping-methods', 'ProductController@get_shipping_method');
        Route::get('social-share-link/{product_id}', 'ProductController@social_share_link');
        Route::post('reviews/submit', 'ProductController@submit_product_review')->middleware('auth:sanctum');
        Route::get('best-sellings', 'ProductController@get_best_sellings');
        Route::get('home-categories', 'ProductController@get_home_categories');
        Route::get('discounted-product', 'ProductController@get_discounted_products');
        Route::get('attributes', 'ProductController@get_attributes');
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@get_notifications');
    });


    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'customer', 'middleware' => 'auth:sanctum'], function () {
        Route::get('info', 'CustomerController@info');
        Route::put('update-profile', 'CustomerController@update_profile');
        Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');

        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('/', 'CustomerController@wish_list');
            Route::post('add', 'CustomerController@add_to_wishlist');
            Route::delete('remove', 'CustomerController@remove_from_wishlist');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('list', 'CustomerController@get_order_list');
            Route::get('details', 'CustomerController@get_order_details');
            Route::post('place', 'OrderController@place_order');
        });
        //wallet
        Route::group(['prefix' => 'wallet'], function () {
            Route::get('list', 'UserWalletController@list');
            Route::post('add-money', 'UserWalletController@addMoney');
        });
        // Loyalty
        Route::group(['prefix' => 'loyalty'], function () {
            Route::get('history-transaction-points', 'LoyaltyController@historyTransactionPoints');
            Route::get('user-loyalty-rank', 'LoyaltyController@getLoyaltyRankForUser');
            Route::get('get-promotion-category', 'LoyaltyController@getPromotionCategories');
            Route::get('get-promotion-by-category', 'LoyaltyController@getPromotionByCategory');
            Route::get('get-promotion-by-source', 'LoyaltyController@getPromotionBySource');
            Route::get('get-promotion-by-feature', 'LoyaltyController@getPromotionByFeature');
            Route::get('get-promotions', 'LoyaltyController@getPromotions');
            Route::get('redeem-loyalty', 'LoyaltyController@redeemLoyaltyPoints');
            Route::get('get-promotion-customers', 'LoyaltyController@getPromotionForCustomers');
        });
        // Affiliate
        Route::group(['prefix' => 'affiliate', 'middleware' => 'auth:sanctum'], function () {
            Route::get('earning-history', 'AffiliateController@get_earning_history');
            Route::get('withdraw-request-history', 'AffiliateController@get_withdraw_request_history');
            Route::post('withdraw-request-history', 'AffiliateController@withdraw_request_store');
            Route::get('refferal-users', 'AffiliateController@affiliate_refferal_users');
        });
    });

    Route::post('order/place', 'OrderController@store_order');

    Route::group(['prefix' => 'cart', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/', 'CartController@cart');
        Route::post('add', 'CartController@add_to_cart');
        Route::put('update', 'CartController@update_cart');
        Route::delete('remove', 'CartController@remove_from_cart');
        Route::delete('remove-all', 'CartController@remove_all_from_cart');
    });

    Route::group(['prefix' => 'coupon', 'middleware' => 'auth:sanctum'], function () {
        Route::get('apply', 'CouponController@apply');
        Route::get('get-coupons', 'CouponController@getAllCoupon');
        Route::get('get-coupon-exist', 'CouponController@getCouponExist');
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('track', 'OrderController@track_order');
        Route::get('cancel-order', 'OrderController@order_cancel');
    });

    Route::group(['prefix' => 'shipping-method'], function () {
        Route::get('detail', 'ShippingMethodController@get_shipping_method_info');
        Route::get('by-seller/{id}/{seller_is}', 'ShippingMethodController@shipping_methods_by_seller');
        Route::get('check-shipping-type', 'ShippingMethodController@check_shipping_type');
    });

    Route::group(['as' => 'seller.', 'prefix' => 'payment-method'], function () {
        Route::get('detail', 'ShippingMethodController@get_payment_method_info');
    });

    Route::group(['as' => 'seller.', 'prefix' => 'payment-deposit'], function () {
        Route::get('detail', 'DepositMethodController@get_deposit_method_info');
    });

    Route::group(['prefix' => 'flash-deals'], function () {
        Route::get('/', 'FlashDealController@get_flash_deal');
        Route::get('products/{id}', 'FlashDealController@get_products');
        Route::get('featured', 'FlashDealController@flash_deal_feature');
        Route::get('deal-of-the-day', 'FlashDealController@get_deal_of_the_day_product');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });

    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'BlogController@get_blogs');
        Route::get('/{slug}', 'BlogController@get_blog_detail');
    });

    Route::put('remove-account/{id}', 'CustomerController@remove_account');
    Route::group(['prefix' => 'location'], function () {
        Route::get('/', 'LocationController@getLocations');
    });
    Route::group(['prefix' => 'location', 'middleware' => 'auth:sanctum'], function () {
        Route::get('favorites', 'LocationController@getCustomersFavoriteLocation');
        Route::post('add', 'LocationController@addFavoriteLocation');
        Route::post('remove', 'LocationController@removeFavoriteLocation');
    });

    Route::group(['prefix' => 'booking', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/', 'BookingController@getAllBookedForUser');
        Route::get('get-booked-user-in-7d', 'BookingController@getBookedOfUserIn7Days');
        Route::post('add', 'BookingController@store');
        Route::put('cancel', 'BookingController@cancelBooking');
    });

    Route::group(['prefix' => 'booking'], function () {
        Route::get('get-booked-in-7d', 'BookingController@getBookedIn7Days');
        Route::get('category-service', 'BookingController@getCategoryServices');
        Route::get('service', 'BookingController@getServices');
        Route::get('booking-setting', 'BookingController@getBookingSetting');
    });

    Route::group(['prefix' => 'discount', 'middleware' => 'auth:sanctum'], function () {
        Route::get('apply', 'DiscountController@apply');
        Route::get('get-discount', 'DiscountController@getAllDiscount');
        Route::get('get-discount-exist', 'DiscountController@getDiscountExist');
    });

    Route::group(['prefix' => 'loyalty'], function () {
        Route::get('rank', 'LoyaltyController@getLoyaltyRank');
    });
});

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::get('plans/get-plan', 'ApiendpointController@getPlan');
    Route::post('create-order', 'ApiendpointController@create_order');
});