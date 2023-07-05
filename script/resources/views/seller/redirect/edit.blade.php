@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Redirect Settings')])
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Edit Redirect') }}</h4>
		    </div>
			<div class="card-body">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">   
                        <form method="post" action="{{ route('seller.redirect.update',$redirects->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <div class="form-divider">
                                        {{ __('Sub Link') }} <br>
                                        <span>{{ __('Example') }}: </span>
                                        <span class="text-success">{{ url('') }}/{sub_link}</span>
                                    </div>
                                        
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">{{ url('') }}/</div>
                                        </div>
                                        <input type="text" name="link_check" class="form-control" required placeholder="Sub Link" value="{{$redirects->link_check}}"> 	
                                    </div>
                                </div>
                               

                                <div class="form-group col-lg-6">
                                    <div class="form-divider">
                                        {{ __('Full Redirect Link') }} <br>
                                        <span>{{ __('Example') }}: </span>
                                        <span class="text-success">https://di4l.vn</span>
                                    </div>
                                    <div class="input-group mb-2">
                                        <input type="text" name="link_redirect" class="form-control" required placeholder="Full Redirect Link" value="{{$redirects->link_redirect}}"> 	
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-sm-12 text-center">
                                            <button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button>
                                        </div>
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