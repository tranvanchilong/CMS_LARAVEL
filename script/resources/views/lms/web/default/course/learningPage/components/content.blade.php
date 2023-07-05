@php
    $showLoading = true;

    if(
        (!empty($noticeboards) and $noticeboards) or
        !empty($assignment) or
        (!empty($isForumPage) and $isForumPage) or
        (!empty($isForumAnswersPage) and $isForumAnswersPage)
    ) {
        $showLoading = false;
    }
@endphp

<div class="learning-content" id="learningPageContent">

    @if(!empty($isForumAnswersPage) and $isForumAnswersPage)
        @include('lms.web.default.course.learningPage.components.forum.forum_answers')
    @elseif(!empty($isForumPage) and $isForumPage)
        @include('lms.web.default.course.learningPage.components.forum.forum')
    @elseif(!empty($noticeboards) and $noticeboards)
        @include('lms.web.default.course.learningPage.components.noticeboards')
    @elseif(!empty($assignment))
        @include('lms.web.default.course.learningPage.components.assignment')
    @endif

    <div class="learning-content-loading align-items-center justify-content-center flex-column w-100 h-100 {{ $showLoading ? 'd-flex' : 'd-none' }}">
        <img src="/assets/lms/assets/default/img/loading.gif" alt="">
        <p class="mt-10">{{ trans('lms/update.please_wait_for_the_content_to_load') }}</p>
    </div>
</div>
