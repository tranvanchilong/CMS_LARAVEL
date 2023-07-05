@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('career').'')}}">{{__('Career')}}</a></li>
                <li class="active">{{$career->name}}</li>
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
                        <div class="blog-details-content">
                            <div class="blog-meta-2">
                                <ul>
                                    <li><a href="/{{permalink_type('career')}}?category_id={{$career->category_id ?? ''}}">{{$career->category->name ?? ''}}</a></li>
                                    <li>{{date_format($career->created_at, 'Y-m-d')}}</li>
                                </ul>
                            </div>
                            <h1>{{$career->name}}</h1>
                            {!!html_entity_decode($career->content)!!}
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
                            <form class="sidebar-search-form" action="/career" method="get">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="Search Post">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div> -->
                    <div class="sidebar-widget mb-35 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Categories')}} </h4>
                        <div class="shop-catigory">
                            <ul>
                                @foreach ($careers_category as $key => $career_category)
                                    <li><a @if($career_category->id == $career->category_id) class="filter-item" @endif href="/{{permalink_type('career')}}?category_id={{$career_category->id}}">{{$career_category->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Other Careers')}}</h4>
                        <div class="recent-post">
                            @foreach($careers_random as $career)
                            <div class="single-sidebar-blog">
                                <div class="sidebar-blog-content">
                                    <h5><a href="/{{permalink_type('career_detail')}}/{{$career->slug}}">{{$career->name}}</a></h5>
                                    <span>{{date_format($career->created_at, 'Y-m-d')}}</span>
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
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
