@extends('lms.web.default.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/update.topics_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/125.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $publishedTopics }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.published_topics') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/126.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $lockedTopics }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.locked_topics') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/39.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $topicMessages }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.topic_messages') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('lms/update.filter_topics') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/lms/panel/forums/topics" method="get" class="row">
                <div class="col-12 col-lg-5">
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
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/update.forums') }}</label>
                                <select name="forum_id" class="form-control" data-placeholder="{{ trans('lms/public.all') }}">
                                    <option value="all">{{ trans('lms/public.all') }}</option>

                                    @foreach($forums as $forum)
                                        @if(!empty($forum->subForums) and count($forum->subForums))
                                            <optgroup label="{{ $forum->title }}">
                                                @foreach($forum->subForums as $subForum)
                                                    <option value="{{ $subForum->id }}" {{ (request()->get('forum_id') == $subForum->id) ? 'selected' : '' }}>{{ $subForum->title }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $forum->id }}" {{ (request()->get('forum_id') == $forum->id) ? 'selected' : '' }}>{{ $forum->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="all">{{ trans('lms/public.all') }}</option>
                                    <option value="published" @if(request()->get('status') == 'published') selected @endif >{{ trans('lms/public.published') }}</option>
                                    <option value="closed" @if(request()->get('status') == 'closed') selected @endif >{{ trans('lms/panel.closed') }}</option>
                                </select>
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
            <h2 class="section-title">{{ trans('lms/update.my_topics') }}</h2>
        </div>

        @if($topics->count() > 0)

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ trans('lms/public.title') }}</th>
                                    <th class="text-center">{{ trans('lms/update.forum') }}</th>
                                    <th class="text-center">{{ trans('lms/site.posts') }}</th>
                                    <th class="text-center">{{ trans('lms/public.status') }}</th>
                                    <th class="text-center">{{ trans('lms/public.publish_date') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($topics as $topic)
                                    <tr>
                                        <td class="text-left align-middle">
                                            <div class="user-inline-avatar d-flex align-items-center">
                                                <div class="avatar bg-gray200">
                                                    <img src="{{get_path_lms()}}{{ $topic->forum->icon }}" class="img-cover" alt="">
                                                </div>
                                                <a href="/lms{{ $topic->getPostsUrl() }}" target="_blank" class="">
                                                    <div class=" ml-5">
                                                        <span class="d-block font-16 font-weight-500 text-dark-blue">{{ $topic->title }}</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">{{ $topic->forum->title }}</td>
                                        <td class="text-center align-middle">{{ $topic->posts_count }}</td>
                                        <td class="text-center align-middle">
                                            @if($topic->close)
                                                {{ trans('lms/panel.closed') }}
                                            @else
                                                {{ trans('lms/public.published') }}
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}</td>
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
                'title' => trans('lms/update.panel_topics_no_result'),
                'hint' => nl2br(trans('lms/update.panel_topics_no_result_hint')),
                'btn' => ['url' => '/lms/forums','text' => trans('lms/update.forums')]
            ])

        @endif

    </section>

    <div class="my-30">
        {{ $topics->appends(request()->input())->links('lms.vendor.pagination.panel') }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush
