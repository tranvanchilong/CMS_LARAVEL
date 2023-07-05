<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\NotificationTemplate;
use App\Models\LMS\Setting;
use App\Models\LMS\SettingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdvertisingModalController extends Controller
{
    protected $settingName = 'advertising_modal';

    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_advertising_modal_config');

        $value = [];

        $settings = Setting::where('name', $this->settingName)
            ->first();

        //$defaultLocale = getDefaultLocale();
        $defaultLocale = Setting::$defaultSettingsLocale;

        $locale = $request->get('locale', $defaultLocale);

        if (!empty($settings)) {
            storeContentLocale($locale, $settings->getTable(), $settings->id);

            if (!empty($settings->value)) {
                $value = json_decode($settings->value, true);
            }
        }

        $data = [
            'pageTitle' => trans('lms/update.advertising_modal'),
            'value' => $value,
            'selectedLocal' => $locale
        ];

        return view('lms.admin.advertising_modal.index', $data);
    }

    public function store(Request $request)
    {
        $values = $request->get('value', null);

        if (!empty($values)) {
            // $defaultLocale = getDefaultLocale();
            $defaultLocale = Setting::$defaultSettingsLocale;

            $locale = $request->get('locale', $defaultLocale);

            $values = array_filter($values, function ($val) {
                if (is_array($val)) {
                    return array_filter($val);
                } else {
                    return !empty($val);
                }
            });

            $values = json_encode($values);
            $values = str_replace('record', rand(1, 600), $values);

            $setting = Setting::updateOrCreate(
                ['name' => $this->settingName],
                [
                    'page' => $request->get('page', 'other'),
                    'updated_at' => time(),
                ]
            );

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $setting->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => $values,
                ]
            );

            cache()->forget('settings.' . $this->settingName);
        }

        removeContentLocale();

        return back();
    }
}
