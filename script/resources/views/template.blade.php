@extends('main.app')
@section('content')
<section class="page-title bg-1">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="block text-center">
					<h1 class="text-capitalize mb-5 text-lg">{{ __('Site Templates') }}</h1>
					<span class="text-white">{{ __('gallery_description') }}</span>   
				</div>
			</div>
		</div>
	</div>
</section>

<section class="saas-featured-users section">
    <div class="container">
        <div class="row">
        	@if($raw==true)
        	@foreach($templates as $row)	
			<div class="col-lg-4 col-md-6 col-sm-12 mb-5">
			    <div class="user-item mb-40">
			        <div class="theme-image">
			            <img width="100%" height="auto" src="{{ asset($row->asset_path.'/screenshot.png') }}" alt="">
			        </div>
			        <div class="theme-info mb-3">
			            <h5>{{ $row->name }}</h5>			                    
			            <div class="p-2">
		                    <a href="#" class="btn btn-main"><i class="icofont-ui-add mr-1"></i>Choosse Theme
		                    </a>
			            </div>
			        </div>
			    </div>
			</div>
			@endforeach
			@else
            @foreach($templates as $t)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-5">
			    <div class="user-item mb-40">
			        <div class="theme-image">
			            <img width="100%" height="auto" src="{{ asset($t->user_domain->thumbnail) }}" alt="">
			        </div>
			        <div class="theme-info mb-3">
			            <h5>{{$t->name}}</h5>			                    
			            <div class="p-2">
			            	<a href="{{ $t->user_domain->full_domain ?? '' }}" class="btn btn-main" target="_blank"><i class="icofont-link mr-1"></i>Website</a>
		                    <a href="{{ route('merchant.form') }}" class="btn btn-success"><i class="icofont-ui-add mr-1"></i>Register
		                    </a>
			            </div>
			        </div>
			    </div>
			</div>
            @endforeach
            @endif
        </div>
    </div>
</section>
@endsection
