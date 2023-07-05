@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('lms/update.waitlists')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('lms/update.waitlists')}}</div>
            </div>
        </div>

        <div class="section-body">


            <div class="card">
                <div class="card-header">
                    @if($authUser->can('admin_waitlists_exports'))
                        <a href="/lms{{ getAdminPanelUrl('/waitlists/export') }}" class="btn btn-primary">{{ trans('lms/admin/main.export_xls') }}</a>
                    @endif
                </div>

                <div class="card-body">
                    <table class="table table-striped font-14" id="datatable-details">
                        <thead>
                        <tr>
                            <th class="text-left">{{ trans('lms/admin/main.course') }}</th>
                            <th class="">{{ trans('lms/update.members') }}</th>
                            <th class="">{{ trans('lms/update.registered_members') }}</th>
                            <th class="">{{ trans('lms/update.last_submission') }}</th>
                            <th class="text-left">{{ trans('lms/admin/main.actions') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($waitlists as $waitlist)
                            <tr>
                                <td class="text-left">
                                    <a class="text-primary mt-0 mb-1 font-weight-bold" href="{{ $waitlist->getUrl() }}">{{ $waitlist->title }}</a>
                                    @if(!empty($waitlist->category->title))
                                        <div class="text-small">{{ $waitlist->category->title }}</div>
                                    @else
                                        <div class="text-small text-warning">{{trans('lms/admin/main.no_category')}}</div>
                                    @endif
                                </td>

                                <td>{{ $waitlist->members }}</td>

                                <td>{{ $waitlist->registered_members }}</td>

                                <td>
                                    {{ !empty($waitlist->last_submission) ? dateTimeFormat($waitlist->last_submission, 'j M Y H:i') : '-' }}
                                </td>

                                <td class="text-left">
                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu webinars-lists-dropdown">

                                            @if($authUser->can('admin_waitlists_clear_list'))
                                                @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl("/waitlists/{$waitlist->id}/clear_list"),
                                                    'btnClass' => 'd-flex align-items-center text-warning text-decoration-none btn-transparent btn-sm mt-1',
                                                    'btnText' => '<i class="fa fa-times"></i><span class="ml-2">'. trans("lms/update.clear_list") .'</span>'
                                                ])
                                            @endif

                                            @if($authUser->can('admin_waitlists_users'))
                                                <a href="/lms{{ getAdminPanelUrl("/waitlists/{$waitlist->id}/view_list") }}" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="ml-2">{{ trans("lms/update.view_list") }}</span>
                                                </a>
                                            @endif

                                            @if($authUser->can('admin_waitlists_exports'))
                                                <a href="/lms{{ getAdminPanelUrl("/waitlists/{$waitlist->id}/export_list") }}" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1">
                                                    <i class="fa fa-download"></i>
                                                    <span class="ml-2">{{ trans("lms/update.export_list") }}</span>
                                                </a>
                                            @endif

                                            @if($authUser->can('admin_waitlists_disable'))
                                                @include('lms.admin.includes.delete_button',[
                                                        'url' => '/lms'.getAdminPanelUrl("/waitlists/{$waitlist->id}/disable"),
                                                        'btnClass' => 'd-flex align-items-center text-danger text-decoration-none btn-transparent btn-sm mt-1',
                                                        'btnText' => '<i class="fa fa-lock"></i><span class="ml-2">'. trans("lms/update.disable_waitlist") .'</span>'
                                                    ])
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="card-footer text-center">
                    {{ $waitlists->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
