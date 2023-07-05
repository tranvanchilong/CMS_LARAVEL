<?php

namespace App\Http\Controllers\LMS\Admin\traits;

use App\Models\LMS\NavbarButton;
use App\Models\LMS\Role;
use App\Models\LMS\NavbarButtonTranslation;
use Illuminate\Http\Request;

trait NavbarButtonSettings
{
    protected $settingName = 'navbar_button';

    public function navbarButtonSettings(Request $request)
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $navbarButtons = NavbarButton::query()
            ->with([
                'role'
            ])->get();

        $defaultLocal = getDefaultLocale();
        $locale = $request->get('locale', mb_strtolower($defaultLocal));

        $data = [
            'pageTitle' => trans('lms/admin/main.settings'),
            'navbarButtons' => $navbarButtons,
            'name' => $this->settingName,
            'selectedLocale' => $locale,
            'roles' => Role::all()
        ];

        return view('lms.admin.settings.personalization', $data);
    }

    public function storeNavbarButtonSettings(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $itemId = $request->get('item_id');

        $this->validate($request, [
            'role_id' => 'required|unique:lms_navbar_buttons' . (!empty($itemId) ? (',role_id,' . $itemId) : ''),
            'title' => 'required',
            'url' => 'required',
        ]);

        $data = $request->all();

        $roleId = (!empty($data['role_id']) and $data['role_id'] != 'for_guest') ? $data['role_id'] : null;
        $forGuest = (!empty($data['role_id']) and $data['role_id'] == 'for_guest');

        $navbarButton = NavbarButton::where('role_id', $roleId)
            ->where('for_guest', $forGuest)
            ->first();

        if (!empty($navbarButton) and $navbarButton->id != $itemId) {
            return back()->withErrors([
                'role_id' => trans('lms/validation.unique', ['attribute' => trans('lms/admin/main.role')])
            ]);
        }

        if (empty($navbarButton)) {
            $navbarButton = NavbarButton::create([
                'role_id' => (!empty($data['role_id']) and $data['role_id'] != 'for_guest') ? $data['role_id'] : null,
                'for_guest' => (!empty($data['role_id']) and $data['role_id'] == 'for_guest'),
            ]);
        }

        NavbarButtonTranslation::updateOrCreate([
            'navbar_button_id' => $navbarButton->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'url' => $data['url'],
        ]);

        return redirect('/lms/admin/settings/personalization/navbar_button');
    }

    public function navbarButtonSettingsEdit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $navbarButton = NavbarButton::findOrFail($id);

        $defaultLocal = getDefaultLocale();
        $locale = $request->get('locale', mb_strtolower($defaultLocal));
        storeContentLocale($locale, $navbarButton->getTable(), $navbarButton->id);

        $data = [
            'pageTitle' => trans('lms/admin/main.settings'),
            'navbarButton' => $navbarButton,
            'roles' => Role::all(),
            'name' => $this->settingName,
            'selectedLocale' => $locale,
        ];

        return view('lms.admin.settings.personalization', $data);
    }

    public function navbarButtonSettingsDelete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $navbarButton = NavbarButton::findOrFail($id);

        $navbarButton->delete();

        return redirect('/lms/admin/settings/personalization/navbar_button');
    }
}
