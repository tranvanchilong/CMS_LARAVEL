@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')

@endpush

@section('content')
    <div class="">

        <form method="post" action="/lms/panel/upcoming_courses/{{ !empty($upcomingCourse) ? $upcomingCourse->id .'/update' : 'store' }}" id="upcomingCourseForm" class="webinar-form">
            @include('lms.web.default.panel.upcoming_courses.create_includes.progress')

            {{ csrf_field() }}
            <input type="hidden" name="current_step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
            <input type="hidden" name="draft" value="no" id="forDraft"/>
            <input type="hidden" name="get_next" value="no" id="getNext"/>
            <input type="hidden" name="get_step" value="0" id="getStep"/>


            @if($currentStep == 1)
                @include('lms.web.default.panel.upcoming_courses.create_includes.step_1')
            @elseif(!empty($upcomingCourse))
                @include('lms.web.default.panel.upcoming_courses.create_includes.step_'.$currentStep)
            @endif

        </form>


        <div class="create-webinar-footer d-flex flex-column flex-md-row align-items-center justify-content-between mt-20 pt-15 border-top">
            <div class="d-flex align-items-center">

                @if(!empty($upcomingCourse))
                    <a href="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/step/{{ ($currentStep - 1) }}" class="btn btn-sm btn-primary {{ $currentStep < 2 ? 'disabled' : '' }}">{{ trans('lms/webinars.previous') }}</a>
                @else
                    <a href="/lms" class="btn btn-sm btn-primary disabled">{{ trans('lms/webinars.previous') }}</a>
                @endif

                <button type="button" id="getNextStep" class="btn btn-sm btn-primary ml-15" @if($currentStep >= 8) disabled @endif>{{ trans('lms/webinars.next') }}</button>
            </div>

            <div class="mt-20 mt-md-0">
                <button type="button" id="sendForReview" class="btn btn-sm btn-primary">{{ trans('lms/public.send_for_review') }}</button>

                <button type="button" id="saveAsDraft" class=" btn btn-sm btn-primary">{{ trans('lms/public.save_as_draft') }}</button>

                @if(!empty($upcomingCourse) and $upcomingCourse->creator_id == $authUser->id)
                    <a href="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/delete?redirect_to=/panel/upcoming_courses" class="delete-action webinar-actions btn btn-sm btn-danger mt-20 mt-md-0">{{ trans('lms/public.delete') }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
    </script>

    <script src="/assets/lms/assets/default/js/panel/create_upcoming_course.min.js"></script>
@endpush
