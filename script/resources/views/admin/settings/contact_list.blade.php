@extends('layouts.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
@endpush
@section('head')
@include('layouts.partials.headersection',['title'=>'Contact List'])
@endsection
@section('content')

<div class="row">
	<div class="col-lg-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Add Contact List') }}</h4>
		    </div>
			<div class="card-body">
				<form class="basicform_with_reload" method="post" action="{{ route('admin.contact.store') }}" enctype="multipart/form-data">
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
                                    <a href="{{ route('admin.contact.edit',$notice->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i></a>
                                    <a href="{{ route('admin.contact.destroys',$notice->id) }}" class="btn btn-danger cancel"><i class="fa fa-trash"></i></a>						    
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
                            <th>{{ __('Show Float') }}</th>
                            <th>{{ __('Show Topbar') }}</th>
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
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush