@extends('frontend.norda.layouts.app')
@section('content')    
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Dashboard')}} </li>
            </ul>
        </div>
    </div>
</div>
<!--=====================================
         LOGIN PART START
=======================================-->
<div class="my-account-wrapper pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- My Account Page Start -->
                <div class="myaccount-page-wrapper">
                    <!-- My Account Tab Menu Start -->
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                        @php
                            $wallet_status = \App\Useroption::where('user_id',domain_info('user_id'))->where('key','wallet_status')->first();                               
                            $wallet_status = !empty($wallet_status) ? $wallet_status->value : null;   
                        @endphp
                            <div class="myaccount-tab-menu nav" role="tablist">
                                <a href="{{ url('/user/dashboard') }}" class="@if(url()->current() == url('/user/dashboard')) active @endif"><i class="fa fa-dashboard"></i>
                                    {{ __('Dashboard') }}</a>
                                    @if($wallet_status == 1)
                                    <a href="{{ url('/user/wallet') }}" class="@if(url()->current() == url('/user/wallet')) active @endif"><i class="fa fa-wallet"></i>
                                    {{ __('My Wallet') }}</a>
                                    @endif
                                <a href="{{ url('/user/orders') }}" class="@if(Request::is('user/orders') || Request::is('user/order/*')) active @endif"><i class="fa fa-cart-arrow-down"></i> {{ __('Orders') }}</a>
                                <a href="{{ url('/user/bookings') }}" class="@if(Request::is('user/bookings') || Request::is('user/booking/*')) active @endif"><i class="fa fas fa-tasks"></i> {{ __('Bookings') }}</a>
                                <a href="{{ url('/user/settings') }}" class="@if(url()->current() == url('/user/settings')) active @endif"><i class="fa fa-user"></i> {{ __('Account details') }}</a>
                                @if(feature_is_activated('affiliate_status', domain_info('user_id')))
                                <a href="#affiliate-menu" data-toggle="collapse" aria-expanded="{{ (request()->segment(2) == 'affiliate') ? 'true' : 'false' }}"
                                        class="dropdown-toggle {{ (request()->segment(2) == 'affiliate') ? 'active' : '' }}"><i class="fa fa-dollar-sign"></i>
                                        {{ __('Affiliate') }}</a>
                                    <ul class="collapse list-unstyled {{ (request()->segment(2) == 'affiliate') ? 'show' : '' }}" id="affiliate-menu">
                                        <li><a style="padding-left: 50px;"
                                                href="{{ url('/user/affiliate/affiliate_system') }}"
                                                class="@if (url()->current() == url('/user/affiliate/affiliate_system')) active @endif">{{__('Affiliate System')}}</a>
                                        </li>
                                        <li><a style="padding-left: 50px;"
                                                href="{{ url('/user/affiliate/payment_history') }}"
                                                class="@if (url()->current() == url('/user/affiliate/payment_history')) active @endif">{{__('Payment History')}}</a>
                                        </li>
                                        <li><a style="padding-left: 50px;"
                                                href="{{ url('/user/affiliate/withdraw_request_history') }}"
                                                class="@if (url()->current() == url('/user/affiliate/withdraw_request_history')) active @endif">{{__('Withdraw Request History')}}
                                            </a></li>
                                        <li>
                                            <a style="padding-left: 50px;"
                                                href="{{ url('/user/affiliate/refferal_users') }}"
                                                class="@if (url()->current() == url('/user/affiliate/refferal_users')) active @endif">{{__('Referral Customer')}}
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                                <a href="{{ url('/user/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i>
                                <form id="logout-form" action="{{ url('/user/logout') }}" method="POST" style="display:none">
                                  @csrf
                                </form>
                                
                                {{ __('Logout') }}</a>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8">
                            <div class="tab-content" id="myaccountContent">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <div class="myaccount-content">
                                        @yield('user_content')
                                    </div>
                                </div>
                            </div>
                        </div> <!-- My Account Tab Content End -->
                    </div>
                </div> <!-- My Account Page End -->
            </div>
        </div>
    </div>
</div>
@endsection      




