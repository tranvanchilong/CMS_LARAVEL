<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp
<head>
    @include('lms.' . getTemplate().'.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link href="/assets/lms/assets/default/css/font.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/css/app.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/css/panel.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/lms/assets/default/css/rtl-app.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty(getCustomCssAndJs('css')) ? getCustomCssAndJs('css') : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}
    </style>

    @if(!empty($generalSettings['preloading']) and $generalSettings['preloading'] == '1')
        @include('lms.admin.includes.preloading')
    @endif

</head>
<body class="@if($isRtl) rtl @endif">

@php
    $isPanel = true;
@endphp

<div id="panel_app">

    @include('lms.' . getTemplate().'.includes.navbar')

    <div class="d-flex justify-content-end">
        @include('lms.' . getTemplate(). '.panel.includes.sidebar')

        <div class="panel-content">
            @yield('content')
        </div>
    </div>

    @include('lms.web.default.includes.advertise_modal.index')
</div>
<!-- Template JS File -->
<script src="/assets/lms/assets/default/js/app.js"></script>
<script src="/assets/lms/assets/default/vendors/moment.min.js"></script>
<script src="/assets/lms/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
<script src="/assets/lms/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="/assets/lms/assets/default/vendors/toast/jquery.toast.min.js"></script>
<script type="text/javascript" src="/assets/lms/assets/default/vendors/simplebar/simplebar.min.js"></script>

<script>
    var deleteAlertTitle = '{{ trans('lms/public.are_you_sure') }}';
    var deleteAlertHint = '{{ trans('lms/public.deleteAlertHint') }}';
    var deleteAlertConfirm = '{{ trans('lms/public.deleteAlertConfirm') }}';
    var deleteAlertCancel = '{{ trans('lms/public.cancel') }}';
    var deleteAlertSuccess = '{{ trans('lms/public.success') }}';
    var deleteAlertFail = '{{ trans('lms/public.fail') }}';
    var deleteAlertFailHint = '{{ trans('lms/public.deleteAlertFailHint') }}';
    var deleteAlertSuccessHint = '{{ trans('lms/public.deleteAlertSuccessHint') }}';
    var forbiddenRequestToastTitleLang = '{{ trans('lms/public.forbidden_request_toast_lang') }}';
    var forbiddenRequestToastMsgLang = '{{ trans('lms/public.forbidden_request_toast_msg_lang') }}';
</script>

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            $.toast({
                heading: '{{ session()->get('toast')['title'] ?? '' }}',
                text: '{{ session()->get('toast')['msg'] ?? '' }}',
                bgColor: '@if(session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: '{{ session()->get('toast')['status'] }}'
            });
        })(jQuery)
    </script>
@endif

@stack('styles_bottom')
@stack('scripts_bottom')

<script src="/assets/lms/assets/default/js//parts/main.min.js"></script>
<script src="/assets/lms/assets/default/js/panel/public.min.js"></script>

@stack('scripts_bottom2')

<script>

    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";

        handleLimitedAccountModal('{!! session()->get('registration_package_limited') !!}')
    })(jQuery)

    {{ session()->forget('registration_package_limited') }}
    @endif

    {!! !empty(getCustomCssAndJs('js')) ? getCustomCssAndJs('js') : '' !!}
</script>
</body>
</html>
