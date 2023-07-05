@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    <section class="">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('lms/product.courses') }}</h2>
        </div>

        @if(!empty($bundle->bundleWebinars) and !$bundle->bundleWebinars->isEmpty())
            @foreach($bundle->bundleWebinars as $bundleWebinar)
                @php
                    $webinar = $bundleWebinar->webinar;
                    $lastSession = $webinar->lastSession();
                    $nextSession = $webinar->nextSession();
                    $isProgressing = false;

                    if($webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
                        $isProgressing=true;
                    }
                @endphp

                <div class="row mt-30">
                    <div class="col-12">
                        <div class="webinar-card webinar-list d-flex">
                            <div class="image-box">
                                <img src="{{get_path_lms()}}{{ $webinar->getImage() }}" class="img-cover" alt="">

                                @switch($webinar->status)
                                    @case(\App\Models\LMS\Webinar::$active)
                                    @if($webinar->isWebinar())
                                        @if($webinar->start_date > time())
                                            <span class="badge badge-primary">{{  trans('lms/panel.not_conducted') }}</span>
                                        @elseif($webinar->isProgressing())
                                            <span class="badge badge-secondary">{{ trans('lms/webinars.in_progress') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ trans('lms/public.finished') }}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">{{ trans('lms/webinars.'.$webinar->type) }}</span>
                                    @endif
                                    @break
                                    @case(\App\Models\LMS\Webinar::$isDraft)
                                    <span class="badge badge-danger">{{ trans('lms/public.draft') }}</span>
                                    @break
                                    @case(\App\Models\LMS\Webinar::$pending)
                                    <span class="badge badge-warning">{{ trans('lms/public.waiting') }}</span>
                                    @break
                                    @case(\App\Models\LMS\Webinar::$inactive)
                                    <span class="badge badge-danger">{{ trans('lms/public.rejected') }}</span>
                                    @break
                                @endswitch

                                @if($webinar->isWebinar())
                                    <div class="progress">
                                        <span class="progress-bar" style="width: {{ $webinar->getProgress() }}%"></span>
                                    </div>
                                @endif
                            </div>

                            <div class="webinar-card-body w-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="/lms{{ $webinar->getUrl() }}" target="_blank">
                                        <h3 class="font-16 text-dark-blue font-weight-bold">{{ $webinar->title }}
                                            <span class="badge badge-dark ml-10 status-badge-dark">{{ trans('lms/webinars.'.$webinar->type) }}</span>
                                        </h3>
                                    </a>

                                    @if($authUser->id == $webinar->creator_id or $authUser->id == $webinar->teacher_id)
                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu ">
                                                @if(!empty($webinar->start_date) and ($authUser->id == $webinar->creator_id or $authUser->id == $webinar->teacher_id))
                                                    <button type="button" data-webinar-id="{{ $webinar->id }}" class="js-webinar-next-session webinar-actions btn-transparent d-block">{{ trans('lms/public.create_join_link') }}</button>
                                                @endif

                                                <a href="/lms{{ $webinar->getLearningPageUrl() }}" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/update.learning_page') }}</a>

                                                <a href="/lms/panel/webinars/{{ $webinar->id }}/edit" class="webinar-actions d-block mt-10">{{ trans('lms/public.edit') }}</a>

                                                @if($webinar->isWebinar())
                                                    <a href="/lms/panel/webinars/{{ $webinar->id }}/step/4" class="webinar-actions d-block mt-10">{{ trans('lms/public.sessions') }}</a>
                                                @endif

                                                <a href="/lms/panel/webinars/{{ $webinar->id }}/step/4" class="webinar-actions d-block mt-10">{{ trans('lms/public.files') }}</a>


                                                @if($webinar->isOwner($authUser->id))
                                                    <a href="/lms/panel/webinars/{{ $webinar->id }}/export-students-list" class="webinar-actions d-block mt-10">{{ trans('lms/public.export_list') }}</a>
                                                @endif

                                                @if($authUser->id == $webinar->creator_id)
                                                    <a href="/lms/panel/webinars/{{ $webinar->id }}/duplicate" class="webinar-actions d-block mt-10">{{ trans('lms/public.duplicate') }}</a>
                                                @endif

                                                @if($webinar->isOwner($authUser->id))
                                                    <a href="/lms/panel/webinars/{{ $webinar->id }}/statistics" class="webinar-actions d-block mt-10">{{ trans('lms/update.statistics') }}</a>
                                                @endif

                                                @if($webinar->creator_id == $authUser->id)
                                                    <a href="/lms/panel/webinars/{{ $webinar->id }}/delete" class="webinar-actions d-block mt-10 text-danger delete-action">{{ trans('lms/public.delete') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @include('lms.' . getTemplate() . '.includes.webinar.rate',['rate' => $webinar->getRate()])

                                <div class="webinar-price-box mt-15">
                                    @if($webinar->price > 0)
                                        @if($webinar->bestTicket() < $webinar->price)
                                            <span class="real">{{ handlePrice($webinar->bestTicket()) }}</span>
                                            <span class="off ml-10">{{ handlePrice($webinar->price) }}</span>
                                        @else
                                            <span class="real">{{ handlePrice($webinar->price) }}</span>
                                        @endif
                                    @else
                                        <span class="real">{{ trans('lms/public.free') }}</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.item_id') }}:</span>
                                        <span class="stat-value">{{ $webinar->id }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.category') }}:</span>
                                        <span class="stat-value">{{ !empty($webinar->category_id) ? $webinar->category->title : '' }}</span>
                                    </div>

                                    @if($webinar->isProgressing() and !empty($nextSession))
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/webinars.next_session_duration') }}:</span>
                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($nextSession->duration) }} Hrs</span>
                                        </div>

                                        @if($webinar->isWebinar())
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/webinars.next_session_start_date') }}:</span>
                                                <span class="stat-value">{{ dateTimeFormat($nextSession->date,'j M Y') }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/public.duration') }}:</span>
                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($webinar->duration) }} Hrs</span>
                                        </div>

                                        @if($webinar->isWebinar())
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/public.start_date') }}:</span>
                                                <span class="stat-value">{{ dateTimeFormat($webinar->start_date,'j M Y') }}</span>
                                            </div>
                                        @endif
                                    @endif

                                    @if($webinar->isTextCourse() or $webinar->isCourse())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/public.files') }}:</span>
                                            <span class="stat-value">{{ $webinar->files->count() }}</span>
                                        </div>
                                    @endif

                                    @if($webinar->isTextCourse())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/webinars.text_lessons') }}:</span>
                                            <span class="stat-value">{{ $webinar->textLessons->count() }}</span>
                                        </div>
                                    @endif

                                    @if($webinar->isCourse())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/home.downloadable') }}:</span>
                                            <span class="stat-value">{{ ($webinar->downloadable) ? trans('lms/public.yes') : trans('lms/public.no') }}</span>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/panel.sales') }}:</span>
                                        <span class="stat-value">{{ count($webinar->sales) }} ({{ (!empty($webinar->sales) and count($webinar->sales)) ? handlePrice($webinar->sales->sum('amount')) : 0 }})</span>
                                    </div>

                                    @if(!empty($webinar->partner_instructor) and $webinar->partner_instructor and $authUser->id != $webinar->teacher_id and $authUser->id != $webinar->creator_id)
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/panel.invited_by') }}:</span>
                                            <span class="stat-value">{{ $webinar->teacher->full_name }}</span>
                                        </div>
                                    @elseif($authUser->id != $webinar->teacher_id and $authUser->id != $webinar->creator_id)
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/webinars.teacher_name') }}:</span>
                                            <span class="stat-value">{{ $webinar->teacher->full_name }}</span>
                                        </div>
                                    @elseif($authUser->id == $webinar->teacher_id and $authUser->id != $webinar->creator_id and $webinar->creator->isOrganization())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/webinars.organization_name') }}:</span>
                                            <span class="stat-value">{{ $webinar->creator->full_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @else
            @include('lms.' . getTemplate() . '.includes.no-result',[
                'file_name' => 'webinar.png',
                'title' => trans('lms/panel.you_not_have_any_webinar'),
                'hint' =>  trans('lms/panel.no_result_hint') ,
                'btn' => ['url' => '/lms/panel/webinars/new','text' => trans('lms/panel.create_a_webinar') ]
            ])
        @endif

    </section>

    @include('lms.web.default.panel.webinar.make_next_session_modal')

@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script>
        var undefinedActiveSessionLang = '{{ trans('lms/webinars.undefined_active_session') }}';
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
        var selectChapterLang = '{{ trans('lms/update.select_chapter') }}';
    </script>

    <script src="/assets/lms/assets/default/js/panel/make_next_session.min.js"></script>
@endpush
