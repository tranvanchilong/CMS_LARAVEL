@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/update.saas_settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}/financial/registration-packages">{{ trans('lms/update.registration_packages') }}</a></div>
                <div class="breadcrumb-item ">{{ trans('lms/admin/main.settings') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">{{ trans('lms/update.general') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link " id="instructors-tab" data-toggle="tab" href="#instructors" role="tab" aria-controls="instructors" aria-selected="true">{{ trans('lms/admin/main.instructors') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link " id="organizations-tab" data-toggle="tab" href="#organizations" role="tab" aria-controls="organizations" aria-selected="true">{{ trans('lms/admin/main.organizations') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                @include('lms.admin.financial.registration_packages.settings.general')
                                @include('lms.admin.financial.registration_packages.settings.instructors')
                                @include('lms.admin.financial.registration_packages.settings.organizations')
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

    <script src="/assets/lms/assets/default/js/admin/registration_packages_settings.min.js"></script>
@endpush
