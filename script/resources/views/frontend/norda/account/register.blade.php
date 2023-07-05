@extends('frontend.norda.layouts.app')
@push('css')
 <link rel="stylesheet" href="{{ asset('frontend/norda/css/login.css') }}">
@endpush  
@section('content')    
<!--=====================================
         SINGLE BANNER PART START
=======================================-->
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="active">{{ __('Register') }}</li>
            </ul>
        </div>
    </div>
</div>
<!--=====================================
         SINGLE BANNER PART END
=======================================-->


<!--=====================================
         LOGIN PART START
=======================================-->
<section class="section-padding">
  <div class="form-box">
    <h2>{{ __('Register') }}</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
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
    <form action="{{ url('/user/register-user') }}" method="POST" class="mt-3 basicform">
      @csrf
      <fieldset class="form-group mb-3">
        <input type="text" placeholder="Name"  value="{{ old('name') }}" name="name" class="form-control" required="">
      </fieldset>
       <fieldset class="form-group mb-3">
        <input type="email" placeholder="Email" value="{{ old('email') }}" name="email" class="form-control" required="">
      </fieldset>
      <fieldset class="form-group mb-3">
        <input type="number" placeholder="Phone" value="{{ old('phone') }}" name="phone" class="form-control" required="">
      </fieldset>
      <fieldset class="form-group mb-3">
        <input type="password" name="password" placeholder="Password" class="form-control" required="">
      </fieldset>
       <fieldset class="form-group mb-3">
        <input type="password" placeholder="Confirm password"  name="password_confirmation"class="form-control" required="">
      </fieldset>
     
      <div class="row mt-4">
        <div class="col-md-6 col-lg-6">
          <button type="submit" class="bigbag_btn-custom btn-block basicbtn">{{ __('Sign Up') }}</button>
        </div>
        <div class="col-md-6 col-lg-6 text-center align-self-md-center pt-4 pt-md-0">
          <p class="mb-0">{{ __('Already have an account?') }}<br><a href="{{ url('/user/login') }}" class="base_color">{{ __('Login here') }}</a></p>
        </div>
      </div>
      @if(isset($social_login) && $social_login->status == 1)
        <div class="row mt-4">
          <div class="col-md-12">
            <a href="{{ url('/login/redirect-google') }}" class="bigbag_btn-custom btn-block basicbtn" style="color:#fff"><img alt="Google login" src="{{asset('uploads/google-signin.webp')}}">{{ __('Sign In With Google') }}</a>
          </div>
        </div>
      @endif
    </form>
  </div>
</section>
 
@endsection