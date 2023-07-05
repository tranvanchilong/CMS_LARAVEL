<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeSectionSettingsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        removeContentLocale();

        $sections = HomeSection::orderBy('order', 'asc')->get();
        $selectedSectionsName = $sections->pluck('name')->toArray();

        $data = [
            'pageTitle' => trans('lms/admin/main.home_sections'),
            'sections' => $sections,
            'selectedSectionsName' => $selectedSectionsName,
            'name' => 'home_sections'
        ];

        return view('lms.admin.settings.personalization', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $this->validate($request, [
            'name' => 'required'
        ]);


        HomeSection::updateOrCreate([
            'name' => $request->get('name'),
        ], [
            'order' => HomeSection::query()->count() + 1
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $section = HomeSection::findOrFail($id);

        $section->delete();

        $allSections = HomeSection::orderBy('order', 'asc')->get();

        $order = 1;
        foreach ($allSections as $allSection) {
            $allSection->update([
                'order' => $order
            ]);

            $order += 1;
        }

        return redirect()->back();
    }

    public function sort(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_settings_personalization');

        $data = $request->all();

        $validator = Validator::make($data, [
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $itemIds = explode(',', $data['items']);

        foreach ($itemIds as $order => $id) {
            HomeSection::where('id', $id)
                ->update(['order' => ($order + 1)]);
        }

        return response()->json([
            'title' => trans('lms/public.request_success'),
            'msg' => trans('lms/update.items_sorted_successful')
        ]);
    }
}
