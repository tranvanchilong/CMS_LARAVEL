@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">
            <section class="card">
                <div class="card-header">

                    @if($authUser->can('admin_regions_create'))
                        <div class="text-right">
                            <a href="/lms{{ getAdminPanelUrl() }}/regions/new?type={{ $type }}" class="btn btn-primary">{{ trans('lms/admin/main.new') }}</a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center font-14">

                            <tr>
                                <th class="text-left">{{ trans('lms/admin/main.title') }}</th>

                                @if($type == \App\Models\LMS\Region::$country)
                                    <th class="text-center">{{ trans('lms/update.provinces') }}</th>
                                @elseif($type == \App\Models\LMS\Region::$province)
                                    <th class="text-center">{{ trans('lms/update.country') }}</th>
                                    <th class="text-center">{{ trans('lms/update.cities') }}</th>
                                @elseif($type == \App\Models\LMS\Region::$city)
                                    <th class="text-center">{{ trans('lms/update.country') }}</th>
                                    <th class="text-center">{{ trans('lms/update.province') }}</th>
                                @elseif($type == \App\Models\LMS\Region::$district)
                                    <th class="text-center">{{ trans('lms/update.country') }}</th>
                                    <th class="text-center">{{ trans('lms/update.province') }}</th>
                                    <th class="text-center">{{ trans('lms/update.city') }}</th>
                                @endif

                                <th class="text-center">{{ trans('lms/admin/main.instructor') }}</th>
                                <th class="text-center">{{ trans('lms/admin/main.date') }}</th>
                                <th class="text-center">{{ trans('lms/admin/main.actions') }}</th>
                            </tr>

                            @foreach($regions as $region)

                                <tr>
                                    <td>{{ $region->title }}</td>

                                    @if($type == \App\Models\LMS\Region::$country)
                                        <td>{{ $region->countryProvinces->count() }}</td>

                                        <td>{{ $region->countryUsers->count() }}</td>
                                    @elseif($type == \App\Models\LMS\Region::$province)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->provinceCities->count() }}</td>

                                        <td>{{ $region->provinceUsers->count() }}</td>
                                    @elseif($type == \App\Models\LMS\Region::$city)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->province->title }}</td>
                                        <td>{{ $region->cityUsers->count() }}</td>
                                    @elseif($type == \App\Models\LMS\Region::$district)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->province->title }}</td>
                                        <td>{{ $region->city->title }}</td>
                                        <td>{{ $region->districtUsers->count() }}</td>
                                    @endif

                                    <td>{{ dateTimeFormat($region->created_at, 'Y M j | H:i') }}</td>

                                    <td>
                                        @if($authUser->can('admin_regions_edit'))
                                            <a href="/lms{{ getAdminPanelUrl() }}/regions/{{ $region->id }}/edit" class="btn-transparent text-primary mr-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif

                                        @if($authUser->can('admin_regions_delete'))
                                            @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/regions/'.$region->id.'/delete'])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $regions->appends(request()->input())->links() }}
                </div>
            </section>
        </div>
    </section>
@endsection
