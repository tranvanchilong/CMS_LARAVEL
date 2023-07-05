@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

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

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($authUser->can('admin_pages_create'))
                                <a href="/lms{{ getAdminPanelUrl() }}/pages/create" class="btn btn-primary">{{ trans('lms/admin/main.add_new') }}</a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.name') }}</th>
                                        <th>{{ trans('lms/admin/main.link') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.robot') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.created_at') }}</th>
                                        <th>{{ trans('lms/admin/main.action') }}</th>
                                    </tr>
                                    @foreach($pages as $page)
                                        <tr>
                                            <td>{{ $page->name }}</td>
                                            <td>{{ $page->link }}</td>
                                            <td class="text-center">
                                                @if($page->robot)
                                                    <span class="text-success">{{ trans('lms/admin/main.follow') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('lms/admin/main.no_follow') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($page->status == 'publish')
                                                    <span class="text-success">{{ trans('lms/admin/main.published') }}</span>
                                                @else
                                                    <span class="text-warning">{{ trans('lms/admin/main.is_draft') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ dateTimeFormat($page->created_at, 'j M Y | H:i') }}</td>
                                            <td width="150px">

                                                @if($authUser->can('admin_pages_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/pages/{{ $page->id }}/edit" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_pages_toggle'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/pages/{{ $page->id }}/toggle" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ ($page->status == 'draft') ? trans('lms/admin/main.publish') : trans('lms/admin/main.draft') }}">
                                                        @if($page->status == 'draft')
                                                            <i class="fa fa-arrow-up"></i>
                                                        @else
                                                            <i class="fa fa-arrow-down"></i>
                                                        @endif
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_pages_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/pages/'.$page->id.'/delete' , 'btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $pages->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

