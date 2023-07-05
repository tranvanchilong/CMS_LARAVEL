@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('service').'')}}">{{__('Services')}}</a></li>
                <li class="active">{{$service->name}}</li>
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
                            <img alt="" src="{{ asset(ImageThumnail($service->image ?? 'uploads/default.png')) }}">
                        </div>
                        <div class="blog-details-content">
                            <div class="blog-meta-2">
                                <ul>
                                    <li>{{date_format($service->created_at, 'Y-m-d')}}</li>
                                </ul>
                            </div>
                            <h1>{{$service->name}}</h1>
                            {!!html_entity_decode($service->content)!!}
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
        </div>
    </div>
</div>
@endsection
@push('js')
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
