@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Checkout')}} </li>
            </ul>
        </div>
    </div>
</div>

<div class="checkout-main-area pt-60 pb-60">
    <div class="container">
        <div class="customer-zone mb-20">
            @if(env('MULTILEVEL_CUSTOMER_REGISTER') == true)
    			@if(!Auth::guard('customer')->check())
    			    <p class="cart-page-title">{{ __('Are you a returning customer?') }} <a href="{{ url('/user/login') }}">{{ __('Click here to login') }}</a></p>
    			@endif
			@endif
        </div>
        <div class="customer-zone mb-20">
            <p class="cart-page-title">{{ __('Do you have a coupon code?') }} <a class="checkout-click3" href="#">{{ __('Click here to apply') }}</a></p>
            <div class="checkout-login-info3">
                <form class="basicform_with_reload" action="{{ url('/apply_coupon') }}" method="post">
                    @csrf
                    <input type="text" name="code" id="coupon_code" value="" placeholder="Coupon code" required="">
                    <button class="couponbtn basicbtn">Apply Coupon</button>
                </form>
            </div>
        </div>
        @if ($errors->any())
    		<div class="alert alert-danger">
    			<ul>
                    @if ($errors->has('name'))
                    <li class="text-danger">{{ __('Name Required') }}</li>
                    @endif
                    @if ($errors->has('email'))
                    <li class="text-danger">{{ __('Email Required') }}</li>
                    @endif
                    @if ($errors->has('phone'))
                    <li class="text-danger">{{ __('Phone Required') }}</li>
                    @endif
                    @if ($errors->has('location'))
                    <li class="text-danger">{{ __('Location Required') }}</li>
                    @endif
                    @if ($errors->has('delivery_address'))
                    <li class="text-danger">{{ __('Delivery Address Required') }}</li>
                    @endif
                    @if ($errors->has('zip_code'))
                    <li class="text-danger">{{ __('Zipcode Required') }}</li>
                    @endif
                    @if ($errors->has('shipping_mode'))
                    <li class="text-danger">{{ __('Shipping Mode Required') }}</li>
                    @endif
    			</ul>
    		</div>
    		@endif
    		@if(Session::has('user_limit'))
    		<div class="alert alert-danger">
    			<ul>
    				<li>{{ Session::get('user_limit') }}</li>
    			</ul>
    		</div>
    		@endif
    		@if(Session::has('payment_fail'))
    		<div class="alert alert-danger alert-dismissible fade show" role="alert">
    			{{ Session::get('payment_fail') }}
    			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    				<span aria-hidden="true">&times;</span>
    			</button>
    		</div>
		@endif

		<form action="{{ url('/make_order') }}" class="checkout_form" method="post">
        @csrf
            <div class="checkout-wrap pt-30">

            <div class="row">
                <div class="col-lg-7">
                    <div class="billing-info-wrap mr-50">
                        <h3>{{ __('Billing Details') }}</h3>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('Name') }} <abbr class="required" title="required">*</abbr></label>
							        <input type="text" placeholder="Full Name" name="name" class="form-control" required="" value="{{ Auth::guard('customer')->user()->name  ?? old('name') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="billing-info mb-20">
                                    <label>{{ __('Email Address') }} <abbr class="required" title="required">*</abbr></label>
							        <input type="email" placeholder="Email Address" name="email" required="" value="{{ Auth::guard('customer')->user()->email ?? old('email') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="billing-info mb-20">
                                    <label>{{ __('Phone Number') }} <abbr class="required" title="required">*</abbr></label>
							    <input type="text" placeholder="Phone Number" name="phone" class="form-control" value="{{ Auth::guard('customer')->user()->phone ?? old('phone') }}" required="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                @if(env('MULTILEVEL_CUSTOMER_REGISTER') == true)
            						@if(!Auth::guard('customer')->check())
            						<div class="checkout-account mb-25">
                                        <input class="checkout-toggle2 create_account" name="create_account" type="checkbox">
                                        <span>{{ __('With Create Account') }}</span>
                                    </div>
            						<div class="checkout-account-toggle open-toggle2 mb-20" style="display:none">

            							<label>{{ __('Password') }} <abbr class="required" title="required">*</abbr></label>
            							<input type="password" placeholder="Enter Password" name="password" class="form-control" value="" minlength="8">
            						</div>
            						@endif
        						@endif
                            </div>

                            @if(domain_info('shop_type') == 1)
    						@if(count($locations) == 1)
                            <input type="hidden"  name="required_location" class="form-control" value="1">
                            <div class="col-lg-12">
                                <div class="billing-select mb-20">
                                    <label>{{ __('Location') }} <abbr class="required" title="required">*</abbr></label>
        							<select class="location" name="location">
        								@foreach($locations as $location)
        								<option value="{{ $location->id }}" data-method="{{ $location->child_relation }}" selected>{{ $location->name }}</option>
        								@endforeach

        							</select>
                                </div>
                            </div>
                            @elseif(count($locations) > 1)
                            <input type="hidden"  name="required_location" class="form-control" value="1">
                            <div class="col-lg-12">
                                <div class="billing-select mb-20">
                                    <label>{{ __('Location') }} <abbr class="required" title="required">*</abbr></label>
        							<select class="location" name="location">
                                        <option selected disabled value="">{{ __('Select Location') }}</option>
        								@foreach($locations as $location)
        								<option value="{{ $location->id }}" data-method="{{ $location->child_relation }}">{{ $location->name }}</option>
        								@endforeach

        							</select>
                                </div>
                            </div>
    						@endif

                            <div class="col-lg-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('Delivery Address') }} <abbr class="required" title="required">*</abbr></label>
							        <input type="text" placeholder="{{ __('Delivery Address') }}" name="delivery_address" value="{{ old('delivery_address') }}" required="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('Zip Code') }} <abbr class="required" title="required">*</abbr></label>
							        <input type="number" placeholder="Zip Code" name="zip_code" value="{{ old('zip_code') }}" required="">
                                </div>
                            </div>

    						@endif
                        </div>

                        <div class="additional-info-wrap">
                            <label>{{ __('Order Notes') }}</label>
                            <textarea name="comment" rows="5" class="form-control" placeholder="Order Notes (Optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="your-order-area">
                        <h3>{{ __('Your order') }}</h3>
                        <div class="your-order-wrap gray-bg-4">
                            <div class="your-order-info-wrap">
                                <div class="your-order-info">
                                    <ul>
                                        <li>{{ __('Product') }} <span>{{ __('Total') }}</span></li>
                                    </ul>
                                </div>
                                <div class="your-order-middle">
                                    <ul>
                                        @foreach(Cart::content() as $row)
                                        <li>
                                            <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{$row->name }} x {{ $row->qty }} <span>{{ amount_format($row->price) }}</span></a>
                                            @foreach ($row->options->attribute as $attribute)
                                            <p><b>{{ $attribute->attribute->name }}</b> : {{ $attribute->variation->name }}</p>
                                            @endforeach
											@foreach ($row->options->options as $op)
											<p>{{ $op->name }}</p>
                                            @endforeach
											<p>{{ $row->qty }} {{ __('Piece') }}</p>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="your-order-info order-subtotal">
                                    <ul>
                                        <li>{{ __('Discount') }}
                                        @if((Cart::discount() == 0))
                                            <span>{{ amount_format(Cart::discount()) }}</span>
                                        @else
                                            <span>- {{ amount_format(Cart::discount()) }}</span>
                                        @endif
                                        </li>
                                    </ul>
                                </div>
                                <div class="your-order-info order-subtotal">
                                    <ul>
                                        <li>{{__('Subtotal')}} <span>{{ amount_format(Cart::subtotal()) }} </span></li>
                                    </ul>
                                </div>
                                <div class="your-order-info order-shipping">
                                    <ul>
                                        <li>{{ __('Shipping Charge') }} <p id="shipping_charge">0</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="your-order-info order-shipping">
                                    <ul>
                                        <li>{{ __('Tax') }} <p>{{ amount_format(Cart::tax()) }}</p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="your-order-info order-total">
                                    <ul>
                                        <li>{{ __('Grand Total') }} <span class="total_cost_amount">{{ amount_format(Cart::total()) }}</span></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="payment-method bigbag-checkout-payment" style="display:none">
        						<h5>{{ __('Select Shipping Mode') }}</h5>
                                <div class="shipping_methods">

                                </div>
        				    </div>

                            <div class="payment-method">
                                <h5>{{ __('Select Payment Mode') }}</h5>
                                @foreach($getways as $key => $row)
    							@php
    							$data=json_decode($row->content);
    							@endphp
    							<div class="pay-top sin-payment">
                                    <input id="payment_method_{{ $key }}" class="input-radio" type="radio" name="payment_method" value="{{ $row->category_id  }}" @if($key==0) checked="checked" @endif>
                                    <label for="payment_method_{{ $key }}">{{ $data->title }} </label>
                                    <div class="payment-box payment_method_bacs payment_method_{{ $key }}">
                                        <p>{{ $data->additional_details ?? '' }}</p>
                                        @if(isset($row->image))
                                            <img  width="200" src="{{asset($row->image ?? '')}}" alt="Section Element">
                                        @endif
                                    </div>
                                    
                                </div>
    							@endforeach
                            </div>
                        </div>
                        <div class="Place-order">
                            @if(Cart::count() > 0)
            				    <a href="javascript:$('.checkout_form').submit();" class="btn-main checkout_submit_btn">{{ __('Place Order') }}</a>
            				@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<input type="hidden" value="{{ str_replace(',','',number_format(Cart::total(),2)) }}" id="total_amount"/>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('frontend/norda/js/checkout.js?v=2') }}"></script>
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush