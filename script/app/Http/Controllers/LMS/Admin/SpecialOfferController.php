<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Bundle;
use App\Models\LMS\Discount;
use App\Models\LMS\DiscountUser;
use App\Models\LMS\Product;
use App\Models\LMS\RegistrationPackage;
use App\Models\LMS\SpecialOffer;
use App\Models\LMS\Subscribe;
use App\Models\LMS\Webinar;
use App\Models\LMS\User;
use Illuminate\Http\Request;

class SpecialOfferController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_list');

        $query = SpecialOffer::query();

        $specialOffers = $this->filters($query, $request)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.special_offers_list_page_title'),
            'specialOffers' => $specialOffers,
        ];

        $webinar_ids = $request->get('webinar_ids');
        if (!empty($webinar_ids)) {
            $data['webinars'] = Webinar::select('id')
                ->whereIn('id', $webinar_ids)
                ->get();
        }

        return view('lms.admin.financial.special_offers.lists', $data);
    }

    private function filters($query, $request)
    {
        $name = $request->get('name');
        $from = $request->get('from');
        $to = $request->get('to');
        $sort = $request->get('sort');
        $webinar_ids = $request->get('webinar_ids');
        $status = $request->get('status');
        $type = $request->get('type');

        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }

        if (!empty($from)) {
            $from = strtotime($from);
            $query->where('from_date', '>=', $from);
        }

        if (!empty($to)) {
            $to = strtotime($to);
            $query->where('to_date', '<', $to);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'percent_asc':
                    $query->orderBy('percent', 'asc');
                    break;
                case 'percent_desc':
                    $query->orderBy('percent', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'expire_at_asc':
                    $query->orderBy('to_date', 'asc');
                    break;
                case 'expire_at_desc':
                    $query->orderBy('to_date', 'desc');
                    break;
            }
        }

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($type) and in_array($type, ['webinar_id', 'bundle_id', 'subscribe_id', 'registration_package_id'])) {
            $query->whereNotNull($type);
        }

        return $query;
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_create');

        $subscribes = Subscribe::query()->get();
        $registrationPackages = RegistrationPackage::query()->where('status', 'active')->get();

        $data = [
            'pageTitle' => trans('lms/admin/main.new_product_discount_title'),
            'subscribes' => $subscribes,
            'registrationPackages' => $registrationPackages,
        ];

        return view('lms.admin.financial.special_offers.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_create');

        $this->validate($request, [
            'percent' => 'required',
            'status' => 'nullable|in:active,inactive',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $data = $request->all();

        $item = $this->getItem($data);
        $activeSpecialOfferForWebinar = $item->activeSpecialOffer();

        if ($activeSpecialOfferForWebinar) {
            $toastData = [
                'title' => trans('lms/public.request_failed'),
                'msg' => trans('lms/update.this_course_has_active_special_offer'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $fromDate = convertTimeToUTCzone($data['from_date'], getTimezone());
        $toDate = convertTimeToUTCzone($data['to_date'], getTimezone());

        SpecialOffer::create([
            'creator_id' => auth()->guard('lms_user')->user()->id,
            'name' => $data["name"],
            'webinar_id' => $data["webinar_id"] ?? null,
            'bundle_id' => $data["bundle_id"] ?? null,
            'subscribe_id' => $data["subscribe_id"] ?? null,
            'registration_package_id' => $data["registration_package_id"] ?? null,
            'percent' => $data["percent"],
            'status' => $data["status"],
            'created_at' => time(),
            'from_date' => $fromDate->getTimestamp(),
            'to_date' => $toDate->getTimestamp(),
        ]);

        return redirect('/lms/admin/financial/special_offers');
    }

    private function getItem($data)
    {
        if (!empty($data['webinar_id'])) {
            return Webinar::query()->findOrFail($data['webinar_id']);
        } elseif (!empty($data['bundle_id'])) {
            return Bundle::query()->findOrFail($data['bundle_id']);
        } elseif (!empty($data['subscribe_id'])) {
            return Subscribe::query()->findOrFail($data['subscribe_id']);
        } elseif (!empty($data['registration_package_id'])) {
            return RegistrationPackage::query()->findOrFail($data['registration_package_id']);
        }
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_edit');

        $specialOffer = SpecialOffer::findOrFail($id);

        $subscribes = Subscribe::query()->get();
        $registrationPackages = RegistrationPackage::query()->where('status', 'active')->get();

        $data = [
            'pageTitle' => trans('lms/admin/main.edit_product_discount_title'),
            'specialOffer' => $specialOffer,
            'subscribes' => $subscribes,
            'registrationPackages' => $registrationPackages,
        ];

        return view('lms.admin.financial.special_offers.new', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_edit');

        $this->validate($request, [
            'percent' => 'required',
            'status' => 'nullable|in:active,inactive',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $specialOffer = SpecialOffer::findOrfail($id);
        $data = $request->all();

        $fromDate = convertTimeToUTCzone($data['from_date'], getTimezone());
        $toDate = convertTimeToUTCzone($data['to_date'], getTimezone());

        $specialOffer->update([
            'creator_id' => auth()->guard('lms_user')->user()->id,
            'name' => $data["name"],
            'webinar_id' => $data["webinar_id"] ?? null,
            'bundle_id' => $data["bundle_id"] ?? null,
            'subscribe_id' => $data["subscribe_id"] ?? null,
            'registration_package_id' => $data["registration_package_id"] ?? null,
            'percent' => $data["percent"],
            'status' => $data["status"],
            'created_at' => time(),
            'from_date' => $fromDate->getTimestamp(),
            'to_date' => $toDate->getTimestamp(),
        ]);

        return redirect('/lms/admin/financial/special_offers');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_product_discount_delete');

        SpecialOffer::findOrfail($id)->delete();

        return redirect()->back();
    }
}
