@extends('frontend.norda.account.layout.app')
@section('user_content')
    <div class="mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ __('Payment Settings') }}</h1>
            </div>
        </div>
    </div>

    <div class="account-details-form">
        <form action="{{ url('/user/affiliate/payment/settings/store') }}" method="POST" class="basicform">
            @csrf
            <div class="single-input-item row">
                <label class="col-md-2 col-form-label">{{ __('Paypal Email') }}</label>
                <div class="col-md-10">
                    <input type="email" class="bigbag-Input" placeholder="{{ __('Paypal Email') }}" name="paypal_email"
                        value="{{ $affiliate_customer->paypal_email }}">
                </div>
            </div>
            <div class="single-input-item row">
                <label class="col-md-2 col-form-label">{{ __('Bank Informations') }}</label>
                <div class="col-md-10">
                    <input type="text" class="bigbag-Input" placeholder="{{ __('Acc. No, Bank Name etc') }}"
                        name="bank_information" value="{{ $affiliate_customer->bank_information }}">
                </div>
            </div>
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{ __('Update Payment Settings') }}</button>
            </div>
        </form>
    </div>
@endsection
