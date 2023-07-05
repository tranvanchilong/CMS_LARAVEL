@extends('frontend.norda.account.layout.app')
<link rel="stylesheet" href="{{ asset('frontend/norda/css/affilate.css') }}">
@section('user_content')
    <div class="aiz-user-panel">
        <div class="aiz-titlebar mt-2 mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3">{{ __('Affiliate') }}</h1>
                </div>
            </div>
        </div>
        <div class="row gutters-10">
            <div class="col-md-4 mx-auto mb-3">
                <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                    <span
                        class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3" style="color:#ffff">
                        {{data_get(currency_info(),'currency_icon')}}
                    </span>
                    <div class="px-3 pt-3 pb-3">
                        <div class="h4 fw-700 text-center">
                            {{ amount_format($affiliate_user->balance) }}
                        </div>
                        <div class="opacity-50 text-center">{{ __('Affiliate Balance') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mx-auto mb-3">
                <a href="{{ url('/user/affiliate/payment/settings') }}">
                    <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                        <span
                            class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                            <i class="fa fa-dharmachakra fa-2x text-white"></i>
                        </span>
                        <div class="fs-18 text-primary">{{ __('Configure Payout') }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mx-auto mb-3">
                <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition"
                    data-toggle="modal" data-target="#affiliate_withdraw_modal">
                    <span
                        class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                        <i class="fa fa-plus fa-2x text-white"></i>
                    </span>
                    <div class="fs-18 text-primary">{{ __('Affiliate Withdraw Request') }}</div>
                </div>
            </div>
        </div>
        @yield("affiliate_content")
    </div>

@endsection
<div class="modal fade" id="affiliate_withdraw_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('Affiliate Withdraw Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="" action="/user/affiliate/withdraw_request/store" method="post">
                @csrf
                <div class="modal-body gry-bg px-3 pt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ trans('Amount')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <input type="number" class="form-control mb-3" name="amount" min="1" max="{{$affiliate_user->balance}}" placeholder="{{ trans('Amount')}}" required>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">{{trans('Confirm')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>