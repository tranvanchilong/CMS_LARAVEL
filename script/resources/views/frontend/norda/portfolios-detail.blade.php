@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('portfolio').'')}}">{{__('Portfolio')}}</a></li>
                <li class="active">{{$portfolio->name}}</li>
            </ul>
        </div>
    </div>
</div>

<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
               <div class="blog-details-wrapper">
                    <div class="blog-details-top">
                        <div class="blog-details-img">
                            <img alt="" src="{{ asset(ImageThumnail($portfolio->image ?? 'uploads/default.png')) }}">
                        </div>
                        <div class="blog-details-content">
                            <div class="blog-meta-2">
                                <ul>
                                    <li>{{$portfolio->category->name ?? ''}}</li>
                                    <li>{{date_format($portfolio->created_at, 'Y-m-d')}}</li>
                                </ul>
                            </div>
                            <h1>{{$portfolio->name}}</h1>
                            {!!html_entity_decode($portfolio->content)!!}
                        </div>
                    </div>
                    <div class="tag-share">
                        <div class="blog-share">
                            <span>{{__('Share')}} :</span>
                            <div class="share-social">
                                <ul>
                                    <li>
                                        <a class="facebook" href="//www.facebook.com/sharer/sharer.php?u=">
                                            <i class="icon-social-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="twitter" href="#">
                                            <i class="icon-social-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="instagram" href="#">
                                            <i class="icon-social-instagram"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                    <!-- <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">Search </h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form" action="/portfolio" method="get">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="Search Post">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div> -->
                    <div class="sidebar-widget mb-35 pt-40">
                        @if(count($portfolios_category) > 0)
                        <h4 class="sidebar-widget-title">{{__('Categories')}} </h4>
                        @endif
                        <div class="shop-catigory">
                            <ul>
                                @foreach ($portfolios_category as $key => $portfolio_category)
                                    <li><a @if($portfolio_category->id == $portfolio->category_id) class="filter-item" @endif href="/{{permalink_type('portfolio')}}?category_id={{$portfolio_category->id}}">{{$portfolio_category->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                        <h4 class="sidebar-widget-title">Other Portfolios </h4>
                        <div class="recent-post">
                            @foreach($portfolios_random as $portfolio)
                            <div class="single-sidebar-blog">
                                <div class="sidebar-blog-img">
                                    <a href="/portfolio/{{$portfolio->slug}}"><img src="{{ asset($portfolio->image ?? 'uploads/default.png') }}" alt=""></a>
                                </div>
                                <div class="sidebar-blog-content">
                                    <h5><a href="/portfolio/{{$portfolio->slug}}">{{$portfolio->name}}</a></h5>
                                    <span>{{date_format($portfolio->created_at, 'Y-m-d')}}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
