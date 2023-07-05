<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Useroption;
use App\Domain;
use App\ExchangeRate;
use App\Getway;

class ConfigController extends Controller
{
    public function configuration()
    {
        $langlists=\App\Option::where('key','languages')->first();
        $languages= Useroption::where('user_id',domain_info('user_id'))->where('key','languages')->first();
        $active_languages= json_decode($languages->value ?? '');
        $my_languages=[];
        foreach ($active_languages ?? [] as $key => $value) {
            array_push($my_languages, [
                'code' => $value,
                'name' => $key
            ]);
        }
        $social_login = Useroption::where('user_id',domain_info('user_id'))->where('key','social_login')->first();
        $active_social_login= json_decode($social_login->value ?? '');
        $my_social_logins=[];
        array_push($my_social_logins, [
            'login_medium' => $active_social_login->medium ?? null,
            'status' => ($active_social_login->status ?? 0)==1 ? true : false,
        ]);

        $shop_name = Useroption::where('key','shop_name')->where('user_id',domain_info('user_id'))->first();
        $store_description = Useroption::where('key','shop_description')->where('user_id',domain_info('user_id'))->first();
        $store_email = Useroption::where('key','store_email')->where('user_id',domain_info('user_id'))->first();
        $order_prefix = Useroption::where('key','order_prefix')->where('user_id',domain_info('user_id'))->first();
        $currency = Useroption::where('key','currency')->where('user_id',domain_info('user_id'))->first();
        $tax = Useroption::where('user_id',domain_info('user_id'))->where('key','tax')->first();
        $shop_type = Domain::where('user_id',domain_info('user_id'))->first();
        $shop_type = $shop_type->shop_type ?? null;
        $order_receive_method= Useroption::where('user_id',domain_info('user_id'))->where('key','order_receive_method')->first();
        $location=Useroption::where('key','location')->where('user_id',domain_info('user_id'))->first();
        $local= Useroption::where('user_id',domain_info('user_id'))->where('key','local')->first();
        $socials= Useroption::where('user_id',domain_info('user_id'))->where('key','socials')->first();
        $social = json_decode($socials->value ?? '');
        $currencys = json_decode($currency->value ?? '');
        $locations = json_decode($location->value ?? '');
        $theme_color = Useroption::where('key','theme_color')->where('user_id',domain_info('user_id'))->first();
        $theme_colors = ($theme_color->value ?? '');
        $currency = Useroption::where('key','currency')->where('user_id',domain_info('user_id'))->first();
        $exchange_rate = ExchangeRate::where('code',(json_decode($currency->value)->currency_name ?? ''))->first();
        $getway = Getway::where('user_id',domain_info('user_id'))->where('category_id', 2908)->first();
        $binance = json_decode($getway->content ?? '');
        $booking_status = Useroption::where('key','booking_status')->where('user_id',domain_info('user_id'))->first();
        $loyalty_name = Useroption::where('key', 'loyalty_name')->where('user_id', domain_info('user_id'))->first();
        $loyalty_status = Useroption::where('key', 'loyalty_status')->where('user_id', domain_info('user_id'))->first();
        $wallet_status = Useroption::where('user_id', domain_info('user_id'))->where('key', 'wallet_status')->first();

        return response()->json([

            'system_default_currency' => 1,
            'digital_payment' => true,
            'cash_on_delivery' => true,
            'base_urls' => [
                // 'product_image_url' => asset('/uploads/' .domain_info('user_id').'/'.domain_info('user_id').'/5'),
                'product_image_url' => asset('/uploads'),
                'product_thumbnail_url' => asset('/uploads'),
                'brand_image_url' => asset('/uploads'),
                'customer_image_url' => asset('/uploads'),
                'banner_image_url' => asset('/uploads'),
                'category_image_url' => asset('/uploads'),
                'review_image_url' => asset('/uploads'),
                'shop_image_url' => asset('/uploads'),
            ],

            'static_url' => [
                'contact_us' => url('/contact-us'),
                'brands' => url('/brands'),
                'categories' => url('/categories'),
                'customer_account' => url('user/settings'),
                'term' => url('page/term-condition') ?? null,
                'privacy' => url('page/privacy-policy') ?? null,
                'about_us' => url('page/about-us') ?? null,
                'faq' => url('page/faq') ?? null,
            ],
            'about_us' => ($store_description->value ?? ''),
            'privacy_policy' => ($shop_name->value ?? ''),
            'terms_&_conditions'=> "<p>terms and conditions</p>",
            'currency_list' => [
                [
                    'id'=> 1,
                    'name'=> "USD",
                    'symbol'=> "$",
                    'code'=> "USD",
                    'exchange_rate'=> 1,
                    'status'=> 1,
                    'created_at'=> null,
                    'updated_at'=> "2021-06-27T15:39:37.000000Z"
                ],
            ],
            'currency_symbol_position' => ($currencys->currency_position ?? ''),
            'business_mode'=> "single",
            'maintenance_mode'=> false,
            'language' => $my_languages,
            'color' => [$theme_color],
            'unit' => [
                'kg',
                'pc',
                'gms',
                'ltrs'
            ],
            'shipping_method' => 'inhouse_shipping',
            'email_verification' => false,
            'phone_verification' => false,
            'country_code' => "BD",
            'social_login' => $my_social_logins,
            'currency_model' => "single_currency",
            'forgot_password_verification' => "email",
            'announcement' => [
                'status'=> "0",
                'color'=> "#000000",
                'text_color' => "#000000",
                'announcement'=> null
            ],
            'pixel_analytics'=> null,
            'software_version'=> "10.0",
            'decimal_point_settings'=> 2,
            'inhouse_selected_shipping_type'=> "order_wise",
            'billing_input_by_customer'=> 1,
            'wallet_status'=> (($wallet_status->value ?? '0') == '1' ? 1 :0 ),
            'loyalty_point_status'=> 0,
            'loyalty_point_exchange_rate'=> 0,
            'loyalty_point_minimum_point'=> 0,
            'contract_address'=> ($binance->contract_address ?? ''),
            'receiver_address'=> ($binance->receiver_address ?? ''),
            'coin_name'=> $exchange_rate->code ?? null,
            'rate_coin'=> $exchange_rate->rate ?? null,
            'remove_account'=> true,
            'booking_status'=> ($booking_status->value ?? '0'),
            'loyalty_status' => ($loyalty_status->value ?? '0'),
            'loyalty_name' =>($loyalty_name->value ?? '')
        ]);
    }
}
