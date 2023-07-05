@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/panel.comments_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/46.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $quizzesCount }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/quiz.quizzes') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/47.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $questionsCount }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/public.questions') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/48.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $userCount }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/quiz.students') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('lms/quiz.filter_quizzes') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/lms/panel/quizzes" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off" class="form-control @if(!empty(request()->get('from'))) datepicker @else datefilter @endif" aria-describedby="dateInputGroupPrepend" value="{{ request()->get('from','') }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off" class="form-control @if(!empty(request()->get('to'))) datepicker @else datefilter @endif" aria-describedby="dateInputGroupPrepend" value="{{ request()->get('to','') }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/quiz.quiz_or_webinar') }}</label>
                                <select name="quiz_id" class="form-control select2" data-placeholder="{{ trans('lms/public.all') }}">
                                    <option value="all">{{ trans('lms/public.all') }}</option>

                                    @foreach($allQuizzesLists as $allQuiz)
                                        <option value="{{ $allQuiz->id }}" @if(request()->get('quiz_id') == $allQuiz->id) selected @endif>{{ $allQuiz->title .' - '. ($allQuiz->webinar ? $allQuiz->webinar->title : '-') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/public.total_mark') }}</label>
                                        <input type="text" name="total_mark" class="form-control" value="{{ request()->get('total_mark','') }}"/>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/public.status') }}</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="all">{{ trans('lms/public.all') }}</option>
                                            <option value="active" @if(request()->get('status') == 'active') selected @endif >{{ trans('lms/public.active') }}</option>
                                            <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif >{{ trans('lms/public.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('lms/public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('lms/quiz.quizzes') }}</h2>

            <form action="/lms/panel/quizzes" method="get" class="">
                <div class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                    <label class="mb-0 mr-10 cursor-pointer text-gray font-14 font-weight-500" for="activeQuizzesSwitch">{{ trans('lms/quiz.show_only_active_quizzes') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="active_quizzes" class="custom-control-input" id="activeQuizzesSwitch" @if(request()->get('active_quizzes',null) == 'on') checked @endif>
                        <label class="custom-control-label" for="activeQuizzesSwitch"></label>
                    </div>
                </div>
            </form>
        </div>

        @if($quizzes->count() > 0)

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ trans('lms/public.title') }}</th>
                                    <th class="text-center">{{ trans('lms/public.questions') }}</th>
                                    <th class="text-center">{{ trans('lms/public.time') }} <span class="braces">({{ trans('lms/public.min') }})</span></th>
                                    <th class="text-center">{{ trans('lms/public.total_mark') }}</th>
                                    <th class="text-center">{{ trans('lms/public.pass_mark') }}</th>
                                    <th class="text-center">{{ trans('lms/quiz.students') }}</th>
                                    {{--<th>{{ trans('lms/quiz.average') }}</th>--}}
                                    <th class="text-center">{{ trans('lms/public.status') }}</th>
                                    <th class="text-center">{{ trans('lms/public.date_created') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($quizzes as $quiz)
                                    <tr>
                                        <td class="text-left">
                                            <span class="d-block">{{ $quiz->title }}</span>
                                            <span class="font-12 text-gray d-block">
                                                @if(!empty($quiz->webinar))
                                                    {{ $quiz->webinar->title }}
                                                @else
                                                    {{ trans('lms/panel.not_assign_any_webinar') }}
                                                @endif
                                        </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ $quiz->quizQuestions->count() }}
                                            @if(($quiz->display_limited_questions and !empty($quiz->display_number_of_questions)))
                                                <span class="font-12 text-gray">({{ trans('lms/public.active') }}: {{ $quiz->display_number_of_questions }})</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ $quiz->time }}</td>
                                        <td class="text-center align-middle">{{ $quiz->quizQuestions->sum('grade') }}</td>
                                        <td class="text-center align-middle">{{ $quiz->pass_mark }}</td>
                                        <td class="text-center align-middle">
                                            <span class="d-block">{{ $quiz->quizResults->pluck('user_id')->count() }}</span>

                                            @if(!empty($quiz->userSuccessRate) and $quiz->userSuccessRate > 0)
                                                <span class="font-12 text-primary d-block">{{ $quiz->userSuccessRate }}% {{ trans('lms/quiz.passed')  }}</span>
                                            @endif
                                        </td>

                                        <td class="text-center align-middle">{{ trans('lms/public.'.$quiz->status) }}</td>

                                        <td class="text-center align-middle">{{ dateTimeFormat($quiz->created_at, 'j M Y H:i') }}</td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu font-weight-normal">
                                                    <a href="/lms/panel/quizzes/{{ $quiz->id }}/edit" class="webinar-actions d-block mt-10">{{ trans('lms/public.edit') }}</a>
                                                    <a href="/lms/panel/quizzes/{{ $quiz->id }}/delete" data-item-id="1" class="webinar-actions d-block mt-10 delete-action">{{ trans('lms/public.delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else

            @include('lms.' . getTemplate() . '.includes.no-result',[
                'file_name' => 'quiz.png',
                'title' => trans('lms/quiz.quiz_no_result'),
                'hint' => nl2br(trans('lms/quiz.quiz_no_result_hint')),
                'btn' => ['url' => '/lms/panel/quizzes/new','text' => trans('lms/quiz.create_a_quiz')]
            ])

        @endif

    </section>

    <div class="my-30">
        {{ $quizzes->appends(request()->input())->links('lms.vendor.pagination.panel') }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>

    <script src="/assets/lms/assets/default/js/panel/quiz_list.min.js"></script>
@endpush
