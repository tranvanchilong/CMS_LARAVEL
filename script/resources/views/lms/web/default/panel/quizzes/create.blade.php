@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    @include('lms.web.default.panel.quizzes.create_quiz_form')
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('lms/quiz.quizzes_section') }}';
    </script>

    <script src="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/lms/assets/default/js/panel/quiz.min.js"></script>
    <script src="/assets/lms/assets/default/js/panel/webinar_content_locale.min.js"></script>
@endpush
