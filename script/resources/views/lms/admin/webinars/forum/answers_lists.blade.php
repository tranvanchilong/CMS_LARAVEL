@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('lms/admin/main.classes')}}</div>

                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body ">

                            {{-- Question Card --}}
                            <div class="d-flex align-items-start mb-3 border rounded-lg p-2">
                                <img class="avatar mr-2" src="{{ $question->user->getAvatar() }}">

                                <div class="ml-2">
                                    <div class="font-weight-bold">{{ $question->user->full_name }}</div>

                                    <h3 class="mt-2 font-16 font-weight-bold">{{ $question->title }}</h3>
                                    <div class="mt-1">{!! $question->description !!}</div>

                                    <div class="mt-2">
                                        <span class="mr-2">{{ dateTimeFormat($question->created_at,'Y M j | H:i') }}</span>

                                        @if(!empty($question->attach))
                                            <a href="/lms{{ $course->getForumPageUrl() }}/{{ $question->id }}/downloadAttach" target="_blank" class="text-success"><i class="fa fa-paperclip"></i> {{ trans('lms/admin/main.open_attach') }}</a>
                                        @endif

                                        <button type="button" data-action="/lms{{ getAdminPanelUrl() }}/webinars/{{ $course->id }}/forums/{{ $question->id }}/edit" class="js-answer-edit btn-transparent ml-2 font-14 font-weight-500 text-gray">{{ trans('lms/public.edit') }}</button>

                                        @include('lms.admin.includes.delete_button', [
                                            'url' => "/admin/webinars/$course->id/forums/$question->id/delete",
                                            'btnText' => trans('lms/admin/main.delete'),
                                            'btnClass' => 'ml-2 font-14 font-weight-500 text-danger'
                                        ])
                                    </div>
                                </div>
                            </div>

                            {{-- Answers Cards --}}

                            @foreach($answers as $answer)
                                <div class="d-flex align-items-start mb-3 border rounded-lg p-2 {{ $answer->resolved ? 'border-danger' : '' }}">
                                    <img src="{{ $answer->user->getAvatar() }}">

                                    <div class="ml-2">

                                        <div class="font-weight-bold">{{ $answer->user->full_name }}</div>

                                        <div class="mt-1">{!! $answer->description !!}</div>

                                        <div class="d-flex align-items-center mt-2">
                                            <span class="">{{ dateTimeFormat($answer->created_at,'Y M j | H:i') }}</span>

                                            @if($answer->resolved)
                                                <span class="text-danger ml-2">{{ trans('lms/update.resolved') }}</span>
                                            @endif

                                            @if($answer->pin)
                                                <span class="text-success ml-2">{{ trans('lms/update.pin') }}</span>
                                            @endif

                                            <button type="button" data-action="/lms{{ getAdminPanelUrl() }}/webinars/{{ $course->id }}/forums/{{ $question->id }}/answers/{{ $answer->id }}/edit" class="js-answer-edit btn-transparent ml-2 font-14 font-weight-500 text-gray">{{ trans('lms/public.edit') }}</button>

                                            @include('lms.admin.includes.delete_button', [
                                                'url' => "/admin/webinars/$course->id/forums/$question->id/answers/$answer->id/delete",
                                                'btnText' => trans('lms/admin/main.delete'),
                                                'btnClass' => 'ml-2 font-14 font-weight-500 text-danger'
                                            ])
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        var editPostLang = '{{ trans('lms/update.edit_post') }}';
        var titleLang = '{{ trans('lms/public.title') }}';
        var descriptionLang = '{{ trans('lms/public.description') }}';
        var sendLang = '{{ trans('lms/update.send') }}';
        var oopsLang = '{{ trans('lms/update.oops') }}';
        var somethingWentWrongLang = '{{ trans('lms/update.something_went_wrong') }}';
        var editAttachmentLabelLang = '{{ trans('lms/update.attach_a_file') }} ({{ trans('lms/public.optional') }})';
        var savedSuccessfullyLang = '{{ trans('lms/update.saved_successfully') }}'
    </script>
    <script src="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/lms/assets/default/js/admin/course-forum-answers.min.js"></script>
@endpush
