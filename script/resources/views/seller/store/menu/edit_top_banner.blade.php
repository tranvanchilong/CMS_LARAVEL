@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Banner Topbar'])
@endsection
@section('content')

<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-20">{{ __('Top Banner') }}</h4>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger none">
							<ul id="errors"></ul>
						</div>	
						<form class="basicform_with_reload" method="post" action="{{ route('seller.top_banner.store') }}">
							<div class="custom-form">
								<div class="form-group">
									<label for="text">{{ __('Image') }}</label>
									<div class="input-group">
										<input type="file" class="form-control" name="image" id="image">
									</div>
									
								</div>
								<div class="form-group">
									<label for="url">{{ __('URL') }}</label>
									<input type="text" class="form-control" id="url" name="url" placeholder="URL">
								</div>
								<div class="form-group">
									<label for="">{{ __('Status') }}</label>
									<select id="status" name="status" class="form-control">
									@if(!empty($info))
										<option {{$info->status == '0' ? 'selected' : '' }} value="0">Deactive</option>
										<option {{$info->status == '1' ? 'selected' : '' }} value="1">Active</option>
									@else
										<option value="0" >{{ __('Deactive') }}</option>
                             			<option value="1" >{{ __('Active') }}</option>
									@endif
									</select>
								</div>
								
							</div>
							
							<div class="form-group">
                         		<button class="btn btn-primary  col-3 basicbtn" type="submit">{{ __('Update') }}</button> 
                     		</div>
						</form>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card mb-3">
				<div class="card-body">
					<div class="table-responsive custom-table">
						<table class="table">
							<thead>
								<tr>
									<th class="am-title">{{ __('Image') }}</th>
									<th class="am-title">{{ __('Url') }}</th>
									<th class="am-title">{{ __('Status') }}</th>
								</tr>
							</thead>
							<tbody>
							@if(!empty($info))
								<tr>
									<td class="text-left"><img src="{{ asset($topbar_image->data ?? '') }}" style="height: 30px;width: 280px;"></td>
									<td class="text-left">
										{{ $info->url ?? '' }}
									</td>
									<td>
										@if($info->status==1 ?? '')
											<span class="badge badge-success">{{ __('Active') }}</span>
										@else
											<span class="badge badge-danger">{{ __('Deactive') }}</span>	
										@endif
									</td>
								</tr>
							@endif
							</tbody>
						</table>
					</div>
				</div>
		
		</div>
	</div>

@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush