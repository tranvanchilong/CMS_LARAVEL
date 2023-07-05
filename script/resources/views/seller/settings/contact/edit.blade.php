@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Contact List'])
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Edit Contact List') }}</h4>
		    </div>
			<div class="card-body">
				<form method="post" action="{{ route('seller.contactlists.update',$contacts->id) }}" enctype="multipart/form-data">
					@csrf
					@method('PUT')
					<div class="custom-form pt-20">
						<div class="form-group">
							<label>{{ __('Url') }}</label>
							<input type="text" name="url" class="form-control" required value="{{$contacts->url}}"> 							
						</div> 

						<div class="form-group">
							<label>{{ __('Image') }}</label>
							<input type="file" name="file" class="form-control"> 
						</div>

						<div class="form-group">
							<div class="custom-control custom-switch">
								<input id="is_show_float_content" class="custom-control-input" @if($contacts->is_show_float_content==1) checked="checked" @endif name="is_show_float_content" type="checkbox" value="1">
								<label class="custom-control-label" for="is_show_float_content">{{ __('Show Float') }}</label>
							</div>
						</div>

						<div class="form-group">
							<div class="custom-control custom-switch">
								<input id="is_show_topbar" class="custom-control-input" @if($contacts->is_show_topbar==1) checked="checked" @endif name="is_show_topbar" type="checkbox" value="1">
								<label class="custom-control-label" for="is_show_topbar">{{ __('Show Topbar') }}</label>
							</div>
						</div>

                        <div class="form-group">
							<label>{{ __('Serial Number') }}</label>
							<input type="number" name="serial_number" class="form-control" value="{{$contacts->serial_number}}"> 	
						</div> 

						<div class="form-group">
							<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
							<div class="col-sm-12 col-md-7">
								<button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button>
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
@endpush