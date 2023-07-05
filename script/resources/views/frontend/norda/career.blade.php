@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Career')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="row">
                    @foreach($careers as $career)
                    <div class="col-lg-12 mb-4">
                        <div class="single-job-besco">
                            <div class="single-job-besco__header">
                                <a href="/{{permalink_type('career')}}?category_id={{$career->category_id ?? ''}}"><p class="category">{{$career->category->name ?? ''}}</p></a>
                                <a href="/{{permalink_type('career_detail')}}/{{$career->slug}}" class="title">
                                    <p class="title">{{$career->name}}</p>
                                </a>
                            </div>
                            <div class="single-job-besco__info">
                                <p class="pl-1 created-date"><img src="{{ asset('uploads/icon_wall_clock.png') }}" alt="" class="icon-wall-clock">{{date_format($career->created_at, 'Y-m-d')}}</p>
                                <p class="job-type">Toàn thời gian</p>
                                <p class="salary"><img src="{{ asset('uploads/icon_salary.png') }}" alt="" class="icon-salary"> Salary: {{$career->salary}}</p>
                            </div>
                            <p class="single-job-besco__summary">
                                {{$career->summary}}
                            </p>
                            <div class="text-right">
                                <a href="/{{permalink_type('career_detail')}}/{{$career->slug}}" class="single-job-detail">{{__('Learn More')}}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{$careers->appends(request()->input())->links()}}
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
                        <h4 class="sidebar-widget-title">{{__('Categories')}} </h4>
                        <div class="shop-catigory">
                            <ul>
                                @foreach ($careers_category as $key => $career_category)
                                    <li><a @if($career_category->id == request()->input('category_id')) class="filter-item" @endif href="/{{permalink_type('career')}}?category_id={{$career_category->id}}">{{$career_category->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                        <h4 class="sidebar-widget-title"> {{__('Other Careers')}}</h4>
                        <div class="recent-post">
                            @foreach($careers_random as $career)
                            <div class="single-sidebar-blog">
                                <!-- <div class="sidebar-blog-img">
                                    <a href="/career/{{$career->slug}}"><img src="{{ asset($career->image ?? 'uploads/default.png') }}" alt=""></a>
                                </div> -->
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
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
