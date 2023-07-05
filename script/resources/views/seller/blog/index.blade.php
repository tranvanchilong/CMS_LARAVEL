@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Blog')])
@endsection
@section('content')
<div class="card"  >
	<div class="card-body">
		<div class="row mb-30">
			<div class="col-lg-6">
				<h4>{{ __('Blog List') }}</h4>
			</div>
			<div class="col-lg-6">
				
			</div>
		</div>
		<a href="{{ url('/').'/'.permalink_type('blog').'' }}" target="_blank" class="text-success">{{ url('/').'/'.permalink_type('blog').'' }}</a>
		<br>
		<br>
		
		<div class="card-action-filter">
			<form method="post" class="basicform_with_reload" action="{{ route('seller.blogs.destroys') }}">
				@csrf
				<div class="row">
					<div class="col-lg-4">
						<div class="d-flex">
							<div class="single-filter">
								<div class="form-group">
									<select class="form-control selectric" name="status">
										<option disabled="" selected="">Select Action</option>
										<option value="delete">{{ __('Delete Permanently') }}</option>

									</select>
								</div>
							</div>
							<div class="single-filter">
								<button type="submit" class="btn btn-primary btn-lg ml-2">{{ __('Apply') }}</button>
							</div>
						</div>
					</div>
					<div class="col-lg-4">                       
						<div class="single-filter">
						  <div class="form-group">
						      <select class="form-control" name="language" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
						          <option value="" selected="">All Language</option>                                 
						          @foreach(languages() ?? [] as $key => $row)                                              
						              <option value="{{ $row }}" {{$row == request()->input('language') ? 'selected' : ''}}>{{ $key }}</option>
						          @endforeach
						      </select>
						  </div>
						</div>                       
		            </div>
					<div class="col-lg-4">
						<div class="add-new-btn">
							<a href="{{ route('seller.blog.create') }}" class="btn btn-primary float-right">{{ __('Add New blog') }}</a>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive custom-table">
				<table class="table">
					<thead>
						<tr>
							<th><input type="checkbox" class="checkAll"></th>
			                <th><i class="fa fa-image"></i></th>
			                <th>{{ __('Category') }}</th>
			                <th>{{ __('Title') }}</th>
			                <th>{{ __('Language') }}</th>
			                <th>{{ __('Created at') }}</th>
			                <th>{{ __('Action') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($blogs as $blog)
						<tr>
							<td><input type="checkbox" name="ids[]" value="{{ base64_encode($blog->id) }}"></td>
							<td><img src="{{ asset($blog->image ?? 'uploads/default.png') }}" height="50"></td>
							<td>{{ $blog->bcategory->name ?? '' }}</td>
							<td>{{ $blog->title }}</td>
							<td>
			                    @foreach(json_decode($blog->lang_id) ?? [] as $lang)
			                    	<span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
			                    @endforeach
			                 </td>
							<td>
								<div class="date">
									{{ $blog->updated_at->diffForHumans() }}
								</div>
							</td>
							<td>
			                    <a href="{{ route('seller.blog.edit',$blog->id) }}" class="btn btn-primary btn-sm text-center"><i class="far fa-edit"></i></a>
			                </td>
						</tr>
						@endforeach
					</tbody>
				</form>
				<tfoot>
					<tr>
						<th><input type="checkbox" class="checkAll"></th>
						<th><i class="fa fa-image"></i></th>
		                <th>{{ __('Category') }}</th>
		                <th>{{ __('Title') }}</th>
		                <th>{{ __('Language') }}</th>
		                <th>{{ __('Created at') }}</th>
		                <th>{{ __('Action') }}</th>
					</tr>
				</tfoot>
			</table>
			{{ $blogs->links('vendor.pagination.bootstrap-4') }}

		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush