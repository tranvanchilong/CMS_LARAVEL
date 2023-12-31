<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Promotion;
use App\Models\LMS\Sale;
use App\Models\LMS\PromotionTranslation;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_list');

        removeContentLocale();

        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.promotions'),
            'promotions' => $promotions
        ];

        return view('lms.admin.financial.promotions.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_create');

        removeContentLocale();

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.new_promotion')
        ];

        return view('lms.admin.financial.promotions.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_create');

        $this->validate($request, [
            'title' => 'required|string',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
            'description' => 'required|string',
        ]);

        $data = $request->all();

        $promotion = Promotion::create([
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'is_popular' => $data['is_popular'],
            'created_at' => time(),
        ]);

        if (!empty($promotion)) {
            PromotionTranslation::updateOrCreate([
                'promotion_id' => $promotion->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
            ]);
        }

        return redirect('/lms/admin/financial/promotions');
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_edit');

        $promotion = Promotion::findOrFail($id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $promotion->getTable(), $promotion->id);


        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.edit_promotion'),
            'promotion' => $promotion
        ];

        return view('lms.admin.financial.promotions.new', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_create');

        $this->validate($request, [
            'title' => 'required|string',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
            'description' => 'required|string',
        ]);

        $promotion = Promotion::findOrFail($id);

        $data = $request->all();

        $promotion->update([
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'is_popular' => $data['is_popular'],
            'created_at' => time(),
        ]);

        PromotionTranslation::updateOrCreate([
            'promotion_id' => $promotion->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        removeContentLocale();

        return redirect('/lms/admin/financial/promotions');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_delete');

        $promotion = Promotion::findOrFail($id);

        $promotion->delete();

        return redirect('/lms/admin/financial/promotions');
    }

    public function sales(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_promotion_list');

        $promotionSales = Sale::where('type', Sale::$promotion)
            ->whereNull('refund_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.promotion_sales'),
            'promotionSales' => $promotionSales
        ];

        return view('lms.admin.financial.promotions.promotion_sales', $data);
    }
}
