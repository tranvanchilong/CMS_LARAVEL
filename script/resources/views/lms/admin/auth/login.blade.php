@extends('lms.admin.auth.auth_layout')

@section('content')
    @php
        $siteGeneralSettings = getGeneralSettings();
    @endphp

    <div class="p-4 m-3">
        <img src="{{get_path_lms()}}{{ $siteGeneralSettings['logo'] ?? '' }}" alt="logo" width="40%" class="mb-5 mt-2">

        <h4 class="text-dark font-weight-normal">{{ trans('lms/admin/main.welcome') }} <span class="font-weight-bold">{{ $siteGeneralSettings['site_name'] ?? '' }}</span></h4>

        <p class="text-muted">{{ trans('lms/auth.admin_tagline') }}</p>

        <form method="POST" action="/lms{{ getAdminPanelUrl() }}/login" class="needs-validation" novalidate="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="email">{{ trans('lms/auth.email') }}</label>
                <input id="email" type="email" value="{{ old('email') }}" class="form-control  @error('email')  is-invalid @enderror"
                       name="email" tabindex="1"
                       required autofocus>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <div class="d-block">
                    <label for="password" class="control-label">{{ trans('lms/auth.password') }}</label>
                </div>
                <input id="password" type="password" class="form-control  @error('password')  is-invalid @enderror"
                       name="password" tabindex="2" required>
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if(!empty(getGeneralSecuritySettings('captcha_for_admin_login')))
                @include('lms.admin.includes.captcha_input')
            @endif

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3"
                           id="remember-me">
                    <label class="custom-control-label"
                           for="remember-me">{{ trans('lms/auth.remember_me') }}</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    {{ trans('lms/auth.login') }}
                </button>
            </div>
        </form>

        <a href="/lms{{ getAdminPanelUrl() }}/forget-password" class="">{{ trans('lms/auth.forget_your_password') }}</a>
    </div>
@endsection
