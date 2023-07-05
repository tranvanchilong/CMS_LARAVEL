@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Redirect Settings')])
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Redirect') }}</h4>
		    </div>
			<div class="card-body">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">   
                        <form class="basicform_with_reload" method="post" action="{{ route('seller.redirect.store') }}" enctype="multipart/form-data">
                            @csrf
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
                                        <input type="text" name="link_check" class="form-control" required placeholder="Sub Link"> 	
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <div class="form-divider">
                                        {{ __('Full Redirect Link') }} <br>
                                        <span>{{ __('Example') }}: </span>
                                        <span class="text-success">https://di4l.vn</span>
                                    </div>
                                    <div class="input-group mb-2">
                                        <input type="text" name="link_redirect" class="form-control" required placeholder="Full Redirect Link"> 	
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-sm-12 text-center">
                                            <button class="btn btn-primary basicbtn" type="submit">{{ __('Save') }}</button>
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
    <div class="card"  >
        <div class="card-body">
            <div class="row mb-30">
                <div class="col-lg-6">
                    <div class="d-flex">
                        <div>
                            <h4>{{ __('Redirect') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <br>
                <div class="table-responsive custom-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Sub Link') }}</th>
                                <th>{{ __('Full Redirect Link') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <form class="basicform_with_reload">
                            <tbody>
                                @foreach($redirects as $redirect)
                                <tr>
                                    <td>
                                        <a href="{{ url('/').'/'.$redirect->link_check.''}}" target="_blank"><span class="badge badge-success">{{ url('/').'/'.$redirect->link_check.'' }}</span></a>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $redirect->link_redirect}}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.redirect.edit',$redirect->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('seller.redirect.destroys',$redirect->id) }}" class="btn btn-danger  cancel"><i class="fa fa-trash"></i></a>						    
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </form>
                        <tfoot>
                            <tr>
                                <th>{{ __('Sub Link') }}</th>
                                <th>{{ __('Full Redirect Link') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    {{ $redirects->links('vendor.pagination.bootstrap-4') }}
                </div>
        </div>
    </div>
</div> 
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush