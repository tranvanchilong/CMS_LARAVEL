<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\WebinarExtraDescriptionTranslation;
use App\Models\LMS\UpcomingCourse;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarExtraDescription;
use Illuminate\Http\Request;

class WebinarExtraDescriptionController extends Controller
{
    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinars_edit');

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['locale'])) {
            $data['locale'] = getDefaultLocale();
        }

        $creator = $this->getCreator($data);

        if (!empty($creator)) {
            $columnName = !empty($data['webinar_id']) ? 'webinar_id' : 'upcoming_course_id';
            $columnValue = !empty($data['webinar_id']) ? $data['webinar_id'] : $data['upcoming_course_id'];

            $order = WebinarExtraDescription::query()->where('creator_id', $creator->id)
                    ->where($columnName, $columnValue)
                    ->where('type', $data['type'])
                    ->count() + 1;

            $webinarExtraDescription = WebinarExtraDescription::create([
                'creator_id' => $creator->id,
                'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
                'upcoming_course_id' => !empty($data['upcoming_course_id']) ? $data['upcoming_course_id'] : null,
                'type' => $data['type'],
                'order' => $order,
                'created_at' => time()
            ]);

            if (!empty($webinarExtraDescription)) {
                WebinarExtraDescriptionTranslation::updateOrCreate([
                    'webinar_extra_description_id' => $webinarExtraDescription->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'value' => $data['value'],
                ]);
            }
        }

        return response()->json([
            'code' => 200,
        ], 200);
    }

    private function getCreator($data)
    {
        $creator = false;

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::findOrFail($data['webinar_id']);

            $creator = $webinar->creator;
        } elseif (!empty($data['upcoming_course_id'])) {
            $upcomingCourse = UpcomingCourse::findOrFail($data['upcoming_course_id']);

            $creator = $upcomingCourse->creator;
        }

        return $creator;
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinars_edit');

        $webinarExtraDescription = WebinarExtraDescription::find($id);

        if (!empty($webinarExtraDescription)) {
            $locale = $request->get('locale', app()->getLocale());
            if (empty($locale)) {
                $locale = app()->getLocale();
            }
            storeContentLocale($locale, $webinarExtraDescription->getTable(), $webinarExtraDescription->id);

            $webinarExtraDescription->value = $webinarExtraDescription->getValueAttribute();
            $webinarExtraDescription->locale = mb_strtoupper($locale);

            return response()->json([
                'webinarExtraDescription' => $webinarExtraDescription
            ], 200);
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinars_edit');

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['locale'])) {
            $data['locale'] = getDefaultLocale();
        }

        $webinarExtraDescription = WebinarExtraDescription::find($id);

        if ($webinarExtraDescription) {

            WebinarExtraDescriptionTranslation::updateOrCreate([
                'webinar_extra_description_id' => $webinarExtraDescription->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'value' => $data['value'],
            ]);
        }

        return response()->json([
            'code' => 200,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinars_edit');

        WebinarExtraDescription::find($id)->delete();

        return redirect()->back();
    }
}
