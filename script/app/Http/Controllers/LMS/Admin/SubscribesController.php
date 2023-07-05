<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Subscribe;
use App\Models\LMS\SubscribeTranslation;
use Illuminate\Http\Request;

class SubscribesController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_subscribe_list');

        removeContentLocale();

        $subscribes = Subscribe::with([
            'sales' => function ($query) {
                $query->whereNull('refund_at');
            }
        ])->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.subscribes'),
            'subscribes' => $subscribes
        ];

        return view('lms.admin.financial.subscribes.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_subscribe_create');

        removeContentLocale();

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.new_subscribe'),
        ];

        return view('lms.admin.financial.subscribes.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_subscribe_create');

        $this->validate($request, [
            'title' => 'required|string',
            'usable_count' => 'required|numeric',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
        ]);

        $data = $request->all();

        $subscribe = Subscribe::create([
            'usable_count' => $data['usable_count'],
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'is_popular' => (!empty($data['is_popular']) and $data['is_popular'] == '1'),
            'infinite_use' => (!empty($data['infinite_use']) and $data['infinite_use'] == '1'),
            'created_at' => time(),
        ]);

        if (!empty($subscribe)) {
            SubscribeTranslation::updateOrCreate([
                'subscribe_id' => $subscribe->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => !empty($data['description']) ? $data['description'] : null,
            ]);
        }

        return redirect('/lms/admin/financial/subscribes');
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_subscribe_edit');

        $subscribe = Subscribe::findOrFail($id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $subscribe->getTable(), $subscribe->id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.new_subscribe'),
            'subscribe' => $subscribe
        ];

        return view('lms.admin.financial.subscribes.new', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_subscribe_edit');

        $this->validate($request, [
            'title' => 'required|string',
            'usable_count' => 'required|numeric',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
        ]);

        $data = $request->all();
        $subscribe = Subscribe::findOrFail($id);

        $subscribe->update([
            'usable_count' => $data['usable_count'],
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'is_popular' => (!empty($data['is_popular']) and $data['is_popular'] == '1'),
            'infinite_use' => (!empty($data['infinite_use']) and $data['infinite_use'] == '1'),
            'created_at' => time(),
        ]);

        SubscribeTranslation::updateOrCreate([
            'subscribe_id' => $subscribe->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => !empty($data['description']) ? $data['description'] : null,
        ]);

        removeContentLocale();

        return redirect('/lms/admin/financial/subscribes');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_subscribe_delete');

        $promotion = Subscribe::findOrFail($id);

        $promotion->delete();

        return redirect('/lms/admin/financial/subscribes');
    }
}
