<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Testimonial;
use App\Models\LMS\TestimonialTranslation;
use Illuminate\Http\Request;

class TestimonialsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_testimonials_list');

        removeContentLocale();

        $testimonials = Testimonial::query()->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/comments.testimonials'),
            'testimonials' => $testimonials
        ];

        return view('lms.admin.testimonials.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_testimonials_create');

        removeContentLocale();

        $data = [
            'pageTitle' => trans('lms/admin/pages/comments.new_testimonial'),
        ];

        return view('lms.admin.testimonials.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_testimonials_create');

        $this->validate($request, [
            'user_avatar' => 'nullable|string',
            'user_name' => 'required|string',
            'user_bio' => 'required|string',
            'rate' => 'required|integer|between:0,5',
            'comment' => 'required|string',
        ]);

        $data = $request->all();

        if (empty($data['user_avatar'])) {
            $data['user_avatar'] = getPageBackgroundSettings('user_avatar');
        }

        $testimonial = Testimonial::create([
            'user_avatar' => $data['user_avatar'],
            'rate' => $data['rate'],
            'status' => $data['status'],
            'created_at' => time(),
        ]);

        if (!empty($testimonial)) {
            TestimonialTranslation::updateOrCreate([
                'testimonial_id' => $testimonial->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'user_name' => $data['user_name'],
                'user_bio' => $data['user_bio'],
                'comment' => $data['comment'],
            ]);
        }

        return redirect('/lms/admin/testimonials');
    }


    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_testimonials_edit');

        $testimonial = Testimonial::findOrFail($id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $testimonial->getTable(), $testimonial->id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/comments.edit_testimonial'),
            'testimonial' => $testimonial
        ];

        return view('lms.admin.testimonials.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_testimonials_edit');

        $this->validate($request, [
            'user_avatar' => 'nullable|string',
            'user_name' => 'required|string',
            'user_bio' => 'required|string',
            'rate' => 'required|integer|between:0,5',
            'comment' => 'required|string',
        ]);

        $testimonial = Testimonial::findOrFail($id);

        $data = $request->all();

        if (empty($data['user_avatar'])) {
            $data['user_avatar'] = getPageBackgroundSettings('user_avatar');
        }


        $testimonial->update([
            'user_avatar' => $data['user_avatar'],
            'rate' => $data['rate'],
            'status' => $data['status'],
        ]);

        TestimonialTranslation::updateOrCreate([
            'testimonial_id' => $testimonial->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'user_name' => $data['user_name'],
            'user_bio' => $data['user_bio'],
            'comment' => $data['comment'],
        ]);

        removeContentLocale();

        return redirect('/lms/admin/testimonials');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_testimonials_delete');

        $testimonial = Testimonial::findOrFail($id);

        $testimonial->delete();

        return redirect('/lms/admin/testimonials');
    }
}
