@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Notification')])
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Edit Notification') }}</h4>
		    </div>
			<div class="card-body">
				<form method="post" action="{{ route('seller.notifications.update',$notices->id) }}" enctype="multipart/form-data">
					@csrf
					@method('PUT')
					<div class="custom-form pt-20">
						<div class="form-group">
							<label>{{ __('Title') }}</label>
							<input type="text" name="title" class="form-control" required placeholder="Title" value="{{$notices->title}}"> 							
						</div> 

						<div class="form-group">
							<label>{{ __('Description') }}</label>
							<textarea type="text" name="description" class="form-control" required placeholder="Description">{{$notices->description}}</textarea>
							
						</div> 

						<div class="form-group">
							<label>{{ __('Image') }}</label>
							<input type="file" name="file" class="form-control"> 
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