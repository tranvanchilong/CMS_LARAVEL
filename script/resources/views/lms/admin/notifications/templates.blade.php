@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.templates') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.templates') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped font-14" id="datatable-basic">

                        <tr>
                            <th>{{ trans('lms/admin/main.title') }}</th>
                            <th>{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @foreach($templates as $template)
                            <tr>
                                <td>{{ $template->title }}</td>

                                <td width="100">
                                    @if($authUser->can('admin_notifications_template_edit'))
                                        <a href="/lms{{ getAdminPanelUrl() }}/notifications/templates/{{ $template->id }}/edit" class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif

                                    @if($authUser->can('admin_notifications_template_delete'))
                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/notifications/templates/'. $template->id.'/delete','btnClass' => 'btn-sm'])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="card-footer text-center">
                    {{ $templates->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

