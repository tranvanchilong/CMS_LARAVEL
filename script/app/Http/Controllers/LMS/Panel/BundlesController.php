<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Exports\WebinarStudents;
use App\Http\Controllers\LMS\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\LMS\Bundle;
use App\Models\LMS\BundleFilterOption;
use App\Models\LMS\Category;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\Tag;
use App\Models\LMS\BundleTranslation;
use App\Models\LMS\WebinarTranslation;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarFilterOption;
use App\Models\LMS\WebinarPartnerTeacher;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BundlesController extends Controller
{
    public function index()
    {
        $user = auth()->guard('lms_user')->user();

        if ($user->isUser()) {
            abort(404);
        }

        $query = Bundle::where(function ($query) use ($user) {
            $query->where('lms_bundles.teacher_id', $user->id);
            $query->orWhere('lms_bundles.creator_id', $user->id);
        });

        $bundlesHours = deepClone($query)->join('lms_bundle_webinars', 'lms_bundle_webinars.bundle_id', 'lms_bundles.id')
            ->join('lms_webinars', 'lms_webinars.id', 'lms_bundle_webinars.webinar_id')
            ->select('lms_bundles.*', DB::raw('sum(lms_webinars.duration) as duration'))
            ->sum('duration');

        $query->with([
            /*'reviews' => function ($query) {
                $query->where('status', 'active');
            },*/
            'bundleWebinars',
            'category',
            'teacher',
            'sales' => function ($query) {
                $query->where('type', 'bundle')
                    ->whereNull('refund_at');
            }
        ])->orderBy('updated_at', 'desc');

        $bundlesCount = $query->count();

        $bundles = $query->paginate(10);

        $bundleSales = Sale::where('seller_id', $user->id)
            ->where('type', 'bundle')
            ->whereNotNull('bundle_id')
            ->whereNull('refund_at')
            ->get();

        $data = [
            'pageTitle' => trans('lms/update.my_bundles'),
            'bundles' => $bundles,
            'bundlesCount' => $bundlesCount,
            'bundleSalesAmount' => $bundleSales->sum('amount'),
            'bundleSalesCount' => $bundleSales->count(),
            'bundlesHours' => $bundlesHours,
        ];

        return view('lms.web.default.panel.bundle.index', $data);
    }

    public function create()
    {
        $user = auth()->guard('lms_user')->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $teachers = null;
        $isOrganization = $user->isOrganization();

        if ($isOrganization) {
            $teachers = User::where('role_name', Role::$teacher)
                ->where('organ_id', $user->id)->get();
        }


        $data = [
            'pageTitle' => trans('lms/update.new_bundle'),
            'teachers' => $teachers,
            'categories' => $categories,
            'isOrganization' => $isOrganization,
            'currentStep' => 1,
            'userLanguages' => getUserLanguagesLists(),
        ];

        return view('lms.web.default.panel.bundle.create', $data);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('lms_user')->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }

        $rules = [
            'title' => 'required|max:255',
            'thumbnail' => 'required',
            'image_cover' => 'required',
            'description' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        if (empty($data['video_demo'])) {
            $data['video_demo_source'] = null;
        }

        if (!empty($data['video_demo_source']) and !in_array($data['video_demo_source'], ['upload', 'youtube', 'vimeo', 'external_link'])) {
            $data['video_demo_source'] = 'upload';
        }

        $bundle = Bundle::create([
            'teacher_id' => $user->isTeacher() ? $user->id : (!empty($data['teacher_id']) ? $data['teacher_id'] : $user->id),
            'creator_id' => $user->id,
            'slug' => Bundle::makeSlug($data['title']),
            'thumbnail' => $data['thumbnail'],
            'image_cover' => $data['image_cover'],
            'video_demo' => $data['video_demo'],
            'video_demo_source' => $data['video_demo'] ? $data['video_demo_source'] : null,
            'status' => ((!empty($data['draft']) and $data['draft'] == 1) or (!empty($data['get_next']) and $data['get_next'] == 1)) ? Bundle::$isDraft : Bundle::$pending,
            'created_at' => time(),
        ]);

        if ($bundle) {
            BundleTranslation::updateOrCreate([
                'bundle_id' => $bundle->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'seo_description' => $data['seo_description'],
            ]);
        }

        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[item_title]' => $bundle->title,
            '[content_type]' => trans('lms/update.bundle'),
        ];
        sendNotification("bundle_submission", $notifyOptions, $user->id);
        sendNotification("bundle_submission_for_admin", $notifyOptions, 1);
        sendNotification("new_item_created", $notifyOptions, 1);

        $url = '/lms/panel/bundles';
        if ($data['get_next'] == 1) {
            $url = '/lms/panel/bundles/' . $bundle->id . '/step/2';
        }

        return redirect($url);
    }

    public function edit(Request $request, $id, $step = 1)
    {
        $user = auth()->guard('lms_user')->user();
        $isOrganization = $user->isOrganization();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }
        $locale = $request->get('locale', app()->getLocale());

        $data = [
            'pageTitle' => trans('lms/update.new_bundle_page_title_step', ['step' => $step]),
            'currentStep' => $step,
            'isOrganization' => $isOrganization,
            'userLanguages' => getUserLanguagesLists(),
            'locale' => mb_strtolower($locale),
            'defaultLocale' => getDefaultLocale(),
        ];

        $query = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            });

        if ($step == '1') {
            $data['teachers'] = $user->getOrganizationTeachers()->get();
        } elseif ($step == 2) {
            $query->with([
                'category' => function ($query) {
                    $query->with([
                        'filters' => function ($query) {
                            $query->with('options');
                        }
                    ]);
                },
                'filterOptions',
                'tags',
            ]);

            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $data['categories'] = $categories;
        } elseif ($step == 3) {
            $query->with([
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ]);
        } elseif ($step == 4) {
            $query->with([
                'bundleWebinars' => function ($query) {
                    $query->with([
                        'webinar'
                    ]);
                    $query->orderBy('order', 'asc');
                }
            ]);
        } elseif ($step == 5) {
            $query->with([
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ]);
        }

        $bundle = $query->first();

        if (empty($bundle)) {
            abort(404);
        }

        $data['bundle'] = $bundle;

        $data['pageTitle'] = trans('lms/public.edit') . ' ' . $bundle->title;

        $definedLanguage = [];
        if ($bundle->translations) {
            $definedLanguage = $bundle->translations->pluck('locale')->toArray();
        }

        $data['definedLanguage'] = $definedLanguage;

        if ($step == 2) {
            $data['bundleTags'] = $bundle->tags->pluck('title')->toArray();

            $bundleCategoryFilters = !empty($bundle->category) ? $bundle->category->filters : [];

            if (empty($bundle->category) and !empty($request->old('category_id'))) {
                $category = Category::where('id', $request->old('category_id'))->first();

                if (!empty($category)) {
                    $bundleCategoryFilters = $category->filters;
                }
            }

            $data['bundleCategoryFilters'] = $bundleCategoryFilters;
        } elseif ($step == 4) {
            $data['webinars'] = Webinar::select('id', 'creator_id', 'teacher_id')
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->get();
        }

        return view('lms.web.default.panel.bundle.create', $data);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }

        $rules = [];
        $data = $request->all();
        $currentStep = $data['current_step'];
        $getStep = $data['get_step'];
        $getNextStep = (!empty($data['get_next']) and $data['get_next'] == 1);
        $isDraft = (!empty($data['draft']) and $data['draft'] == 1);

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (empty($bundle)) {
            abort(404);
        }

        if ($currentStep == 1) {
            $rules = [
                'title' => 'required|max:255',
                'thumbnail' => 'required',
                'image_cover' => 'required',
                'description' => 'required',
            ];
        }

        if ($currentStep == 2) {
            $rules = [
                'category_id' => 'required',
            ];
        }

        $bundleRulesRequired = false;
        if (($currentStep == 6 and !$getNextStep and !$isDraft) or (!$getNextStep and !$isDraft)) {
            $bundleRulesRequired = empty($data['rules']);
        }

        $this->validate($request, $rules);


        $data['status'] = ($isDraft or $bundleRulesRequired) ? Bundle::$isDraft : Bundle::$pending;
        $data['updated_at'] = time();

        if ($currentStep == 1) {
            if (empty($data['video_demo'])) {
                $data['video_demo_source'] = null;
            }

            if (!empty($data['video_demo_source']) and !in_array($data['video_demo_source'], ['upload', 'youtube', 'vimeo', 'external_link'])) {
                $data['video_demo_source'] = 'upload';
            }

            BundleTranslation::updateOrCreate([
                'bundle_id' => $bundle->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'seo_description' => $data['seo_description'],
            ]);
        }

        if ($currentStep == 2) {
            BundleFilterOption::where('bundle_id', $bundle->id)->delete();
            Tag::where('bundle_id', $bundle->id)->delete();

            $filters = $request->get('filters', null);
            if (!empty($filters) and is_array($filters)) {
                foreach ($filters as $filter) {
                    BundleFilterOption::create([
                        'bundle_id' => $bundle->id,
                        'filter_option_id' => $filter
                    ]);
                }
            }

            if (!empty($request->get('tags'))) {
                $tags = explode(',', $request->get('tags'));

                foreach ($tags as $tag) {
                    Tag::create([
                        'bundle_id' => $bundle->id,
                        'title' => $tag,
                    ]);
                }
            }
        }

        if ($currentStep == 3) {
            $data['subscribe'] = !empty($data['subscribe']) ? true : false;
            $data['price'] = !empty($data['price']) ? convertPriceToDefaultCurrency($data['price']) : null;
        }

        unset($data['_token'],
            $data['current_step'],
            $data['draft'],
            $data['get_next'],
            $data['partners'],
            $data['tags'],
            $data['filters'],
            $data['ajax'],
            $data['title'],
            $data['description'],
            $data['seo_description'],
        );

        if (empty($data['teacher_id']) and $user->isOrganization() and $bundle->creator_id == $user->id) {
            $data['teacher_id'] = $user->id;
        }

        $bundle->update($data);

        $url = '/lms/panel/bundles';
        if ($getNextStep) {
            $nextStep = (!empty($getStep) and $getStep > 0) ? $getStep : $currentStep + 1;

            $url = '/lms/panel/bundles/' . $bundle->id . '/step/' . (($nextStep <= 6) ? $nextStep : 6);
        }

        if ($bundleRulesRequired) {
            $url = '/lms/panel/bundles/' . $bundle->id . '/step/6';

            return redirect($url)->withErrors(['rules' => trans('lms/validation.required', ['attribute' => 'rules'])]);
        }

        if (!$getNextStep and !$isDraft and !$bundleRulesRequired) {
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[item_title]' => $bundle->title,
                '[content_type]' => trans('lms/update.bundle'),
            ];
            sendNotification("content_review_request", $notifyOptions, 1);
        }

        return redirect($url);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!$bundle) {
            abort(404);
        }

        $bundle->delete();

        return response()->json([
            'code' => 200,
            'redirect_to' => $request->get('redirect_to')
        ], 200);
    }

    public function getContentItemByLocale(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'locale' => 'required',
            'relation' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->guard('lms_user')->user();

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($bundle)) {

            $itemId = $data['item_id'];
            $locale = $data['locale'];
            $relation = $data['relation'];

            if (!empty($bundle->$relation)) {
                $item = $bundle->$relation->where('id', $itemId)->first();

                if (!empty($item)) {
                    foreach ($item->translatedAttributes as $attribute) {
                        try {
                            $item->$attribute = $item->translate(mb_strtolower($locale))->$attribute;
                        } catch (\Exception $e) {
                            $item->$attribute = null;
                        }
                    }

                    return response()->json([
                        'item' => $item
                    ], 200);
                }
            }
        }

        abort(403);
    }

    public function exportStudentsList($id)
    {
        $user = auth()->guard('lms_user')->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($bundle)) {
            $sales = Sale::where('type', 'bundle')
                ->where('bundle_id', $bundle->id)
                ->whereNull('refund_at')
                ->with([
                    'buyer' => function ($query) {
                        $query->select('id', 'full_name', 'email', 'mobile');
                    }
                ])->get();

            if (!empty($sales) and !$sales->isEmpty()) {
                $export = new WebinarStudents($sales);
                return Excel::download($export, trans('lms/panel.users') . '.xlsx');
            }

            $toastData = [
                'title' => trans('lms/public.request_failed'),
                'msg' => trans('lms/webinars.export_list_error_not_student'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function courses($id)
    {
        $user = auth()->guard('lms_user')->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->with([
                'bundleWebinars' => function ($query) {
                    $query->with([
                        'webinar'
                    ]);
                    $query->orderBy('order', 'asc');
                }
            ])
            ->first();

        if (!empty($bundle)) {

            $data = [
                'pageTitle' => trans('lms/product.courses'),
                'bundle' => $bundle
            ];

            return view('lms.web.default.panel.bundle.courses', $data);
        }

        abort(404);
    }
}
