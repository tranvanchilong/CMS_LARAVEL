@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Knowledge')}} </li>
            </ul>
        </div>
    </div>
</div>
<div class="blog-area pt-60 pb-60 team gallery">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="filter-nav text-center mb-15">
                <ul class="filter-btn">
                    <a href="{{url('/'.permalink_type('knowledge').'')}}" class="@if(isset($_GET['category'])) @elseif(isset($_GET['page'])) active @else active @endif">{{__('All')}}</a>
                    @foreach ($guides_category as $category)                
                        <a href="{{url('/'.permalink_type('knowledge').'') .'?category='.$category->id}}" class="@if($category->id == request()->category ?? '') active @endif">{{ ($category->name) }}</a>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($guides as $guide)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
                <div style="border-radius: 5px;border: 2px solid #f0f4f6;">
                    <br/>                
                    <div class="text-center">
                        <h5><a href="/{{permalink_type('knowledge')}}/{{$guide->slug}}">{{$guide->title}}</a></h5>    
                    </div>
                    <br/>
                </div>
            </div>
            @endforeach
        </div>
        <br/>
        <div style="text-align: right">
            {{$guides->appends(request()->input())->links()}}
        </div>

    </div>

</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush