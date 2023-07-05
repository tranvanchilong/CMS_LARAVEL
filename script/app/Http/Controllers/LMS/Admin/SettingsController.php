<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Admin\traits\FinancialCurrencySettings;
use App\Http\Controllers\LMS\Admin\traits\FinancialOfflineBankSettings;
use App\Http\Controllers\LMS\Admin\traits\FinancialUserBankSettings;
use App\Http\Controllers\LMS\Admin\traits\NavbarButtonSettings;
use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Currency;
use App\Models\LMS\NotificationTemplate;
use App\Models\LMS\OfflineBank;
use App\Models\LMS\PaymentChannel;
use App\Models\LMS\Setting;
use App\Models\LMS\SettingTranslation;
use App\Models\LMS\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    use NavbarButtonSettings;
    use FinancialCurrencySettings;
    use FinancialOfflineBankSettings;
    use FinancialUserBankSettings;

    public function index()
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings');

        $data = [
            'pageTitle' => trans('lms/admin/main.settings_title'),
        ];

        return view('lms.admin.settings.index', $data);
    }

    public function page(Request $request, $page)
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_' . $page);

        $settings = Setting::where('page', $page)
            ->get()
            ->keyBy('name');

        foreach ($settings as $setting) {
            $setting->value = json_decode($setting->value, true);
        }

        $data = [
            'pageTitle' => trans('lms/admin/main.settings_title'),
            'settings' => $settings
        ];

        if ($page == 'notifications') {
            $data['notificationTemplates'] = NotificationTemplate::all();
        }

        if ($page == 'financial') {
            $paymentChannels = PaymentChannel::orderBy('created_at', 'desc')->paginate(10);
            $data['paymentChannels'] = $paymentChannels;
        
            if ($request->get('tab') == 'currency') {
                $data['currencies'] = Currency::query()
                    ->orderBy('order', 'asc')
                    ->get();
            }

            if ($request->get('tab') == 'offline_banks') {
                $data['offlineBanks'] = OfflineBank::query()
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            if ($request->get('tab') == 'user_banks') {
                $data['userBanks'] = UserBank::query()
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('lms.admin.settings.' . $page, $data);
    }

    public function personalizationPage(Request $request, $name)
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $settings = Setting::where('name', $name)->first();

        $values = null;

        if (!empty($settings)) {
            $defaultLocal = getDefaultLocale();

            if (in_array($name, [Setting::$pageBackgroundName, Setting::$homeSectionsName, Setting::$themeFontsName, Setting::$themeColorsName])) {
                $defaultLocal = Setting::$defaultSettingsLocale;
            }

            $locale = $request->get('locale', mb_strtolower($defaultLocal));

            storeContentLocale($locale, $settings->getTable(), $settings->id);

            if (!empty($settings->value)) {
                $values = json_decode($settings->value, true);

                $values['locale'] = mb_strtoupper($settings->locale);
            }
        }

        $data = [
            'pageTitle' => trans('lms/admin/main.settings_title'),
            'values' => $values,
            'name' => $name
        ];

        return view('lms.admin.settings.personalization', $data);
    }

    public function store(Request $request, $name)
    {

        if (!empty($request->get('name'))) {
            $name = $request->get('name');
        }

        $tmpValues = $request->get('value', null);
        $adminPanelUrl = (!empty($tmpValues) and !empty($tmpValues['admin_panel_url'])) ? $tmpValues['admin_panel_url'] : null;

        if (!empty($tmpValues)) {
            $locale = $request->get('locale', Setting::$defaultSettingsLocale); // default is "en"

            $values = [];
            foreach ($tmpValues as $key => $val) {
                if (is_array($val)) {
                    $values[$key] = array_filter($val);
                } else {
                    $values[$key] = $val;
                }
            }


            if ($name == 'referral') {
                $getFinancialCommission = getFinancialSettings('commission') ?? 0;

                $validator = Validator::make($values, [
                    'affiliate_user_commission' => 'nullable|numeric|max:' . $getFinancialCommission,
                ]);

                $validator->validate();
            } elseif ($name == 'general') {
                if (empty($values['user_languages']) or !is_array($values['user_languages'])) {
                    $values['content_translate'] = false;
                }
            } elseif ($name == 'maintenance_settings') {
                if (!empty($values['end_date'])) {
                    $values['end_date'] = convertTimeToUTCzone($values['end_date'], null)->getTimestamp();
                }
            }

            $values = json_encode($values);
            $values = str_replace('record', rand(1, 600), $values);

            $settings = Setting::updateOrCreate(
                ['name' => $name],
                [
                    'page' => $request->get('page', 'other'),
                    'updated_at' => time(),
                ]
            );

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $settings->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => $values,
                ]
            );

            cache()->forget('settings.' . $name);

            if ($name == 'general') {
                cache()->forget('settings.getDefaultLocale');
            }
        }

        if ($name == "security") { // after change admin panel url
            $url = !empty($adminPanelUrl) ? $adminPanelUrl : getAdminPanelUrl();
            $url .= '/settings/general';

            return redirect($url);
        }

        return back();
    }

    public function storeSeoMetas(Request $request)
    {
        $name = Setting::$seoMetasName;

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_seo');

        $data = $request->all();
        $locale = $request->get('locale', Setting::$defaultSettingsLocale);
        $newValues = $data['value'];
        $values = [];
        $settings = Setting::where('name', $name)->first();

        if (!empty($settings) and !empty($settings->value)) {
            $values = json_decode($settings->value);
        }

        if (!empty($newValues) and !empty($values)) {
            foreach ($newValues as $newKey => $newValue) {
                foreach ($values as $key => $value) {
                    if ($key == $newKey) {
                        $values->$key = $newValue;
                        unset($newValues[$key]);
                    }
                }
            }
        }

        if (!empty($newValues)) {
            $values = array_merge((array)$values, $newValues);
        }

        $settings = Setting::updateOrCreate(
            ['name' => $name],
            [
                'page' => 'seo',
                'updated_at' => time(),
            ]
        );

        SettingTranslation::updateOrCreate(
            [
                'setting_id' => $settings->id,
                'locale' => mb_strtolower($locale)
            ],
            [
                'value' => json_encode($values),
            ]
        );

        cache()->forget('settings.' . $name);

        return back();
    }

    public function editSocials($social_key)
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_general');
        $settings = Setting::where('name', Setting::$socialsName)->first();

        if (!empty($settings)) {
            $values = json_decode($settings->value);

            foreach ($values as $key => $value) {
                if ($key == $social_key) {
                    $data = [
                        'pageTitle' => trans('lms/admin/pages/setting.settings_socials'),
                        'social' => $value,
                        'socialKey' => $social_key,
                    ];

                    return view('lms.admin.settings.general', $data);
                }
            }
        }

        abort(404);
    }

    public function deleteSocials($social_key, $locale = null)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_general');
        $settings = Setting::where('name', Setting::$socialsName)->first();

        if (empty($locale)) {
            $locale = Setting::$defaultSettingsLocale;
        }

        if (!empty($settings)) {
            $values = json_decode($settings->value);
            foreach ($values as $key => $value) {
                if ($key == $social_key) {
                    unset($values->$social_key);
                }
            }

            $settings = Setting::updateOrCreate(
                ['name' => Setting::$socialsName],
                [
                    'page' => 'general',
                    'updated_at' => time(),
                ]
            );

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $settings->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => json_encode($values),
                ]
            );

            cache()->forget('settings.' . Setting::$socialsName);

            return redirect('/lms/admin/settings/general');
        }

        abort(404);
    }

    public function storeSocials(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_general');
        $this->validate($request, [
            'value.*' => 'required',
        ]);

        $data = $request->all();
        $locale = $request->get('locale', Setting::$defaultSettingsLocale);
        $social = $data['social'];
        $values = [];

        $settings = Setting::where('name', Setting::$socialsName)->first();

        if ($social !== 'newSocial') {
            if (!empty($settings) and !empty($settings->value)) {
                $values = json_decode($settings->value);
                foreach ($values as $key => $value) {
                    if ($key == $social) {
                        $values->$key = $data['value'];
                    }
                }
            }
        } else {
            if (!empty($settings) and !empty($settings->value)) {
                $values = json_decode($settings->value);
            }
            $key = str_replace(' ', '_', $data['value']['title']);
            $newValue[$key] = $data['value'];
            $values = array_merge((array)$values, $newValue);
        }

        $settings = Setting::updateOrCreate(
            ['name' => Setting::$socialsName],
            [
                'page' => 'general',
                'updated_at' => time(),
            ]
        );

        SettingTranslation::updateOrCreate(
            [
                'setting_id' => $settings->id,
                'locale' => mb_strtolower($locale)
            ],
            [
                'value' => json_encode($values),
            ]
        );

        cache()->forget('settings.' . Setting::$socialsName);

        return redirect('/lms/admin/settings/general');
    }

    public function storeCustomCssJs(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_customization');

        $newValues = $request->get('value', null);
        $locale = $request->get('locale', Setting::$defaultSettingsLocale);
        $values = [];
        $settings = Setting::where('name', Setting::$customCssJsName)->first();

        if (!empty($settings) and !empty($settings->value)) {
            $values = json_decode($settings->value);
        }

        if (!empty($newValues) and !empty($values)) {
            foreach ($newValues as $newKey => $newValue) {
                foreach ($values as $key => $value) {
                    if ($key == $newKey) {
                        $values->$key = $newValue;
                        unset($newValues[$key]);
                    }
                }
            }
        }

        if (!empty($newValues)) {
            $values = array_merge((array)$values, $newValues);
        }

        if (!empty($values)) {
            $values = json_encode($values);

            $settings = Setting::updateOrCreate(
                ['name' => Setting::$customCssJsName],
                [
                    'page' => 'customization',
                    'updated_at' => time(),
                ]
            );

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $settings->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => $values,
                ]
            );

            cache()->forget('settings.' . Setting::$customCssJsName);

            return back();
        }
    }

    public function notificationsMetas(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_notifications');
        $name = 'notifications';
        $values = $request->get('value', []);
        $locale = $request->get('locale', Setting::$defaultSettingsLocale);

        $settings = Setting::where('name', $name)->first();

        if (!empty($settings) and !empty($settings->value)) {
            $oldValues = json_decode($settings->value, true);

            $values = array_merge($oldValues, $values);
        }

        if (!empty($values)) {
            $values = array_filter($values);
            $values = json_encode($values);

            $settings = Setting::updateOrCreate(
                ['name' => $name],
                [
                    'page' => 'notifications',
                    'updated_at' => time(),
                ]
            );

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $settings->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => $values,
                ]
            );

            cache()->forget('settings.' . $name);
        }

        return back();
    }
}
