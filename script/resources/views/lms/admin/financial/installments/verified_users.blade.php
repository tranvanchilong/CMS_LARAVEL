@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('lms/update.verified_users') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="/lms{{ getAdminPanelUrl("/financial/installments/verified_users/export") }}" class="btn btn-primary">{{ trans('lms/admin/main.export_xls') }}</a>
                        </div>

                        <div class="card-body">
                            <div class="{{ (count($users) > 4) ? 'table-responsive' : 'table-responsive-2' }}">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.user') }}</th>
                                        <th class="">{{ trans('lms/admin/main.register_date') }}</th>
                                        <th class="">{{ trans('lms/update.total_purchases') }}</th>
                                        <th class="">{{ trans('lms/update.total_installments') }}</th>
                                        <th class="text-center">{{ trans('lms/update.installments_count') }}</th>
                                        <th class="text-center">{{ trans('lms/update.overdue_installments') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($users as $user)
                                        <tr>
                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1 font-weight-bold">{{ $user->full_name }}</div>

                                                        @if($user->mobile)
                                                            <div class="text-primary text-small font-600-bold">{{ $user->mobile }}</div>
                                                        @endif

                                                        @if($user->email)
                                                            <div class="text-primary text-small font-600-bold">{{ $user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="">{{ dateTimeFormat($user->created_at, 'j M Y') }}</td>

                                            <td class="text-center">{{ handlePrice($user->getPurchaseAmounts()) }}</td>

                                            <td class="text-center">{{ handlePrice($user->totalAmount) }}</td>

                                            <td>
                                                <span class="d-block font-14">{{ $user->unpaidStepsCount }}</span>

                                                @if($user->unpaidStepsAmount)
                                                    <span class="d-block font-12">{{ handlePrice($user->unpaidStepsAmount) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="d-block font-14">{{ $user->overdueCount }}</span>

                                                @if($user->overdueAmount)
                                                    <span class="d-block font-12">{{ handlePrice($user->overdueAmount) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group dropdown table-actions">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu text-left webinars-lists-dropdown">

                                                        @include('lms.admin.includes.delete_button',[
                                                            'url' => '/lms'.getAdminPanelUrl("/users/{$user->id}/disable_installment_approval"),
                                                            'btnClass' => 'd-flex align-items-center text-danger text-decoration-none btn-transparent btn-sm mb-1',
                                                            'btnText' => '<i class="fa fa-times"></i><span class="ml-2">'. trans("lms/update.unverifiable") .'</span>'
                                                        ])

                                                        @if($authUser->can('admin_users_impersonate'))
                                                            <a href="/lms{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1">
                                                                <i class="fa fa-user-shield"></i>
                                                                <span class="ml-2">{{ trans('lms/admin/main.login') }}</span>
                                                            </a>
                                                        @endif

                                                        @if($authUser->can('admin_users_edit'))
                                                            <a href="/lms{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1">
                                                                <i class="fa fa-edit"></i>
                                                                <span class="ml-2">{{ trans('lms/admin/main.edit') }}</span>
                                                            </a>
                                                        @endif

                                                        @if($authUser->can('admin_support_send'))
                                                            <a href="/lms{{ getAdminPanelUrl() }}/supports/create?user_id={{ $user->id }}" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm text-primary mt-1">
                                                                <i class="fa fa-comment"></i>
                                                                <span class="ml-2">{{ trans('lms/site.send_message') }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $users->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
