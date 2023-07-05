<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Category;
use App\Models\LMS\Filter;
use App\Models\LMS\FilterOption;
use App\Models\LMS\FilterOptionTranslation;
use App\Models\LMS\FilterTranslation;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index()
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_filters_list');

        $filters = Filter::with('category')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.filters_list_page_title'),
            'filters' => $filters
        ];

        return view('lms.admin.filters.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_filters_create');

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('lms/admin/main.filter_new_page_title'),
            'categories' => $categories
        ];

        return view('lms.admin.filters.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_filters_create');

        $this->validate($request, [
            'title' => 'required|min:3|max:128',
            'category_id' => 'required|exists:lms_categories,id',
        ]);

        $data = $request->all();

        $filter = Filter::create([
            'category_id' => $data['category_id'],
        ]);

        FilterTranslation::updateOrCreate([
            'filter_id' => $filter->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);


        $filterOptions = !empty($data['sub_filters']) ? $data['sub_filters'] : [];
        $this->setSubFilters($filter, $filterOptions, $data['locale']);

        removeContentLocale();

        return redirect('/lms/admin/filters');
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_filters_edit');

        $filter = Filter::findOrFail($id);
        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $filterOptions = FilterOption::where('filter_id', $filter->id)
            ->orderBy('order', 'asc')
            ->get();

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $filter->getTable(), $filter->id);

        $data = [
            'pageTitle' => trans('lms/admin/main.admin_filters_edit'),
            'filter' => $filter,
            'categories' => $categories,
            'filterOptions' => $filterOptions,
        ];

        return view('lms.admin.filters.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_filters_edit');

        $this->validate($request, [
            'title' => 'required|min:3|max:128',
            'category_id' => 'required|exists:lms_categories,id',
        ]);

        $data = $request->all();

        $filter = Filter::findOrFail($id);
        $filter->update([
            'category_id' => $data['category_id'],
        ]);

        FilterTranslation::updateOrCreate([
            'filter_id' => $filter->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        $filterOptions = !empty($data['sub_filters']) ? $data['sub_filters'] : [];
        $this->setSubFilters($filter, $filterOptions, $data['locale']);

        removeContentLocale();

        return back();
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_filters_delete');

        Filter::find($id)->delete();

        removeContentLocale();

        return redirect('/lms/admin/filters');
    }

    public function setSubFilters(Filter $filter, $filterOptions, $locale)
    {

        $allFilterOptionsIds = $filter->options->pluck('id')->toArray();

        if (!empty($filterOptions) and count($filterOptions)) {
            $order = 1;

            foreach ($filterOptions as $key => $filterOption) {
                if (!empty($filterOption['title'])) {
                    $oldFilterOption = FilterOption::where('filter_id', $filter->id)
                        ->where('id', $key)
                        ->first();

                    if (!empty($oldFilterOption)) {

                        $oldIdsSearch = array_search($key, $allFilterOptionsIds);

                        if ($oldIdsSearch !== -1) {
                            unset($allFilterOptionsIds[$oldIdsSearch]);
                        }

                        $oldFilterOption->update([
                            'order' => $order,
                        ]);

                        FilterOptionTranslation::updateOrCreate([
                            'filter_option_id' => $oldFilterOption->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $filterOption['title'],
                        ]);
                    } else {
                        $option = FilterOption::create([
                            'filter_id' => $filter->id,
                            'order' => $order,
                        ]);

                        FilterOptionTranslation::updateOrCreate([
                            'filter_option_id' => $option->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $filterOption['title'],
                        ]);
                    }

                    $order += 1;
                }
            }
        }

        if (!empty($allFilterOptionsIds) and count($allFilterOptionsIds)) {
            FilterOption::whereIn('id', $allFilterOptionsIds)->delete();
        }
    }

    public function getByCategoryId($categoryId)
    {
        $filters = Filter::where('category_id', $categoryId)
            ->with([
                'options' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ])
            ->get();

        return response()->json([
            'filters' => $filters,
        ], 200);
    }
}
