@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('lms/update.cashback_rules') }}
                </div>
            </div>
        </div>

        {{-- Stats --}}
        @include('lms.admin.cashback.rules.lists.stats')

        {{-- Filters --}}
        @include('lms.admin.cashback.rules.lists.filters')

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('lms/update.target_type') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.amount') }}</th>
                                        <th class="text-center">{{ trans('lms/public.paid_amount') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.users') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.start_date') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.end_date') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($rules as $rule)
                                        <tr>
                                            <td>
                                                <span class="d-block font-16 font-weight-500">{{ $rule->title }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ trans('lms/update.target_types_'.$rule->target_type) }}</span>
                                            </td>

                                            <td class="text-center">
                                                {{ ($rule->amount_type == 'percent') ? $rule->amount.'%' : handlePrice($rule->amount) }}
                                            </td>

                                            <td class="text-center">0</td>

                                            <td class="text-center">0</td>

                                            <td class="text-center">{{ $rule->start_date ? dateTimeFormat($rule->start_date, 'Y M j | H:i') : '-' }}</td>

                                            <td class="text-center">{{ $rule->end_date ? dateTimeFormat($rule->end_date, 'Y M j | H:i') : trans('lms/update.unlimited') }}</td>

                                            <td class="text-center">
                                                @if($rule->enable)
                                                    <span class="text-success">{{ trans('lms/admin/main.active') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('lms/admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($authUser->can('admin_cashback_rules'))
                                                    <a href="/lms{{ getAdminPanelUrl("/cashback/rules/{$rule->id}/edit") }}" class="btn-sm btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_cashback_rules'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl('/cashback/rules/'. $rule->id.'/statusToggle'), 'tooltip' => ($rule->enable ? trans('lms/admin/main.inactive') : trans('lms/admin/main.active')), 'btnClass' => 'ml-2', 'btnIcon' => "fa-times-circle"])
                                                @endif

                                                @if($authUser->can('admin_cashback_rules'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl('/cashback/rules/'. $rule->id.'/delete'), 'tooltip' => trans('lms/admin/main.delete'), 'btnClass' => 'ml-2'])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $rules->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
