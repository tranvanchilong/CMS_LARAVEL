@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Packages')}} </li>
            </ul>
        </div>
    </div>
</div>

<section class="package-area">
    <div class="blog-area pt-60 pb-60 package">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="filter-nav text-center mb-15">
                        <ul class="filter-btn">
                        <a href="{{url('/'.permalink_type('package').'')}}" class="@if(isset($_GET['category'])) @else active @endif">{{__('All')}}</a>
                        @foreach ($packages_category as $category)
                            @php
                                $filterValue = $category->slug;
                                $get_category = $_GET['category'] ?? 'all';
                            @endphp

                            <a href="{{url('/'.permalink_type('package').'') .'?category='.$filterValue.''}}" class="@if($filterValue == $get_category) active @endif">{{ ($category->name) }}</a>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @php
                $count_package = $packages->count();
            @endphp
            <div class="row flex-row-reverse">
                <div class="col-lg-12">
                    <div class="row" @if($count_package <= 2) style="justify-content: center;" @endif>
                        @foreach ($packages as $row)
                        @php
                        $package_features = explode(PHP_EOL, $row->package_feature);
                        $not_package_features = explode(PHP_EOL, $row->not_package_feature);
                        $notes = explode(PHP_EOL, $row->note);
                        @endphp
                        <div class="col-md-6 col-lg-4 popular mb-4">
                            <div class="pricing card">
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="card-title">{{$row->name}}</h2>
                                        <h1 class="price-value">{{$row->price}}</h1>
                                    </div>
                                    <ul class="text-start package_feature mt-4">
                                        @if(!empty($row->package_feature))
                                            @foreach($package_features as $package_feature)
                                            <li><i class="icon-check"></i><span class="ml-2">{{$package_feature}}</span></li>
                                            @endforeach
                                        @endif
                                        @if(!empty($row->not_package_feature))
                                            @foreach($not_package_features as $not_package_feature)
                                            <li><i class="icon-close"></i><span class="ml-2">{{$not_package_feature}}</span></li>
                                            @endforeach
                                        @endif
                                        @if(!empty($row->note))
                                            @foreach($notes as $note_package_feature)
                                            <li><span>{{$note_package_feature}}</span></li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    @if (!empty($row->btn_url_2) && !empty($row->btn_text_2))
                                        <a class="p-3 px-4 f-left" href="{{$row->btn_url_2}}">{{$row->btn_text_2}}</a>
                                    @endif
                                    @if (!empty($row->btn_url) && !empty($row->btn_text))
                                    <div class="btn-style-1 mt-30 @if(empty($row->btn_url_2) && empty($row->btn_text_2)) text-center @else text-right mrr-30 @endif">
                                        <a class="p-3 px-4" href="{{$row->btn_url}}">{{$row->btn_text}}</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
