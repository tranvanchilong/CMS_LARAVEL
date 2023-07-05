@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Templates'])


<style>
    .hidden{
        display:none;
    }
    .show{
        display: block;
    }
    .blockPage {
        padding: 0px;
        margin: 0px;
        text-align: center;
        color: #ffffff;
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0%;
        opacity: 0.6;
        z-index: 1004;
        cursor: wait;
        right: 0px;
        background: #000;
    }
    .blockUI {
        padding: 0px;
        margin: 0px;
        top: 40%;
        font-weight: normal;
        font-size: 20px;
        left: 35%;
        text-align: center;
        z-index: 999999 ! important;
        position: fixed;
        width: 35%;
    }
    .blockUI p{
        margin-top: 10px;
        color: #f2f2f2;
    }
    @if(!Auth::user()->user_domain->template_id)
	.fa-bars, .main-sidebar{
		display: none;
	}
	.main-content{
		padding-left: 30px;
	}
    @endif
</style>

@endsection
@section('content')
<div id="blockDiv" class="hidden">
    <div class="blockPage"></div>
    <div class="blockUI">
        <img class="height" src="{{ asset('uploads/loading-gif.gif') }}" width="50">
        <p>{{__('The system is importing data')}}<br>{{__('Please wait')}}</p>

    </div>
</div>

@if(Session::has('success'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<h4 class="mb-0">{{ Session::get('success') }}</h4>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
@if(Session::has('warning'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<h4 class="mb-0">{{ Session::get('warning') }}</h4>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif


<section class="saas-featured-users section">
    <div class="container">
        <div class="row">
            @foreach($templates as $t)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-5">
			    <div class="user-item1 mb-40">
			        <div class="theme-image">
			            <img width="100%" height="auto" src="{{ asset($t->user_domain->thumbnail) }}" alt="">
			        </div>
			        <div class="theme-info my-3">
			            <h5>{{$t->name}}</h5>
			            <div class="p-2">
			            	<a target="_blank" href="{{ $t->user_domain->full_domain ?? '' }}" class="btn btn-success"><i class="flaticon-link mr-1"></i>{{ __('View Demo') }}</a>
                            @if(Auth::User()->user_domain->template_id)
			            	<form class="d-inline-block mr-1" method="post" action="{{ route('seller.template.update',$t->user_domain->id) }}">
							@method('PUT')
							@csrf
<!-- 		                    <a href="" class="btn btn-primary"><i class="icofont-ui-add mr-1"></i>
		                    </a> -->
		                    <button type="submit" class="btn @if($active_theme->template_domain_id != $t->user_domain->id) btn-primary @endif col-12" @if($active_theme->template_domain_id == $t->user_domain->id) disabled="" @endif>@if($active_theme->template_domain_id == $t->user_domain->id) {{ __('Selected') }} @else {{ __('Select Template') }} @endif</button>
		                	</form>
                            @endif

							@if($active_theme->template_domain_id != $t->user_domain->id)
								<form onSubmit="return submitForm(this) " class="ml-1 d-inline-block import_template" method="post" action="{{ route('seller.template.update',$t->user_domain->id) }}">
								@method('PUT')
								@csrf
	<!-- 		                    <a href="" class="btn btn-primary"><i class="icofont-ui-add mr-1"></i>
								</a> -->
								<input type="hidden" name="import" value="1" />
								<button type="submit" class="btn btn-primary col-12">{{ __('Select and Import Template data') }}</button>
								</form>
							@endif
			            </div>
			        </div>
			    </div>
			</div>
            @endforeach
        </div>
    </div>
</section>


@endsection
@push('style')
<style type="text/css">
	.saas-featured-users .user-item1 {
	    border-radius: 5px;
	    border: 2px solid #e2eeff;
	    text-align: center;
	}
	.saas-featured-users .user-item1 .theme-image {
	    height: 300px;
	    overflow: hidden;
	}
	.theme-info .btn{
		padding: 8px 10px;
		margin: 5px;
	}
</style>
@endpush
@push('js')
<script>
function submitForm(item) {
	if ( confirm('{{__('Import Template')}}') == false ) {
      return false ;
   } else {
        $('#blockDiv').show();
        return true ;
   }
}</script>
<script src="{{ asset('assets/js/form.js') }}"></script>

@endpush
