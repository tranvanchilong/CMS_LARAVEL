@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Team')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60 team">
    <div class="container">
        <div class="row flex-row-reverse">
            <!-- <div class="col-lg-3">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                    <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">Search </h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="Search Post">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-12">
                <div class="row">
                    @foreach($teams as $team)
                    <div class="col-lg-3 col-md-6 col-12 team-slider-active">
                        <div class="text-center p-3">
                            <div class="image-member">
                                <a href="/{{permalink_type('team_detail')}}/{{$team->id}}">
                                    <img src="{{ asset($team->image ?? 'uploads/default.png') }}" class="img-fluid lazy" alt="">
                                </a>
                            </div>
                            <h4 class="mt-3 mb-2"><a href="/{{permalink_type('team_detail')}}/{{$team->id}}">{{($team->name)}}</a></h4>
                            <p class="text-slide-img">{{$team->rank}}</p>
                            <nav class="nav social justify-content-center text-center mb-0">
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
                        </div>
                    </div>
                    @endforeach
                </div>

                {{$teams->appends(request()->input())->links()}}
                <!--<div class="pro-pagination-style text-center mt-10">-->
                <!--    <ul>-->
                <!--        <li><a class="prev" href="#"><i class="icon-arrow-left"></i></a></li>-->
                <!--        <li><a class="active" href="#">1</a></li>-->
                <!--        <li><a href="#">2</a></li>-->
                <!--        <li><a class="next" href="#"><i class="icon-arrow-right"></i></a></li>-->
                <!--    </ul>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
