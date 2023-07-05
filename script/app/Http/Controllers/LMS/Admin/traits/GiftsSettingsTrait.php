<?php

namespace App\Http\Controllers\LMS\Admin\traits;

use App\Models\LMS\Setting;
use App\Models\LMS\SettingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait GiftsSettingsTrait
{
    public function settings(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_gift_settings');

        removeContentLocale();

        $setting = Setting::where('page', 'general')
            ->where('name', Setting::$giftsGeneralSettingsName)
            ->first();

        $data = [
            'pageTitle' => trans('lms/update.gifts_settings'),
            'setting' => $setting,
            'selectedLocale' => mb_strtolower($request->get('locale', Setting::$defaultSettingsLocale)),
        ];

        return view('lms.admin.gifts.settings', $data);
    }

    public function storeSettings(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_gift_settings');

        $page = 'general';
        $data = $request->all();
        $name = $data['name'];
        $locale = $data['locale'];
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
                'page' => $page,
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
}
