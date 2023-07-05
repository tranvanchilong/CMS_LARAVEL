@extends('frontend.norda.layouts.app')
@section('content')

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Shop')}}</li>
            </ul>
        </div>
    </div>
</div>


<div class="shop-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="shop-topbar-wrapper d-block d-lg-flex">
                    <div class="shop-topbar-left">
                        <!--<div class="view-mode nav">-->
                        <!--    <a class="active" href="#shop-1" data-toggle="tab"><i class="icon-grid"></i></a>-->
                        <!--    <a href="#shop-2" data-toggle="tab"><i class="icon-menu"></i></a>-->
                        <!--</div>-->
                        <p>{{ __('Showing') }} <span id="from">0</span> - <span id="to">0</span> of <span id="total">0</span> {{ __('results') }} </p>
                    </div>
                    <div class="d-lg-none">
                        <div class="sidebar-widget">
                            <div class="sidebar-search">
                                <form class="sidebar-search-form">
                                    <input type="text" class="src" name="src" value="{{request()->input('src')}}" placeholder="{{__('Search here...')}}">
                                    <button>
                                        <i class="icon-magnifier"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="product-sorting-wrapper col-10">
                                <div class="product-show shorting-style d-block">
                                    <label>{{ __('Sort by') }} :</label>
                                    <select class="order_by">
                                        <option value="DESC">{{ __('New item') }}</option>
                                        <option value="ASC">{{ __('Old item') }}</option>
                                        <option value="trending">{{ __('Trending') }}</option>
                                        <option value="best_sell">{{ __('Best selling') }}</option>
                                        <option value="top_rate">{{ __('Top rate') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div id="filter-btn" class="float-right"><i class="fa fa-filter d-block"></i></div>
                            </div>
                        </div>
                        <!-- <div class="mt-2">
                            <ul class="category_area_mobile filter-mobile">
                            </ul>
                        </div> -->
                        <!-- <div class="mt-2">
                            <ul class="brand_area_mobile filter-mobile">
                            </ul>
                        </div> -->
                        <div class="sidebar-wrapper" id="filter-active">
                            <div class="text-right">
                                <a href="javascript:void(0)" id="filter-btn-close" class="btn-main">{{ __('Done') }}</a>
                            </div>
                            <div class="sidebar-widget mt-2">
                                <h4 class="sidebar-widget-title">{{ __('Filter by Category') }} </h4>
                                <div class="sidebar-widget-list">
                                    <ul class="category_area">

                                    </ul>
                                </div>
                            </div>
                            <div class="sidebar-widget mt-2" id="section_brand_area">
                                <h4 class="sidebar-widget-title">{{ __('Filter by Brand') }} </h4>
                                <div class="sidebar-widget-list">
                                    <ul class="brand_area">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-sorting-wrapper d-none d-lg-block">
                        <div class="product-show shorting-style">
                            <label>{{ __('Sort by') }} :</label>
                            <select class="order_by">
                                <option value="DESC">{{ __('New item') }}</option>
                                <option value="ASC">{{ __('Old item') }}</option>
                                <option value="trending">{{ __('Trending') }}</option>
                                <option value="best_sell">{{ __('Best selling') }}</option>
                                <option value="top_rate">{{ __('Top rate') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="shop-bottom-area">
                    <div class="tab-content jump">
                        <div id="shop-1" class="tab-pane active">
                            <div class="row product-parent">
                            </div>
                        </div>
                    </div>

                    <div class="pro-pagination-style text-center mt-10">
                        <ul class="pagination-render">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right" id="left_sidebar">
                    <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">{{__('Search')}}</h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form">
                                <input type="text" class="src" name="src" value="{{request()->input('src')}}" placeholder="{{__('Search here...')}}">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                        <h4 class="sidebar-widget-title">{{ __('Filter by Category') }} </h4>
                        <div class="sidebar-widget-list">
                            <ul class="category_area">

                            </ul>
                        </div>
                    </div>

                    <div id="show_brand_area" style="display:none">
                    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40" id="section_brand_area">
                        <h4 class="sidebar-widget-title"  >{{ __('Filter by Brand') }} </h4>
                        <div class="sidebar-widget-list">
                            <ul class="brand_area">

                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--=====================================
         SINGLE BANNER PART END
=======================================-->


<!--=====================================
         PRODUCT LIST PART START
=======================================-->
<!--<section class="product-list">-->
<!--            <div class="container">-->
<!--                <div class="row">-->
<!--                    <div class="col-lg-3" id="left_sidebar">-->


<!--                        <div class="product-list-bar cat">-->
<!--                           <div class="product-list-bar"><h4 class="mb-3">{{ __('Filter by Category') }}</h4><ul class="product-size category_area">-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            </ul>-->
<!--                        </div>-->

<!--                        </div>-->

<!--                        <div class="product-list-bar bran">-->
<!--                         <div class="product-list-bar"><h4 class="mb-3">{{ __('Filter by Brand') }}</h4><ul class="product-size brand_area">-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                            <li class="cat-item cat-item"><p><span class="content-placeholder h-20">&nbsp;</span></p></li>-->
<!--                         </ul></div>-->
<!--                        </div> -->
<!--                    </div>-->
<!--                    <div class="col-lg-9">-->
<!--                        <div class="product-filter">-->
<!--                            <div class="product-page-number">-->
<!--                                <p>{{ __('Showing') }} <span id="from">0</span>–<span id="to">0</span> of <span id="total">0</span> {{ __('results') }}</p>-->
<!--                            </div>-->
<!--                            <select class="custom-select order_by" name="order">-->
<!--                                <option value="DESC">{{ __('Short by new item') }}</option>-->
<!--                                <option value="ASC">{{ __('Short by old item') }}</option>-->
<!--                                <option value="bast_sell">{{ __('Short by best selling') }}</option>-->

<!--                            </select>-->

<!--                        </div>-->
<!--                        <div class="preload_area"></div>-->
<!--                        <div class="product-parent">-->
<!--                        </div>-->
<!--                        <ul class="pagination pagi-ghape">-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--        </div>-->
<!--</section>-->
<!--=====================================
         PRODUCT LIST PART END
=======================================-->


<input type="hidden" id="category" value="{{ $info->id ?? null }}">

@endsection
@push('js')
<script src="{{ asset('frontend/norda/js/shop.js')}}"></script>
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
<script type="text/javascript">
    const slider = document.querySelector('.filter-mobile');
    let mouseDown = false;
    let startX, scrollLeft;

    let startDragging = function (e) {
      mouseDown = true;
      startX = e.pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    };
    let stopDragging = function (event) {
      mouseDown = false;
    };

    slider.addEventListener('mousemove', (e) => {
      e.preventDefault();
      if(!mouseDown) { return; }
      const x = e.pageX - slider.offsetLeft;
      const scroll = x - startX;
      slider.scrollLeft = scrollLeft - scroll;
    });

    // Add the event listeners
    slider.addEventListener('mousedown', startDragging, false);
    slider.addEventListener('mouseup', stopDragging, false);
    slider.addEventListener('mouseleave', stopDragging, false);
</script>

<script type="text/javascript">
    $('#filter-btn').click(function() {
        $('#filter-active').css('display','block');
    });
    $('#filter-btn-close').click(function() {
        $('#filter-active').css('display','none');
    });
</script>
@endpush





