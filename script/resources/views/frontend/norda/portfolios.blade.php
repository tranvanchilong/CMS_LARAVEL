@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Portfolio')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="row slider-image">
                    @foreach ($portfolios as $portfolio)
                    <div class="col-lg-6 col-md-12 p-0 cat-{{$portfolio->category_id}}">
                        <div class="border-slider text-center p-4 m-3">
                            <div class="">
                                <a href="/{{permalink_type('portfolio_detail')}}/{{$portfolio->slug}}">
                                    <img style="object-fit: cover;" src="{{asset($portfolio->image)}}" height="200px" width="100%" class="rounded lazy" alt="">
                                </a>
                            </div>
                            <a href="/{{permalink_type('portfolio_detail')}}/{{$portfolio->slug}}">
                                <h4 class="mt-3">{{($portfolio->name)}}</h4>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>


                {{$portfolios->appends(request()->input())->links()}}
                <!--<div class="pro-pagination-style text-center mt-10">-->
                <!--    <ul>-->
                <!--        <li><a class="prev" href="#"><i class="icon-arrow-left"></i></a></li>-->
                <!--        <li><a class="active" href="#">1</a></li>-->
                <!--        <li><a href="#">2</a></li>-->
                <!--        <li><a class="next" href="#"><i class="icon-arrow-right"></i></a></li>-->
                <!--    </ul>-->
                <!--</div>-->
            </div>
            <div class="col-lg-3">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                    <!-- <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">Search </h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form">
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
                                    <li><a @if($portfolio_category->id == request()->input('category_id')) class="filter-item" @endif href="/{{permalink_type('portfolio')}}?category_id={{$portfolio_category->id}}">{{$portfolio_category->name}}</a></li>
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
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
