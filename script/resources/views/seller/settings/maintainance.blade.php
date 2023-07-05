@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Maintenance Mode')])
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Update Maintenance Mode') }}</h4>
		    </div>
			<div class="card-body">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3"> 
                        <form class="basicform" method="post" action="{{ route('seller.maintainance.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>{{__('Maintenance Mode')}} **</label>
                                <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="maintainance_mode" value="1" class="selectgroup-input" {{ $maintainance_mode->is_maintainance_mode == 1 ? 'checked' : ''}}>
                                    <span class="selectgroup-button">Active</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="maintainance_mode" value="0" class="selectgroup-input" {{ $maintainance_mode->is_maintainance_mode == 0 ? 'checked' : ''}}>
                                    <span class="selectgroup-button">Deactive</span>
                                </label>
                                </div>
                                @if ($errors->has('maintainance_mode'))
                                <p class="mb-0 text-danger">{{$errors->first('maintainance_mode')}}</p>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{__('Maintenance Password')}} **</label>
                                <input name="secret_password" type="password" class="form-control" placeholder="Enter Password">
                                
                            </div>
                            <div class="form-group col-lg-12">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-primary basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>

</div> 
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush