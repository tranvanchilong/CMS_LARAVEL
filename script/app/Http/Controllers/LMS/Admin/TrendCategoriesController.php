<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Category;
use App\Models\LMS\TrendCategory;
use Illuminate\Http\Request;

class TrendCategoriesController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_trending_categories');

        $trends = TrendCategory::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/home.trending_categories'),
            'trends' => $trends,
        ];

        return view('lms.admin.categories.trends_lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_create_trending_categories');

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('lms/admin/pages/categories.new_trend'),
            'categories' => $categories
        ];

        return view('lms.admin.categories.create_trend', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_create_trending_categories');

        $this->validate($request, [
            'category_id' => 'required',
            'icon' => 'required',
            'color' => 'required',
        ]);

        $data = $request->all();

        TrendCategory::create([
            'category_id' => $data['category_id'],
            'icon' => $data['icon'],
            'color' => $data['color'],
            'created_at' => time(),
        ]);

        return redirect('/lms/admin/categories/trends');
    }

    public function edit($trend_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_edit_trending_categories');

        $trend = TrendCategory::findOrFail($trend_id);

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('lms/admin/pages/categories.new_trend'),
            'categories' => $categories,
            'trend' => $trend
        ];

        return view('lms.admin.categories.create_trend', $data);
    }

    public function update(Request $request, $trend_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_create_trending_categories');

        $this->validate($request, [
            'category_id' => 'required',
            'icon' => 'required',
            'color' => 'required',
        ]);

        $trend = TrendCategory::findOrFail($trend_id);
        $data = $request->all();

        $trend->update([
            'category_id' => $data['category_id'],
            'icon' => $data['icon'],
            'color' => $data['color'],
        ]);

        return redirect('/lms/admin/categories/trends');
    }

    public function destroy($trend_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_delete_trending_categories');

        $trend = TrendCategory::findOrFail($trend_id);

        $trend->delete();

        return redirect('/lms/admin/categories/trends');
    }
}
