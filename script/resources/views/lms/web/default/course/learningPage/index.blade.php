@extends('lms.web.default.layouts.app',['appFooter' => false, 'appHeader' => false])

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/learning_page/styles.css"/>
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/video/video-js.min.css">
@endpush

@section('content')

    <div class="learning-page">

        @include('lms.web.default.course.learningPage.components.navbar')

        <div class="d-flex position-relative">
            <div class="learning-page-content flex-grow-1 bg-info-light p-15">
                @include('lms.web.default.course.learningPage.components.content')
            </div>

            <div class="learning-page-tabs show">
                <ul class="nav nav-tabs py-15 d-flex align-items-center justify-content-around" id="tabs-tab" role="tablist">
                    <li class="nav-item">
                        <a class="position-relative font-14 d-flex align-items-center active" id="content-tab"
                           data-toggle="tab" href="#content" role="tab" aria-controls="content"
                           aria-selected="true">
                            <i class="learning-page-tabs-icons mr-5">
                                @include('lms.web.default.panel.includes.sidebar_icons.webinars')
                            </i>
                            <span class="learning-page-tabs-link-text">{{ trans('lms/product.content') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="position-relative font-14 d-flex align-items-center" id="quizzes-tab" data-toggle="tab"
                           href="#quizzes" role="tab" aria-controls="quizzes"
                           aria-selected="false">
                            <i class="learning-page-tabs-icons mr-5">
                                @include('lms.web.default.panel.includes.sidebar_icons.quizzes')
                            </i>
                            <span class="learning-page-tabs-link-text">{{ trans('lms/quiz.quizzes') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="position-relative font-14 d-flex align-items-center" id="certificates-tab" data-toggle="tab"
                           href="#certificates" role="tab" aria-controls="certificates"
                           aria-selected="false">
                            <i class="learning-page-tabs-icons mr-5">
                                @include('lms.web.default.panel.includes.sidebar_icons.certificate')
                            </i>
                            <span class="learning-page-tabs-link-text">{{ trans('lms/panel.certificates') }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content h-100" id="nav-tabContent">
                    <div class="pb-20 tab-pane fade show active h-100" id="content" role="tabpanel"
                         aria-labelledby="content-tab">
                        @include('lms.web.default.course.learningPage.components.content_tab.index')
                    </div>

                    <div class="pb-20 tab-pane fade  h-100" id="quizzes" role="tabpanel"
                         aria-labelledby="quizzes-tab">
                        @include('lms.web.default.course.learningPage.components.quiz_tab.index')
                    </div>

                    <div class="pb-20 tab-pane fade  h-100" id="certificates" role="tabpanel"
                         aria-labelledby="certificates-tab">
                        @include('lms.web.default.course.learningPage.components.certificate_tab.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/video/vimeo.js"></script>

    <script>
        var defaultItemType = '{{ request()->get('type') }}'
        var defaultItemId = '{{ request()->get('item') }}'
        var loadFirstContent = {{ (!empty($dontAllowLoadFirstContent) and $dontAllowLoadFirstContent) ? 'false' : 'true' }}; // allow to load first content when request item is empty

        var courseUrl = '{{ $course->getUrl() }}';

        // lang
        var pleaseWaitForTheContentLang = '{{ trans('lms/update.please_wait_for_the_content_to_load') }}';
        var downloadTheFileLang = '{{ trans('lms/update.download_the_file') }}';
        var downloadLang = '{{ trans('lms/home.download') }}';
        var showHtmlFileLang = '{{ trans('lms/update.show_html_file') }}';
        var showLang = '{{ trans('lms/update.show') }}';
        var sessionIsLiveLang = '{{ trans('lms/update.session_is_live') }}';
        var youCanJoinTheLiveNowLang = '{{ trans('lms/update.you_can_join_the_live_now') }}';
        var joinTheClassLang = '{{ trans('lms/update.join_the_class') }}';
        var coursePageLang = '{{ trans('lms/update.course_page') }}';
        var quizPageLang = '{{ trans('lms/update.quiz_page') }}';
        var sessionIsNotStartedYetLang = '{{ trans('lms/update.session_is_not_started_yet') }}';
        var thisSessionWillBeStartedOnLang = '{{ trans('lms/update.this_session_will_be_started_on') }}';
        var sessionIsFinishedLang = '{{ trans('lms/update.session_is_finished') }}';
        var sessionIsFinishedHintLang = '{{ trans('lms/update.this_session_is_finished_You_cant_join_it') }}';
        var goToTheQuizPageForMoreInformationLang = '{{ trans('lms/update.go_to_the_quiz_page_for_more_information') }}';
        var downloadCertificateLang = '{{ trans('lms/update.download_certificate') }}';
        var enjoySharingYourCertificateWithOthersLang = '{{ trans('lms/update.enjoy_sharing_your_certificate_with_others') }}';
        var attachmentsLang = '{{ trans('lms/public.attachments') }}';
        var checkAgainLang = '{{ trans('lms/update.check_again') }}';
        var learningToggleLangSuccess = '{{ trans('lms/public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('lms/public.course_learning_change_status_error') }}';
        var sequenceContentErrorModalTitle = '{{ trans('lms/update.sequence_content_error_modal_title') }}';
        var sendAssignmentSuccessLang = '{{ trans('lms/update.send_assignment_success') }}';
        var saveAssignmentRateSuccessLang = '{{ trans('lms/update.save_assignment_grade_success') }}';
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
        var changesSavedSuccessfullyLang = '{{ trans('lms/update.changes_saved_successfully') }}';
        var oopsLang = '{{ trans('lms/update.oops') }}';
        var somethingWentWrongLang = '{{ trans('lms/update.something_went_wrong') }}';
        var notAccessToastTitleLang = '{{ trans('lms/public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('lms/public.not_access_toast_msg_lang') }}';
        var cantStartQuizToastTitleLang = '{{ trans('lms/public.request_failed') }}';
        var cantStartQuizToastMsgLang = '{{ trans('lms/quiz.cant_start_quiz') }}';
        var learningPageEmptyContentTitleLang = '{{ trans('lms/update.learning_page_empty_content_title') }}';
        var learningPageEmptyContentHintLang = '{{ trans('lms/update.learning_page_empty_content_hint') }}';
        var expiredQuizLang = '{{ trans('lms/update.expired_quiz') }}';
    </script>
    <script type="text/javascript" src="/assets/lms/assets/default/vendors/dropins/dropins.js"></script>
    <script src="/assets/lms/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    <script src="/assets/lms/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/lms/assets/learning_page/scripts.min.js"></script>

    @if((!empty($isForumPage) and $isForumPage) or (!empty($isForumAnswersPage) and $isForumAnswersPage))
        <script src="/assets/lms/assets/learning_page/forum.min.js"></script>
    @endif
@endpush
