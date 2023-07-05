@extends('lms.web.default.panel.layouts.panel_layout')

@push('styles_top')

@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/update.overview') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/upcoming.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $totalCourses }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.total_courses') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $releasedCourses }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.released_courses') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/hours.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $notReleased }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.not_released') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/49.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $followers }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.followers') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-25">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('lms/update.my_upcoming_courses') }}</h2>

            <form action="/lms" method="get">
                <div class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                    <label class="cursor-pointer mb-0 mr-10 font-weight-500 font-14 text-gray" for="onlyReleasedSwitch">{{ trans('lms/update.only_not_released_courses') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="only_not_released_courses" @if(request()->get('only_not_released_courses','') == 'on') checked @endif class="custom-control-input" id="onlyReleasedSwitch">
                        <label class="custom-control-label" for="onlyReleasedSwitch"></label>
                    </div>
                </div>
            </form>
        </div>

        @if(!empty($upcomingCourses) and !$upcomingCourses->isEmpty())
            @foreach($upcomingCourses as $upcomingCourse)
                <div class="row mt-30">
                    <div class="col-12">
                        <div class="webinar-card webinar-list d-flex">
                            <div class="image-box">
                                <img src="{{get_path_lms()}}{{ $upcomingCourse->getImage() }}" class="img-cover" alt="">

                                @if(!empty($upcomingCourse->webinar_id))
                                    <span class="badge badge-secondary">{{  trans('lms/update.released') }}</span>
                                @else
                                    @switch($upcomingCourse->status)
                                        @case(\App\Models\LMS\UpcomingCourse::$active)
                                            <span class="badge badge-primary">{{  trans('lms/public.published') }}</span>
                                            @break
                                        @case(\App\Models\LMS\UpcomingCourse::$isDraft)
                                            <span class="badge badge-danger">{{ trans('lms/public.draft') }}</span>
                                            @break
                                        @case(\App\Models\LMS\UpcomingCourse::$pending)
                                            <span class="badge badge-warning">{{ trans('lms/public.waiting') }}</span>
                                            @break
                                        @case(\App\Models\LMS\UpcomingCourse::$inactive)
                                            <span class="badge badge-danger">{{ trans('lms/public.rejected') }}</span>
                                            @break
                                    @endswitch
                                @endif

                                @if(!empty($upcomingCourse->course_progress))
                                    <div class="progress">
                                        <span class="progress-bar {{ ($upcomingCourse->course_progress < 50) ? 'bg-warning' : '' }}" style="width: {{ $upcomingCourse->course_progress }}%"></span>
                                    </div>
                                @endif
                            </div>

                            <div class="webinar-card-body w-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="/lms{{ $upcomingCourse->getUrl() }}" target="_blank">
                                        <h3 class="font-16 text-dark-blue font-weight-bold">{{ $upcomingCourse->title }}
                                            <span class="badge badge-dark ml-10 status-badge-dark">{{ trans('lms/webinars.'.$upcomingCourse->type) }}</span>
                                        </h3>
                                    </a>

                                    @if($upcomingCourse->canAccess($authUser))
                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu ">
                                                @if(!empty($upcomingCourse->webinar_id))
                                                    <a href="/lms{{ $upcomingCourse->webinar->getUrl() }}" class="webinar-actions d-block text-primary">{{ trans('lms/update.view_course') }}</a>
                                                @else
                                                    @if($upcomingCourse->status == \App\Models\LMS\UpcomingCourse::$isDraft)
                                                        <a href="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/step/4" class="js-send-for-reviewer webinar-actions btn-transparent d-block text-primary">{{ trans('lms/update.send_for_reviewer') }}</a>
                                                    @elseif($upcomingCourse->status == \App\Models\LMS\UpcomingCourse::$active)
                                                        <button type="button" data-id="{{ $upcomingCourse->id }}" class="js-mark-as-released webinar-actions btn-transparent d-block text-primary">{{ trans('lms/update.mark_as_released') }}</button>
                                                    @endif

                                                    <a href="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/edit" class="webinar-actions d-block mt-10">{{ trans('lms/public.edit') }}</a>
                                                @endif

                                                @if($upcomingCourse->status == \App\Models\LMS\UpcomingCourse::$active)
                                                    <a href="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/followers" class="webinar-actions d-block mt-10">{{ trans('lms/update.view_followers') }}</a>
                                                @endif

                                                @if($upcomingCourse->creator_id == $authUser->id)
                                                    <a href="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/delete" class="webinar-actions d-block mt-10 text-danger delete-action">{{ trans('lms/public.delete') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.item_id') }}:</span>
                                        <span class="stat-value">{{ $upcomingCourse->id }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.category') }}:</span>
                                        <span class="stat-value">{{ !empty($upcomingCourse->category_id) ? $upcomingCourse->category->title : '' }}</span>
                                    </div>

                                    @if(!empty($upcomingCourse->duration))
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/public.duration') }}:</span>
                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($upcomingCourse->duration) }} Hrs</span>
                                        </div>
                                    @endif

                                    @if(!empty($upcomingCourse->publish_date))
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/update.estimated_publish_date') }}:</span>
                                            <span class="stat-value">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y H:i') }}</span>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.price') }}:</span>
                                        <span class="stat-value">{{ (!empty($upcomingCourse->price)) ? handlePrice($upcomingCourse->price) : trans('lms/public.free') }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/update.followers') }}:</span>
                                        <span class="stat-value">{{ $upcomingCourse->followers_count ?? 0 }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="my-30">
                {{ $upcomingCourses->appends(request()->input())->links('lms.vendor.pagination.panel') }}
            </div>

        @else
            @include('lms.' . getTemplate() . '.includes.no-result',[
                'file_name' => 'webinar.png',
                'title' => trans('lms/update.you_not_have_any_upcoming_courses'),
                'hint' =>  trans('lms/update.you_not_have_any_upcoming_courses_hint') ,
                'btn' => ['url' => '/lms/panel/upcoming_courses/new','text' => trans('lms/update.create_a_upcoming_course') ]
            ])
        @endif
    </section>
@endsection

@push('scripts_bottom')

    <script src="/assets/lms/assets/default/js/panel/upcoming_course.min.js"></script>
@endpush
