@extends('frontend.arafa-cart.layouts.app')
@section('content')
<section class="page-title bg-1">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="text-center" style="text-align:center;margin:30px 0">
            <h1 class="text-capitalize mb-5 text-lg">{{ __('Gallery') }}</h1>
          </div>
        </div>
      </div>
    </div>
  </section>

<section class="section saas-blog blog-page pt-120 pb-120">
    <div class="container">
        <div class="row">
            @foreach($templates as $template)
            @php
                $data = json_decode($template->excerpt->content, true);
            @endphp
            <div class="col-lg-4 col-md-6 col-sm-12">
			    <div style="border-radius: 5px;border: 2px solid #f0f4f6;">
			        <div style="height: 400px;overflow: hidden;">
			            <img width="100%" height="auto" src="/{{ $data['image'] }}" alt="">
			        </div>
                    <br/>                
			        <div class="text-center">
			            <h4>{{$data['title']}}</h4>    
			            <div class="p-2">
			            	<a href="{{$data['button_link_1']}}" class="btn btn-info" target="_blank"><i class="icofont-link mr-1"></i>{{$data['button_text_1']}}</a>
		                    <a href="{{$data['button_link_2']}}" class="btn btn-success"><i class="icofont-ui-add mr-1"></i>{{$data['button_text_2']}}</a>
			            </div>
			        </div>
                    <br/>
			    </div>
			</div>
            @endforeach
        </div>
        <br/>
        <div style="text-align: right">
            {{$templates->appends(request()->input())->links()}}
        </div>

    </div>
</section><!--====== End saas-blog section ======-->	
@endsection
