@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Blog'])
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
		<br>
		<div class="card-action-filter">
			<form method="post" class="basicform_with_reload" action="{{ route('admin.blogs.destroys') }}">
				@csrf
				<div class="row">
					@can('blog.delete')
					<div class="col-lg-6">
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
					@endcan

					@can('blog.create')
					<div class="col-lg-6">
						<div class="add-new-btn">
							<a href="{{ route('admin.blog.create') }}" class="btn btn-primary float-right">{{ __('Add New blog') }}</a>
						</div>
					</div>
					@endcan
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
			                <th>{{ __('Created at') }}</th>
			                <th>{{ __('Action') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($blogs as $blog)
						<tr>
							<td><input type="checkbox" name="ids[]" value="{{ base64_encode($blog->id) }}"></td>
							<td><img src="{{ asset($blog->image ?? 'uploads/default.png') }}" height="50"></td>
							<td>{{ $blog->bcategory->name }}</td>
							<td>{{ $blog->title }}</td>
							<td>
								<div class="date">
									{{ $blog->updated_at->diffForHumans() }}
								</div>
							</td>
							<td>
								@can('blog.edit')
			                    <a href="{{ route('admin.blog.edit',$blog->id) }}" class="btn btn-primary btn-sm text-center"><i class="far fa-edit"></i></a>
			                    @endcan
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