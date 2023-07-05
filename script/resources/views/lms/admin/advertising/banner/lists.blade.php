@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($authUser->can('admin_advertising_banners_create'))
                                <a href="/lms{{ getAdminPanelUrl() }}/advertising/banners/new" class="btn btn-primary">{{ trans('lms/admin/main.new_banner') }}</a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.position') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.banner_size') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.published') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.created_at') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($banners as $banner)
                                        <tr>
                                            <td>{{ $banner->title }}</td>
                                            <td class="text-center">{{ $banner->position }}</td>
                                            <td class="text-center">{{ \App\Models\LMS\AdvertisingBanner::$size[$banner->size] }}</td>
                                            <td class="text-center">
                                                @if($banner->published)
                                                    <span class="text-success fas fa-check"></span>
                                                @else
                                                    <span class="text-danger fas fa-times"></span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($banner->created_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                @if($authUser->can('admin_advertising_banners_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/advertising/banners/{{ $banner->id }}/edit" class="btn-sm btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_advertising_banners_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/advertising/banners/'. $banner->id.'/delete','btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $banners->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
