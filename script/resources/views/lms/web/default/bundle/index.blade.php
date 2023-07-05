@extends('lms.'.getTemplate().'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/css/css-stars.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/video/video-js.min.css">
@endpush


@section('content')
    <section class="course-cover-container {{ empty($activeSpecialOffer) ? 'not-active-special-offer' : '' }}">
        <img src="{{get_path_lms()}}{{ $bundle->getImageCover() }}" class="img-cover course-cover-img" alt="{{ $bundle->title }}"/>

        <div class="cover-content pt-40">
            <div class="container position-relative">
                @if(!empty($activeSpecialOffer))
                    @include('lms.web.default.course.special_offer')
                @endif
            </div>
        </div>
    </section>

    <section class="container course-content-section {{ $bundle->type }}">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="course-content-body user-select-none">
                    <div class="course-body-on-cover text-white">
                        <h1 class="font-30 course-title">
                            {{ ($bundle->title) }}
                        </h1>
                        <span class="d-block font-16 mt-10">{{ trans('lms/public.in') }} <a href="/lms{{ $bundle->category->getUrl() }}" target="_blank" class="font-weight-500 text-decoration-underline text-white">{{ $bundle->category->title }}</a></span>

                        <div class="d-flex align-items-center">
                            @include('lms.web.default.includes.webinar.rate',['rate' => $bundle->getRate()])
                            <span class="ml-10 mt-15 font-14">({{ $bundle->reviews->pluck('creator_id')->count() }} {{ trans('lms/public.ratings') }})</span>
                        </div>

                        <div class="mt-15">
                            <span class="font-14">{{ trans('lms/public.created_by') }}</span>
                            <a href="/lms{{ $bundle->teacher->getProfileUrl() }}" target="_blank" class="text-decoration-underline text-white font-14 font-weight-500">{{ $bundle->teacher->full_name }}</a>
                        </div>
                    </div>

                    <div class="mt-20 pt-20  mt-md-40 pt-md-40">
                        <ul class="nav nav-tabs bg-secondary rounded-sm p-15 d-flex align-items-center justify-content-between" id="tabs-tab" role="tablist">
                            <li class="nav-item">
                                <a class="position-relative font-14 text-white {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'information') ? 'active' : '' }}" id="information-tab"
                                   data-toggle="tab" href="#information" role="tab" aria-controls="information"
                                   aria-selected="true">{{ trans('lms/product.information') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="position-relative font-14 text-white {{ (request()->get('tab','') == 'content') ? 'active' : '' }}" id="content-tab" data-toggle="tab"
                                   href="#content" role="tab" aria-controls="content"
                                   aria-selected="false">{{ trans('lms/product.content') }} ({{ $bundle->bundleWebinars->count() }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="position-relative font-14 text-white {{ (request()->get('tab','') == 'reviews') ? 'active' : '' }}" id="reviews-tab" data-toggle="tab"
                                   href="#reviews" role="tab" aria-controls="reviews"
                                   aria-selected="false">{{ trans('lms/product.reviews') }} ({{ $bundle->reviews->count() > 0 ? $bundle->reviews->pluck('creator_id')->count() : 0 }})</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade {{ (empty(request()->get('tab','')) or request()->get('tab','') == 'information') ? 'show active' : '' }} " id="information" role="tabpanel"
                                 aria-labelledby="information-tab">
                                @include('lms.web.default.bundle.tabs.information')
                            </div>
                            <div class="tab-pane fade {{ (request()->get('tab','') == 'content') ? 'show active' : '' }}" id="content" role="tabpanel" aria-labelledby="content-tab">
                                @include('lms.web.default.bundle.tabs.content')
                            </div>
                            <div class="tab-pane fade {{ (request()->get('tab','') == 'reviews') ? 'show active' : '' }}" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                @include('lms.web.default.bundle.tabs.reviews')
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="course-content-sidebar col-12 col-lg-4 mt-25 mt-lg-0">
                <div class="rounded-lg shadow-sm">
                    <div class="course-img {{ $bundle->video_demo ? 'has-video' :'' }}">

                        <img src="{{get_path_lms()}}{{ $bundle->getImage() }}" class="img-cover" alt="">

                        @if($bundle->video_demo)
                            <div id="webinarDemoVideoBtn"
                                 data-video-path="{{ $bundle->video_demo_source == 'upload' ?  url($bundle->video_demo) : $bundle->video_demo }}"
                                 data-video-source="{{ $bundle->video_demo_source }}"
                                 class="course-video-icon cursor-pointer d-flex align-items-center justify-content-center">
                                <i data-feather="play" width="25" height="25"></i>
                            </div>
                        @endif
                    </div>

                    <div class="px-20 pb-30">
                        <form action="/lms/cart/store" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="item_id" value="{{ $bundle->id }}">
                            <input type="hidden" name="item_name" value="bundle_id">

                            @if(!empty($bundle->tickets))
                                @foreach($bundle->tickets as $ticket)

                                    <div class="form-check mt-20">
                                        <input class="form-check-input" @if(!$ticket->isValid()) disabled @endif type="radio"
                                               data-discount="{{ $ticket->discount }}"
                                               data-currency
                                               value="{{ ($ticket->isValid()) ? $ticket->id : '' }}"
                                               name="ticket_id"
                                               id="courseOff{{ $ticket->id }}">
                                        <label class="form-check-label d-flex flex-column cursor-pointer" for="courseOff{{ $ticket->id }}">
                                            <span class="font-16 font-weight-500 text-dark-blue">{{ $ticket->title }} @if(!empty($ticket->discount))
                                                    ({{ $ticket->discount }}% {{ trans('lms/public.off') }})
                                                @endif</span>
                                            <span class="font-14 text-gray">{{ $ticket->getSubTitle() }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif

                            @if($bundle->price > 0)
                                <div id="priceBox" class="d-flex align-items-center justify-content-center mt-20 {{ !empty($activeSpecialOffer) ? ' flex-column ' : '' }}">
                                    <div class="text-center">
                                        @php
                                            $realPrice = handleCoursePagePrice($bundle->price);
                                        @endphp
                                        <span id="realPrice" data-value="{{ $bundle->price }}"
                                              data-special-offer="{{ !empty($activeSpecialOffer) ? $activeSpecialOffer->percent : ''}}"
                                              class="d-block @if(!empty($activeSpecialOffer)) font-16 text-gray text-decoration-line-through @else font-30 text-primary @endif">
                                            {{ $realPrice['price'] }}
                                        </span>

                                        @if(!empty($realPrice['tax']) and empty($activeSpecialOffer))
                                            <span class="d-block font-14 text-gray">+ {{ $realPrice['tax'] }} tax</span>
                                        @endif
                                    </div>

                                    @if(!empty($activeSpecialOffer))
                                        <div class="text-center">
                                            @php
                                                $priceWithDiscount = handleCoursePagePrice($bundle->getPrice());
                                            @endphp
                                            <span id="priceWithDiscount"
                                                  class="d-block font-30 text-primary">
                                                {{ $priceWithDiscount['price'] }}
                                            </span>

                                            @if(!empty($priceWithDiscount['tax']))
                                                <span class="d-block font-14 text-gray">+ {{ $priceWithDiscount['tax'] }} tax</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center mt-20">
                                    <span class="font-36 text-primary">{{ trans('lms/public.free') }}</span>
                                </div>
                            @endif

                            @php
                                $canSale = ($bundle->canSale() and !$hasBought);
                            @endphp

                            <div class="mt-20 d-flex flex-column">
                                @if($hasBought or !empty($bundle->getInstallmentOrder()))
                                    <button type="button" class="btn btn-primary" disabled>{{ trans('lms/panel.purchased') }}</button>
                                @elseif($bundle->price > 0)
                                    <button type="{{ $canSale ? 'submit' : 'button' }}" @if(!$canSale) disabled @endif class="btn btn-primary">
                                        @if(!$canSale)
                                            {{ trans('lms/update.disabled_add_to_cart') }}
                                        @else
                                            {{ trans('lms/public.add_to_cart') }}
                                        @endif
                                    </button>

                                    @if($canSale and $bundle->subscribe)
                                        <a href="/lms/subscribes/apply/bundle/{{ $bundle->slug }}" class="btn btn-outline-primary btn-subscribe mt-20 @if(!$canSale) disabled @endif">{{ trans('lms/public.subscribe') }}</a>
                                    @endif

                                    @if($canSale and !empty($bundle->points))
                                        <a href="/lms{{ !(auth()->guard('lms_user')->check()) ? '/login' : '#' }}" class="{{ (auth()->guard('lms_user')->check()) ? 'js-buy-with-point' : '' }} btn btn-outline-warning mt-20 {{ (!$canSale) ? 'disabled' : '' }}" rel="nofollow">
                                            {!! trans('lms/update.buy_with_n_points',['points' => $bundle->points]) !!}
                                        </a>
                                    @endif

                                    @if($canSale and !empty(getFeaturesSettings('direct_bundles_payment_button_status')))
                                        <button type="button" class="btn btn-outline-danger mt-20 js-bundle-direct-payment">
                                            {{ trans('lms/update.buy_now') }}
                                        </button>
                                    @endif
                                @else
                                    <a href="/lms{{ $canSale ? '/bundles/'. $bundle->slug .'/free' : '#' }}" class="btn btn-primary @if(!$canSale) disabled @endif">{{ trans('lms/update.enroll_on_bundle') }}</a>
                                @endif
                            </div>

                        </form>

                        @if(!empty(getOthersPersonalizationSettings('show_guarantee_text')) and getOthersPersonalizationSettings('show_guarantee_text'))
                            <div class="mt-20 d-flex align-items-center justify-content-center text-gray">
                                <i data-feather="thumbs-up" width="20" height="20"></i>
                                <span class="ml-5 font-14">{{ getOthersPersonalizationSettings('guarantee_text') }}</span>
                            </div>
                        @endif

                        <div class="mt-40 p-10 rounded-sm border row align-items-center favorites-share-box">

                            <div class="col">
                                <a href="/lms/bundles/{{ $bundle->slug }}/favorite" id="favoriteToggle" class="d-flex flex-column align-items-center text-gray">
                                    <i data-feather="heart" class="{{ !empty($isFavorite) ? 'favorite-active' : '' }}" width="20" height="20"></i>
                                    <span class="font-12">{{ trans('lms/panel.favorite') }}</span>
                                </a>
                            </div>

                            <div class="col">
                                <a href="/lms#" class="js-share-course d-flex flex-column align-items-center text-gray">
                                    <i data-feather="share-2" width="20" height="20"></i>
                                    <span class="font-12">{{ trans('lms/public.share') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cashback Alert --}}
                @include('lms.web.default.includes.cashback_alert',['itemPrice' => $bundle->price])

                {{-- Gift Card --}}
                @if($bundle->canSale() and !empty(getGiftsGeneralSettings('status')) and !empty(getGiftsGeneralSettings('allow_sending_gift_for_bundles')))
                    <a href="/lms/gift/bundle/{{ $bundle->slug }}" class="d-flex align-items-center mt-30 rounded-lg border p-15">
                        <div class="size-40 d-flex-center rounded-circle bg-gray200">
                            <i data-feather="gift" class="text-gray" width="20" height="20"></i>
                        </div>
                        <div class="ml-5">
                            <h4 class="font-14 font-weight-bold text-gray">{{ trans('lms/update.gift_this_bundle') }}</h4>
                            <p class="font-12 text-gray">{{ trans('lms/update.gift_this_bundle_hint') }}</p>
                        </div>
                    </a>
                @endif

                @if($bundle->teacher->offline)
                    <div class="rounded-lg shadow-sm mt-35 d-flex">
                        <div class="offline-icon offline-icon-left d-flex align-items-stretch">
                            <div class="d-flex align-items-center">
                                <img src="/assets/lms/assets/default/img/profile/time-icon.png" alt="offline">
                            </div>
                        </div>

                        <div class="p-15">
                            <h3 class="font-16 text-dark-blue">{{ trans('lms/public.instructor_is_not_available') }}</h3>
                            <p class="font-14 font-weight-500 text-gray mt-15">{{ $bundle->teacher->offline_message }}</p>
                        </div>
                    </div>
                @endif

                <div class="rounded-lg shadow-sm mt-35 px-25 py-20">
                    <h3 class="sidebar-title font-16 text-secondary font-weight-bold">{{ trans('lms/update.bundle_specifications') }}</h3>

                    <div class="mt-30">
                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <i data-feather="clock" width="20" height="20"></i>
                                <span class="ml-5 font-14 font-weight-500">{{ trans('lms/public.duration') }}:</span>
                            </div>
                            <span class="font-14">{{ convertMinutesToHourAndMinute($bundle->getBundleDuration()) }} {{ trans('lms/home.hours') }}</span>
                        </div>

                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <i data-feather="users" width="20" height="20"></i>
                                <span class="ml-5 font-14 font-weight-500">{{ trans('lms/quiz.students') }}:</span>
                            </div>
                            <span class="font-14">{{ $bundle->sales_count }}</span>
                        </div>

                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <img src="/assets/lms/assets/default/img/icons/sessions.svg" width="20" alt="">
                                <span class="ml-5 font-14 font-weight-500">{{ trans('lms/product.courses') }}:</span>
                            </div>
                            <span class="font-14">{{ $bundle->bundleWebinars->count() }}</span>
                        </div>

                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <img src="/assets/lms/assets/default/img/icons/sessions.svg" width="20" alt="">
                                <span class="ml-5 font-14 font-weight-500">{{ trans('lms/public.created_at') }}:</span>
                            </div>
                            <span class="font-14">{{ dateTimeFormat($bundle->created_at,'j M Y') }}</span>
                        </div>

                        @if(!empty($bundle->access_days))
                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <i data-feather="alert-circle" width="20" height="20"></i>
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('lms/update.access_period') }}:</span>
                                </div>
                                <span class="font-14">{{ $bundle->access_days }} {{ trans('lms/public.days') }}</span>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- organization --}}
                @if($bundle->creator_id != $bundle->teacher_id)
                    @include('lms.web.default.course.sidebar_instructor_profile', ['courseTeacher' => $bundle->creator])
                @endif
                {{-- teacher --}}
                @include('lms.web.default.course.sidebar_instructor_profile', ['courseTeacher' => $bundle->teacher])
                {{-- ./ teacher --}}

                {{-- tags --}}
                @if($bundle->tags->count() > 0)
                    <div class="rounded-lg tags-card shadow-sm mt-35 px-25 py-20">
                        <h3 class="sidebar-title font-16 text-secondary font-weight-bold">{{ trans('lms/public.tags') }}</h3>

                        <div class="d-flex flex-wrap mt-10">
                            @foreach($bundle->tags as $tag)
                                <a href="/lms" class="tag-item bg-gray200 p-5 font-14 text-gray font-weight-500 rounded">{{ $tag->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- ads --}}
                @if(!empty($advertisingBannersSidebar) and count($advertisingBannersSidebar))
                    <div class="row">
                        @foreach($advertisingBannersSidebar as $sidebarBanner)
                            <div class="rounded-lg sidebar-ads mt-35 col-{{ $sidebarBanner->size }}">
                                <a href="/lms{{ $sidebarBanner->link }}">
                                    <img src="{{get_path_lms()}}{{ $sidebarBanner->image }}" class="img-cover rounded-lg" alt="{{ $sidebarBanner->title }}">
                                </a>
                            </div>
                        @endforeach
                    </div>

                @endif
            </div>
        </div>

        {{-- Ads Bannaer --}}
        @if(!empty($advertisingBanners) and count($advertisingBanners))
            <div class="mt-30 mt-md-50">
                <div class="row">
                    @foreach($advertisingBanners as $banner)
                        <div class="col-{{ $banner->size }}">
                            <a href="/lms{{ $banner->link }}">
                                <img src="{{get_path_lms()}}{{ $banner->image }}" class="img-cover rounded-sm" alt="{{ $banner->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- ./ Ads Bannaer --}}
    </section>

    @include('lms.web.default.bundle.share_modal')
    @include('lms.web.default.bundle.buy_with_point_modal')
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/video/vimeo.js"></script>

    <script>
        var webinarDemoLang = '{{ trans('lms/webinars.webinar_demo') }}';
        var replyLang = '{{ trans('lms/panel.reply') }}';
        var closeLang = '{{ trans('lms/public.close') }}';
        var saveLang = '{{ trans('lms/public.save') }}';
        var reportLang = '{{ trans('lms/panel.report') }}';
        var reportSuccessLang = '{{ trans('lms/panel.report_success') }}';
        var reportFailLang = '{{ trans('lms/panel.report_fail') }}';
        var messageToReviewerLang = '{{ trans('lms/public.message_to_reviewer') }}';
        var copyLang = '{{ trans('lms/public.copy') }}';
        var copiedLang = '{{ trans('lms/public.copied') }}';
        var learningToggleLangSuccess = '{{ trans('lms/public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('lms/public.course_learning_change_status_error') }}';
        var notLoginToastTitleLang = '{{ trans('lms/public.not_login_toast_lang') }}';
        var notLoginToastMsgLang = '{{ trans('lms/public.not_login_toast_msg_lang') }}';
        var notAccessToastTitleLang = '{{ trans('lms/public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('lms/public.not_access_toast_msg_lang') }}';
        var canNotTryAgainQuizToastTitleLang = '{{ trans('lms/public.can_not_try_again_quiz_toast_lang') }}';
        var canNotTryAgainQuizToastMsgLang = '{{ trans('lms/public.can_not_try_again_quiz_toast_msg_lang') }}';
        var canNotDownloadCertificateToastTitleLang = '{{ trans('lms/public.can_not_download_certificate_toast_lang') }}';
        var canNotDownloadCertificateToastMsgLang = '{{ trans('lms/public.can_not_download_certificate_toast_msg_lang') }}';
        var sessionFinishedToastTitleLang = '{{ trans('lms/public.session_finished_toast_title_lang') }}';
        var sessionFinishedToastMsgLang = '{{ trans('lms/public.session_finished_toast_msg_lang') }}';
        var sequenceContentErrorModalTitle = '{{ trans('lms/update.sequence_content_error_modal_title') }}';

    </script>

    <script src="/assets/lms/assets/default/js/parts/comment.min.js"></script>
    <script src="/assets/lms/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/lms/assets/default/js/parts/webinar_show.min.js"></script>
@endpush
