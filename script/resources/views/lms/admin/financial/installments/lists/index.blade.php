@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('lms/update.installment_plans') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('lms/update.sales_count') }}</th>
                                        <th class="text-center">{{ trans('lms/update.upfront') }}</th>
                                        <th class="text-center">{{ trans('lms/update.number_of_installments') }}</th>
                                        <th class="text-center">{{ trans('lms/update.amount_of_installments') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.capacity') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.created_at') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.end_date') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($installments as $installment)
                                        <tr>
                                            <td>
                                                <div class="">
                                                    <span class="d-block font-16 font-weight-500">{{ $installment->title }}</span>
                                                    <span class="d-block font-12 mt-1">{{ trans('lms/update.target_types_'.$installment->target_type) }}</span>
                                                </div>
                                            </td>

                                            <td class="text-center">{{ 0 }}</td>

                                            <td class="text-center">
                                                {{ ($installment->upfront_type == 'percent') ? $installment->upfront.'%' : handlePrice($installment->upfront) }}
                                            </td>

                                            <td class="text-center">{{ $installment->steps_count }}</td>

                                            <td class="text-center">
                                                @php
                                                    $stepsFixedAmount = $installment->steps->where('amount_type', 'fixed_amount')->sum('amount');
                                                    $stepsPercents = $installment->steps->where('amount_type', 'percent')->sum('amount');
                                                @endphp

                                                <span class="">{{ $stepsFixedAmount ? handlePrice($stepsFixedAmount) : '' }}</span>

                                                @if($stepsPercents)
                                                    <span>{{ $stepsFixedAmount ? ' + ' : '' }}{{ $stepsPercents }}%</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $installment->capacity ?? '' }}</td>

                                            <td class="text-center">{{ dateTimeFormat($installment->created_at, 'Y M j | H:i') }}</td>

                                            <td class="text-center">{{ $installment->end_date ? dateTimeFormat($installment->end_date, 'Y M j | H:i') : '-' }}</td>

                                            <td class="text-center">
                                                @if($installment->enable)
                                                    <span class="text-success">{{ trans('lms/admin/main.active') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('lms/admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($authUser->can('admin_promotion_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl("/financial/installments/{$installment->id}/edit") }}" class="btn-sm btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_promotion_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl('/financial/installments/'. $installment->id.'/delete'),'btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $installments->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
