@extends('frontend.norda.layouts.app') 
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Gallery')}} </li>
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
                    <a href="{{url('/'.permalink_type('gallery').'')}}" class="@if(isset($_GET['category'])) @elseif(isset($_GET['page'])) active @else active @endif">{{__('All')}}</a>
                    @foreach ($gallery_category as $category)
                        @php
                            $filterValue = Str::slug($category->name);
                            $get_category = $_GET['category'] ?? 'all';
                        
                        @endphp                       
                        <a href="{{url('/'.permalink_type('gallery').'') .'?category='.$filterValue.''}}" class="@if($filterValue == $get_category) active @endif">{{ ($category->name) }}</a>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($templates as $template)
            @php
                $data = json_decode($template->excerpt->content);
            @endphp
            
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
                <div style="border-radius: 5px;border: 2px solid #f0f4f6;">
                    <div style="height: 400px;overflow: hidden;">
                        @if (gettype($data->image)=="string")
                        <img width="100%" height="auto" src="/{{$data->image}}" alt="">
                        @else
                        <img width="100%" height="auto" src="/{{ array_values($data->image)[0]; }}" alt="">
                        @endif                      
                    </div>
                    @if($data->button_link_1 && $data->button_text_1 && $data->button_link_2 && $data->button_text_2)
                    <br/>     
                    @endif           
                    <div class="text-center">
                        <h4>{{$data->title}}</h4>    
                        <div class="p-2">
                            @if($data->button_link_1 && $data->button_text_1)
                            <a href="{{$data->button_link_1}}" class="btn btn-info" target="_blank"><i class="icofont-link mr-1"></i>{{$data->button_text_1}}</a>
                            @endif
                            @if($data->button_link_2 && $data->button_text_2)
                            <a href="{{$data->button_link_2}}" class="btn btn-success"><i class="icofont-ui-add mr-1"></i>{{$data->button_text_2}}</a>
                            @endif
                        </div>
                    </div>
                    @if($data->button_link_1 && $data->button_text_1 && $data->button_link_2 && $data->button_text_2)
                    <br/>     
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <br/>
        <div style="text-align: right">
            {{$templates->appends(request()->input())->links()}}
        </div>

    </div>

</div>
@endsection
