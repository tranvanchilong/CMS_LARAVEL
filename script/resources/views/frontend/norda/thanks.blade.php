@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="active">{{ __('Thank you') }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="container padding-bottom-3x mb-2 mt-5">
	<div class="card text-center">
		<div class="card-body padding-top-2x">
			<h3 class="card-title">{{ __('Thank you for your order!') }}</h3>
			<p class="card-text">{{ __('Your order has been placed and will be processed as soon as possible.') }}</p>
			<p class="card-text">{{ __('Make sure you make note of your order number, which is') }} <span class="text-medium">{{ Session::get('order_no') }}</p>
			<p class="card-text">{{ __('You will be receiving an email shortly with confirmation of your order.') }}</p>
			<div class="padding-top-1x padding-bottom-1x"><a class="btn btn-primary" href="{{ url('/'.permalink_type('shop').'') }}">{{ __('Go Back Shopping') }}</a></div>
		</div>
	</div>
</div>
<hr>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
