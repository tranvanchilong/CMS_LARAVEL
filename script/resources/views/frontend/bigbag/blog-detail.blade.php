@extends('frontend.bigbag.layouts.app')
@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="text-center" style="text-align:center;margin:30px 0">
          <h1 class="text-capitalize mb-5 text-lg">{{ $blog->title }}</h1>
        </div>
      </div>
    </div>
  </div>
</section>
<!--====== BLOG DETAILS PART START ======-->

    <section class="section blog-details-area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="blog-details-items">
                        <div class="blog-thumb">
                            <img class="lazy" src="{{ asset($blog->medias->first()->name ?? 'uploads/default.png') }}" width="100%" alt="blog">
                        </div>
                        <div class="blog-details-content mt-5">
                            <h3 class="title">{{ $blog->title }}</h3>
                            <div class="summernote-content">
                                {!!html_entity_decode($blog->content->value)!!}
                            </div>
                        </div>

                        <div class="blog-social">
                            <div class="shop-social d-flex align-items-center">
                                <span>{{__('Share')}} :</span>
                                <ul class="ml-3 d-flex">
                                    <li class="p-1"><a href="//www.facebook.com/sharer/sharer.php?u="><i class="icofont-facebook"></i></a></li>
                                    <li class="p-1"><a href="//twitter.com/intent/tweet?text=my share text&amp;url="><i class="icofont-twitter"></i></a></li>
                                    <li class="p-1"><a href="//www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title="><i class="icofont-linkedin"></i></a></li>

                                </ul>
                            </div>
                        </div>

                        <div class="blog-details-comment mt-5">
                            <div class="comment-lists">
                                <div id="disqus_thread"></div>
                            </div>
                        </div> <!-- blog details comment -->
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
    </section>

    <!--====== BLOG DETAILS PART ENDS ======-->
@endsection
@push('js')
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
@endpush
