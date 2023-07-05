@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Contact List')])
@endsection
@php
	$position = \App\Useroption::where('user_id', Auth::id())->where('key', 'contact_list')->first()->value ?? '1';
	$icon = \App\Useroption::where('user_id', Auth::id())->where('key', 'icon_contact')->first()->value ?? '';
	$status_contact = \App\Domain::where('user_id',Auth::id())->first();
@endphp
@section('content')
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Icon Default') }}</h4>
		    </div>
			<div class="card-body">
				<form class="ajaxFormUpdate" method="post" action="{{ route('seller.contactlists.icon_update') }}" enctype="multipart/form-data">
					@csrf
					<div class="custom-form pt-20">
						<div class="form-group">
							<label for="">{{ __('Image') }} ** </label>
							<br>
							<div class="thumb-preview">
								<img width="300" src="{{$icon ? asset($icon) : asset('uploads/icon_default.png')}}" alt="Contact">
							</div>
							<br>
							<br>
							<input type="file" class="form-control" name="image">
							<p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
							<p class="em text-danger mb-0" id="errimage"></p>
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
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Hide/Show Contact List') }}</h4>
		    </div>
			<div class="card-body">
				<form class="basicform_with_reload" method="post" action="{{ route('seller.contactlists.status_contact') }}" enctype="multipart/form-data">
					@csrf
					<div class="custom-form pt-20">
						<div class="form-group">
							<div class="custom-control custom-switch">
								<input id="enabled" class="custom-control-input" @if($status_contact->top_bar_contact_status==1) checked="checked" @endif name="top_bar_contact_status" type="checkbox" value="1">
								<label class="custom-control-label" for="enabled">Enable Top Bar</label>
							</div>
						</div>

						<div class="form-group">
							<div class="custom-control custom-switch">
								<input id="float_contact" class="custom-control-input" @if($status_contact->float_contact_status==1) checked="checked" @endif  name="float_contact_status" type="checkbox" value="1">
								<label class="custom-control-label" for="float_contact">Enable Float Contact</label>
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
<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Add Contact List') }}</h4>
		    </div>
			<div class="card-body">
				<form class="basicform_with_reload" method="post" action="{{ route('seller.contactlists.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="custom-form pt-20">

                        <div class="form-group">
							<label>{{ __('Image') }}</label>
							<input type="file" name="file" class="form-control" required> 
						</div>

						<div class="form-group">
							<label>{{ __('URL') }}</label>
							<input type="text" name="url" class="form-control" required placeholder="Url"> 	
						</div> 

						<div class="form-group">
							<label >{{ __('Show Float') }}</label>
							<select name="is_show_float_content" class="form-control">
								<option value="1">Active</option>
								<option value="0">Deactive</option>
							</select>
                    	</div>

						<div class="form-group">
							<label >{{ __('Show TopBar') }}</label>
							<select name="is_show_topbar" class="form-control">
								<option value="1">Active</option>
								<option value="0">Deactive</option>
							</select>
                    	</div>
						
						<div class="form-group">
							<label>{{ __('Serial Number') }}</label>
							<input type="number" name="serial_number" class="form-control" placeholder="Serial Number"> 	
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
<div class="card">
	<div class="card-body">
		<div class="row mb-30">
			<div class="col-lg-6">
				<div class="d-flex">
					<div>
						<h4>{{ __('Contact List') }}</h4>
					</div>
					<div style="margin-left: 10px;">
						@if ($position == 1)
							<a class="btn btn-info btn-sm" href="{{ route('seller.contactlists.position_contact',[0]) }}">Right</a>
						@else
							<a class="btn btn-warning btn-sm" href="{{ route('seller.contactlists.position_contact',[1]) }}">Left</a>
						@endif
					</div>
				</div>
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
			                <th>{{ __('Url') }}</th>
							<th>{{ __('Serial Number') }}</th>
							<th>{{ __('Show Float') }}</th>
							<th>{{ __('Show Topbar') }}</th>
							<th>{{ __('Created at') }}</th>
							<th>{{ __('Action') }}</th>
						</tr>
					</thead>
					<form class="basicform_with_reload">
					<tbody>
						@foreach($contact as $notice)
						<tr>
							<td><img src="{{asset($notice->image ?? 'uploads/default.png') }}" height="50"></td>
							<td>{{ $notice->url }}</td>
							<td>
								<span class="badge badge-success">{{ $notice->serial_number}}</span>
							</td>
							<td>
								@if ($notice->is_show_float_content == 1)
									<h5 class="d-inline-block"><span class="badge badge-success">Active</span></h5>
								@else
									<h5 class="d-inline-block"><span class="badge badge-danger">Deactive</span></h5>
								@endif
							</td>
							<td>
								@if ($notice->is_show_topbar == 1)
									<h5 class="d-inline-block"><span class="badge badge-success">Active</span></h5>
								@else
									<h5 class="d-inline-block"><span class="badge badge-danger">Deactive</span></h5>
								@endif
							</td>
							<td>
								<div class="date">
									{{ $notice->updated_at->diffForHumans() }}
								</div>
							</td>
							<td>
			                    <a href="{{ route('seller.contactlists.edit',$notice->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i></a>
								<a href="{{ route('seller.contactlists.destroys',$notice->id) }}" class="btn btn-danger  cancel"><i class="fa fa-trash"></i></a>						    
			                </td>
						</tr>
						@endforeach
					</tbody>
				</form>
				<tfoot>
					<tr>
						<th><i class="fa fa-image"></i></th>
		                <th>{{ __('Url') }}</th>
						<th>{{ __('Serial Number') }}</th>
						<th>{{ __('Status') }}</th>
		                <th>{{ __('Created at') }}</th>
		                <th>{{ __('Action') }}</th>
					</tr>
				</tfoot>
			</table>
			{{ $contact->links('vendor.pagination.bootstrap-4') }}

		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush