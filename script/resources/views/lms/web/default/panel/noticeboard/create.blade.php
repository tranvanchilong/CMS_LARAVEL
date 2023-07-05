@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/panel.new_noticeboard') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/lms/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ !empty($noticeboard) ? $noticeboard->id.'/update' : 'store' }}" method="post">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="input-label control-label" for="inputDefault">{!! trans('lms/public.title') !!}</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ !empty($noticeboard) ? $noticeboard->title : old('title') }}">
                            <div class="invalid-feedback">@error('title') {{ $message }} @enderror</div>
                        </div>

                        @if(!empty($isCourseNotice) and $isCourseNotice)
                            <div class="form-group">
                                <label class="input-label control-label">{!! trans('lms/product.course') !!}</label>
                                <select name="webinar_id" class="form-control @error('webinar_id') is-invalid @enderror">
                                    <option value="" selected disabled>{{ trans('lms/panel.select_course') }}</option>

                                    @foreach($webinars as $webinar)
                                        <option value="{{ $webinar->id }}" @if(!empty($noticeboard) and $noticeboard->webinar_id == $webinar->id) selected @endif>{{ $webinar->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">@error('webinar_id') {{ $message }} @enderror</div>
                            </div>


                            <div class="form-group">
                                <label class="input-label control-label">{!! trans('lms/update.color') !!}</label>
                                <select name="color" id="colorSelect" class="form-control @error('color') is-invalid @enderror">
                                    <option value="" selected disabled>{{ trans('lms/update.select_a_color') }}</option>

                                    @foreach(\App\Models\LMS\CourseNoticeboard::$colors as $color)
                                        <option value="{{ $color }}" @if(!empty($noticeboard) and $noticeboard->color == $color) selected @endif>{{ trans('lms/update.course_noticeboard_color_'.$color) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">@error('color') {{ $message }} @enderror</div>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="input-label control-label">{!! trans('lms/admin/main.type') !!}</label>
                                <select name="type" id="typeSelect" class="form-control @error('type') is-invalid @enderror">
                                    <option value="" selected disabled>{{ trans('lms/admin/main.select_type') }}</option>

                                    @if($authUser->isOrganization())
                                        @foreach(\App\Models\LMS\Noticeboard::$types as $type)
                                            <option value="{{ $type }}" @if(!empty($noticeboard) and $noticeboard->type == $type) selected @endif>{{ trans('lms/public.'.$type) }}</option>
                                        @endforeach
                                    @else
                                        <option value="students" @if(!empty($noticeboard) and empty($noticeboard->webinar_id)) selected @endif>{{ trans('lms/update.all_students') }}</option>
                                        <option value="course" @if(!empty($noticeboard) and !empty($noticeboard->webinar_id)) selected @endif>{{ trans('lms/update.course_students') }}</option>
                                    @endif

                                </select>         
                                <div>
                                    <p class="font-12 text-gray">{{ trans('lms/update.new_notice_hint') }}</p>
                                </div>
                                <div class="invalid-feedback">@error('type') {{ $message }} @enderror</div>
                            </div>

                            @if($authUser->isTeacher())
                                <div class="form-group {{ (!empty($noticeboard) and !empty($noticeboard->webinar_id)) ? '' : 'd-none' }}" id="instructorCourses">
                                    <label class="input-label control-label">{!! trans('lms/product.course') !!}</label>
                                    <select name="webinar_id" class="form-control @error('webinar_id') is-invalid @enderror">
                                        <option value="" selected disabled>{{ trans('lms/panel.select_course') }}</option>

                                        @foreach($webinars as $webinar)
                                            <option value="{{ $webinar->id }}" @if(!empty($noticeboard) and $noticeboard->webinar_id == $webinar->id) selected @endif>{{ $webinar->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">@error('webinar_id') {{ $message }} @enderror</div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="form-group ">
                    <label class="input-label control-label">{{ trans('lms/site.message') }}</label>
                    <textarea name="message" class="summernote form-control text-left  @error('message') is-invalid @enderror">{{ (!empty($noticeboard)) ? $noticeboard->message :'' }}</textarea>
                    <div class="invalid-feedback">@error('message') {{ $message }} @enderror</div>
                </div>

                <div class="form-group">
                    <button id="submitForm" class="btn btn-primary btn-sm" type="button">{{ trans('lms/notification.post_notice') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        var noticeboard_success_send = '{{ trans('lms/panel.noticeboard_success_send') }}';
    </script>

    <script src="/assets/lms/assets/default/js/panel/noticeboard.min.js"></script>
@endpush
