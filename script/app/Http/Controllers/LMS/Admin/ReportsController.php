<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\NotificationTemplate;
use App\Models\LMS\Setting;
use App\Models\LMS\SettingTranslation;
use App\Models\LMS\WebinarReport;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function reasons(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_report_reasons');

        $value = [];

        $settings = Setting::where('name', 'report_reasons')->first();

        $locale = $request->get('locale', getDefaultLocale());
        storeContentLocale($locale, $settings->getTable(), $settings->id);

        if (!empty($settings) and !empty($settings->value)) {
            $value = json_decode($settings->value, true);
        }


        $data = [
            'pageTitle' => trans('lms/admin/pages/setting.report_reasons'),
            'value' => $value,
        ];


        return view('lms.admin.reports.reasons', $data);
    }

    public function storeReasons(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_report_reasons');

        $name = 'report_reasons';

        $values = $request->get('value', null);

        if (!empty($values)) {
            $locale = $request->get('locale', getDefaultLocale());

            $values = array_filter($values, function ($val) {
                if (is_array($val)) {
                    return array_filter($val);
                } else {
                    return !empty($val);
                }
            });

            $values = json_encode($values);
            $values = str_replace('record', rand(1, 600), $values);

            $settings = Setting::updateOrCreate(
                ['name' => $name],
                [
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

        removeContentLocale();

        return back();
    }

    public function webinarsReports()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinar_reports');

        $reports = WebinarReport::with(['user' => function ($query) {
            $query->select('id', 'full_name');
        }, 'webinar' => function ($query) {
            $query->select('id', 'slug');
        }])->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/comments.classes_reports'),
            'reports' => $reports
        ];

        return view('lms.admin.webinars.reports', $data);
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinar_reports_delete');

        $report = WebinarReport::findOrFail($id);

        $report->delete();

        return redirect()->back();
    }
}
