@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/chartjs/chart.min.css"/>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item"><a href="/lms{{ getAdminPanelUrl() }}/webinars">{{trans('lms/admin/main.classes')}}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>
    </section>

    <div class="section-body">
        <section>
            <h2 class="section-title">{{ $webinar->title }}</h2>

            <div class="activities-container mt-3 p-3 p-lg-3">
                <div class="row">
                    <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/lms/assets/default/img/activity/48.svg" width="64" height="64" alt="">
                            <strong class="font-30 font-weight-bold text-dark mt-1">{{ $studentsCount }}</strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('lms/public.students') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/lms/assets/default/img/activity/125.svg" width="64" height="64" alt="">
                            <strong class="font-30 font-weight-bold text-dark mt-1">{{ $commentsCount }}</strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.comments') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/lms/assets/default/img/activity/sales.svg" width="64" height="64" alt="">
                            <strong class="font-30 font-weight-bold text-dark mt-1">{{ $salesCount }}</strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.sales') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/lms/assets/default/img/activity/33.png" width="64" height="64" alt="">
                            <strong class="font-30 font-weight-bold text-dark mt-1">{{ (!empty($salesAmount) and $salesAmount > 0) ? handlePrice($salesAmount) : 0 }}</strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.sales_amount') }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="row">

            <div class="col-6 col-md-3 mt-3">
                <div class="dashboard-stats rounded-sm panel-shadow p-10 p-md-3 d-flex align-items-center">
                    <div class="stat-icon stat-icon-chapters">
                        <img src="/assets/lms/assets/default/img/icons/course-statistics/1.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-2">
                        <span class="font-30 font-weight-bold text-dark">{{ $chaptersCount }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/public.chapters') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mt-3">
                <div class="dashboard-stats rounded-sm panel-shadow p-10 p-md-3 d-flex align-items-center">
                    <div class="stat-icon stat-icon-sessions">
                        <img src="/assets/lms/assets/default/img/icons/course-statistics/2.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-2">
                        <span class="font-30 font-weight-bold text-dark">{{ $sessionsCount }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/public.sessions') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mt-3">
                <div class="dashboard-stats rounded-sm panel-shadow p-10 p-md-3 d-flex align-items-center">
                    <div class="stat-icon stat-icon-pending-quizzes">
                        <img src="/assets/lms/assets/default/img/icons/course-statistics/3.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-2">
                        <span class="font-30 font-weight-bold text-dark">{{ $pendingQuizzesCount }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.pending_quizzes') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mt-3">
                <div class="dashboard-stats rounded-sm panel-shadow p-10 p-md-3 d-flex align-items-center">
                    <div class="stat-icon stat-icon-pending-assignments">
                        <img src="/assets/lms/assets/default/img/icons/course-statistics/4.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-2">
                        <span class="font-30 font-weight-bold text-dark">{{ $pendingAssignmentsCount }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.pending_assignments') }}</span>
                    </div>
                </div>
            </div>

        </section>

        <section>
            <div class="row">
                <div class="col-12 col-md-3 mt-3">
                    <div class="course-statistic-cards-shadow py-3 px-2 py-md-3 px-md-3 rounded-sm bg-white">
                        <div class="d-flex align-items-center flex-column">
                            <img src="/assets/lms/assets/default/img/activity/33.png" width="64" height="64" alt="">

                            <span class="font-30 text-dark mt-3 font-weight-bold">{{ $courseRate }}</span>
                            @include('lms.admin.webinars.includes.rate',['rate' => $courseRate, 'className' => 'mt-2', 'dontShowRate' => true, 'showRateStars' => true])
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top font-16 font-weight-500">
                            <span class="text-gray">{{ trans('lms/update.total_rates') }}</span>
                            <span class="text-dark font-weight-bold">{{ $courseRateCount }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 mt-3">
                    <div class="course-statistic-cards-shadow py-3 px-2 py-md-3 px-md-3 rounded-sm bg-white">
                        <div class="d-flex align-items-center flex-column">
                            <img src="/assets/lms/assets/default/img/activity/88.svg" width="64" height="64" alt="">

                            <span class="font-30 text-dark mt-3 font-weight-bold">{{ $webinar->quizzes->count() }}</span>
                            <span class="mt-2 font-16 font-weight-500 text-gray">{{ trans('lms/quiz.quizzes') }}</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top font-16 font-weight-500">
                            <span class="text-gray">{{ trans('lms/quiz.average_grade') }}</span>
                            <span class="text-dark font-weight-bold">{{ $quizzesAverageGrade }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 mt-3">
                    <div class="course-statistic-cards-shadow py-3 px-2 py-md-3 px-md-3 rounded-sm bg-white">
                        <div class="d-flex align-items-center flex-column">
                            <img src="/assets/lms/assets/default/img/activity/homework.svg" width="64" height="64" alt="">

                            <span class="font-30 text-dark mt-3 font-weight-bold">{{ $webinar->assignments->count() }}</span>
                            <span class="mt-2 font-16 font-weight-500 text-gray">{{ trans('lms/update.assignments') }}</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top font-16 font-weight-500">
                            <span class="text-gray">{{ trans('lms/quiz.average_grade') }}</span>
                            <span class="text-dark font-weight-bold">{{ $assignmentsAverageGrade }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 mt-3">
                    <div class="course-statistic-cards-shadow py-3 px-2 py-md-3 px-md-3 rounded-sm bg-white">
                        <div class="d-flex align-items-center flex-column">
                            <img src="/assets/lms/assets/default/img/activity/39.svg" width="64" height="64" alt="">

                            <span class="font-30 text-dark mt-3 font-weight-bold">{{ $courseForumsMessagesCount }}</span>
                            <span class="mt-2 font-16 font-weight-500 text-gray">{{ trans('lms/update.forum_messages') }}</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top font-16 font-weight-500">
                            <span class="text-gray">{{ trans('lms/update.forum_students') }}</span>
                            <span class="text-dark font-weight-bold">{{ $courseForumsStudentsCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="row">
                @include('lms.admin.webinars.course_statistics.includes.pie_charts',[
                    'cardTitle' => trans('lms/update.students_user_roles'),
                    'cardId' => 'studentsUserRolesChart',
                    'cardPrimaryLabel' => trans('lms/public.students'),
                    'cardSecondaryLabel' => trans('lms/public.instructors'),
                    'cardWarningLabel' => trans('lms/home.organizations'),
                ])

                @include('lms.admin.webinars.course_statistics.includes.pie_charts',[
                    'cardTitle' => trans('lms/update.course_progress'),
                    'cardId' => 'courseProgressChart',
                    'cardPrimaryLabel' => trans('lms/update.completed'),
                    'cardSecondaryLabel' => trans('lms/webinars.in_progress'),
                    'cardWarningLabel' => trans('lms/update.not_started'),
                ])

                @include('lms.admin.webinars.course_statistics.includes.pie_charts',[
                    'cardTitle' => trans('lms/quiz.quiz_status'),
                    'cardId' => 'quizStatusChart',
                    'cardPrimaryLabel' => trans('lms/quiz.passed'),
                    'cardSecondaryLabel' => trans('lms/public.pending'),
                    'cardWarningLabel' => trans('lms/quiz.failed'),
                ])

                @include('lms.admin.webinars.course_statistics.includes.pie_charts',[
                    'cardTitle' => trans('lms/update.assignments_status'),
                    'cardId' => 'assignmentsStatusChart',
                    'cardPrimaryLabel' => trans('lms/quiz.passed'),
                    'cardSecondaryLabel' => trans('lms/public.pending'),
                    'cardWarningLabel' => trans('lms/quiz.failed'),
                ])

            </div>
        </section>


        <section>
            <div class="row">
                <div class="col-12 col-md-6 mt-3">
                    <div class="course-statistic-cards-shadow monthly-sales-card pt-2 px-2 pb-3 rounded-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="font-16 text-dark font-weight-bold">{{ trans('lms/panel.monthly_sales') }}</h3>

                            <span class="font-16 font-weight-500 text-gray">{{ dateTimeFormat(time(),'M Y') }}</span>
                        </div>

                        <div class="monthly-sales-chart mt-2">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 mt-3">
                    <div class="course-statistic-cards-shadow monthly-sales-card pt-2 px-2 pb-3 rounded-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="font-16 text-dark font-weight-bold">{{ trans('lms/update.course_progress') }} (%)</h3>
                        </div>

                        <div class="monthly-sales-chart mt-2">
                            <canvas id="courseProgressLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-5">
            <h2 class="section-title">{{ trans('lms/panel.students_list') }}</h2>

            @if(!empty($students) and !$students->isEmpty())
                <div class="panel-section-card py-3 px-3 mt-3">
                    <div class="row">
                        <div class="col-12 ">
                            <div class="table-responsive">
                                <table class="table custom-table text-center ">
                                    <thead>
                                    <tr>
                                        <th class="text-left text-gray">{{ trans('lms/quiz.student') }}</th>
                                        <th class="text-center text-gray">{{ trans('lms/update.progress') }}</th>
                                        <th class="text-center text-gray">{{ trans('lms/update.passed_quizzes') }}</th>
                                        <th class="text-center text-gray">{{ trans('lms/update.unsent_assignments') }}</th>
                                        <th class="text-center text-gray">{{ trans('lms/update.pending_assignments') }}</th>
                                        <th class="text-center text-gray">{{ trans('lms/panel.purchase_date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php
                                        $usersLists = new \Illuminate\Support\Collection($students->items());
                                        $usersLists = $usersLists->merge($unregisteredUsers);
                                    @endphp

                                    @foreach($usersLists as $user)

                                        <tr>
                                            <td class="text-left">
                                                <div class="user-inline-avatar d-flex align-items-center">
                                                    <div class="avatar bg-gray200">
                                                        <img src="{{ $user->getAvatar() }}" class="img-cover" alt="">
                                                    </div>
                                                    <div class=" ml-2">
                                                        <span class="d-block text-dark font-weight-500">{{ $user->full_name }}</span>
                                                        <span class="mt-2 d-block font-12 text-gray">{{ $user->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-dark font-weight-500">{{ $user->course_progress ?? 0 }}%</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-dark font-weight-500">{{ $user->passed_quizzes ?? 0 }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-dark font-weight-500">{{ $user->unsent_assignments ?? 0 }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-dark font-weight-500">{{ $user->pending_assignments ?? 0 }}</span>
                                            </td>
                                            <td class="align-middle">
                                                @if(empty($user->id))
                                                    <span class="text-warning">{{ trans('lms/update.unregistered') }}</span>
                                                @else
                                                    <span class="text-dark font-weight-500">{{ dateTimeFormat($user->created_at,'j M Y | H:i') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    {{ $students->appends(request()->input())->links() }}
                </div>
            @else

                @include('lms.' . getTemplate() . '.includes.no-result',[
                    'file_name' => 'studentt.png',
                    'title' => trans('lms/update.course_statistic_students_no_result'),
                    'hint' =>  nl2br(trans('lms/update.course_statistic_students_no_result_hint')),
                ])
            @endif

        </section>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/lms/assets/default/js/panel/course_statistics.min.js"></script>

    <script>
        (function ($) {
            "use strict";

            @if(!empty($studentsUserRolesChart))
            makePieChart('studentsUserRolesChart', @json($studentsUserRolesChart['labels']),@json($studentsUserRolesChart['data']));
            @endif

            @if(!empty($courseProgressChart))
            makePieChart('courseProgressChart', @json($courseProgressChart['labels']),@json($courseProgressChart['data']));
            @endif

            @if(!empty($quizStatusChart))
            makePieChart('quizStatusChart', @json($quizStatusChart['labels']),@json($quizStatusChart['data']));
            @endif

            @if(!empty($assignmentsStatusChart))
            makePieChart('assignmentsStatusChart', @json($assignmentsStatusChart['labels']),@json($assignmentsStatusChart['data']));
            @endif


            @if(!empty($monthlySalesChart))
            handleMonthlySalesChart(@json($monthlySalesChart['labels']),@json($monthlySalesChart['data']));
            @endif

            @if(!empty($courseProgressLineChart))
            handleCourseProgressChart(@json($courseProgressLineChart['labels']),@json($courseProgressLineChart['data']));
            @endif

            // handleCourseProgressChartChart();
        })(jQuery)
    </script>
@endpush
