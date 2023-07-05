@extends('lms.admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.personalization') }} {{ trans('lms/admin/main.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}/settings">{{ trans('lms/admin/main.settings') }}</a></div>
                <div class="breadcrumb-item ">{{ trans('lms/admin/main.personalization') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @php
                                $items = ['page_background','home_sections','home_hero','home_hero2','home_video_or_image_box',
                                            'panel_sidebar','find_instructors','reward_program','become_instructor_section',
                                            'theme_colors', 'theme_fonts', 'forums_section', 'navbar_button','cookie_settings', 'mobile_app', 'maintenance_settings',
                                            'others_personalization', 'statistics'
                                         ];
                            @endphp

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                @foreach($items as $item)
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($item == $name) ? 'active' : '' }}" href="/lms{{ getAdminPanelUrl() }}/settings/personalization/{{ $item }}">{{ trans('lms/admin/main.'.$item) }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @include('lms.admin.settings.personalization.'.$name,['itemValue' => (!empty($values)) ? $values : ''])
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')


@endpush
