@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')

@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/panel.my_activity') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $purchasedCount }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.purchased') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/hours.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ convertMinutesToHourAndMinute($hours) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/home.hours') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/upcoming.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $upComing }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.upcoming') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('lms/panel.my_purchases') }}</h2>
        </div>

        @if(!empty($sales) and !$sales->isEmpty())
            @foreach($sales as $sale)
                @php
                    $item = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

                    $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
                    $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
                    $isProgressing = false;

                    if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
                        $isProgressing = true;
                    }
                @endphp

                @if(!empty($item))
                    <div class="row mt-30">
                        <div class="col-12">
                            <div class="webinar-card webinar-list d-flex">
                                <div class="image-box">
                                    <img src="{{get_path_lms()}}{{ $item->getImage() }}" class="img-cover" alt="">

                                    @if(!empty($sale->webinar))
                                        @if($item->type == 'webinar')
                                            @if($item->start_date > time())
                                                <span class="badge badge-primary">{{  trans('lms/panel.not_conducted') }}</span>
                                            @elseif($item->isProgressing())
                                                <span class="badge badge-secondary">{{ trans('lms/webinars.in_progress') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ trans('lms/public.finished') }}</span>
                                            @endif
                                        @elseif(!empty($item->downloadable))
                                            <span class="badge badge-secondary">{{ trans('lms/home.downloadable') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ trans('lms/webinars.'.$item->type) }}</span>
                                        @endif

                                        @php
                                            $percent = $item->getProgress();

                                            if($item->isWebinar()){
                                                if($item->isProgressing()) {
                                                    $progressTitle = trans('lms/public.course_learning_passed',['percent' => $percent]);
                                                } else {
                                                    $progressTitle = $item->sales_count .'/'. $item->capacity .' '. trans('lms/quiz.students');
                                                }
                                            } else {
                                                   $progressTitle = trans('lms/public.course_learning_passed',['percent' => $percent]);
                                            }
                                        @endphp

                                        @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                            {{--  --}}
                                        @else
                                            <div class="progress cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $progressTitle }}">
                                                <span class="progress-bar" style="width: {{ $percent }}%"></span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">{{ trans('lms/update.bundle') }}</span>
                                    @endif
                                </div>

                                <div class="webinar-card-body w-100 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="/lms{{ $item->getUrl() }}">
                                            <h3 class="webinar-title font-weight-bold font-16 text-dark-blue">
                                                {{ $item->title }}

                                                @if(!empty($item->access_days))
                                                    @if(!$item->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id))
                                                        <span class="badge badge-outlined-danger ml-10">{{ trans('lms/update.access_days_expired') }}</span>
                                                    @else
                                                        <span class="badge badge-outlined-warning ml-10">{{ trans('lms/update.expired_on_date',['date' => dateTimeFormat($item->getExpiredAccessDays($sale->created_at, $sale->gift_id),'j M Y')]) }}</span>
                                                    @endif
                                                @endif

                                                @if($sale->payment_method == \App\Models\LMS\Sale::$subscribe and $sale->checkExpiredPurchaseWithSubscribe($sale->buyer_id, $item->id, !empty($sale->webinar) ? 'webinar_id' : 'bundle_id'))
                                                    <span class="badge badge-outlined-danger ml-10">{{ trans('lms/update.subscribe_expired') }}</span>
                                                @endif

                                                @if(!empty($sale->webinar))
                                                    <span class="badge badge-dark ml-10 status-badge-dark">{{ trans('lms/webinars.'.$item->type) }}</span>
                                                @endif

                                                @if(!empty($sale->gift_id))
                                                    <span class="badge badge-primary ml-10">{{ trans('lms/update.gift') }}</span>
                                                @endif
                                            </h3>
                                        </a>

                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                                    <a href="/lms/panel/webinars/{{ $item->id }}/sale/{{ $sale->id }}/invoice" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/public.invoice') }}</a>
                                                @else
                                                    @if(!empty($item->access_days) and !$item->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id))
                                                        <a href="/lms{{ $item->getUrl() }}" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/update.enroll_on_course') }}</a>
                                                    @elseif(!empty($sale->webinar))
                                                        <a href="/lms{{ $item->getLearningPageUrl() }}" target="_blank" class="webinar-actions d-block">{{ trans('lms/update.learning_page') }}</a>

                                                        @if(!empty($item->start_date) and ($item->start_date > time() or ($item->isProgressing() and !empty($nextSession))))
                                                            <button type="button" data-webinar-id="{{ $item->id }}" class="join-purchase-webinar webinar-actions btn-transparent d-block mt-10">{{ trans('lms/footer.join') }}</button>
                                                        @endif

                                                        @if(!empty($item->downloadable) or (!empty($item->files) and count($item->files)))
                                                            <a href="/lms{{ $item->getUrl() }}?tab=content" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/home.download') }}</a>
                                                        @endif

                                                        @if($item->price > 0)
                                                            <a href="/lms/panel/webinars/{{ $item->id }}/sale/{{ $sale->id }}/invoice" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/public.invoice') }}</a>
                                                        @endif
                                                    @endif

                                                    <a href="/lms{{ $item->getUrl() }}?tab=reviews" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/public.feedback') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @include('lms.' . getTemplate() . '.includes.webinar.rate',['rate' => $item->getRate()])

                                    <div class="webinar-price-box mt-15">
                                        @if($item->price > 0)
                                            @if($item->bestTicket() < $item->price)
                                                <span class="real">{{ handlePrice($item->bestTicket(), true, true, false, null, true) }}</span>
                                                <span class="off ml-10">{{ handlePrice($item->price, true, true, false, null, true) }}</span>
                                            @else
                                                <span class="real">{{ handlePrice($item->price, true, true, false, null, true) }}</span>
                                            @endif
                                        @else
                                            <span class="real">{{ trans('lms/public.free') }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">

                                        @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/update.gift_status') }}:</span>

                                                @if(!empty($sale->gift_date) and $sale->gift_date > time())
                                                    <span class="stat-value text-warning">{{ trans('lms/public.pending') }}</span>
                                                @else
                                                    <span class="stat-value text-primary">{{ trans('lms/update.sent') }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/public.item_id') }}:</span>
                                                <span class="stat-value">{{ $item->id }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($sale->gift_id))
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/update.gift_receive_date') }}:</span>
                                                <span class="stat-value">{{ (!empty($sale->gift_date)) ? dateTimeFormat($sale->gift_date, 'j M Y H:i') : trans('lms/update.instantly') }}</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/public.category') }}:</span>
                                                <span class="stat-value">{{ !empty($item->category_id) ? $item->category->title : '' }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($sale->webinar) and $item->type == 'webinar')
                                            @if($item->isProgressing() and !empty($nextSession))
                                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                    <span class="stat-title">{{ trans('lms/webinars.next_session_duration') }}:</span>
                                                    <span class="stat-value">{{ convertMinutesToHourAndMinute($nextSession->duration) }} Hrs</span>
                                                </div>

                                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                    <span class="stat-title">{{ trans('lms/webinars.next_session_start_date') }}:</span>
                                                    <span class="stat-value">{{ dateTimeFormat($nextSession->date,'j M Y') }}</span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                    <span class="stat-title">{{ trans('lms/public.duration') }}:</span>
                                                    <span class="stat-value">{{ convertMinutesToHourAndMinute($item->duration) }} Hrs</span>
                                                </div>

                                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                    <span class="stat-title">{{ trans('lms/public.start_date') }}:</span>
                                                    <span class="stat-value">{{ dateTimeFormat($item->start_date,'j M Y') }}</span>
                                                </div>
                                            @endif
                                        @elseif(!empty($sale->bundle))
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/public.duration') }}:</span>
                                                <span class="stat-value">{{ convertMinutesToHourAndMinute($item->getBundleDuration()) }} Hrs</span>
                                            </div>
                                        @endif

                                        @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/update.receipt') }}:</span>
                                                <span class="stat-value">{{ $sale->gift_recipient }}</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/public.instructor') }}:</span>
                                                <span class="stat-value">{{ $item->teacher->full_name }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($sale->gift_id) and $sale->buyer_id != $authUser->id)
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/update.gift_sender') }}:</span>
                                                <span class="stat-value">{{ $sale->gift_sender }}</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                <span class="stat-title">{{ trans('lms/panel.purchase_date') }}:</span>
                                                <span class="stat-value">{{ dateTimeFormat($sale->created_at,'j M Y') }}</span>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            @include('lms.' . getTemplate() . '.includes.no-result',[
            'file_name' => 'student.png',
            'title' => trans('lms/panel.no_result_purchases') ,
            'hint' => trans('lms/panel.no_result_purchases_hint') ,
            'btn' => ['url' => '/lms/classes?sort=newest','text' => trans('lms/panel.start_learning')]
        ])
        @endif
    </section>

    <div class="my-30">
        {{ $sales->appends(request()->input())->links('lms.vendor.pagination.panel') }}
    </div>

    @include('lms.web.default.panel.webinar.join_webinar_modal')
@endsection

@push('scripts_bottom')
    <script>
        var undefinedActiveSessionLang = '{{ trans('lms/webinars.undefined_active_session') }}';
    </script>

    <script src="/assets/lms/assets/default/js/panel/join_webinar.min.js"></script>
@endpush
