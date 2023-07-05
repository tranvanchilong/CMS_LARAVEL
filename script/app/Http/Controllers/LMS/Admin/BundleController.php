<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Http\Controllers\LMS\Panel\WebinarStatisticController;
use App\Mail\SendNotifications;
use App\Models\LMS\Bundle;
use App\Models\LMS\BundleFilterOption;
use App\Models\LMS\Category;
use App\Models\LMS\Gift;
use App\Models\LMS\Group;
use App\Models\LMS\GroupUser;
use App\Models\LMS\Notification;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\SpecialOffer;
use App\Models\LMS\Tag;
use App\Models\LMS\Ticket;
use App\Models\LMS\BundleTranslation;
use App\Models\LMS\Webinar;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_bundles_list');

        removeContentLocale();

        $query = Bundle::query();

        $totalBundles = $query->count();
        $totalPendingBundles = deepClone($query)->where('lms_bundles.status', Bundle::$pending)->count();
        $totalSales = deepClone($query)->join('lms_sales', 'lms_bundles.id', '=', 'lms_sales.bundle_id')
            ->select(DB::raw('count(lms_sales.bundle_id) as sales_count, sum(total_amount) as total_amount'))
            ->whereNotNull('lms_sales.bundle_id')
            ->whereNull('lms_sales.refund_at')
            ->first();

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $query = $this->handleFilters($query, $request)
            ->with([
                'category',
                'teacher' => function ($qu) {
                    $qu->select('id', 'full_name');
                },
                'sales' => function ($query) {
                    $query->whereNull('refund_at');
                }
            ])
            ->withCount([
                'bundleWebinars'
            ]);

        $bundles = $query->paginate(10);
        
        foreach ($bundles as $bundle) {
            $giftsIds = Gift::query()->where('bundle_id', $bundle->id)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('date');
                    $query->orWhere('date', '<', time());
                })
                ->whereHas('sale')
                ->pluck('id')
                ->toArray();

            $sales = Sale::query()
                ->where(function ($query) use ($bundle, $giftsIds) {
                    $query->where('bundle_id', $bundle->id);
                    $query->orWhereIn('gift_id', $giftsIds);
                })
                ->whereNull('refund_at')
                ->get();

            $bundle->sales = $sales;
        }

        $data = [
            'pageTitle' => trans('lms/update.bundles'),
            'bundles' => $bundles,
            'totalBundles' => $totalBundles,
            'totalPendingBundles' => $totalPendingBundles,
            'totalSales' => $totalSales,
            'categories' => $categories,
        ];

        $teacher_ids = $request->get('teacher_ids', null);
        if (!empty($teacher_ids)) {
            $data['teachers'] = User::select('id', 'full_name')->whereIn('id', $teacher_ids)->get();
        }

        return view('lms.admin.bundles.lists', $data);
    }

    private function handleFilters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $teacher_ids = $request->get('teacher_ids', null);
        $category_id = $request->get('category_id', null);
        $status = $request->get('status', null);
        $sort = $request->get('sort', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($title)) {
            $query->whereTranslationLike('title', '%' . $title . '%');
        }

        if (!empty($teacher_ids) and count($teacher_ids)) {
            $query->whereIn('teacher_id', $teacher_ids);
        }

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($status)) {
            $query->where('lms_bundles.status', $status);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'has_discount':
                    $now = time();
                    $bundleIdsHasDiscount = [];

                    $tickets = Ticket::where('start_date', '<', $now)
                        ->where('end_date', '>', $now)
                        ->get();

                    foreach ($tickets as $ticket) {
                        if ($ticket->isValid()) {
                            $bundleIdsHasDiscount[] = $ticket->bundle_id;
                        }
                    }

                    $specialOffersBundleIds = SpecialOffer::where('status', 'active')
                        ->where('from_date', '<', $now)
                        ->where('to_date', '>', $now)
                        ->pluck('bundle_id')
                        ->toArray();

                    $bundleIdsHasDiscount = array_merge($specialOffersBundleIds, $bundleIdsHasDiscount);

                    $query->whereIn('id', $bundleIdsHasDiscount)
                        ->orderBy('created_at', 'desc');
                    break;
                case 'sales_asc':
                    $query->join('lms_sales', 'lms_bundles.id', '=', 'lms_sales.bundle_id')
                        ->select('lms_bundles.*', 'lms_sales.bundle_id', 'lms_sales.refund_at', DB::raw('count(lms_sales.bundle_id) as sales_count'))
                        ->whereNotNull('lms_sales.bundle_id')
                        ->whereNull('lms_sales.refund_at')
                        ->groupBy('lms_sales.bundle_id')
                        ->orderBy('sales_count', 'asc');
                    break;
                case 'sales_desc':
                    $query->join('lms_sales', 'lms_bundles.id', '=', 'lms_sales.bundle_id')
                        ->select('lms_bundles.*', 'lms_sales.bundle_id', 'lms_sales.refund_at', DB::raw('count(lms_sales.bundle_id) as sales_count'))
                        ->whereNotNull('lms_sales.bundle_id')
                        ->whereNull('lms_sales.refund_at')
                        ->groupBy('lms_sales.bundle_id')
                        ->orderBy('sales_count', 'desc');
                    break;

                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;

                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;

                case 'income_asc':
                    $query->join('lms_sales', 'lms_bundles.id', '=', 'lms_sales.bundle_id')
                        ->select('lms_bundles.*', 'lms_sales.bundle_id', 'lms_sales.total_amount', 'lms_sales.refund_at', DB::raw('(sum(lms_sales.total_amount) - (sum(lms_sales.tax) + sum(lms_sales.commission))) as amounts'))
                        ->whereNotNull('lms_sales.bundle_id')
                        ->whereNull('lms_sales.refund_at')
                        ->groupBy('lms_sales.bundle_id')
                        ->orderBy('amounts', 'asc');
                    break;

                case 'income_desc':
                    $query->join('lms_sales', 'lms_bundles.id', '=', 'lms_sales.bundle_id')
                        ->select('lms_bundles.*', 'lms_sales.bundle_id', 'lms_sales.total_amount', 'lms_sales.refund_at', DB::raw('(sum(lms_sales.total_amount) - (sum(lms_sales.tax) + sum(lms_sales.commission))) as amounts'))
                        ->whereNotNull('lms_sales.bundle_id')
                        ->whereNull('lms_sales.refund_at')
                        ->groupBy('lms_sales.bundle_id')
                        ->orderBy('amounts', 'desc');
                    break;

                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;

                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }


        return $query;
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_bundles_create');

        removeContentLocale();

        $categories = Category::where('parent_id', null)->get();

        $data = [
            'pageTitle' => trans('lms/update.new_bundle'),
            'categories' => $categories
        ];

        return view('lms.admin.bundles.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_bundles_create');

        $this->validate($request, [
            'title' => 'required|max:255',
            'slug' => 'max:255|unique:lms_bundles,slug',
            'thumbnail' => 'required',
            'image_cover' => 'required',
            'description' => 'required',
            'teacher_id' => 'required|exists:lms_users,id',
            'category_id' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Webinar::makeSlug($data['title']);
        }

        if (empty($data['video_demo'])) {
            $data['video_demo_source'] = null;
        }

        if (!empty($data['video_demo_source']) and !in_array($data['video_demo_source'], ['upload', 'youtube', 'vimeo', 'external_link'])) {
            $data['video_demo_source'] = 'upload';
        }

        $bundle = Bundle::create([
            'slug' => $data['slug'],
            'teacher_id' => $data['teacher_id'],
            'creator_id' => $data['teacher_id'],
            'thumbnail' => $data['thumbnail'],
            'image_cover' => $data['image_cover'],
            'video_demo' => $data['video_demo'],
            'video_demo_source' => $data['video_demo'] ? $data['video_demo_source'] : null,
            'subscribe' => !empty($data['subscribe']) ? true : false,
            'points' => $data['points'] ?? null,
            'price' => $data['price'],
            'access_days' => $data['access_days'] ?? null,
            'category_id' => $data['category_id'],
            'message_for_reviewer' => $data['message_for_reviewer'] ?? null,
            'status' => Bundle::$pending,
            'created_at' => time(),
            'updated_at' => time(),
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

        $filters = $request->get('filters', null);
        if (!empty($filters) and is_array($filters)) {
            BundleFilterOption::where('bundle_id', $bundle->id)->delete();

            foreach ($filters as $filter) {
                BundleFilterOption::create([
                    'bundle_id' => $bundle->id,
                    'filter_option_id' => $filter
                ]);
            }
        }

        if (!empty($request->get('tags'))) {
            $tags = explode(',', $request->get('tags'));
            Tag::where('bundle_id', $bundle->id)->delete();

            foreach ($tags as $tag) {
                Tag::create([
                    'bundle_id' => $bundle->id,
                    'title' => $tag,
                ]);
            }
        }

        return redirect('/lms/admin/bundles/' . $bundle->id . '/edit?locale=' . $data['locale']);
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_bundles_edit');

        $bundle = Bundle::where('id', $id)
            ->with([
                'tickets',
                'faqs',
                'category' => function ($query) {
                    $query->with(['filters' => function ($query) {
                        $query->with('options');
                    }]);
                },
                'tags',
                'bundleWebinars'
            ])
            ->first();

        if (empty($bundle)) {
            abort(404);
        }

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $bundle->getTable(), $bundle->id);

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $tags = $bundle->tags->pluck('title')->toArray();

        $userIds = [$bundle->creator_id, $bundle->teacher_id];
        $userWebinars = Webinar::select('id', 'creator_id', 'teacher_id')
            ->where('status', Webinar::$active)
            ->where('private', false)
            ->where(function ($query) use ($userIds) {
                $query->whereIn('creator_id', $userIds)
                    ->orWhereIn('teacher_id', $userIds);
            })
            ->get();

        $data = [
            'pageTitle' => trans('lms/admin/main.edit') . ' | ' . $bundle->title,
            'userWebinars' => $userWebinars,
            'categories' => $categories,
            'bundle' => $bundle,
            'bundleCategoryFilters' => $bundle->category->filters,
            'bundleFilterOptions' => $bundle->filterOptions->pluck('filter_option_id')->toArray(),
            'tickets' => $bundle->tickets,
            'faqs' => $bundle->faqs,
            'bundleTags' => $tags,
            'bundleWebinars' => $bundle->bundleWebinars,
        ];

        return view('lms.admin.bundles.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_bundles_edit');
        $data = $request->all();

        $bundle = Bundle::find($id);
        $isDraft = (!empty($data['draft']) and $data['draft'] == 1);
        $reject = (!empty($data['draft']) and $data['draft'] == 'reject');
        $publish = (!empty($data['draft']) and $data['draft'] == 'publish');

        $rules = [
            'title' => 'required|max:255',
            'slug' => 'max:255|unique:lms_bundles,slug,' . $bundle->id,
            'thumbnail' => 'required',
            'image_cover' => 'required',
            'description' => 'required',
            'teacher_id' => 'required|exists:lms_users,id',
            'category_id' => 'required',
        ];

        $this->validate($request, $rules);

        if (!empty($data['teacher_id'])) {
            $teacher = User::findOrFail($data['teacher_id']);
            $creator = $bundle->creator;

            if (empty($teacher) or ($creator->isOrganization() and ($teacher->organ_id != $creator->id and $teacher->id != $creator->id))) {
                $toastData = [
                    'title' => trans('lms/public.request_failed'),
                    'msg' => trans('lms/admin/main.is_not_the_teacher_of_this_organization'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
        }


        if (empty($data['slug'])) {
            $data['slug'] = Bundle::makeSlug($data['title']);
        }

        $data['status'] = $publish ? Bundle::$active : ($reject ? Bundle::$inactive : ($isDraft ? Bundle::$isDraft : Bundle::$pending));
        $data['updated_at'] = time();
        $data['subscribe'] = !empty($data['subscribe']) ? true : false;

        if ($data['category_id'] != $bundle->category_id) {
            BundleFilterOption::where('bundle_id', $bundle->id)->delete();
        }

        $filters = $request->get('filters', null);
        if (!empty($filters) and is_array($filters)) {
            BundleFilterOption::where('bundle_id', $bundle->id)->delete();

            foreach ($filters as $filter) {
                BundleFilterOption::create([
                    'bundle_id' => $bundle->id,
                    'filter_option_id' => $filter
                ]);
            }
        }

        if (!empty($request->get('tags'))) {
            $tags = explode(',', $request->get('tags'));
            Tag::where('bundle_id', $bundle->id)->delete();

            foreach ($tags as $tag) {
                Tag::create([
                    'bundle_id' => $bundle->id,
                    'title' => $tag,
                ]);
            }
        }

        unset($data['_token'],
            $data['current_step'],
            $data['draft'],
            $data['get_next'],
            $data['partners'],
            $data['tags'],
            $data['filters'],
            $data['ajax']
        );

        if (empty($data['video_demo'])) {
            $data['video_demo_source'] = null;
        }

        if (!empty($data['video_demo_source']) and !in_array($data['video_demo_source'], ['upload', 'youtube', 'vimeo', 'external_link'])) {
            $data['video_demo_source'] = 'upload';
        }

        $bundle->update([
            'slug' => $data['slug'],
            'teacher_id' => $data['teacher_id'],
            'thumbnail' => $data['thumbnail'],
            'image_cover' => $data['image_cover'],
            'video_demo' => $data['video_demo'],
            'video_demo_source' => $data['video_demo'] ? $data['video_demo_source'] : null,
            'subscribe' => $data['subscribe'],
            'points' => $data['points'] ?? null,
            'price' => $data['price'],
            'access_days' => $data['access_days'] ?? null,
            'category_id' => $data['category_id'],
            'message_for_reviewer' => $data['message_for_reviewer'] ?? null,
            'status' => $data['status'],
            'updated_at' => time(),
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

        if ($publish) {
            /*sendNotification('course_approve', ['[c.title]' => $bundle->title], $bundle->teacher_id);

            $createClassesReward = RewardAccounting::calculateScore(Reward::CREATE_CLASSES);
            RewardAccounting::makeRewardAccounting(
                $bundle->creator_id,
                $createClassesReward,
                Reward::CREATE_CLASSES,
                $bundle->id,
                true
            );*/

        } elseif ($reject) {
            //sendNotification('course_reject', ['[c.title]' => $bundle->title], $bundle->teacher_id);
        }

        removeContentLocale();

        return back();
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_bundles_delete');

        Webinar::find($id)->delete();

        return redirect('/lms/admin/webinars');
    }

    public function studentsLists(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinar_students_lists');

        $bundle = Bundle::where('id', $id)
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'full_name');
                }
            ])
            ->first();


        if (!empty($bundle)) {

            $query = User::join('lms_sales', 'lms_sales.buyer_id', 'lms_users.id')
                ->leftJoin('lms_webinar_reviews', function ($query) use ($bundle) {
                    $query->on('lms_webinar_reviews.creator_id', 'lms_users.id')
                        ->where('lms_webinar_reviews.bundle_id', $bundle->id);
                })
                ->select('lms_users.*', 'lms_webinar_reviews.rates', DB::raw('lms_sales.created_at as purchase_date'))
                ->where('lms_sales.bundle_id', $bundle->id)
                ->whereNull('lms_sales.refund_at');

            $students = $this->studentsListsFilters($bundle, $query, $request)
                ->orderBy('lms_sales.created_at', 'desc')
                ->paginate(10);

            $userGroups = Group::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            $totalExpireStudents = 0;
            if (!empty($bundle->access_days)) {
                $accessTimestamp = $bundle->access_days * 24 * 60 * 60;

                $totalExpireStudents = User::join('lms_sales', 'lms_sales.buyer_id', 'lms_users.id')
                    ->select('lms_users.*', DB::raw('lms_sales.created_at as purchase_date'))
                    ->where('lms_sales.bundle_id', $bundle->id)
                    ->whereRaw('lms_sales.created_at + ? < ?', [$accessTimestamp, time()])
                    ->whereNull('lms_sales.refund_at')
                    ->count();
            }

            $bundleWebinars = $bundle->bundleWebinars;

            $webinarStatisticController = new WebinarStatisticController();

            foreach ($students as $student) {
                $learnings = 0;
                $webinarCount = 0;

                foreach ($bundleWebinars as $bundleWebinar) {
                    if (!empty($bundleWebinar->webinar)) {
                        $webinarCount += 1;
                        $learnings += $webinarStatisticController->getCourseProgressForStudent($bundleWebinar->webinar, $student->id);
                    }
                }

                $student->learning = ($learnings > 0 and $webinarCount > 0) ? round($learnings / $webinarCount,2) : 0;
            }

            $roles = Role::all();

            $data = [
                'pageTitle' => trans('lms/admin/main.students'),
                'bundle' => $bundle,
                'students' => $students,
                'userGroups' => $userGroups,
                'roles' => $roles,
                'totalStudents' => $students->total(),
                'totalActiveStudents' => $students->total() - $totalExpireStudents,
                'totalExpireStudents' => $totalExpireStudents,
            ];

            return view('lms.admin.bundles.students', $data);
        }

        abort(404);
    }

    private function studentsListsFilters($bundle, $query, $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $full_name = $request->get('full_name');
        $sort = $request->get('sort');
        $group_id = $request->get('group_id');
        $role_id = $request->get('role_id');
        $status = $request->get('status');

        $query = fromAndToDateFilter($from, $to, $query, 'lms_sales.created_at');

        if (!empty($full_name)) {
            $query->where('lms_users.full_name', 'like', "%$full_name%");
        }

        if (!empty($sort)) {
            if ($sort == 'rate_asc') {
                $query->orderBy('lms_webinar_reviews.rates', 'asc');
            }

            if ($sort == 'rate_desc') {
                $query->orderBy('lms_webinar_reviews.rates', 'desc');
            }
        }

        if (!empty($group_id)) {
            $userIds = GroupUser::where('group_id', $group_id)->pluck('user_id')->toArray();

            $query->whereIn('lms_users.id', $userIds);
        }

        if (!empty($role_id)) {
            $query->where('lms_users.role_id', $role_id);
        }

        if (!empty($status)) {
            if ($status == 'expire' and !empty($bundle->access_days)) {
                $accessTimestamp = $bundle->access_days * 24 * 60 * 60;

                $query->whereRaw('lms_sales.created_at + ? < ?', [$accessTimestamp, time()]);
            }
        }

        return $query;
    }

    public function notificationToStudents($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinar_notification_to_students');

        $bundle = Bundle::findOrFail($id);

        $data = [
            'pageTitle' => trans('lms/notification.send_notification'),
            'bundle' => $bundle
        ];

        return view('lms.admin.bundles.send-notification-to-course-students', $data);
    }


    public function sendNotificationToStudents(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_webinar_notification_to_students');

        $this->validate($request, [
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        $data = $request->all();

        $bundle = Bundle::where('id', $id)
            ->with([
                'sales' => function ($query) {
                    $query->whereNull('refund_at');
                    $query->with([
                        'buyer'
                    ]);
                }
            ])
            ->first();

        if (!empty($bundle)) {
            foreach ($bundle->sales as $sale) {
                if (!empty($sale->buyer)) {
                    $user = $sale->buyer;

                    Notification::create([
                        'user_id' => $user->id,
                        'group_id' => null,
                        'sender_id' => auth()->guard('lms_user')->id(),
                        'title' => $data['title'],
                        'message' => $data['message'],
                        'sender' => Notification::$AdminSender,
                        'type' => 'single',
                        'created_at' => time()
                    ]);

                    if (!empty($user->email) and env('APP_ENV') == 'production') {
                        \Mail::to($user->email)->send(new SendNotifications(['title' => $data['title'], 'message' => $data['message']]));
                    }
                }
            }

            $toastData = [
                'title' => trans('lms/public.request_success'),
                'msg' => trans('lms/update.the_notification_was_successfully_sent_to_n_students', ['count' => count($bundle->sales)]),
                'status' => 'success'
            ];

            return redirect("/lms/admin/bundles/{$bundle->id}/students")->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        $option = $request->get('option', null);

        $query = Bundle::select('id')
            ->whereTranslationLike('title', "%$term%");

        $bundles = $query->get();

        return response()->json($bundles, 200);
    }
}
