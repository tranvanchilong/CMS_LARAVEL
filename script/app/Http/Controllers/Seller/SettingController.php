<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plan;
use Auth;
use App\Usermeta;
use App\Useroption;
use App\Category;
use App\Domain;
use App\Models\User;
use App\Models\ContactLists;
use Hash;
use Cache;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{


    public function settings_view()
    {
        return view('seller.settings');
    }

    public function profile_update(Request $request)
    {

        $user = User::find(Auth::id());
        if ($request->password) {
            $validatedData = $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);


            $check = Hash::check($request->password_current, auth()->user()->password);

            if ($check == true) {
                $user->password = Hash::make($request->password);
            } else {

                $returnData['errors']['password'] = array(0 => "Enter Valid Password");
                $returnData['message'] = "given data was invalid.";

                return response()->json($returnData, 401);
            }
        } else {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email'  =>  'required|email|unique:users,email,' . Auth::id()

            ]);
            $user->name = $request->name;
            $user->email = $request->email;
        }
        $user->save();

        return response()->json(['Profile Updated Successfully']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'general') {
            $user_id = Auth::id();

            $validatedData = $request->validate([
                'shop_name' => 'required',
                'shop_description' => 'required|max:250',
                'store_email' => 'required|max:50|email',
                'order_prefix' => 'required',
                'currency_position' => 'required',
                'currency_name' => 'required|max:10',
                'currency_icon' => 'required|max:10',
                'lanugage' => 'required',
                'local' => 'required',
            ]);

            $shop_name = Useroption::where('user_id', $user_id)->where('key', 'shop_name')->first();
            if (empty($shop_name)) {
                $shop_name = new Useroption;
                $shop_name->key = 'shop_name';
            }
            $shop_name->value = $request->shop_name;
            $shop_name->user_id = $user_id;
            $shop_name->save();

            $shop_description = Useroption::where('user_id', $user_id)->where('key', 'shop_description')->first();
            if (empty($shop_description)) {
                $shop_description = new Useroption;
                $shop_description->key = 'shop_description';
            }
            $shop_description->value = $request->shop_description;
            $shop_description->user_id = $user_id;
            $shop_description->save();


            $store_email = Useroption::where('user_id', $user_id)->where('key', 'store_email')->first();
            if (empty($store_email)) {
                $store_email = new Useroption;
                $store_email->key = 'store_email';
            }
            $store_email->value = $request->store_email;
            $store_email->user_id = $user_id;
            $store_email->save();

            $order_prefix = Useroption::where('user_id', $user_id)->where('key', 'order_prefix')->first();
            if (empty($order_prefix)) {
                $order_prefix = new Useroption;
                $order_prefix->key = 'order_prefix';
            }
            $order_prefix->value = $request->order_prefix;
            $order_prefix->user_id = $user_id;
            $order_prefix->save();

            $local = Useroption::where('user_id', $user_id)->where('key', 'local')->first();
            if (empty($local)) {
                $local = new Useroption;
                $local->key = 'local';
            }
            $local->value = $request->local;
            $local->user_id = $user_id;
            $local->save();

            $order_receive_method = Useroption::where('user_id', $user_id)->where('key', 'order_receive_method')->first();
            if (empty($order_receive_method)) {
                $order_receive_method = new Useroption;
                $order_receive_method->key = 'order_receive_method';
            }
            $order_receive_method->value = $request->order_receive_method;
            $order_receive_method->user_id = $user_id;
            $order_receive_method->save();



            $currency = Useroption::where('user_id', $user_id)->where('key', 'currency')->first();
            if (empty($currency)) {
                $currency = new Useroption;
                $currency->key = 'currency';
            }
            $currencyInfo['currency_position'] = $request->currency_position;
            $currencyInfo['currency_name'] = $request->currency_name;
            $currencyInfo['currency_icon'] = $request->currency_icon;

            $currency->value = json_encode($currencyInfo);
            $currency->user_id = $user_id;
            $currency->save();
            Cache::forget(Auth::id() . 'currency_info');

            $langs = [];
            foreach ($request->lanugage as $key => $value) {
                $str = explode(',', $value);
                $langs[$str[0]] = $str[1];
            }

            $languages = Useroption::where('user_id', $user_id)->where('key', 'languages')->first();
            if (empty($languages)) {
                $languages = new Useroption;
                $languages->key = 'languages';
                $languages->user_id = $user_id;
            }
            $languages->value = json_encode($langs);
            $languages->save();

            $key_firebase = Useroption::where('user_id', $user_id)->where('key', 'push_firebase')->first();
            if (empty($key_firebase)) {
                $key_firebase = new Useroption;
                $key_firebase->key = 'push_firebase';
            }
            $key_firebase->value = $request->push_firebase;
            $key_firebase->user_id = $user_id;
            $key_firebase->save();

            $tax = Useroption::where('user_id', $user_id)->where('key', 'tax')->first();
            if (empty($tax)) {
                $tax = new Useroption;
                $tax->key = 'tax';
                $tax->user_id = $user_id;
            }
            $tax->value = $request->tax;
            $tax->save();
            Cache::forget('tax' . Auth::id());

            $domain = Domain::where('user_id', $user_id)->first();
            if ($domain) {
                $domain->shop_type = $request->shop_type;
                $domain->save();
            }

            $receiver_address = Useroption::where('user_id', $user_id)->where('key', 'receiver_address')->first();
            if (empty($receiver_address)) {
                $receiver_address = new Useroption;
                $receiver_address->key = 'receiver_address';
            }
            $receiver_address->value = $request->receiver_address;
            $receiver_address->user_id = $user_id;
            $receiver_address->save();

            Cache::forget(auth()->id().'autoload_loaded');
            Cache::forget(domain_info('domain_name'));

            return response()->json(['Settings Updated']);
        }

        if ($request->type == 'location') {
            $user_id = Auth::id();
            $validatedData = $request->validate([
                'company_name' => 'required',
                'address' => 'required|max:250',
                'city' => 'required|max:20',
                'state' => 'required|max:20',
                'zip_code' => 'required|max:20',
                'email' => 'required|max:30',
                'phone' => 'required|max:15',
            ]);

            $location = Useroption::where('user_id', $user_id)->where('key', 'location')->first();
            if (empty($location)) {
                $location = new Useroption;
                $location->key = 'location';
            }
            $data['company_name'] = $request->company_name;
            $data['address'] = $request->address;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['zip_code'] = $request->zip_code;
            $data['email'] = $request->email;
            $data['phone'] = $request->phone;
            $data['invoice_description'] = $request->invoice_description;

            $location->value = json_encode($data);
            $location->user_id = $user_id;
            $location->save();

            return response()->json(['Location Updated']);
        }

        if ($request->type == 'pwa_settings') {
            $user_id = Auth::id();
            $validatedData = $request->validate([
                'pwa_app_title' => 'required|max:20',
                'pwa_app_name' => 'required|max:15',
                'app_lang' => 'required|max:15',
                'pwa_app_background_color' => 'required|max:15',
                'pwa_app_theme_color' => 'required|max:15',
                'app_icon_128x128' => 'max:300|mimes:png',
                'app_icon_144x144' => 'max:300|mimes:png',
                'app_icon_152x152' => 'max:300|mimes:png',
                'app_icon_192x192' => 'max:300|mimes:png',
                'app_icon_512x512' => 'max:500|mimes:png',
                'app_icon_256x256' => 'max:400|mimes:png',
            ]);

            if ($request->app_icon_128x128) {
                $request->app_icon_128x128->move('uploads/' . $user_id, '128x128.png');
            }
            if ($request->app_icon_144x144) {
                $request->app_icon_144x144->move('uploads/' . $user_id, '144x144.png');
            }
            if ($request->app_icon_152x152) {
                $request->app_icon_152x152->move('uploads/' . $user_id, '152x152.png');
            }
            if ($request->app_icon_192x192) {
                $request->app_icon_192x192->move('uploads/' . $user_id, '192x192.png');
            }
            if ($request->app_icon_512x512) {
                $request->app_icon_512x512->move('uploads/' . $user_id, '512x512.png');
            }
            if ($request->app_icon_256x256) {
                $request->app_icon_256x256->move('uploads/' . $user_id, '256x256.png');
            }

            $mainfest = '{
  "name": "' . $request->pwa_app_title . '",
  "short_name": "' . $request->pwa_app_name . '",
  "icons": [
    {
      "src": "' . asset('uploads/' . $user_id . '/192x192.png') . '",
      "sizes": "128x128",
      "type": "image/png"
    },
    {
      "src": "' . asset('uploads/' . $user_id . '/144x144.png') . '",
      "sizes": "144x144",
      "type": "image/png"
    },
    {
      "src": "' . asset('uploads/' . $user_id . '/152x152.png') . '",
      "sizes": "152x152",
      "type": "image/png"
    },
    {
      "src": "' . asset('uploads/' . $user_id . '/192x192.png') . '",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "' . asset('uploads/' . $user_id . '/256x256.png') . '",
      "sizes": "256x256",
      "type": "image/png"
    },
    {
      "src": "' . asset('uploads/' . $user_id . '/512x512.png') . '",
      "sizes": "512x512",
      "type": "image/png"
    }
  ],
  "lang": "' . $request->app_lang . '",
  "start_url": "/pwa",
  "display": "standalone",
  "background_color": "' . $request->pwa_app_background_color . '",
  "theme_color": "' . $request->pwa_app_theme_color . '"
}';

            \File::put('uploads/' . $user_id . '/manifest.json', $mainfest);

            return response()->json(['Update success']);
        }
        if ($request->type == 'theme_settings') {
            $user_id = Auth::id();

            $social = Useroption::where('user_id', $user_id)->where('key', 'socials')->first();
            if (empty($social)) {
                $social = new Useroption;
            }
            $social->key = 'socials';

            $links = [];
            foreach ($request->icon ?? [] as $key => $value) {
                $data['icon'] = $value;
                $data['url'] = $request->url[$key];
                array_push($links, $data);
            }

            $social->value = json_encode($links);
            $social->user_id = $user_id;
            $social->save();
            Cache::forget(auth()->id().'socials');

            $domain = Domain::where('user_id', $user_id)->first();
            if ($domain) {
                $domain->menu_type = $request->menu_type;
                $domain->save();
            }

            $requestAll = $request->except(['icon', 'type', '_token', 'url','menu_type']);

            if ($requestAll) {
                foreach ($requestAll as $key => $value) {
                    $data_other = Useroption::where('key', $key)->where('user_id', $user_id)->first();
                    if (empty($data_other)) {
                        $data_other = new Useroption;
                    }
                    $data_other->key = $key;
                    $data_other->value = $value ?? '';
                    $data_other->user_id = $user_id;
                    $data_other->save();

                    //forget cache
                    Cache::forget(auth()->id().$key);
                }
            }
            Cache::forget(auth()->id().'autoload_loaded');

            return response()->json(['Theme Settings Updated']);
        }

        if ($request->type == 'css') {
            $user_id = Auth::id();
            $plan = user_limit();
            if (filter_var($plan['custom_css']) == true) {
                \File::put('uploads/' . $user_id . '/additional.css', $request->css);
                return response()->json(['Updated success']);
            }
        }

        if ($request->type == 'js') {
            $user_id = Auth::id();
            $plan = user_limit();
            if (filter_var($plan['custom_js']) == true) {
                \File::put('uploads/' . $user_id . '/additional.js', $request->js);
                return response()->json(['Updated success']);
            }
        }

        if ($request->type == 'feature_settings') {
            $user_id = Auth::id();
            $requestAll = $request->except(['type', '_token']);

            if ($requestAll) {
                foreach ($requestAll as $key => $value) {
                    $data_other = Useroption::where('key', $key)->where('user_id', $user_id)->firstOrNew();
                    $data_other->key = $key;
                    $data_other->value = $value ?? '';
                    $data_other->user_id = $user_id;
                    $data_other->save();

                    //forget cache
                    Cache::forget(auth()->id().$key);
                }
            }
            Cache::forget(auth()->id().'autoload_loaded');

            return response()->json(['Feature Settings Updated']);
        }

        if ($request->type == 'text_settings') {
            $user_id = Auth::id();
            $requestAll = $request->except(['type', '_token']);

            if ($requestAll) {
                foreach ($requestAll as $key => $value) {
                    $data_other = Useroption::where('key', $key)->where('user_id', $user_id)->firstOrNew();
                    $data_other->key = $key;
                    $data_other->value = $value ?? '';
                    $data_other->user_id = $user_id;
                    $data_other->save();

                    //forget cache
                    Cache::forget(auth()->id().$key);
                }
            }
            Cache::forget(auth()->id().'autoload_loaded');

            return response()->json(['Feature Settings Updated']);
        }

        if ($request->type == 'certificate_settings') {
            $user_id = Auth::id();

            $validatedData = $request->validate([
                'certificate_status' => 'nullable',
                'certificate_id' => 'required',
                'certificate_image' => 'required',
            ]);

            $certificate = Useroption::where('user_id', $user_id)->where('key', 'certificate')->first();

            if (empty($certificate)) {
                $certificate = new Useroption;
                $certificate->key = 'certificate';
            }

            $data['certificate_status'] = $request->certificate_status;
            $data['certificate_id'] = $request->certificate_id;
            $data['certificate_image'] = $request->certificate_image;

            $certificate->value = json_encode($data);
            $certificate->user_id = $user_id;
            $certificate->save();

            Cache::forget(auth()->id().'autoload_loaded');

            return response()->json(['Certificate Settings Updated']);
        }

        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if ($slug == 'shop-settings') {
            $user_id = Auth::id();

            $langlist = \App\Option::where('key', 'languages')->first();
            $langlist = json_decode($langlist->value ?? '');

            $languages = Useroption::where('user_id', $user_id)->where('key', 'languages')->first();
            $active_languages = json_decode($languages->value ?? '');
            $my_languages = [];
            foreach ($active_languages ?? [] as $key => $value) {
                array_push($my_languages, $value);
            }

            $shop_name = Useroption::where('key', 'shop_name')->where('user_id', $user_id)->first();
            $shop_description = Useroption::where('key', 'shop_description')->where('user_id', $user_id)->first();
            $store_email = Useroption::where('key', 'store_email')->where('user_id', $user_id)->first();
            $order_prefix = Useroption::where('key', 'order_prefix')->where('user_id', $user_id)->first();
            $currency = Useroption::where('key', 'currency')->where('user_id', $user_id)->first();
            $location = Useroption::where('key', 'location')->where('user_id', $user_id)->first();
            $certificate = Useroption::where('key', 'certificate')->where('user_id', $user_id)->first();
            $theme_color = Useroption::where('key', 'theme_color')->where('user_id', $user_id)->first();
            $footer_background = Useroption::where('key', 'footer_background')->where('user_id', $user_id)->first();
            $title_color = Useroption::where('key', 'title_color')->where('user_id', $user_id)->first();
            $title_background = Useroption::where('key', 'title_background')->where('user_id', $user_id)->first() ?? '';
            $text_color = Useroption::where('key', 'text_color')->where('user_id', $user_id)->first();
            $fill_theme = Useroption::where('key', 'fill_theme')->where('user_id', $user_id)->first()->value ?? '';
            $fill_title = Useroption::where('key', 'fill_title')->where('user_id', $user_id)->first()->value ?? '';
            $font = Useroption::where('key', 'font')->where('user_id', $user_id)->first()->value ?? '';
            $menu_size = Useroption::where('key', 'menu_size')->where('user_id', $user_id)->first()->value ?? '';
            $title_size = Useroption::where('key', 'title_size')->where('user_id', $user_id)->first()->value ?? '';
            $subtitle_size = Useroption::where('key', 'subtitle_size')->where('user_id', $user_id)->first()->value ?? '';
            $hero_title_size = Useroption::where('key', 'hero_title_size')->where('user_id', $user_id)->first()->value ?? '';
            $hero_subtitle_size = Useroption::where('key', 'hero_subtitle_size')->where('user_id', $user_id)->first()->value ?? '';
            $text_size = Useroption::where('key', 'text_size')->where('user_id', $user_id)->first()->value ?? '';
            $currency = json_decode($currency->value ?? '');
            $location = json_decode($location->value ?? '');
            $certificate = json_decode($certificate->value ?? '');
            $tax = Useroption::where('user_id', $user_id)->where('key', 'tax')->first();
            $local = Useroption::where('user_id', $user_id)->where('key', 'local')->first();
            $socials = Useroption::where('user_id', $user_id)->where('key', 'socials')->first();
            $key_firebase = Useroption::where('user_id', $user_id)->where('key', 'push_firebase')->first();
            $local = $local->value ?? '';
            $socials = json_decode($socials->value ?? '');
            if (file_exists('uploads/' . Auth::id() . '/manifest.json')) {
                $pwa = file_get_contents('uploads/' . Auth::id() . '/manifest.json');
                $pwa = json_decode($pwa);
            } else {
                $pwa = [];
            }

            $order_receive_method = Useroption::where('user_id', $user_id)->where('key', 'order_receive_method')->first();
            $order_receive_method = $order_receive_method->value ?? 'email';

            $wallet_status = Useroption::where('user_id', $user_id)->where('key', 'wallet_status')->first();
            $wallet_status = $wallet_status->value ?? 'email';

            $booking_status = Useroption::where('user_id', $user_id)->where('key', 'booking_status')->first();
            $booking_status = $booking_status->value ?? 0;

            $affiliate_status = Useroption::where('user_id', $user_id)->where('key', 'affiliate_status')->first();
            $affiliate_status = $affiliate_status->value ?? 0;

            $hide_price_product = Useroption::where('user_id', $user_id)->where('key', 'hide_price_product')->first();
            $hide_price_product = $hide_price_product->value ?? 0;

            $account_status = Useroption::where('user_id', $user_id)->where('key', 'account_status')->first();
            $account_status = $account_status->value ?? 1;

            $cart_status = Useroption::where('user_id', $user_id)->where('key', 'cart_status')->first();
            $cart_status = $cart_status->value ?? 1;

            $wishlist_status = Useroption::where('user_id', $user_id)->where('key', 'wishlist_status')->first();
            $wishlist_status = $wishlist_status->value ?? 1;

            $slide_type = Useroption::where('key', 'slide_type')->where('user_id', $user_id)->first()->value ?? '';
            $domain = Domain::where('user_id',$user_id)->first();
            $booking_setting = Useroption::where('user_id', Auth::id())->ofType('booking_setting');
            $footer_text = Useroption::where('user_id', Auth::id())->ofType('footer_text');

            if (file_exists('uploads/' . Auth::id() . '/additional.js')) {
                $js = file_get_contents('uploads/' . Auth::id() . '/additional.js');
            } else {
                $js = '';
            }

            if (file_exists('uploads/' . Auth::id() . '/additional.css')) {
                $css = file_get_contents('uploads/' . Auth::id() . '/additional.css');
            } else {
                $css = '';
            }

            return view('seller.settings.general', compact('shop_name', 'order_receive_method', 'shop_description',
                'store_email', 'order_prefix', 'currency', 'location', 'theme_color', 'langlist', 'my_languages',
                'tax', 'local', 'socials', 'pwa', 'js', 'css', 'key_firebase', 'wallet_status', 'booking_status', 'affiliate_status',
                'font', 'account_status', 'cart_status', 'wishlist_status', 'title_color', 'text_color', 'fill_theme',
                'menu_size', 'title_size', 'subtitle_size','hero_title_size', 'hero_subtitle_size','text_size',
                'title_background','fill_title','slide_type','domain', 'booking_setting','footer_text', 'certificate', 'hide_price_product',
                'footer_background'
            ));
        }
        if ($slug == 'payment') {
            abort_if(!\Route::has('admin.plan.index'), 404);
            $posts = Category::with('description', 'active_getway')->where('type', 'payment_getway')->where('featured', 1)->whereNotIn('slug', ['cod', 'cod2'])->get();
            $cod = Category::with('description', 'active_getway')->where('type', 'payment_getway')->where('featured', 1)->whereIn('slug', ['cod', 'cod2'])->get();
            return view('seller.settings.payment_method', compact('posts', 'cod'));
        }
        if ($slug == 'plan') {
            abort_if(!\Route::has('admin.plan.index'), 404);
            $posts = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->latest()->get();
            return view('seller.plan.index', compact('posts'));
        }
        if ($slug == 'contact-list') {
            $contact = ContactLists::where('user_id', Auth::id())->latest()->paginate(5);
            return view('seller.settings.contact.index', compact('contact'));
        }
        if ($slug == 'social-login') {
            $social_login = Useroption::where('user_id', Auth::id())->where('key', 'social_login')->first();
            $social_login = json_decode($social_login->value ?? '');
            return view('seller.settings.social_login', compact('social_login'));
        }
        if ($slug == 'system-environment') {
            $status_mail_config = User::where('id', Auth::id())->first();
            $mail_configs = Useroption::where('user_id', Auth::id())->where('key', 'mail_config')->first();
            $mail_configs = json_decode($mail_configs->value ?? '');
            return view('seller.settings.system_mail', compact('mail_configs', 'status_mail_config'));
        }
        if ($slug == 'contact-page') {
            $contact_page = Useroption::where('user_id', Auth::id())->where('key', 'contact_page')->first();
            $contact_page = json_decode($contact_page->value ?? '');
            return view('seller.settings.contact_page', compact('contact_page'));
        }

        return back();
    }

    public function store_mailConfig(Request $request)
    {
        $mail_config = Useroption::where('user_id', Auth::id())->where('key', 'mail_config')->first();
        $status_mail_config = User::where('id', Auth::id())->first();
        $status_mail_config->mail_configuration = $request->mail_configuration;
        $status_mail_config->save();
        if (empty($mail_config)) {
            $mail_config = new Useroption;
            $mail_config->key = 'mail_config';
        }
        $mail_configInfo['driver'] = $request->driver;
        $mail_configInfo['host'] = $request->host;
        $mail_configInfo['port'] = $request->port;
        $mail_configInfo['username'] = $request->username;
        $mail_configInfo['password'] = $request->password;
        $mail_configInfo['encryption'] = $request->encryption;
        $mail_configInfo['mail_from_address'] = $request->mail_from_address;
        $mail_configInfo['mail_to'] = $request->mail_to;
        $mail_configInfo['mail_from_name'] = $request->mail_from_name;

        $mail_config->value = json_encode($mail_configInfo);
        $mail_config->user_id = Auth::id();
        $mail_config->save();

        return response()->json(['Mail Configuration Updated']);
    }

    public function storeSocialLogin(Request $request)
    {
        $social_login = Useroption::where('user_id', Auth::id())->where('key', 'social_login')->first();

        if (empty($social_login)) {
            $social_login = new Useroption;
            $social_login->key = 'social_login';
        }
        $social_loginInfo['medium'] = 'google';
        $social_loginInfo['status'] = $request->status;
        $social_loginInfo['client_id'] = $request->client_id;
        $social_loginInfo['client_secret'] = $request->client_secret;

        $social_login->value = json_encode($social_loginInfo);
        $social_login->user_id = Auth::id();
        $social_login->save();

        return response()->json(['Configuration Google Updated']);
    }

    public function storeContactPage(Request $request)
    {
        $contact_page = Useroption::where('user_id', Auth::id())->where('key', 'contact_page')->first();

        if (empty($contact_page)) {
            $contact_page = new Useroption;
            $contact_page->key = 'contact_page';
        }
        $contact_pageInfo['title'] = $request->title;
        $contact_pageInfo['subtitle'] = $request->subtitle;
        $contact_pageInfo['latitude'] = $request->latitude;
        $contact_pageInfo['longitude'] = $request->longitude;
        $contact_pageInfo['map_zoom'] = $request->map_zoom;

        $contact_page->value = json_encode($contact_pageInfo);
        $contact_page->user_id = Auth::id();
        $contact_page->save();

        return response()->json(['Contact Page Updated']);
    }

    public function support_view()
    {
        // $plan_limit = user_limit();
        // if (filter_var($plan_limit['live_support'], FILTER_VALIDATE_BOOLEAN) != true) {
        //     return redirect('/seller/dashboard');
        // }

        $adminIds = User::where('role_id', 1)->pluck('id');
        $contact_list = ContactLists::whereIn('user_id', $adminIds)->get();

        return view('seller.settings.support', compact('contact_list'));
    }

    public function booking()
    {
        $booking_setting = Useroption::where('user_id', Auth::id())->where('key', 'booking_setting')->first();
        return view('seller.settings.booking',compact('booking_setting'));
    }
    public function booking_update(Request $request)
    {
        $booking_setting = Useroption::where('user_id', Auth::id())->where('key', 'booking_setting')->first();

        if (empty($booking_setting)) {
            $booking_setting = new Useroption;
            $booking_setting->key = 'booking_setting';
        }
        $booking_setting->value = $request->content;
        $booking_setting->user_id = Auth::id();
        $booking_setting->save();

        return response()->json(['Booking Setting Updated']);
    }

    public function cache_clear(Request $request)
    {
        $cache_clear = Useroption::where('user_id', Auth::id())->pluck('key');
        foreach($cache_clear as $item){
            Cache::forget(auth()->id().$item);
        }
        Cache::forget(auth()->id().'autoload_loaded');
        Cache::forget(domain_info('domain_name'));
        return redirect()->back();
    }

    public function logo_favicon(Request $request)
    {
        return view('seller.settings.logo_favicon');
    }

    public function update_logo_favicon(Request $request)
    {
        $validatedData = $request->validate([
            'logo' => 'max:1000|mimes:png',
            'favicon' => 'max:1000|mimes:ico',
        ]);

        $user_id = Auth::id();
        if ($request->logo) {
            if (! File::exists('uploads/' . $user_id)) {
                File::makeDirectory('uploads/' . $user_id);
            }
            $request->logo->move('uploads/' . $user_id, 'logo.png');
        }

        if ($request->favicon) {
            if (! File::exists('uploads/' . $user_id)) {
                File::makeDirectory('uploads/' . $user_id);
            }
            $request->favicon->move('uploads/' . $user_id, 'favicon.ico');
        }

        return response()->json(['success','Logo & Favicon Updated']);
    }
    public function viewTokenPage()
    {
        $id = Auth::id();
        $user = User::find($id);
        $shopSyncToken = $user->shop_sync_token;

        return view('seller.settings.token_mydi4sell', compact('shopSyncToken'));
    }
    public function saveTokenMyDi4sell(Request $request)
    {
        $token = $request->token;
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->update(['shop_sync_token'=>$token]);
        return redirect()->back()->with('success', 'Token Updated.')
        ->with('data', json_encode(['success' => 'Token Updated']));
    }
    public function loyalty()
    {
        $loyalty_point = Useroption::where('user_id', Auth::id())->where('key', 'loyalty_point')->first();
        $loyalty_name = Useroption::where('user_id', Auth::id())->where('key', 'loyalty_name')->first();
        $status = Useroption::where('user_id', Auth::id())->where('key', 'loyalty_status')->first();
        $loyalty_status =  $status->value ?? '';
        return view('seller.settings.loyalty', compact('loyalty_status', 'loyalty_point', 'loyalty_name'));
    }
    public function loyalty_update(Request $request)
    {
        /**
         *
         * loyalty_point: Cấu hình số điểm tương ứng mỗi khi mua hàng quy đổi
         * Ex:  => ví dụ 1000 được 1 điểm.
         *
         */
        $loyalty_point = Useroption::where('user_id', Auth::id())->where('key', 'loyalty_point')->first();
        if (empty($loyalty_point)) {
            $loyalty_point = new Useroption;
            $loyalty_point->key = 'loyalty_point';
        }
        $loyalty_point->value = $request->loyalty_point;
        $loyalty_point->user_id = Auth::id();
        $loyalty_point->save();

        $loyalty_name = Useroption::where('user_id', Auth::id())->where('key', 'loyalty_name')->first();
        if (empty($loyalty_name)) {
            $loyalty_name = new Useroption;
            $loyalty_name->key = 'loyalty_name';
        }
        $loyalty_name->value = $request->loyalty_name;
        $loyalty_name->user_id = Auth::id();
        $loyalty_name->save();

        $loyalty_status = Useroption::where('user_id', Auth::id())->where('key', 'loyalty_status')->first();
        if (empty($loyalty_status)) {
            $loyalty_status = new Useroption;
            $loyalty_status->key = 'loyalty_status';
        }
        $loyalty_status->value = $request->loyalty_status;
        $loyalty_status->user_id = Auth::id();
        $loyalty_status->save();

        return response()->json(['Loyalty Setting Updated']);
    }
}
