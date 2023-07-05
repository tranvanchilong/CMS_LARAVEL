<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp

<head>
    @include('lms.web.default.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/css/app.css">

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

<div id="app" class="{{ (!empty($floatingBar) and $floatingBar->position == 'top' and $floatingBar->fixed) ? 'has-fixed-top-floating-bar' : '' }}">
    @if(!empty($floatingBar) and $floatingBar->position == 'top')
        @include('lms.web.default.includes.floating_bar')
    @endif

    @if(!isset($appHeader))
        @include('lms.web.default.includes.top_nav')
        @include('lms.web.default.includes.navbar')
    @endif

    @if(!empty($justMobileApp))
        @include('lms.web.default.includes.mobile_app_top_nav')
    @endif

    @yield('content')

    @if(!isset($appFooter))
        @include('lms.web.default.includes.footer')
    @endif

    @include('lms.web.default.includes.advertise_modal.index')

    @if(!empty($floatingBar) and $floatingBar->position == 'bottom')
        @include('lms.web.default.includes.floating_bar')
    @endif
</div>
<!-- Template JS File -->
<script src="/assets/lms/assets/default/js/app.js"></script>
<script src="/assets/lms/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
<script src="/assets/lms/assets/default/vendors/moment.min.js"></script>
<script src="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="/assets/lms/assets/default/vendors/toast/jquery.toast.min.js"></script>
<script type="text/javascript" src="/assets/lms/assets/default/vendors/simplebar/simplebar.min.js"></script>

@if(empty($justMobileApp) and checkShowCookieSecurityDialog())
    @include('lms.web.default.includes.cookie-security')
@endif


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

<script src="/assets/lms/assets/default/js/parts/main.min.js"></script>

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
