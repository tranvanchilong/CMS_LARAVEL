@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link href="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.financial_settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}/settings">{{ trans('lms/admin/main.settings') }}</a></div>
                <div class="breadcrumb-item ">{{ trans('lms/admin/main.financial') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link  @if(empty(request()->get('tab'))) active @endif" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">{{ trans('lms/admin/main.basic') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link @if(request()->get('tab') == "offline_banks") active @endif" id="offline_banks-tab" href="/lms{{ getAdminPanelUrl("/settings/financial?tab=offline_banks") }}">{{ trans('lms/admin/main.offline_banks_credits') }}</a>
                                </li>

                                @if($authUser->can('admin_payment_channel'))
                                    <li class="nav-item">
                                        <a class="nav-link @if(request()->get('tab') == "payment_channels") active @endif" id="payment_channels-tab" data-toggle="tab" href="#payment_channels" role="tab" aria-controls="payment_channels" aria-selected="true">{{ trans('lms/admin/main.payment_channels') }}</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="nav-link " id="referral-tab" data-toggle="tab" href="#referral" role="tab" aria-controls="referral" aria-selected="true">{{ trans('lms/admin/main.referral') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link @if(request()->get('tab') == "currency") active @endif" id="currency-tab" href="/lms{{ getAdminPanelUrl("/settings/financial?tab=currency") }}">{{ trans('lms/admin/main.currency') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link @if(request()->get('tab') == "user_banks") active @endif" id="user_banks-tab" href="/lms{{ getAdminPanelUrl("/settings/financial?tab=user_banks") }}">{{ trans('lms/update.user_banks') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                @include('lms.admin.settings.financial.basic',['itemValue' => (!empty($settings) and !empty($settings['financial'])) ? $settings['financial']->value : ''])

                                @if(request()->get('tab') == "offline_banks")
                                    @include('lms.admin.settings.financial.offline_banks.index',['itemValue' => (!empty($settings) and !empty($settings['offline_banks'])) ? $settings['offline_banks']->value : ''])
                                @endif

                                @if($authUser->can('admin_payment_channel'))
                                    @include('lms.admin.settings.financial.payment_channel.lists')
                                @endif

                                @include('lms.admin.settings.financial.referral',['itemValue' => (!empty($settings) and !empty($settings['referral'])) ? $settings['referral']->value : ''])

                                @if(request()->get('tab') == "currency")
                                    @include('lms.admin.settings.financial.currency',['itemValue' => (!empty($settings) and !empty($settings[\App\Models\LMS\Setting::$currencySettingsName])) ? $settings[\App\Models\LMS\Setting::$currencySettingsName]->value : ''])
                                @endif

                                @if(request()->get('tab') == "user_banks")
                                    @include('lms.admin.settings.financial.user_banks.index',['itemValue' => (!empty($settings) and !empty($settings['user_banks'])) ? $settings['user_banks']->value : ''])
                                @endif
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
    <script src="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
