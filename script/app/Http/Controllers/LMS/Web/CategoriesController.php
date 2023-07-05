<?php

namespace App\Http\Controllers\LMS\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Bundle;
use App\Models\LMS\Category;
use App\Models\LMS\FeatureWebinar;
use App\Models\LMS\Sale;
use App\Models\LMS\Ticket;
use App\Models\LMS\CategoryTranslation;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarFilterOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function index(Request $request, $categorySlug, $subCategorySlug = null)
    {

        if (!empty($categorySlug)) {

            $categoryQuery = Category::query()->where('slug', $categorySlug);

            if (!empty($subCategorySlug)) {
                $categoryQuery = Category::query()->where('slug', $subCategorySlug);
            }

            $category = $categoryQuery->withCount('webinars')
                ->with(['filters' => function ($query) {
                    $query->with('options');
                }])->first();

            if (!empty($category)) {
                $categoryIds = [$category->id];

                if (!empty($category->subCategories) and count($category->subCategories)) {
                    $categoryIds = array_merge($categoryIds, $category->subCategories->pluck('id')->toArray());
                }

                $featureWebinars = FeatureWebinar::whereIn('page', ['categories', 'home_categories'])
                    ->where('status', 'publish')
                    ->whereHas('webinar', function ($q) use ($categoryIds) {
                        $q->where('status', Webinar::$active);
                        $q->whereHas('category', function ($q) use ($categoryIds) {
                            $q->whereIn('id', $categoryIds);
                        });
                    })
                    ->with(['webinar' => function ($query) {
                        $query->with(['teacher' => function ($qu) {
                            $qu->select('id', 'full_name', 'avatar');
                        }, 'reviews', 'tickets', 'feature']);
                    }])
                    ->orderBy('updated_at', 'desc')
                    ->get();


                $webinarsQuery = Webinar::where('lms_webinars.status', 'active')
                    ->where('private', false)
                    ->whereIn('category_id', $categoryIds);

                $classesController = new ClassesController();
                $moreOptions = $request->get('moreOptions');
                $tableName = 'lms_webinars';

                if (!empty($moreOptions) and is_array($moreOptions) and in_array('bundles', $moreOptions)) {
                    $webinarsQuery = Bundle::where('lms_bundles.status', 'active')
                        ->whereIn('category_id', $categoryIds);

                    $tableName = 'lms_bundles';
                    $classesController->tableName = 'lms_bundles';
                    $classesController->columnId = 'bundle_id';
                }

                $webinarsQuery = $classesController->handleFilters($request, $webinarsQuery);

                $sort = $request->get('sort', null);

                if (empty($sort)) {
                    $webinarsQuery = $webinarsQuery->orderBy("{$tableName}.created_at", 'desc');
                }

                $webinars = $webinarsQuery->with(['tickets'])
                    ->paginate(6);

                $seoSettings = getSeoMetas('categories');
                $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('lms/site.categories_page_title');
                $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('lms/site.categories_page_title');
                $pageRobot = getPageRobot('categories');

                $data = [
                    'pageTitle' => $pageTitle,
                    'pageDescription' => $pageDescription,
                    'pageRobot' => $pageRobot,
                    'category' => $category,
                    'webinars' => $webinars,
                    'featureWebinars' => $featureWebinars,
                    'webinarsCount' => $webinars->total(),
                    'sortFormAction' => $category->getUrl(),
                ];

                return view('lms.'. getTemplate() . '.pages.categories', $data);
            }
        }

        abort(404);
    }
}
