@extends('frontend.norda.layouts.app')
@section('content')
<div class="main-wrapper">

<div class="slider-area">
    <div class="hero-slider-active-1 nav-style-1 dot-style-2 dot-style-2-position-2 dot-style-2-active-black slider_preload content-placeholder">

    </div>
</div>

<div class="service-area pt-50 pb-25">
    <div class="container">
        <div class="row" id="service-area">

        </div>
    </div>
</div>

<div class="banner-area padding-10-row-col pb-20">
    <div class="container">
        <div class="row banner_ad">

        </div>
    </div>
</div>

<div class="product-area pb-20" id="latest-product-area">
    <div class="container">
        <div class="section-title-tab-wrap border-bottom-3 mb-30 pb-15">
            <div class="section-title-3">
                <h2>{{__('New arrival products')}}</h2>
            </div>
            <div class="tab-style-3 nav">
                <a class="active" href="/{{permalink_type('shop')}}">{{__('View All')}}</a>
                <!--<a href="#product-2" data-toggle="tab"> Womens</a>-->
            </div>
        </div>
        <div class="tab-content jump">
            <div id="product-1" class="tab-pane active">
                <div class="product-slider-active-2 dot-style-2 dot-style-2-position-static dot-style-2-mrg-2 dot-style-2-active-black">
                    @foreach($latest_products as $p)
                    <div class="product-plr-2">
                        <div class="single-product-wrap-2 mb-25 px-0 px-lg-3">
                            <div class="product-img-2 mb-3">
                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                    @if($p->preview != 'null')
                                    <img src="{{asset($p->preview->media->url)}}" alt="">
                                    @else
                                    <img src="{{asset('uploads/default.png')}}" alt="">
                                    @endif
                                </a>
                                @if($p->stock && $p->stock->stock_status == 0)
                                <span class="pro-badge left bg-red">Stock Out</span>
                                @elseif($p->featured = 1)
                                <span class="pro-badge left bg-red">Trending</span>
                                @elseif($p->featured = 2)
                                <span class="pro-badge left bg-red">Best selling</span>
                                @else
                                @endif
                            </div>
                            <div class="product-content-3">
                                <span>{{$p->category->category->name ?? ''}}</span>
                                <h4><a href="/product/{{$p->slug}}/{{$p->id}}">{!! strlen(strip_tags(html_entity_decode($p->title))) > 22 ? mb_substr(strip_tags(html_entity_decode($p->title)), 0, 22, 'utf-8') . '...' : strip_tags(html_entity_decode($p->title)) !!}</a></h4>
                                <div class="product-rating-wrap-2">
                                    <div class="product-rating-2">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                    </div>
                                    <span>({{$p->reviews_count}})</span>
                                </div>
                                <div class="pro-price-action-wrap">
                                    <div class="product-price-3">
                                        @if($p->price)
                                            @if($p->price->price == $p->price->regular_price)
                                                {{amount_format($p->price->price)}}
                                            @else
                                            <span class="new-price">{{amount_format($p->price->price)}}</span><span class="old-price">{{amount_format($p->price->regular_price)}}</span>
                                            @endif
                                        @else
                                            {{amount_format(0)}}
                                        @endif
                                    </div>
                                    <div class="product-action-3">
                                        <button title="Wishlist" class="wishlist_{{$p->id}}" onclick="add_to_wishlist({{$p->id}})"><i class="icon-heart"></i></button>
                                        @if($p->stock->stock_status == 0)
                                        <button title="Out Stock" class="cart_{{$p->id}}"><i class="icon-basket-loaded"></i></button>
                                        @else
                                        <button title="Add to cart" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}}"><i class="icon-basket-loaded"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Banner ADS 2222222222222222222 -->

<div class="banner-area padding-10-row-col pb-20">
    <div class="container">
        <div class="row banner_ads_2">

        </div>
    </div>
</div>

<div class="product-area pb-20" id="best-product-area">
    <div class="container">
        <div class="section-title-tab-wrap border-bottom-3 mb-30 pb-15">
            <div class="section-title-3">
                <h2>{{__('Best selling products')}}</h2>
            </div>
            <div class="tab-style-3 nav">
                <a class="active" href="/{{permalink_type('shop')}}">{{__('View All')}}</a>
                <!--<a href="#product-2" data-toggle="tab"> Womens</a>-->
            </div>
        </div>
        <div class="tab-content jump">
            <div id="product-1" class="tab-pane active">
                <div class="product-slider-active-2 dot-style-2 dot-style-2-position-static dot-style-2-mrg-2 dot-style-2-active-black">
                @foreach($best_selling_products as $p)
                    <div class="product-plr-2">
                        <div class="single-product-wrap-2 mb-25 px-0 px-lg-3">
                            <div class="product-img-2 mb-3">
                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                    @if($p->preview != 'null')
                                    <img src="{{asset($p->preview->media->url)}}" alt="">
                                    @else
                                    <img src="{{asset('uploads/default.png')}}" alt="">
                                    @endif
                                </a>
                                @if($p->stock && $p->stock->stock_status == 0)
                                <span class="pro-badge left bg-red">Stock Out</span>
                                @elseif($p->featured = 1)
                                <span class="pro-badge left bg-red">Trending</span>
                                @elseif($p->featured = 2)
                                <span class="pro-badge left bg-red">Best selling</span>
                                @else
                                @endif
                            </div>
                            <div class="product-content-3">
                                <span>{{$p->category->category->name ?? ''}}</span>
                                <h4><a href="/product/{{$p->slug}}/{{$p->id}}">{!! strlen(strip_tags(html_entity_decode($p->title))) > 22 ? mb_substr(strip_tags(html_entity_decode($p->title)), 0, 22, 'utf-8') . '...' : strip_tags(html_entity_decode($p->title)) !!}</a></h4>
                                <div class="product-rating-wrap-2">
                                    <div class="product-rating-2">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                    </div>
                                    <span>({{$p->reviews_count}})</span>
                                </div>
                                <div class="pro-price-action-wrap">
                                    <div class="product-price-3">
                                        @if($p->price)
                                            @if($p->price->price == $p->price->regular_price)
                                                {{amount_format($p->price->price)}}
                                            @else
                                            <span class="new-price">{{amount_format($p->price->price)}}</span><span class="old-price">{{amount_format($p->price->regular_price)}}</span>
                                            @endif
                                        @else
                                            {{amount_format(0)}}
                                        @endif
                                    </div>
                                    <div class="product-action-3">
                                        <button title="Wishlist" class="wishlist_{{$p->id}}" onclick="add_to_wishlist({{$p->id}})"><i class="icon-heart"></i></button>
                                        @if($p->stock->stock_status == 0)
                                        <button title="Out Stock" class="cart_{{$p->id}}"><i class="icon-basket-loaded"></i></button>
                                        @else
                                        <button title="Add to cart" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}}"><i class="icon-basket-loaded"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="banner-area padding-10-row-col pb-20">
    <div class="container">
        <div class="row banner_ads_3">

        </div>
    </div>
</div>

<div class="product-area pb-20" id="trending-product-area">
    <div class="container">
        <div class="section-title-tab-wrap border-bottom-3 mb-30 pb-15">
            <div class="section-title-3">
                <h2>{{__('Trending products')}}</h2>
            </div>
            <div class="tab-style-3 nav">
                <a class="active" href="/{{permalink_type('shop')}}">{{__('View All')}}</a>
                <!--<a href="#product-2" data-toggle="tab"> Womens</a>-->
            </div>
        </div>
        <div class="tab-content jump">
            <div id="product-1" class="tab-pane active">
                <div class="product-slider-active-2 dot-style-2 dot-style-2-position-static dot-style-2-mrg-2 dot-style-2-active-black">
                @foreach($trending_products as $p)
                    <div class="product-plr-2">
                        <div class="single-product-wrap-2 mb-25 px-0 px-lg-3">
                            <div class="product-img-2 mb-3">
                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                    @if($p->preview != 'null')
                                    <img src="{{asset($p->preview->media->url)}}" alt="">
                                    @else
                                    <img src="{{asset('uploads/default.png')}}" alt="">
                                    @endif
                                </a>
                                @if($p->stock && $p->stock->stock_status == 0)
                                <span class="pro-badge left bg-red">Stock Out</span>
                                @elseif($p->featured = 1)
                                <span class="pro-badge left bg-red">Trending</span>
                                @elseif($p->featured = 2)
                                <span class="pro-badge left bg-red">Best selling</span>
                                @else
                                @endif
                            </div>
                            <div class="product-content-3">
                                <span>{{$p->category->category->name ?? ''}}</span>
                                <h4><a href="/product/{{$p->slug}}/{{$p->id}}">{!! strlen(strip_tags(html_entity_decode($p->title))) > 22 ? mb_substr(strip_tags(html_entity_decode($p->title)), 0, 22, 'utf-8') . '...' : strip_tags(html_entity_decode($p->title)) !!}</a></h4>
                                <div class="product-rating-wrap-2">
                                    <div class="product-rating-2">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                    </div>
                                    <span>({{$p->reviews_count}})</span>
                                </div>
                                <div class="pro-price-action-wrap">
                                    <div class="product-price-3">
                                        @if($p->price)
                                            @if($p->price->price == $p->price->regular_price)
                                                {{amount_format($p->price->price)}}
                                            @else
                                            <span class="new-price">{{amount_format($p->price->price)}}</span><span class="old-price">{{amount_format($p->price->regular_price)}}</span>
                                            @endif
                                        @else
                                            {{amount_format(0)}}
                                        @endif
                                    </div>
                                    <div class="product-action-3">
                                        <button title="Wishlist" class="wishlist_{{$p->id}}" onclick="add_to_wishlist({{$p->id}})"><i class="icon-heart"></i></button>
                                        @if($p->stock->stock_status == 0)
                                        <button title="Out Stock" class="cart_{{$p->id}}"><i class="icon-basket-loaded"></i></button>
                                        @else
                                        <button title="Add to cart" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}}"><i class="icon-basket-loaded"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($blogs) > 0)
<div class="blog-area bg-gray-3 pt-50 pb-80">
    <div class="container">
        <div class="section-title-tab-wrap mb-35">
            <div class="section-title-3">
                <h2>{{__('The Latest News')}}</h2>
            </div>
            <div class="tab-style-3 nav">
                <a class="active" href="blog">{{__('View All')}}</a>
            </div>
        </div>
        <div class="row">
            @foreach($blogs as $blog)
            <div class="col-lg-4 col-md-6">
                <div class="blog-wrap mb-30">
                    <div class="blog-img mb-25">
                        <a href="{{permalink_type('blog_detail')}}/{{$blog->slug}}"><img src="{{ asset($blog->image ?? 'uploads/default.png') }}" alt="blog-img"></a>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <ul>
                                <li><a href="/{{permalink_type('blog')}}?category_id={{$blog->category_id}}">{{$blog->bcategory->name ?? ''}} </a></li>
                                <li>{{date_format($blog->updated_at, 'Y-m-d')}}</li>
                            </ul>
                        </div>
                        <h3><a href="{{permalink_type('blog_detail')}}/{{$blog->slug}}">{{$blog->title}}</a></h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="brand-logo-area bg-gray-3 pb-80" id="brand-logo-area">
    <div class="container">
        <div class="brand-logo-wrap-2" id="brand_adds">
        </div>
    </div>
</div>
</div>

@endsection
