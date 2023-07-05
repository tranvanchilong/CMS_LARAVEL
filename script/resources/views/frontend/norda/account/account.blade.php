@extends('frontend.norda.account.layout.app')
@section('user_content')

<h3>Account Details</h3>
<div class="account-details-form">
	<form method="post" class="basicform" action="{{ url('/user/settings/update') }}">
		@csrf
        <div class="single-input-item">
			<label for="account_last_name">{{ __('Name') }} &nbsp;<span class="required">*</span></label>
			<input type="text" class="bigbag-Input" name="name" id="account_last_name"  required="" value="{{ Auth::guard('customer')->user()->name }}">
		</div>
        <div class="single-input-item">
			<label for="email">{{ __('Email') }} &nbsp;<span class="required">*</span></label>
			<input type="email" class="bigbag-Input" name="email" id="email"  required="" value="{{ Auth::guard('customer')->user()->email }}">
		</div>
		<div class="single-input-item">
			<label for="phone">{{ __('Phone Number') }} &nbsp;<span class="required">*</span></label>
			<input type="phone" class="bigbag-Input" name="phone" id="phone"  required="" value="{{ Auth::guard('customer')->user()->phone }}">
		</div>
		<div class="clear"></div>		


		<fieldset>
			<legend>{{ __('Password change') }}</legend>
            <div class="single-input-item">
				<label for="password_current">{{ __('Current password (leave blank to leave unchanged)') }}</label>
				<input type="password" class="bigbag-Input bigbag-Input--password input-text" name="password_current" id="password_current" autocomplete="off">
			</div>
            <div class="single-input-item">
				<label for="password_1">{{ __('New password (leave blank to leave unchanged)') }}</label>
				<input type="password" class="bigbag-Input bigbag-Input--password input-text" name="password" id="password_1" autocomplete="off">
			</div>
            <div class="single-input-item">
				<label for="password_2">{{ __('Confirm new password') }}</label>
				<input type="password" class="bigbag-Input bigbag-Input--password input-text" name="password_confirmation" id="password_2" autocomplete="off">
			</div>
		</fieldset>
		<div class="clear"></div>
		<p>

			<button type="submit" class="btn btn-primary basicbtn" name="save_account_details" value="Save changes">{{ __('Save change') }}
			</button>

		</p>
	</form>
</div>	
@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush