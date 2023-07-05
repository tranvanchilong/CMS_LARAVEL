@extends('frontend.norda.account.layout.app')
@section('user_content')
<h3>Dashboard</h3>
<div class="welcome">
<p>{{ __('Hello') }}, <strong>{{ Auth::guard('customer')->user()->name }}</strong> ({{__('If Not')}} <strong>{{ Auth::guard('customer')->user()->name  }} !</strong><a href="{{ url('/user/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> {{ __('Logout') }}</a>)</p>
</div>

<p class="mb-0">{{ __('From your account dashboard you can view your') }} <a href="{{ url('/user/orders') }}">{{ __('recent orders') }}</a> and <a href="{{ url('/user/settings') }}">{{ __('edit your password and account details') }}</a>.</p>
@endsection