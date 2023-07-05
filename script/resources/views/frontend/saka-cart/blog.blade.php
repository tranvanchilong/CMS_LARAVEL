@extends('frontend.saka-cart.layouts.app')
@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="text-center" style="text-align:center;margin:30px 0">
          <h1 class="text-capitalize mb-5 text-lg">{{ __('Blog') }}</h1>
        </div>
      </div>
    </div>
  </div>
</section>
<!--====== Start saas-blog section ======-->
    <section class="section saas-blog blog-page pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @foreach($blogs as $blog)
                            <div class="col-lg-12">
                                <div class="blog-item @if(!$loop->last) mb-40 @endif">
                                    <div class="entry-content pr-4">
                                        <div class="entry-meta">
                                            <ul>
                                                <li><span><i class="icofont-stylish-right"></i><a
                                                            href="">{{$blog->bcategories->first()->name}}</a></span></li>
                                                <li>
                                                <span>
                                                    <i class="icofont-calendar"></i>
                                                    <a href="#">{{\Carbon\Carbon::parse($blog->created_at)->format("F j, Y")}}</a>
                                                </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <h3 class="title"><a href="/blog-detail/{{$blog->slug}}">{{$blog->title}}</a></h3>
                                        <a href="/blog-detail/{{$blog->slug}}" class="read-btn">{{__('Read More')}}</a>
                                    </div>
                                    <a class="post-img d-block" href="/blog-detail/{{$blog->slug}}">
                                        <img src="{{ asset($blog->medias->first()->name ?? 'uploads/default.png') }}" style="width: 100%;max-height: 100%;object-fit: contain;" class="img-fluid lazy"
                                             alt="">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        {{$blogs->appends(['category' => request()->input('category')])->links()}}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-sidebar">
					    <div class="blog-box blog-border">
					        <div class="blog-title pl-45">
					            <h4 class="title"><i class="icofont-listine-dots"></i> {{__('All Categories')}}</h4>
					        </div>
					        <div class="blog-cat-list pl-45 pr-45">
					            <ul>
					                <li class="single-category"><a href="">{{__('All')}}</a></li>
					                @foreach ($blogs_category as $key => $blog_category)
					                    <li class="single-category"><a href="">{{$blog_category->name}}</a></li>
					                @endforeach
					            </ul>
					        </div>
					    </div>
					</div>
                </div>
            </div>
        </div>
    </section><!--====== End saas-blog section ======-->	
@endsection
@push('js')
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
@endpush