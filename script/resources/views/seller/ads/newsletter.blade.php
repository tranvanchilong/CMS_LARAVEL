@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Email Newsletter')])
@endsection
@section('content')
@if(Session::has('error'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('error') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
<div class="card">
	<div class="card-body">
		<div class="table-responsive custom-table">
			<table class="table">
				<thead>
					<tr>
						<th class="am-title">{{ __('Email') }}</th>
						<th class="text-right">{{ __('Created At') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($posts as $row)
					<tr>
						<td class="text-left">{{($row->name) }}</td>
						<td class="text-right">{{ $row->updated_at->diffForHumans() }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush