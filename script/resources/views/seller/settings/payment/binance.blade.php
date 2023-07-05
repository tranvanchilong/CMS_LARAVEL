@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
	<div class="col-12 col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="col">
					<h5><a href="#" class="header-pretitle">{{ $info->name }} Method</a></h5>
				</div>
			</div>
			<div class="card-body"> 
				<form method="post" class="basicform" action="{{ route('seller.payment.update',$info->active_getway->id) }}">
					@csrf
					@method('PUT')
                <div class="form-group">
					<label>{{ __('Display name at checkout') }}</label>
					<input class="form-control" autofocus="" name="name" type="text" value="{{ $data->title }}">
					<small class="form-text text-muted">Customers will see this when checking out.</small>
				</div>
				<div class="form-group">
					<label>{{ __('Contract Address') }}</label>
					<input class="form-control" autofocus="" name="contract_address" type="text" value="{{ $data->contract_address }}">
				</div>
                <div class="form-group">
					<label>{{ __('Receiver Address') }}</label>
					<input class="form-control" autofocus="" name="receiver_address" type="text" value="{{ $data->receiver_address }}">
				</div>
				
				<div class="custom-control custom-switch">
					<input id="enabled" class="custom-control-input" @if($info->active_getway->status_add_money==1) checked="checked" @endif name="status_add_money" type="checkbox" value="1">
					<label class="custom-control-label" for="enabled">Enable Add Money</label>
				</div>
			</div>

			<div class="card-footer clearfix text-muted">			
				<div class="float-left clear-both">
					<a class="btn btn-white" href="{{ route('seller.settings.show','payment') }}">Cancel</a>
					<button type="submit" class="btn btn-primary basicbtn">Save</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>	


@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
@endpush