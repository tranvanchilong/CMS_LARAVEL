<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Admin\traits\DashboardTrait;
use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\FeatureWebinar;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\Ticket;
use App\Models\LMS\Webinar;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    use DashboardTrait;

    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_general_dashboard_show');

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_daily_sales_statistics')) {
            $dailySalesTypeStatistics = $this->dailySalesTypeStatistics();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_income_statistics')) {
            $getIncomeStatistics = $this->getIncomeStatistics();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_total_sales_statistics')) {
            $getTotalSalesStatistics = $this->getTotalSalesStatistics();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_new_sales')) {
            $getNewSalesCount = $this->getNewSalesCount();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_new_comments')) {
            $getNewCommentsCount = $this->getNewCommentsCount();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_new_tickets')) {
            $getNewTicketsCount = $this->getNewTicketsCount();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_new_reviews')) {
            $getPendingReviewCount = $this->getPendingReviewCount();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_sales_statistics_chart')) {
            $getMonthAndYearSalesChart = $this->getMonthAndYearSalesChart('month_of_year');
            $getMonthAndYearSalesChartStatistics = $this->getMonthAndYearSalesChartStatistics();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_recent_comments')) {
            $recentComments = $this->getRecentComments();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_recent_tickets')) {
            $recentTickets = $this->getRecentTickets();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_recent_webinars')) {
            $recentWebinars = $this->getRecentWebinars();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_recent_courses')) {
            $recentCourses = $this->getRecentCourses();
        }

        if (Gate::forUser(auth()->guard('lms_user')->user())->allows('admin_general_dashboard_users_statistics_chart')) {
            $usersStatisticsChart = $this->usersStatisticsChart();
        }

        $data = [
            'pageTitle' => trans('lms/admin/main.general_dashboard_title'),
            'dailySalesTypeStatistics' => $dailySalesTypeStatistics ?? null,
            'getIncomeStatistics' => $getIncomeStatistics ?? null,
            'getTotalSalesStatistics' => $getTotalSalesStatistics ?? null,
            'getNewSalesCount' => $getNewSalesCount ?? 0,
            'getNewCommentsCount' => $getNewCommentsCount ?? 0,
            'getNewTicketsCount' => $getNewTicketsCount ?? 0,
            'getPendingReviewCount' => $getPendingReviewCount ?? 0,
            'getMonthAndYearSalesChart' => $getMonthAndYearSalesChart ?? null,
            'getMonthAndYearSalesChartStatistics' => $getMonthAndYearSalesChartStatistics ?? null,
            'recentComments' => $recentComments ?? null,
            'recentTickets' => $recentTickets ?? null,
            'recentWebinars' => $recentWebinars ?? null,
            'recentCourses' => $recentCourses ?? null,
            'usersStatisticsChart' => $usersStatisticsChart ?? null,
        ];

        return view('lms.admin.dashboard', $data);
    }

    public function marketing()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_marketing_dashboard_show');

        $buyerIds = Sale::whereNull('refund_at')
            ->pluck('buyer_id')
            ->toArray();
        $teacherIdsHasClass = Webinar::where('status', Webinar::$active)
            ->pluck('creator_id', 'teacher_id')
            ->toArray();
        $teacherIdsHasClass = array_merge(array_keys($teacherIdsHasClass), $teacherIdsHasClass);


        $usersWithoutPurchases = User::whereNotIn('id', array_unique($buyerIds))->count();
        $teachersWithoutClass = User::where('role_name', Role::$teacher)
            ->whereNotIn('id', array_unique($teacherIdsHasClass))
            ->count();
        $featuredClasses = FeatureWebinar::where('status', 'publish')
            ->count();

        $now = time();
        $activeDiscounts = Ticket::where('start_date', '<', $now)
            ->where('end_date', '>', $now)
            ->count();

        $getClassesStatistics = $this->getClassesStatistics();

        $getNetProfitChart = $this->getNetProfitChart();

        $getNetProfitStatistics = $this->getNetProfitStatistics();

        $getTopSellingClasses = $this->getTopSellingClasses();

        $getTopSellingAppointments = $this->getTopSellingAppointments();

        $getTopSellingTeachers = $this->getTopSellingTeachersAndOrganizations('teachers');

        $getTopSellingOrganizations = $this->getTopSellingTeachersAndOrganizations('organizations');

        $getMostActiveStudents = $this->getMostActiveStudents();

        $data = [
            'pageTitle' => trans('lms/admin/main.marketing_dashboard_title'),
            'usersWithoutPurchases' => $usersWithoutPurchases,
            'teachersWithoutClass' => $teachersWithoutClass,
            'featuredClasses' => $featuredClasses,
            'activeDiscounts' => $activeDiscounts,
            'getClassesStatistics' => $getClassesStatistics,
            'getNetProfitChart' => $getNetProfitChart,
            'getNetProfitStatistics' => $getNetProfitStatistics,
            'getTopSellingClasses' => $getTopSellingClasses,
            'getTopSellingAppointments' => $getTopSellingAppointments,
            'getTopSellingTeachers' => $getTopSellingTeachers,
            'getTopSellingOrganizations' => $getTopSellingOrganizations,
            'getMostActiveStudents' => $getMostActiveStudents,
        ];

        return view('lms.admin.marketing_dashboard', $data);
    }

    public function getSaleStatisticsData(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_general_dashboard_sales_statistics_chart');

        $type = $request->get('type');

        $chart = $this->getMonthAndYearSalesChart($type);

        return response()->json([
            'code' => 200,
            'chart' => $chart
        ], 200);
    }

    public function getNetProfitChartAjax(Request $request)
    {

        $type = $request->get('type');

        $chart = $this->getNetProfitChart($type);

        return response()->json([
            'code' => 200,
            'chart' => $chart
        ], 200);
    }

    public function cacheClear()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_clear_cache');

        Artisan::call('clear:all');

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => 'Website cache successfully cleared.',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
