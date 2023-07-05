@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Verify Email'])
@if(Auth::user()->email_verified == 0)
<style>
	.fa-bars, .main-sidebar{
		display: none;
	}
	.main-content{
		padding-left: 30px;
	}
</style>
@endif
@endsection
@section('content')
@if(Session::has('success'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('success') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
@if(Session::has('warning'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('warning') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif


<div class="row">
	<div class="col-md-12">
		<div class="col-12 text-center">
		<h1 class="page-not-found">{{ __('Your email have not verified yet !!!')}}</h1>
		@if(Auth::user()->is_check_send_mail == 1)
		<h5 class="page-not-found">{{ __('A confirmation email has been sent to you')}}</h5>
		@endif
			@if($count_day <= 3)
				@if(Auth::user()->is_check_send_mail == 1)
				<form class="basicform_email" method="post" action="{{route('seller.email.store')}}">
				@csrf
					<input type="hidden" name="id" value="{{Auth::user()->id}}">
					<button type="submit" class="btn btn-primary basicbtn">{{ __('Resend verification email')}}</button>
				<form>
				@else
				<form class="d-inline-block mr-1 basicform" method="post" action="{{route('seller.email.store')}}">
				@csrf
				<input type="hidden" name="id" value="{{Auth::user()->id}}">
					<button class="btn btn-primary basicbtn" type="submit">{{ __('Send email to verify')}}</button>
				</form>
				<form class="d-inline-block mr-1" method="post" action="{{ route('email.verify') }}">
					@csrf
					<input type="hidden" name="id" value="{{Auth::user()->id}}">
					<button class="btn btn-primary" type="submit">{{ __('I will verify later')}}</button>
				</form>
				@endif
			@elseif($count_day > 3)
				@if(Auth::user()->is_check_send_mail == 1)
				<form class="basicform_email" method="post" action="{{route('seller.email.store')}}">
				@csrf
					<input type="hidden" name="id" value="{{Auth::user()->id}}">
					<button type="submit" class="btn btn-primary basicbtn">{{ __('Resend verification email')}}</button>
				<form>
				@else
				<form class="basicform_email" method="post" action="{{route('seller.email.store')}}">
				@csrf
					<input type="hidden" name="id" value="{{Auth::user()->id}}">
					<button type="submit" class="btn btn-primary basicbtn">{{ __('Send email to verify')}}</button>
				</form>
				@endif
			@endif
			
		</div> 
    </div>
</div>


@endsection

@push('js')
<script src="{{ asset('assets/js/form.js?v=' . time()) }}"></script>
@endpush
