@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/financial.account_summary') }}</h2>

        @if(!$authUser->financial_approval)
            <div class="p-15 mt-20 p-lg-20 not-verified-alert font-weight-500 text-dark-blue rounded-sm panel-shadow">
                {{ trans('lms/panel.not_verified_alert') }}
                <a href="/lms/panel/setting/step/7" class="text-decoration-underline">{{ trans('lms/panel.this_link') }}</a>.
            </div>
        @endif

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/36.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $accountCharge ? handlePrice($accountCharge) : 0 }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/financial.account_charge') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/37.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ handlePrice($readyPayout ?? 0) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/financial.ready_to_payout') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/38.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ handlePrice($totalIncome ?? 0) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/financial.total_income') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="mt-45">
        <button type="button" @if(!$authUser->financial_approval) disabled @endif class="request-payout btn btn-sm btn-primary">{{ trans('lms/financial.request_payout') }}</button>
    </div>

    @if($payouts->count() > 0)
        <section class="mt-35">
            <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                <h2 class="section-title">{{ trans('lms/financial.payouts_history') }}</h2>
            </div>

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('lms/financial.account') }}</th>
                                    <th class="text-center">{{ trans('lms/public.type') }}</th>
                                    <th class="text-center">{{ trans('lms/panel.amount') }} ({{ $currency }})</th>
                                    <th class="text-center">{{ trans('lms/public.status') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($payouts as $payout)
                                    <tr>
                                        <td>
                                            <div class="text-left">
                                                <span class="d-block font-weight-500 text-dark-blue">{{ $payout->userSelectedBank->bank->title ?? '' }}</span>
                                                <span class="d-block font-12 text-gray mt-1">{{ dateTimeFormat($payout->created_at, 'j M Y | H:i') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{ trans('lms/public.manual') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-primary font-weight-bold">{{ handlePrice($payout->amount, false) }}</span>
                                        </td>
                                        <td>
                                            @switch($payout->status)
                                                @case(\App\Models\LMS\Payout::$waiting)
                                                    <span class="text-warning font-weight-bold">{{ trans('lms/public.waiting') }}</span>
                                                    @break;
                                                @case(\App\Models\LMS\Payout::$reject)
                                                    <span class="text-danger font-weight-bold">{{ trans('lms/public.rejected') }}</span>
                                                    @break;
                                                @case(\App\Models\LMS\Payout::$done)
                                                    <span class="">{{ trans('lms/public.done') }}</span>
                                                    @break;
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="my-30">
                {{ $payouts->appends(request()->input())->links('lms.vendor.pagination.panel') }}
            </div>
        </section>
    @else
        @include('lms.' . getTemplate() . '.includes.no-result',[
            'file_name' => 'payout.png',
            'title' => trans('lms/financial.payout_no_result'),
            'hint' => nl2br(trans('lms/financial.payout_no_result_hint')),
        ])

    @endif


    <div id="requestPayoutModal" class="d-none">
        <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/financial.payout_confirmation') }}</h3>
        <p class="text-gray mt-15">{{ trans('lms/financial.payout_confirmation_hint') }}</p>

        <form method="post" action="/lms/panel/financial/request-payout">
            {{ csrf_field() }}
            <div class="row justify-content-center">
                <div class="w-75 mt-50">
                    <div class="d-flex align-items-center justify-content-between text-gray">
                        <span class="font-weight-bold">{{ trans('lms/financial.ready_to_payout') }}</span>
                        <span>{{ handlePrice($readyPayout ?? 0) }}</span>
                    </div>

                    @if(!empty($authUser->selectedBank) and !empty($authUser->selectedBank->bank))
                        <div class="d-flex align-items-center justify-content-between text-gray mt-20">
                            <span class="font-weight-bold">{{ trans('lms/financial.account_type') }}</span>
                            <span>{{ $authUser->selectedBank->bank->title }}</span>
                        </div>

                        @foreach($authUser->selectedBank->bank->specifications as $specification)
                            @php
                                $selectedBankSpecification = $authUser->selectedBank->specifications->where('user_selected_bank_id', $authUser->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                            @endphp

                            <div class="d-flex align-items-center justify-content-between text-gray mt-20">
                                <span class="font-weight-bold">{{ $specification->name }}</span>
                                <span>{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}</span>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>

            <div class="mt-50 d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-payout btn btn-sm btn-primary">{{ trans('lms/financial.request_payout') }}</button>
                <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('lms/public.close') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/js/panel/financial/payout.min.js"></script>
@endpush
