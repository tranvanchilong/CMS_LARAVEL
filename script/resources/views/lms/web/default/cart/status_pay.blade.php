@extends('lms.'.getTemplate().'.layouts.app')


@section('content')


    @if(!empty($order) && $order->status === \App\Models\LMS\Order::$paid)
        <div class="no-result default-no-result my-50 d-flex align-items-center justify-content-center flex-column">
            <div class="no-result-logo">
                <img src="/assets/lms/assets/default/img/no-results/search.png" alt="">
            </div>
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2>{{ trans('lms/cart.success_pay_title') }}</h2>
                <p class="mt-5 text-center">{!! trans('lms/cart.success_pay_msg') !!}</p>
                <a href="/lms/panel" class="btn btn-sm btn-primary mt-20">{{ trans('lms/public.my_panel') }}</a>
            </div>
        </div>
    @endif

    @if(!empty($order) && $order->status === \App\Models\LMS\Order::$fail)
        <div class="no-result status-failed my-50 d-flex align-items-center justify-content-center flex-column">
            <div class="no-result-logo">
                <img src="/assets/lms/assets/default/img/no-results/failed_pay.png" alt="">
            </div>
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2>{{ trans('lms/cart.failed_pay_title') }}</h2>
                <p class="mt-5 text-center">{!! nl2br(trans('lms/cart.failed_pay_msg')) !!}</p>
                <a href="/lms/panel" class="btn btn-sm btn-primary mt-20">{{ trans('lms/public.my_panel') }}</a>
            </div>
        </div>
    @endif


@endsection
