@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Notification')])
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Send Push Notification') }}</h4>
		    </div>
			<div class="card-body">
				<form class="basicform_with_reload" method="post" action="{{ route('seller.notifications.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="custom-form pt-20">

						<div class="form-group">
							<label>{{ __('Title') }}</label>
							<input type="text" name="title" class="form-control" required placeholder="Title"> 
							
						</div> 

						<div class="form-group">
							<label>{{ __('Description') }}</label>
							<textarea type="text" name="description" class="form-control" required placeholder="Description"></textarea>
							
						</div> 

						<div class="form-group">
							<label>{{ __('Image') }}</label>
							<input type="file" name="file" class="form-control" required> 
						</div>
						
						<div class="form-group">
							<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
							<div class="col-sm-12 col-md-7">
								<button class="btn btn-primary basicbtn" type="submit">{{ __('Send') }}</button>
							</div>
						</div>
					</div>				
				</form>
			</div>
		</div>
	</div>
</div>
<div class="card"  >
	<div class="card-body">
		<div class="row mb-30">
			<div class="col-lg-6">
				<h4>{{ __('Notification List') }}</h4>
			</div>
			<div class="col-lg-6">
				
			</div>
		</div>
		<br>
			<div class="table-responsive custom-table">
				<table class="table">
					<thead>
						<tr>
			                <th><i class="fa fa-image"></i></th>
			                <th>{{ __('Title') }}</th>
							<th>{{ __('Description') }}</th>
							<th>{{ __('Status') }}</th>
							<th>{{ __('Created at') }}</th>
							<th>{{ __('Action') }}</th>
						</tr>
					</thead>
					<form class="basicform_with_reload">
					<tbody>
						@foreach($notices as $notice)
						<tr>
							<td><img src="{{asset($notice->image ?? 'uploads/default.png') }}" height="50"></td>
							<td>{{ $notice->title }}</td>
							<td>{{ $notice->description }}</td>
							<td>
								@if($notice->status==1)
								<span class="badge badge-success">{{ __('Active') }}</span>
								@elseif($notice->status==0)
								<span class="badge badge-danger">{{ __('Deactive') }}</span> 
								@endif
							<td>
								<div class="date">
									{{ $notice->updated_at->diffForHumans() }}
								</div>
							</td>
							<td>
			                    <a href="{{ route('seller.notifications.edit',$notice->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i></a>
								<a href="{{ route('seller.notifications.destroys',$notice->id) }}" class="btn btn-danger  cancel"><i class="fa fa-trash"></i></a>						    
			                </td>
						</tr>
						@endforeach
					</tbody>
				</form>
				<tfoot>
					<tr>
						<th><i class="fa fa-image"></i></th>
		                <th>{{ __('Title') }}</th>
		                <th>{{ __('Description') }}</th>
						<th>{{ __('Status') }}</th>
		                <th>{{ __('Created at') }}</th>
		                <th>{{ __('Action') }}</th>
					</tr>
				</tfoot>
			</table>
			{{ $notices->links('vendor.pagination.bootstrap-4') }}

		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush