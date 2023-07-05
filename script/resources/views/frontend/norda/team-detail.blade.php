@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('team').'')}}">{{__('Team')}}</a></li>
                <li class="active">{{$team->name}}</li>
            </ul>
        </div>
    </div>
</div>

<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
               <div class="blog-details-wrapper">
                    <div class="blog-details-top">
                        <div class="blog-details-img">
                            <img style="border-radius: 50%;width: 300px;height: 300px;object-fit: cover;margin: 0 auto;object-position:" alt="" src="{{ asset($team->image ?? 'uploads/default.png') }}">
                        </div>
                        <div class="blog-details-content">
                            <div class="blog-meta-2">
                                <ul>
                                    <li>{{$team->rank}}</li>
                                    <li>{{date_format($team->created_at, 'Y-m-d')}}</li>
                                </ul>
                            </div>
                            <h1>{{$team->name}}</h1>
                            <nav class="nav social mb-0">
                                <a class="m-2" href="{{$team->facebook ?? 'javascript:void(0)'}}" target="{{$team->facebook ? '_blank' : '_self'}}">
                                <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="m-2" href="{{$team->twitter ?? 'javascript:void(0)'}}" target="{{$team->facebook ? '_blank' : '_self'}}"><i class="fab fa-twitter"></i></a>
                                <a class="m-2" href="{{$team->instagram ?? 'javascript:void(0)'}}" target="{{$team->facebook ? '_blank' : '_self'}}">
                                <i class="fab fa-instagram"></i>
                                </a>
                                <a class="m-2" href="{{$team->linkedin ?? 'javascript:void(0)'}}" target="{{$team->facebook ? '_blank' : '_self'}}">
                                <i class="fab fa-linkedin"></i>
                                </a>
                            </nav>
                            {!!html_entity_decode($team->content)!!}
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
            <!-- <div class="col-lg-3">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                    <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">Search </h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form" action="/team" method="get">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="Search Post">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection
@push('js')
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
