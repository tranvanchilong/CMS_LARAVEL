@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')


@section('content')
    @if($accountings->count() > 0)
        <section>
            <h2 class="section-title">{{ trans('lms/financial.financial_documents') }}</h2>

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('lms/public.title') }}</th>
                                    <th>{{ trans('lms/public.description') }}</th>
                                    <th class="text-center">{{ trans('lms/panel.amount') }} ({{ $currency }})</th>
                                    <th class="text-center">{{ trans('lms/public.creator') }}</th>
                                    <th class="text-center">{{ trans('lms/public.date') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($accountings as $accounting)
                                    <tr>
                                        <td class="text-left">
                                            <div class="d-flex flex-column">
                                                <div class="font-14 font-weight-500">
                                                    @if($accounting->is_cashback)
                                                        {{ trans('lms/update.cashback') }}
                                                    @elseif(!empty($accounting->webinar_id) and !empty($accounting->webinar))
                                                        {{ $accounting->webinar->title }}
                                                    @elseif(!empty($accounting->bundle_id) and !empty($accounting->bundle))
                                                        {{ $accounting->bundle->title }}
                                                    @elseif(!empty($accounting->product_id) and !empty($accounting->product))
                                                        {{ $accounting->product->title }}
                                                    @elseif(!empty($accounting->meeting_time_id))
                                                        {{ trans('lms/meeting.reservation_appointment') }}
                                                    @elseif(!empty($accounting->subscribe_id) and !empty($accounting->subscribe))
                                                        {{ $accounting->subscribe->title }}
                                                    @elseif(!empty($accounting->promotion_id) and !empty($accounting->promotion))
                                                        {{ $accounting->promotion->title }}
                                                    @elseif(!empty($accounting->registration_package_id) and !empty($accounting->registrationPackage))
                                                        {{ $accounting->registrationPackage->title }}
                                                    @elseif(!empty($accounting->installment_payment_id))
                                                        {{ trans('lms/update.installment') }}
                                                    @elseif($accounting->store_type == \App\Models\LMS\Accounting::$storeManual)
                                                        {{ trans('lms/financial.manual_document') }}
                                                    @elseif($accounting->type == \App\Models\LMS\Accounting::$addiction and $accounting->type_account == \App\Models\LMS\Accounting::$asset)
                                                        {{ trans('lms/financial.charge_account') }}
                                                    @elseif($accounting->type == \App\Models\LMS\Accounting::$deduction and $accounting->type_account == \App\Models\LMS\Accounting::$income)
                                                        {{ trans('lms/financial.payout') }}
                                                    @elseif($accounting->is_registration_bonus)
                                                        {{ trans('lms/update.registration_bonus') }}
                                                    @else
                                                        ---
                                                    @endif
                                                </div>

                                                @if(!empty($accounting->gift_id) and !empty($accounting->gift))
                                                    <div class="text-gray font-12">{!! trans('lms/update.a_gift_for_name_on_date',['name' => $accounting->gift->name, 'date' => dateTimeFormat($accounting->gift->date, 'j M Y H:i')]) !!}</div>
                                                @endif

                                                <div class="font-12 text-gray">
                                                    @if(!empty($accounting->webinar_id) and !empty($accounting->webinar))
                                                        #{{ $accounting->webinar->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->webinar->title : '' }}
                                                    @elseif(!empty($accounting->bundle_id) and !empty($accounting->bundle))
                                                        #{{ $accounting->bundle->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->bundle->title : '' }}
                                                    @elseif(!empty($accounting->product_id) and !empty($accounting->product))
                                                        #{{ $accounting->product->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->product->title : '' }}
                                                    @elseif(!empty($accounting->meeting_time_id) and !empty($accounting->meetingTime))
                                                        {{ $accounting->meetingTime->meeting->creator->full_name }}
                                                    @elseif(!empty($accounting->subscribe_id) and !empty($accounting->subscribe))
                                                        {{ $accounting->subscribe->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->subscribe->title : '' }}
                                                    @elseif(!empty($accounting->promotion_id) and !empty($accounting->promotion))
                                                        {{ $accounting->promotion->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->promotion->title : '' }}
                                                    @elseif(!empty($accounting->registration_package_id) and !empty($accounting->registrationPackage))
                                                        {{ $accounting->registrationPackage->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->registrationPackage->title : '' }}
                                                    @elseif(!empty($accounting->installment_payment_id))
                                                        @php
                                                            $installmentItemTitle = "--";
                                                            $installmentOrderPayment = $accounting->installmentOrderPayment;

                                                            if (!empty($installmentOrderPayment)) {
                                                                $installmentOrder = $installmentOrderPayment->installmentOrder;
                                                                if (!empty($installmentOrder)) {
                                                                    $installmentItem = $installmentOrder->getItem();
                                                                    if (!empty($installmentItem)) {
                                                                        $installmentItemTitle = $installmentItem->title;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $installmentItemTitle }}
                                                    @else
                                                        ---
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-left align-middle">
                                            <span class="font-weight-500 text-gray">{{ $accounting->description }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            @switch($accounting->type)
                                                @case(\App\Models\LMS\Accounting::$addiction)
                                                    <span class="font-16 font-weight-bold text-primary">+{{ handlePrice($accounting->amount, false) }}</span>
                                                    @break;
                                                @case(\App\Models\LMS\Accounting::$deduction)
                                                    <span class="font-16 font-weight-bold text-danger">-{{ handlePrice($accounting->amount, false) }}</span>
                                                    @break;
                                            @endswitch
                                        </td>
                                        <td class="text-center align-middle">{{ trans('lms/public.'.$accounting->store_type) }}</td>
                                        <td class="text-center align-middle">
                                            <span>{{ dateTimeFormat($accounting->created_at, 'j M Y') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    @else

        @include('lms.' . getTemplate() . '.includes.no-result',[
            'file_name' => 'financial.png',
            'title' => trans('lms/financial.financial_summary_no_result'),
            'hint' => nl2br(trans('lms/financial.financial_summary_no_result_hint')),
        ])
    @endif
    <div class="my-30">
        {{ $accountings->appends(request()->input())->links('lms.vendor.pagination.panel') }}
    </div>
@endsection
