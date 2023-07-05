@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Blog')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="row">
                    @foreach($blogs as $blog)
                    <div class="col-lg-6 col-md-6 col-12 col-sm-6">
                        <div class="blog-wrap mb-40">
                            <div class="blog-img mb-20">
                                <a href="/{{permalink_type('blog_detail')}}/{{$blog->slug}}"><img src="{{ asset($blog->image ?? 'uploads/default.png') }}" alt="blog-img"></a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <ul>
                                        @if($blog->bcategory)
                                        <li><a href="/{{permalink_type('blog')}}/category/{{$blog->bcategory->slug}}">{{$blog->bcategory->name ?? ''}} </a></li>
                                        @endif
                                        <li>{{date_format($blog->created_at, 'Y-m-d')}}</li>
                                    </ul>
                                </div>
                                <h1><a href="/{{permalink_type('blog_detail')}}/{{$blog->slug}}">{{$blog->title}}</a></h1>
                                <p>
                                {{ strlen($blog->excerpt) > 120 ? mb_substr($blog->excerpt, 0, 120, 'utf-8') . '...' : $blog->excerpt }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{$blogs->appends(request()->input())->links()}}
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
                    <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">{{__('Search')}}</h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="{{__('Search Post')}}">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Categories')}}</h4>
                        <div class="shop-catigory">
                            <ul>
                                @foreach ($blogs_category as $key => $blog_category)
                                    <li><a @if($blog_category->slug == request()->slug) class="filter-item" @endif href="/{{permalink_type('blog')}}/category/{{$blog_category->slug}}">{{$blog_category->name}}</a></li>
				                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Random Posts')}}</h4>
                        <div class="recent-post">
                            @foreach($blogs_random as $blog)
                            <div class="single-sidebar-blog">
                                <div class="sidebar-blog-img">
                                    <a href="/{{permalink_type('blog_detail')}}/{{$blog->slug}}"><img src="{{ asset($blog->image ?? 'uploads/default.png') }}" alt=""></a>
                                </div>
                                <div class="sidebar-blog-content">
                                    <h5><a href="/{{permalink_type('blog_detail')}}/{{$blog->slug}}">{{$blog->title}}</a></h5>
                                    <span>{{date_format($blog->created_at, 'Y-m-d')}}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
