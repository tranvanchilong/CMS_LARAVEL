@extends('frontend.norda.layouts.app')
@section('content')
    <div class="breadcrumb-area bg-gray">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="active">{{ __('course') }} </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="blog-area pt-60 pb-60">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">
                    <div class="shop-topbar-wrapper d-block d-lg-flex">
                        <div class="shop-topbar-left">
                            <!--<div class="view-mode nav">-->
                            <!--    <a class="active" href="#shop-1" data-toggle="tab"><i class="icon-grid"></i></a>-->
                            <!--    <a href="#shop-2" data-toggle="tab"><i class="icon-menu"></i></a>-->
                            <!--</div>-->
                            <p>{{ __('Showing') }} <span>{{ $courses->count() }}</span> {{ __('results') }} </p>
                        </div>
                        <div class="product-sorting-wrapper d-none d-lg-block">
                            <div class="product-show shorting-style">
                                <label>{{ __('Sort by') }} :</label>

                                <select class="order_by" name="sort" id="sort">
                                    <option value="DESC">{{ __('New Course') }}</option>
                                    <option value="ASC">{{ __('Old Course') }}</option>
                                    {{-- <option value="price_ascending">{{ __('Price Ascending') }}</option>
                                    <option value="price_descending">{{ __('Price Descending') }}</option> --}}
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row course-image">
                        @foreach ($courses as $course)
                            <div class="col-lg-4 col-md-12 p-0 cat-{{ $course->category_id }}">
                                <div class="single-course p-3 m-3">
                                    <div class="content-image">
                                        <a class="d-block" href="/{{ permalink_type('course') }}/{{ $course->slug }}">
                                            <img style="object-fit: cover;" src="{{ asset($course->image) }}"
                                                height="200px" width="100%" class="lazy entered loaded" alt="">

                                        </a>
                                        @if ($course->category)
                                            <div class="title-course text-right">
                                                <a class="category" href="">{{ $course->category->name ?? '' }}</a>
                                            </div>
                                        @endif

                                    </div>
                                    <a href="/{{ permalink_type('course') }}/{{ $course->slug }}">
                                        <h4 class="mt-3 d-flex justify-content-start">{{ $course->title }}</h4>
                                    </a>
                                    <div class="row mr-1 d-flex justify-content-between">
                                        <div class="col-lg-6">
                                            @if ($course->instructor)
                                                <p>{{ $course->instructor->name }}</p>
                                            @endif
                                        </div>

                                        <div class="d-flex" style="flex-direction: column;">
                                            @if ($course->current_price == 0)
                                                <span>{{ __('Free') }}</span>
                                            @else
                                                <span>{{ number_format($course->current_price, 0, ',', '.') }}
                                                    VND</span>
                                            @endif

                                            @if (!empty($course->previous_price))
                                                <span
                                                class="pre-price">{{ number_format($course->previous_price, 0, ',', '.') }}
                                                VND</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <ul class="d-flex justify-content-center">
                                        <li><i class="fas fa-users disabled"></i> 0 Students</li>
                                        <li><i class="fas fa-clock disabled"></i> {{ $course->duration }}</li>

                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pro-pagination-style text-center mt-10">
                        {{ $courses->appends(request()->input())->links() }}
                        {{-- <ul>
                                <li><a class="prev" href="/courses?page=1"><i class="icon-arrow-left"></i></a></li>
                                <li><a class="active" href="/courses?page=1">1</a></li>
                                <li><a href="/courses?page=2">2</a></li>
                                <li><a class="next" href="#"><i class="icon-arrow-right"></i></a></li>
                            </ul> --}}
                        <br>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                        <div class="sidebar-widget mb-35 pt-40">
                            <div class="sidebar-widget mb-40">
                                <h4 class="sidebar-widget-title">{{ __('Search') }}</h4>
                                <div class="sidebar-search">
                                    <form class="sidebar-search-form">
                                        <input type="text" class="src" name="src"
                                            value="{{ request()->input('src') }}"
                                            placeholder="{{ __('Search here...') }}">
                                        <button>
                                            <i class="icon-magnifier"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar-widget mb-40">
                                <h4 class="sidebar-widget-title">{{ __('Course Type') }}</h4>
                                <div class="sidebar-widget-list-left">

                                    <div class="form-check mt-4 ml-3">

                                        <div class="row">
                                            <input class="categories-course d-flex justify-content-center" type="checkbox"
                                                value="" id="allCourses">
                                            <label class="form-check-label ml-1" for="allCourses">
                                                All Courses
                                            </label>
                                        </div>
                                        <div class="row">
                                            <input class="categories-course d-flex justify-content-center" type="checkbox"
                                                value="" id="freeCourses">
                                            <label class="form-check-label ml-1" for="freeCourses">
                                                Free Courses
                                            </label>
                                        </div>
                                        <div class="row">
                                            <input class="categories-course d-flex justify-content-center" type="checkbox"
                                                value="" id="PremiumCourses">
                                            <label class="form-check-label ml-1" for="PremiumCourses">
                                                Premium Courses
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                {{-- <div class="shop-catigory">
                                                <ul>
                                                    @foreach ($courses_category as $key => $course_category)
                                                        <li style="margin-left: 20px;">
                                                            <a @if ($course_category->id == request()->input('category_id')) class="filter-item" @endif
                                                                href="/{{ permalink_type('courses') }}?category_id={{ $course_category->id }}">{{ $course_category->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div> --}}

                                <div class="sidebar-widget-list-left">
                                    @if (count($courses_category) > 0)
                                        <h4 class="sidebar-widget-title">{{ __('Categories') }} </h4>
                                    @endif
                                    <div class="form-check mt-4 ml-3">
                                        @foreach ($courses_category as $key => $course_category)
                                            <div class="row">
                                                <input class="categories-course d-flex justify-content-center"
                                                    type="checkbox" value="" id="category{{ $course_category->id }}">
                                                <label class="form-check-label ml-1"
                                                    for="category{{ $course_category->id }}">
                                                    {{ $course_category->name }}
                                                </label>
                                            </div>
                                        @endforeach


                                    </div>

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
@endpush
