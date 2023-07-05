@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('System Environment Settings')])
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Mail Configuration') }}</h4>
		    </div>
			<div class="card-body">
				<form class="basicform_with_reload" method="post" action="{{ route('seller.mail_config.update') }}" enctype="multipart/form-data">
					@csrf
					<div class="custom-form pt-20">
						<div class="form-group">
							<label>{{ __('Mail Configuration Settings') }}</label>
							<select name="mail_configuration" id="mail_configuration" class="form-control">
								<option value="0" @if($status_mail_config->mail_configuration == 0 ) selected="" @endif>{{ __('System Email') }}</option>
								<option value="1" @if($status_mail_config->mail_configuration == 1 ) selected="" @endif>{{ __('Setting Email') }}</option>
							</select>
						</div> 
						
						<div class="configuration @if($status_mail_config->mail_configuration == 0) none @endif">
							<div class="form-group">
								<label>{{ __('MAIL_DRIVER') }}</label>
								<input type="text"  name="driver" class="form-control" value="{{ $mail_configs->driver ?? '' }}" required>
							</div>
							<div class="form-group">
								<label>{{ __('MAIL_HOST') }}</label>
								<input type="text"  name="host" class="form-control" value="{{ $mail_configs->host ?? '' }}" required>
							</div>

							<div class="form-group">
								<label>{{ __('MAIL_PORT') }}</label>
								<input type="text"  name="port" class="form-control" value="{{ $mail_configs->port ?? ''}}" required>
							</div>
							<div class="form-group">
								<label>{{ __('MAIL_USERNAME') }}</label>
								<input type="text"  name="username" class="form-control" value="{{ $mail_configs->username ?? ''}}" required>
							</div>
							<div class="form-group">
								<label>{{ __('MAIL_PASSWORD') }}</label>
								<input type="password"  name="password" class="form-control" value="{{ $mail_configs->password ?? ''}}" required>
							</div>
							<div class="form-group">
								<label>{{ __('MAIL_ENCRYPTION') }}</label>
								<input type="text"  name="encryption" class="form-control" value="{{ $mail_configs->encryption ?? ''}}" required>
							</div>
							<div class="form-group">
								<label>{{ __('MAIL_FROM_ADDRESS') }}</label>
								<input type="text"   name="mail_from_address" class="form-control" value="{{ $mail_configs->mail_from_address ?? ''}}" required>
							</div>

							<div class="form-group">
								<label>{{ __('INCOMING MAIL ALSO ORDER') }}</label>
								<input type="text" name="mail_to" class="form-control" value="{{ $mail_configs->mail_to ?? ''}}" required>
							</div>

							<div class="form-group">
								<label>{{ __('MAIL_FROM_NAME') }}</label>
								<input type="text"  name="mail_from_name" class="form-control" value="{{ $mail_configs->mail_from_name ?? ''}}" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
							<div class="col-sm-12 col-md-7">
								<button class="btn btn-primary basicbtn" type="submit">{{ __('Submit') }}</button>
							</div>
						</div>
					</div>				
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
	(function ($) {
	"use strict";
	$('#mail_configuration').on('change',function(){
		var status= $(this).val();
		if(status == 1){
			$('.configuration').show();
		}
		else{
			$('.configuration').hide();
		}
	});
	})(jQuery);
</script>
@endpush