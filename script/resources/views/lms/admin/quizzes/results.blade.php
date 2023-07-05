@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.quiz_results') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.quiz_results') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($authUser->can('admin_quiz_result_export_excel'))
                                <div class="text-right">
                                    <a href="/lms{{ getAdminPanelUrl() }}/quizzes/{{ $quiz_id}}/results/excel" class="btn btn-primary">{{ trans('lms/admin/main.export_xls') }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('lms/quiz.student') }}</th>
                                        <th class="text-left">{{ trans('lms/admin/main.instructor') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.grade') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.quiz_date') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($quizzesResults as $result)
                                        <tr>
                                            <td>
                                                <span>{{ $result->quiz->title }}</span>
                                                <small class="d-block text-left text-primary">({{ $result->quiz->webinar->title }})</small>
                                            </td>
                                            <td class="text-left">{{ $result->user->full_name }}</td>
                                            <td class="text-left">
                                                {{ $result->quiz->teacher->full_name }}
                                            </td>
                                            <td class="text-center">
                                                <span>{{ $result->user_grade }}</span>
                                            </td>
                                            <td class="text-center">{{ dateTimeformat($result->created_at, 'j F Y') }}</td>
                                            <td class="text-center">
                                                @switch($result->status)
                                                    @case(\App\Models\LMS\QuizzesResult::$passed)
                                                    <span class="text-success">{{ trans('lms/quiz.passed') }}</span>
                                                    @break

                                                    @case(\App\Models\LMS\QuizzesResult::$failed)

                                                    <span class="text-danger">{{ trans('lms/quiz.failed') }}</span>
                                                    @break

                                                    @case(\App\Models\LMS\QuizzesResult::$waiting)
                                                    <span class="text-warning">{{ trans('lms/quiz.waiting') }}</span>
                                                    @break

                                                @endswitch
                                            </td>

                                            <td>
                                                @if($authUser->can('admin_quizzes_results_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/quizzes/result/'. $result->id.'/delete'])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $quizzesResults->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
