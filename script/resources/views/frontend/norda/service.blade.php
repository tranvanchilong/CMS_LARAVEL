@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Services')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
                <div class="row slider-image">
                    @foreach ($services as $row)
                    <div class="col-lg-4 col-md-6 col-md-12 p-0">
                        <div class="border-slider text-center p-4 m-3">
                            <div class="">
                                <a href="/{{permalink_type('service_detail')}}/{{$row->slug}}">
                                    <img src="{{asset($row->image)}}" class="rounded img-fluid lazy" alt="">
                                </a>
                            </div>
                            <a href="/{{permalink_type('service_detail')}}/{{$row->slug}}">
                                <h4 class="mt-3 mb-2">{{($row->name)}}</h4>
                            </a>
                            <p class="text-slide-img">
                                {!! strlen(strip_tags(html_entity_decode($row->content))) > 120 ? mb_substr(strip_tags(html_entity_decode($row->content)), 0, 120, 'utf-8') . '...' : strip_tags(html_entity_decode($row->content)) !!}
                            </p>
                            <a class="read-more" href="/{{permalink_type('service_detail')}}/{{$row->slug}}">{{__('Learn More')}}</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{$services->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
