@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('knowledge').'')}}">{{__('Knowledge')}}</a></li>
                <li class="active">{{$guide->title}}</li>
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
                            <img alt="" src="{{ asset(ImageThumnail($guide->image ?? 'uploads/default.png')) }}">
                        </div>
                        <div class="blog-details-content">
                            <div class="blog-meta-2">
                                <ul>
                                    @if($guide->guide_category)
                                    <li><a href="/{{permalink_type('knowledge')}}?category_id={{$guide->category_id}}">{{$guide->guide_category->name ?? ''}}</a></li>
                                    @endif
                                    <li>{{date_format($guide->created_at, 'Y-m-d')}}</li>
                                </ul>
                            </div>
                            <h1>{{$guide->title}}</h1>
                            {!!html_entity_decode($guide->content)!!}
                        </div>
                    </div>
                    <div class="tag-share">
                        <div class="dec-tag">
                            <ul>
                                @foreach($keyword as $key=>$k)
                                    <li><a href="/blog?keyword={{trim($k)}}">{{trim($k)}} {{count($keyword) - 1 == $key ? '' : ','}}</a></li>
                                @endforeach
                            </ul>
                        </div>
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
                    <div class="next-previous-post btn-style-1">
                        @if($previous)
                            <a class="p-2 px-3" href="/{{permalink_type('knowledge')}}/{{$previous->slug}}"> <i class="fa fa-angle-left"></i> {{__('Prev Post')}}</a>
                        @endif

                        @if($next)
                            <a class="p-2 px-3" href="/{{permalink_type('knowledge')}}/{{$next->slug}}"> <i class="fa fa-angle-right"></i> {{__('Next Post')}} </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                    <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">{{__('Search')}} </h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form" action="/{{permalink_type('knowledge')}}">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="{{__('Search Post')}}">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="knowledge sidebar-widget shop-sidebar-border mb-35 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Categories')}} </h4>
                        <div class="shop-catigory">
                            <ul>
                                @foreach ($guides_category as $guide_category)
                                    <li><a class="knowledge-name" href="/{{permalink_type('knowledge')}}?category={{$guide_category->id}}">{{$guide_category->name}}</a></li>
                                    <ul class="knowledge-title">
                                        @foreach($guide_category->guides as $guides)
                                        <li><a class="{{$guides->slug == $guide->slug ? 'active' : ''}}" href="/{{permalink_type('knowledge')}}/{{$guides->slug}}">{{$guides->title}}</a></li>
                                        @endforeach
                                    </ul>
				                @endforeach
                            </ul>
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
