@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.main_general') }} {{ trans('lms/admin/main.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}/settings">{{ trans('lms/admin/main.settings') }}</a></div>
                <div class="breadcrumb-item ">{{ trans('lms/admin/main.main_general') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link @if(empty($social)) active @endif" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">{{ trans('lms/admin/main.basic') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link @if(!empty($social)) active @endif" id="socials-tab" data-toggle="tab" href="#socials" role="tab" aria-controls="socials" aria-selected="true">{{ trans('lms/admin/main.socials') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="features-tab" data-toggle="tab" href="#features" role="tab" aria-controls="features" aria-selected="true">{{ trans('lms/update.features') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="reminders-tab" data-toggle="tab" href="#reminders" role="tab" aria-controls="reminders" aria-selected="true">{{ trans('lms/update.reminders') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="true">{{ trans('lms/update.security') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="general_options-tab" data-toggle="tab" href="#general_options" role="tab" aria-controls="general_options" aria-selected="true">{{ trans('lms/update.options') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                @include('lms.admin.settings.general.basic',['itemValue' => (!empty($settings) and !empty($settings['general'])) ? $settings['general']->value : ''])
                                @include('lms.admin.settings.general.socials',['itemValue' => (!empty($settings) and !empty($settings['socials'])) ? $settings['socials']->value : ''])
                                @include('lms.admin.settings.general.features',['itemValue' => (!empty($settings) and !empty($settings['features'])) ? $settings['features']->value : ''])
                                @include('lms.admin.settings.general.reminders',['itemValue' => (!empty($settings) and !empty($settings['reminders'])) ? $settings['reminders']->value : ''])
                                @include('lms.admin.settings.general.security',['itemValue' => (!empty($settings) and !empty($settings['security'])) ? $settings['security']->value : ''])
                                @include('lms.admin.settings.general.options',['itemValue' => (!empty($settings) and !empty($settings['general_options'])) ? $settings['general_options']->value : ''])
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/lms/assets/default/js/admin/settings/general.min.js"></script>
@endpush