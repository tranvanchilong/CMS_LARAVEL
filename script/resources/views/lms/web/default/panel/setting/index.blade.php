@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    @if(empty($new_user))
        @include('lms.web.default.panel.setting.setting_includes.progress')
    @endif

    <form method="post" id="userSettingForm" class="mt-30" action="/lms{{ (!empty($new_user)) ? '/panel/manage/'. $user_type .'/new' : '/panel/setting' }}">
        {{ csrf_field() }}
        <input type="hidden" name="step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
        <input type="hidden" name="next_step" value="0">

        @if(!empty($organization_id))
            <input type="hidden" name="organization_id" value="{{ $organization_id }}">
            <input type="hidden" id="userId" name="user_id" value="{{ $user->id }}">
        @endif

        @if(!empty($new_user) or (!empty($currentStep) and $currentStep == 1))
            @include('lms.web.default.panel.setting.setting_includes.basic_information')
        @endif

        @if(empty($new_user) and !empty($currentStep))
            @switch($currentStep)
                @case(2)
                @include('lms.web.default.panel.setting.setting_includes.image')
                @break

                @case(3)
                @include('lms.web.default.panel.setting.setting_includes.about')
                @break

                @case(4)
                @include('lms.web.default.panel.setting.setting_includes.education')
                @break

                @case(5)
                @include('lms.web.default.panel.setting.setting_includes.experiences')
                @break

                @case(6)
                @include('lms.web.default.panel.setting.setting_includes.occupations')
                @break

                @case(7)
                @include('lms.web.default.panel.setting.setting_includes.identity_and_financial')
                @break

                @case(8)
                @if(!$user->isUser())
                    @include('lms.web.default.panel.setting.setting_includes.zoom_api')
                @endif
                @break

                @case(9)
                @if(!$user->isUser())
                    @include('lms.web.default.panel.setting.setting_includes.settings')
                @endif
                @break
            @endswitch
        @endif
    </form>

    <div class="create-webinar-footer d-flex align-items-center justify-content-between mt-20 pt-15 border-top">
        <div class="d-flex align-items-center">
            @if(!empty($user) and empty($new_user))
                @if(!empty($currentStep) and $currentStep > 1)
                    <a href="/lms/panel/setting/step/{{ ($currentStep - 1) }}" class="btn btn-sm btn-primary">{{ trans('lms/webinars.previous') }}</a>
                @else
                    <a href="/lms" class="btn btn-sm btn-primary disabled">{{ trans('lms/webinars.previous') }}</a>
                @endif

                <button type="button" id="getNextStep" class="btn btn-sm btn-primary ml-15" @if(!empty($currentStep) and (($user->isUser() and $currentStep == 7) or (!$user->isUser() and $currentStep == 9))) disabled @endif>{{ trans('lms/webinars.next') }}</button>
            @endif
        </div>

        <div class="d-flex align-items-center">
            @if(empty($new_user) and empty($edit_new_user))
                <a href="/lms/panel/setting/deleteAccount" class="delete-action btn btn-sm btn-danger" data-confirm="{{ trans('lms/update.delete_account_modal_confirm_btn_text') }}" data-title="{{ trans('lms/update.delete_account_modal_hint') }}">{{ trans('lms/update.delete_account') }}</a>
            @endif

            <button type="button" id="saveData" class="btn btn-sm btn-primary ml-15">{{ trans('lms/public.save') }}</button>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/vendors/cropit/jquery.cropit.js"></script>
    <script src="/assets/lms/assets/default/js/parts/img_cropit.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>

    <script>
        var editEducationLang = '{{ trans('lms/site.edit_education') }}';
        var editExperienceLang = '{{ trans('lms/site.edit_experience') }}';
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
        var saveErrorLang = '{{ trans('lms/site.store_error_try_again') }}';
        var notAccessToLang = '{{ trans('lms/public.not_access_to_this_content') }}';
    </script>

    <script src="/assets/lms/assets/default/js/panel/user_setting.min.js"></script>
@endpush
