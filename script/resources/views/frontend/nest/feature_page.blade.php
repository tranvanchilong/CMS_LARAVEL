@extends('frontend.norda.layouts.feature_page')
@push('css')
<link rel="stylesheet" href="{{asset('frontend/nest/css/code_editor_styles.css')}}">
<link rel="stylesheet" href="{{asset('frontend/nest/css/uicons-regular-straight.css')}}">
@endpush
@section('content')  
@if($page->header_status == 1)
    @include('frontend/norda/layouts/header')
@endif
@if($menu_fp)
<section>
    <div class="container">
        <div class="row">
            <div class="main-menu main-menu-padding-1 main-menu-lh-2 px-3">
                <nav>
                    <ul>
                        {{ ThemeMenuFp($menu_fp,'frontend.norda.components.menu') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
@endif
@foreach ($feature as $key => $item)
    @if ($item->feature_type=='hero slide')
    <section class="home-slider style-2 position-relative mb-50">
        <div class="container">
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-8' : 'col-lg-8 order-1') }}">
                    <div class="home-slide-cover">
                        <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                            @if ($item->section_elements->count()>0)
                            @foreach ($item->section_elements as $key => $section_element)
                            <div class="single-hero-slider single-animation-wrap" style="background-image: url({{asset($section_element->image)}})">
                                <div class="slider-content">
                                    <div class="section-title">
                                        <h1>{{$section_element->title}}</h1>
                                        <span>{!!nl2br(($section_element->text))!!}</span>
                                    </div>
                                    <a href="{{$section_element->btn_url}}" class="btn" type="submit">{{$section_element->btn_text}}</a>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="slider-arrow hero-slider-1-arrow"></div>
                    </div>
                </div>
                <div class="col-lg-4 d-none {{$item->feature_position==1 ? '' : ($item->feature_position==0 ? 'pl-0' : 'pr-0').' d-xl-block'}}">
                    <div class="banner-img style-3 animated animated" style="background-image: url({{asset($banner_ads_3[0]['image'] ?? 'uploads/default.png')}})">
                        <div class="banner-text mt-50">
                            <h2 class="mb-50">
                                <span class="text-brand">{{$banner_ads_3[0]['meta']->title ?? ''}}</span> <br/>
                                {{$banner_ads_3[0]['meta']->title_2 ?? ''}}<br/>
                                {{$banner_ads_3[0]['meta']->title_3 ?? ''}}
                            </h2>
                            <a href="{{$banner_ads_3[0]['meta']->btn_text ?? 'shop'}}" class="btn btn-xs">{{$banner_ads_3[0]['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='slider')
    <section class="home-slider style-2 position-relative mb-50">
        <div class="container">
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-8' : 'col-lg-8 order-1') }}">
                    <div class="home-slide-cover">
                        <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                            @if ($sliders->count()>0)
                            @foreach ($sliders as $key => $slider)
                            <div class="single-hero-slider single-animation-wrap" style="background-image: url({{asset($slider['slider'] ?? '')}})">
                                <div class="slider-content">
                                    @if($item->hide_title==0)
                                    <div class="section-title">
                                        <h1>{{$slider['meta']->title ?? ''}}</h1>
                                        <span>{{$slider['meta']->title_2 ?? ''}}</span>
                                    </div>
                                    <p class="mb-30">{{$slider['meta']->title_3 ?? ''}}</p>
                                    <a href="{{$slider['url'] ?? ''}}" class="btn" type="submit">{{$slider['meta']->btn_text ?? ''}}</a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="slider-arrow hero-slider-1-arrow"></div>
                    </div>
                </div>
                <div class="col-lg-4 d-none {{$item->feature_position==1 ? '' : ($item->feature_position==0 ? 'pl-0' : 'pr-0').' d-xl-block'}}">
                    <div class="banner-img style-3 animated animated" style="background-image: url({{asset($banner_ads_3[0]['image'] ?? 'uploads/default.png')}})">
                        <div class="banner-text mt-50">
                            <h2 class="mb-50">
                                {{$banner_ads_3[0]['meta']->title ?? ''}}<br/>
                                {{$banner_ads_3[0]['meta']->title_2 ?? ''}}<br/>
                                <span class="text-brand">{{$banner_ads_3[0]['meta']->title_3 ?? ''}}</span>
                            </h2>
                            <a href="{{$banner_ads_3[0]['meta']->btn_text ?? 'shop'}}" class="btn btn-xs">{{$banner_ads_3[0]['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='slide image')
    <section class="pb-40 pt-40 default-dots">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="slider-image-active nav-style-1 dot-style-2 dot-style-2-position-2">
                @if ($item->section_elements->count()>0)
                    @foreach ($item->section_elements as $key => $section_element)
                        <div class="text-center p-3">
                            <div class="">
                                <a href="{{$section_element->btn_url ?? '#'}}">
                                    <img src="{{asset($section_element->image)}}" class="rounded img-fluid lazy" alt="">
                                </a>
                            </div>                          
                            <h4 class="mt-3 mb-2"><a href="{{ $section_element->btn_url }}">{{($section_element->title)}}</a></h4>                      
                            <p class="text-slide-img">{!!nl2br(($section_element->text))!!}</p>
                            @if (!empty($section_element->btn_text) && !empty($section_element->btn_url))
                            <div class="btn-style-1 mt-30">
                                <a class="p-3 px-4" href="{{$section_element->btn_url}}"><span>{{($section_element->btn_text)}}</span></a>
                            </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='intro')
    <section class="pt-40 pb-40">
        <div class="container">
            <div class="row clearfix">
                <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 order-1' : ($item->feature_position==0 ? 'col-lg-6 order-1' : 'col-lg-6') }}">
                    @if (!empty($item->section_elements->first()->image))
                    <img class="rounded img-fluid lazy" src="{{asset($item->section_elements->first()->image)}}" alt="">
                    @endif
                </div>
                <div class="py-2 px-5 {{$item->feature_position==1 ? 'col-lg-12 text-center' : 'col-lg-6'}}">
                    <div class="mb-4 section-title">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                    @if ($item->section_elements->count()>0)
                        @if ($item->section_elements->count()==1)
                            <div class="">
                                <h4>{{$item->section_elements->first()->title ?? ''}}</h4>
                                <p>{!!nl2br($item->section_elements->first()->text ?? '')!!}</p>
                            </div>
                        @else
                            @foreach ($item->section_elements as $key => $section_element)
                                <div class="d-flex mb-4 {{$item->feature_position==1 ? 'text-left' : ''}}">
                                    <div>
                                        <div class="number-intro text-center">
                                            <h4 class="text-white">0{{$loop->iteration}}</h4>
                                        </div>
                                    </div>
                                    <div class="mt-1 ml-3">
                                        <h4 class="mb-2">{{$section_element->title}}</h4>
                                        <p>{!!nl2br($section_element->text)!!}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if (!empty($item->section_elements->first()->btn_text) && !empty($item->section_elements->first()->btn_url))
                        <div class="btn-style-1 mt-30">
                            <a class="p-3 px-4" href="{{$item->section_elements->first()->btn_url}}">{{($item->section_elements->first()->btn_text)}}</a>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='list image')
    <section class="pt-40 pb-40">
        <div class="container">
            <div class="row">
                <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 order-1' : ($item->feature_position==0 ? 'col-lg-5 order-1' : 'col-lg-5') }}">
                    @if (!empty($item->section_elements->first()->image))
                    <img class="rounded img-fluid lazy"  src="{{asset($item->section_elements->first()->image)}}" alt="">
                    @endif
                </div>
                <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 text-center' : 'col-lg-7'}}">
                    <div class="section-title">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                    <div class="mt-20 facilities-two">
                        <div class="row">
                            @if ($item->section_elements->count()>0)
                                @foreach ($item->section_elements as $key => $section_element)
                                    <div class="col-lg-6 mb-30 md-mb-25 col-md-6">
                                        <div class="d-flex approach_point">
                                            <span class="icon" style="font-size: 12px;color: #FBB63C;"><i style="background: #FBB63C2a;border-radius:50%;padding:5px" class="fas fa-check"></i></span>
                                            <div class="pl-3">
                                                <h4 class="mb-1">{{($section_element->title)}}</h4>
                                                <span class="text-justify">{{($section_element->text)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='only image')
    <section class="pt-40 pb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                        @if ($item->section_elements->count()>0)
                            @if (!empty($item->section_elements->first()->btn_text) && !empty($item->section_elements->first()->btn_url))
                                <div class="btn-style-1 mt-30">
                                    <a class="p-3 px-4" href="{{$item->section_elements->first()->btn_url}}">{{($item->section_elements->first()->btn_text)}}
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <div class="row">
                        @if ($item->section_elements->count()>0)
                            @foreach ($item->section_elements as $key => $section_element)
                            <div class="col-lg-{{ $item->section_elements->count()==1 ? '12' : '6' }}">
                                <h4 class="mb-3">{{$section_element->title}}</h4>
                                <p class="mb-3">{!!nl2br($section_element->text)!!}</p>
                                <img class="rounded img-fluid lazy" src="{{asset($section_element->image)}}" alt="">
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='feature list')
    <section class="pt-40 pb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                    <div class="mt-20 facilities-two">
                        <div class="row">
                            @if ($item->section_elements->count()>0)
                                @foreach ($item->section_elements as $key => $section_element)
                                    <div class="col-lg-{{$item->section_elements->count()>4 ? '4' : '6'}} col-md-6 col-12 mb-40 md-mb-25 px-4">
                                        <div class="d-flex">
                                            <img style="object-fit: contain;" class="lazy" width="50px" height="50px" src="{{asset($section_element->image)}}">
                                            <div class="mt-1 ml-3">
                                                <h4 class="p-1">{{$section_element->title}}</h4>
                                                <p class="p-1">{!!nl2br($section_element->text)!!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='click action')
    <section class="newsletter mb-15">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="position-relative newsletter-inner">
                        <div class="newsletter-content">
                            <h2 class="mb-20">
                                {{$item->section_elements->first()->title ?? ''}}
                            </h2>
                            <p class="mb-45">{!!nl2br($item->section_elements->first()->text ?? '')!!}</p>
                            <a href="{{$item->section_elements->first()->btn_url}}" class="btn" type="submit">{{$item->section_elements->first()->btn_text}}</a>
                        </div>
                        <img src="{{asset($item->section_elements->first()->image ?? '')}}" alt="newsletter" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='subscribe')
    <section class="newsletter mb-15">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="position-relative newsletter-inner">
                        <div class="newsletter-content">
                            <h2 class="mb-20">
                                {{$item->section_elements->first()->title ?? ''}}
                            </h2>
                            <p class="mb-45">{!!nl2br($item->section_elements->first()->text ?? '')!!}</p>
                            <form class="form-subcriber d-flex" method="post" action="/newsletter">
                                @csrf
                                <input type="email" placeholder="{{__('Email Address')}}" />
                                <button class="btn" type="submit">{{__('Subscribe')}}</button>
                            </form>
                        </div>
                        <img src="{{asset($item->section_elements->first()->image ?? '')}}" alt="newsletter" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif 

    @if ($item->feature_type=='blog')
    <section class="pt-40 pb-40 blog default-dots">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-tab-wrap pb-15">
                        @if($item->hide_title==0)
                        <div class="section-title">
                            <h1>{{($item->feature_title)}}</h1>
                            <span>{{($item->feature_subtitle)}}</span>
                        </div>
                        <div class="tab-style-3">
                            <a class="p-2 px-3 active" href="/blog">{{__('View All')}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="slider-image-active-4 nav-style-1 dot-style-2 dot-style-2-position-2">
                @foreach ($blogs as $blog)
                    <div class="text-center p-4 m-3 border-slider">
                        <div class="">
                            <a href="/blog-detail/{{$blog->slug}}">
                                <img src="{{ asset($blog->medias->first()->name ?? 'uploads/default.png') }}" class="rounded img-fluid lazy" alt="">
                            </a>
                        </div>
                        <div class="mt-2">
                            <a href="/blog?category_id={{$blog->bcategories->first()->id ?? ''}}">{{$blog->bcategories->first()->name ?? ''}}</a> | 
                            <span>{{date_format($blog->updated_at, 'Y-m-d')}}</span>
                        </div>
                        <h4 class="mt-3 mb-2"><a href="/blog-detail/{{$blog->slug}}">{{($blog->title)}}</a> </h4>
                        <p>
                            {!! strlen(strip_tags(html_entity_decode($blog->content->value))) > 120 ? mb_substr(strip_tags(html_entity_decode($blog->content->value)), 0, 120, 'utf-8') . '...' : strip_tags(html_entity_decode($blog->content->value)) !!}
                        </p>
                        <a class="read-more d-block" href="/blog-detail/{{$blog->slug}}">{{__('Learn More')}}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='random product')
    <section class="section-padding pb-5">
        <div class="container">
            <div class="section-title-tab-wrap">
                @if($item->hide_title==0)
                <div class="section-title">
                    <h1 class="">{{($item->feature_title)}}</h1>
                    <span>{{($item->feature_subtitle)}}</span>
                </div>
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/shop">{{__('View All')}}</a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                            <div class="arrow-center position-relative">
                                <div class="slider-arrow slider-arrow-nest" id="product-slider-active-nest-arrows"></div>
                                <div class="dot-style-2 dot-style-2-position-2 carausel-arrow-center product-slider-active-nest">
                                    @foreach($random_products as $p)
                                    <div class="px-2">
                                        <div class="product-cart-wrap mb-30 mx-0">
                                            <div class="product-img-action-wrap">
                                                <div class="product-img product-img-zoom">
                                                    <a href="/product/{{$p->slug}}/{{$p->id}}">
                                                        <img class="default-img" src="{{asset($p->image[0] ?? 'uploads/default.png')}}" alt="" />
                                                        @if(!empty($p->image[1]))
                                                        <img class="hover-img" src="{{asset($p->image[1])}}" alt="" />
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="product-action-1">
                                                    <a href="/product/{{$p->slug}}/{{$p->id}}" aria-label="{{__('View Detail')}}" class="action-btn small hover-up"> <i class="fi-rs-eye"></i></a>
                                                    <a href="javascript:void(0);" aria-label="{{__('Add To Wishlist')}}" onclick="add_to_wishlist({{$p->id}})" class="action-btn small hover-up  wishlist_{{$p->id}}"><i class="fi-rs-heart"></i></a>
                                                </div>
                                                <div class="product-badges product-badges-position product-badges-mrg">
                                                    @switch($p->featured)
                                                        @case(0)
                                                            @break
                                                        @case(1)
                                                            <span class="sale">{{__('Trending')}}</span>
                                                            @break
                                                        @case(2)
                                                            <span class="hot">{{__('Best Selling')}}</span>
                                                            @break
                                                        @case(3)
                                                            <span class="best">{{__('Top Rate')}}</span>
                                                            @break
                                                    @endswitch
                                                </div>
                                            </div>
                                            <div class="product-content-wrap">
                                                <div class="product-category">
                                                    <a href="/category/{{$p->category->category->slug}}/{{$p->category->category->id}}">{{$p->category->category->name ?? ''}}</a>
                                                </div>
                                                <h2><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h2>
                                                <div class="product-rate-cover">
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" style="width: 90%"></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                                </div>
                                                <div>
                                                    <span class="font-small text-muted"><a href="#">{{$p->brands->first()->name ?? ''}}</a></span>
                                                </div>
                                                <div class="product-card-bottom d-sm-flex d-block">
                                                    <div class="product-price">
                                                        @if(\Carbon\Carbon::now()->between($p->price->starting_date,$p->price->ending_date))
                                                        <span>{{ amount_format($p->price->price) }}</span>
                                                        <span class="old-price">{{ amount_format($p->price->regular_price) }}</span>
                                                        @else
                                                        <span>{{ amount_format($p->price->price) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="add-cart">
                                                        <a href="javascript:void(0);" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}} add"><i class="fi-rs-shopping-cart mr-5"></i>{{__('Add')}} </a>
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
                    <!--End tab-content-->
                </div>
                <!--End Col-lg-9-->
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='new arrival product')
    <section class="section-padding pb-5">
        <div class="container">
            <div class="section-title-tab-wrap">
                @if($item->hide_title==0)
                <div class="section-title">
                    <h1 class="">{{($item->feature_title)}}</h1>
                    <span>{{($item->feature_subtitle)}}</span>
                </div>
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/shop">{{__('View All')}}</a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-9' : 'col-lg-9 order-1') }}">
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                            <div class="carausel-4-columns-cover arrow-center position-relative">
                                <div class="slider-arrow slider-arrow-2" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-3-arrows"></div>
                                <div class="dot-style-2 dot-style-2-position-2 carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns carausel-arrow-center" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-3">
                                    @foreach($latest_products as $p)
                                    <div class="product-cart-wrap">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                                    <img class="default-img" src="{{asset($p->image[0] ?? 'uploads/default.png')}}" alt="" />
                                                    @if(!empty($p->image[1]))
                                                    <img class="hover-img" src="{{asset($p->image[1])}}" alt="" />
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="product-action-1">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}" aria-label="{{__('View Detail')}}" class="action-btn small hover-up"> <i class="fi-rs-eye"></i></a>
                                                <a href="javascript:void(0);" aria-label="{{__('Add To Wishlist')}}" onclick="add_to_wishlist({{$p->id}})" class="action-btn small hover-up  wishlist_{{$p->id}}"><i class="fi-rs-heart"></i></a>
                                            </div>
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <span class="new">{{__('New')}}</span>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="/category/{{$p->category->category->slug}}/{{$p->category->category->id}}">{{$p->category->category->name ?? ''}}</a>
                                            </div>
                                            <h2><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h2>
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <div class="product-price mt-10 mb-15">
                                                @if(\Carbon\Carbon::now()->between($p->price->starting_date,$p->price->ending_date))
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                <span class="old-price">{{ amount_format($p->price->regular_price) }}</span>
                                                @else
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                @endif
                                            </div>
                                            <a href="javascript:void(0);" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}} btn w-100 hover-up"><i class="fi-rs-shopping-cart mr-5"></i>{{__('Add')}}</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End tab-content-->
                </div>
                <div class="col-lg-3 d-none {{$item->feature_position==1 ? '' : 'd-lg-block'}}">
                    <div class="banner-img style-2" style="background-image: url({{asset($banner_ads_3[1]['image'] ?? 'uploads/default.png')}})">
                        <div class="banner-text">
                           <h2 class="mb-100">
                                {{$banner_ads_3[1]['meta']->title ?? ''}}<br/>
                                {{$banner_ads_3[1]['meta']->title_2 ?? ''}}<br/>
                                <span class="text-brand">{{$banner_ads_3[1]['meta']->title_3 ?? ''}}</span>
                            </h2>
                            <a href="{{$banner_ads_3[1]['meta']->btn_text ?? 'shop'}}" class="btn btn-xs">{{$banner_ads_3[1]['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='best selling product')
    <section class="section-padding pb-5">
        <div class="container">
            <div class="section-title-tab-wrap">
                @if($item->hide_title==0)
                <div class="section-title">
                    <h1 class="">{{($item->feature_title)}}</h1>
                    <span>{{($item->feature_subtitle)}}</span>
                </div>
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/shop">{{__('View All')}}</a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-9' : 'col-lg-9 order-1') }}">
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                            <div class="carausel-4-columns-cover arrow-center position-relative">
                                <div class="slider-arrow slider-arrow-2" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-arrows"></div>
                                <div class="dot-style-2 dot-style-2-position-2 carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns carausel-arrow-center" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns">
                                    @foreach($best_selling_products as $p)
                                    <div class="product-cart-wrap">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                                    <img class="default-img" src="{{asset($p->image[0] ?? 'uploads/default.png')}}" alt="" />
                                                    @if(!empty($p->image[1]))
                                                    <img class="hover-img" src="{{asset($p->image[1])}}" alt="" />
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="product-action-1">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}" aria-label="{{__('View Detail')}}" class="action-btn small hover-up"> <i class="fi-rs-eye"></i></a>
                                                <a href="javascript:void(0);" aria-label="{{__('Add To Wishlist')}}" onclick="add_to_wishlist({{$p->id}})" class="action-btn small hover-up  wishlist_{{$p->id}}"><i class="fi-rs-heart"></i></a>
                                            </div>
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <span class="hot">{{__('Best Selling')}}</span>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="/category/{{$p->category->category->slug}}/{{$p->category->category->id}}">{{$p->category->category->name ?? ''}}</a>
                                            </div>
                                            <h2><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h2>
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <div class="product-price mt-10 mb-15">
                                                @if(\Carbon\Carbon::now()->between($p->price->starting_date,$p->price->ending_date))
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                <span class="old-price">{{ amount_format($p->price->regular_price) }}</span>
                                                @else
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                @endif
                                            </div>
                                            <a href="javascript:void(0);" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}} btn w-100 hover-up"><i class="fi-rs-shopping-cart mr-5"></i>{{__('Add')}}</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End tab-content-->
                </div>
                <div class="col-lg-3 d-none {{$item->feature_position==1 ? '' : 'd-lg-block'}}">
                    <div class="banner-img style-2" style="background-image: url({{asset($banner_ads_3[2]['image'] ?? 'uploads/default.png')}})">
                        <div class="banner-text">
                           <h2 class="mb-100">
                                {{$banner_ads_3[2]['meta']->title ?? ''}}<br/>
                                {{$banner_ads_3[2]['meta']->title_2 ?? ''}}<br/>
                                <span class="text-brand">{{$banner_ads_3[2]['meta']->title_3 ?? ''}}</span>
                            </h2>
                            <a href="{{$banner_ads_3[2]['meta']->btn_text ?? 'shop'}}" class="btn btn-xs">{{$banner_ads_3[2]['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='trending product')
    <section class="section-padding pb-5">
        <div class="container">
            <div class="section-title-tab-wrap">
                @if($item->hide_title==0)
                <div class="section-title">
                    <h1 class="">{{($item->feature_title)}}</h1>
                    <span>{{($item->feature_subtitle)}}</span>
                </div>
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/shop">{{__('View All')}}</a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-9' : 'col-lg-9 order-1') }}">
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                            <div class="carausel-4-columns-cover arrow-center position-relative">
                                <div class="slider-arrow slider-arrow-2" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-2-arrows"></div>
                                <div class="dot-style-2 dot-style-2-position-2 carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns carausel-arrow-center" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-2">
                                    @foreach($trending_products as $p)
                                    <div class="product-cart-wrap">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                                    <img class="default-img" src="{{asset($p->image[0] ?? 'uploads/default.png')}}" alt="" />
                                                    @if(!empty($p->image[1]))
                                                    <img class="hover-img" src="{{asset($p->image[1])}}" alt="" />
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="product-action-1">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}" aria-label="{{__('View Detail')}}" class="action-btn small hover-up"> <i class="fi-rs-eye"></i></a>
                                                <a href="javascript:void(0);" aria-label="{{__('Add To Wishlist')}}" onclick="add_to_wishlist({{$p->id}})" class="action-btn small hover-up  wishlist_{{$p->id}}"><i class="fi-rs-heart"></i></a>
                                            </div>
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <span class="sale">{{__('Trending')}}</span>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="/category/{{$p->category->category->slug}}/{{$p->category->category->id}}">{{$p->category->category->name ?? ''}}</a>
                                            </div>
                                            <h2><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h2>
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <div class="product-price mt-10 mb-15">
                                                @if(\Carbon\Carbon::now()->between($p->price->starting_date,$p->price->ending_date))
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                <span class="old-price">{{ amount_format($p->price->regular_price) }}</span>
                                                @else
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                @endif
                                            </div>
                                            <a href="javascript:void(0);" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}} btn w-100 hover-up"><i class="fi-rs-shopping-cart mr-5"></i>{{__('Add')}}</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End tab-content-->
                </div>
                <div class="col-lg-3 d-none {{$item->feature_position==1 ? '' : 'd-lg-block'}}">
                    <div class="banner-img style-2" style="background-image: url({{asset($banner_ads_3[3]['image'] ?? 'uploads/default.png')}})">
                        <div class="banner-text">
                           <h2 class="mb-100">
                                {{$banner_ads_3[3]['meta']->title ?? ''}}<br/>
                                {{$banner_ads_3[3]['meta']->title_2 ?? ''}}<br/>
                                <span class="text-brand">{{$banner_ads_3[3]['meta']->title_3 ?? ''}}</span>
                            </h2>
                            <a href="{{$banner_ads_3[3]['meta']->btn_text ?? 'shop'}}" class="btn btn-xs">{{$banner_ads_3[3]['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='top rate product')
    <section class="section-padding pb-5">
        <div class="container">
            <div class="section-title-tab-wrap">
                @if($item->hide_title==0)
                <div class="section-title">
                    <h1 class="">{{($item->feature_title)}}</h1>
                    <span>{{($item->feature_subtitle)}}</span>
                </div>
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/shop">{{__('View All')}}</a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-9' : 'col-lg-9 order-1') }}">
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                            <div class="carausel-4-columns-cover arrow-center position-relative">
                                <div class="slider-arrow slider-arrow-2" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-1-arrows"></div>
                                <div class="dot-style-2 dot-style-2-position-2 carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns carausel-arrow-center" id="carausel-{{$item->feature_position==1 ? '5' : '4'}}-columns-1">
                                    @foreach($top_rate_products as $p)
                                    <div class="product-cart-wrap">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                                    <img class="default-img" src="{{asset($p->image[0] ?? 'uploads/default.png')}}" alt="" />
                                                    @if(!empty($p->image[1]))
                                                    <img class="hover-img" src="{{asset($p->image[1])}}" alt="" />
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="product-action-1">
                                                <a href="/product/{{$p->slug}}/{{$p->id}}" aria-label="{{__('View Detail')}}" class="action-btn small hover-up"> <i class="fi-rs-eye"></i></a>
                                                <a href="javascript:void(0);" aria-label="{{__('Add To Wishlist')}}" onclick="add_to_wishlist({{$p->id}})" class="action-btn small hover-up  wishlist_{{$p->id}}"><i class="fi-rs-heart"></i></a>
                                            </div>
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <span class="best">{{__('Top Rate')}}</span>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="/category/{{$p->category->category->slug}}/{{$p->category->category->id}}">{{$p->category->category->name ?? ''}}</a>
                                            </div>
                                            <h2><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h2>
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 80%"></div>
                                            </div>
                                            <div class="product-price mt-10 mb-15">
                                                @if(\Carbon\Carbon::now()->between($p->price->starting_date,$p->price->ending_date))
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                <span class="old-price">{{ amount_format($p->price->regular_price) }}</span>
                                                @else
                                                <span>{{ amount_format($p->price->price) }}</span>
                                                @endif
                                            </div>
                                            <a href="javascript:void(0);" onclick="add_to_cart({{$p->id}})" class="cart_{{$p->id}} btn w-100 hover-up"><i class="fi-rs-shopping-cart mr-5"></i>{{__('Add')}}</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End tab-content-->
                </div>
                <div class="col-lg-3 d-none {{$item->feature_position==1 ? '' : 'd-lg-block'}}">
                    <div class="banner-img style-2" style="background-image: url({{asset($banner_ads_3[4]['image'] ?? 'uploads/default.png')}})">
                        <div class="banner-text">
                           <h2 class="mb-100">
                                {{$banner_ads_3[4]['meta']->title ?? ''}}<br/>
                                {{$banner_ads_3[4]['meta']->title_2 ?? ''}}<br/>
                                <span class="text-brand">{{$banner_ads_3[4]['meta']->title_3 ?? ''}}</span>
                            </h2>
                            <a href="{{$banner_ads_3[4]['meta']->btn_text ?? 'shop'}}" class="btn btn-xs">{{$banner_ads_3[4]['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    @if ($item->feature_type=='product')
    <section class="pt-40 pb-40 product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">         
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                @if ($item->section_elements->count()>0)
                    @foreach ($item->section_elements as $key => $section_element)
                        <div class="col-lg-4 col-md-6 product-feature-product">
                            <div class="item">
                                <div class="title">
                                    <div class="thumb">
                                        <a href="{{$section_element->video_url ?? '#'}}">
                                        <img class="lazy fade show entered loaded" data-ll-status="loaded" src="{{asset($section_element->image)}}">
                                        </a>
                                    </div>
                                    <div>
                                        <h4 class="mt-3"><a href="{{$section_element->video_url ?? '#'}}">{{($section_element->title)}}</a></h4>
                                    </div>
                                </div>
                                <div class="content">
                                    <p class="mb-3">{!!nl2br(($section_element->text))!!}</p>
                                    @if (!empty($section_element->btn_text) && !empty($section_element->btn_url))
                                        <a href="{{$section_element->btn_url ?? '#'}}" class="btn-main float-right">{{($section_element->btn_text)}}</a>
                                    @endif
                                    @if (!empty($section_element->btn_text_1) && !empty($section_element->btn_url_1))
                                        <a href="{{$section_element->btn_url_1 ?? '#'}}" style="line-height: 44px" class="read-more float-left">{{($section_element->btn_text_1)}}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='category')
    <section class="popular-categories section-padding">
        <div class="container">
            <div class="section-title-tab-wrap">
                @if($item->hide_title==0)
                <div class="section-title">
                    <h1 class="">{{($item->feature_title)}}</h1>
                    <span>{{($item->feature_subtitle)}}</span>
                </div>
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/shop">{{__('View All')}}</a>
                </div>
                @endif
            </div>
            <div class="carausel-8-columns-cover position-relative">
                <div class="dot-style-2 dot-style-2-position-2 carausel-8-columns" id="carausel-8-columns-2">
                    @foreach($categories as $category)
                    <div class="card-1">
                        <figure class="img-hover-scale overflow-hidden">
                            <a href="/category/{{$category->slug}}/{{$category->id}}"><img src="{{asset($category->preview->content ?? 'uploads/default.png')}}" alt="" /></a>
                        </figure>
                        <h6>
                            <a href="/category/{{$category->slug}}/{{$category->id}}">{{$category->name}}</a>
                        </h6>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='ads banner')
    <section class="banners mb-25">
        <div class="container">
            <div class="row">
                @foreach($banner_ads as $banner_ad)
                <div class="col-lg-4 col-md-6">
                    <div class="banner-img">
                        <img src="{{$banner_ad['image']}}" alt="" />
                        <div class="banner-text">
                            <h4>
                                {{$banner_ad['meta']->title ?? ''}} <br/>
                                {{$banner_ad['meta']->title_2 ?? ''}}<br/>
                                {{$banner_ad['meta']->title_3 ?? ''}}
                            </h4>
                            <a href="{{$banner_ad['url']}}" class="btn btn-xs">{{$banner_ad['meta']->btn_text ?? ''}} <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='service banner')
    <section class="featured section-padding">
        <div class="container">
            <div class="row">
                @foreach($bump_ads as $key => $bump_ad)
                <div class="col-lg-{{$bump_ads->count()>4 ? '2' : '3'}} col-{{$bump_ads->count()<=4 ? '6' : '4'}} mb-3">
                    <div class="banner-left-icon d-block d-md-flex align-items-center wow fadeIn animated">
                        <div class="banner-icon text-sm-center">
                            <img src="{{$bump_ad['image']}}" alt="" />
                        </div>
                        <div class="banner-text">
                            <h3 class="icon-box-title">{{$bump_ad['meta']->title ?? ''}}</h3>
                            <p>{{$bump_ad['meta']->title_2 ?? ''}}</p>
                            <p>{{$bump_ad['meta']->title_3 ?? ''}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='brand banner')
    <section class="pt-40 pb-40 partner default-dots">
        <div class="container">
            <div class="row">
                <div class="{{$item->feature_position==1 ? 'col-lg-12 text-center' : 'col-lg-12'}}">
                    <div class="section-title">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                    <div class="partner-active-6-1 nav-style-1 dot-style-2 dot-style-2-position-2">
                        @foreach($brand_ads as $key => $brand_ad)
                        <div class="">
                            <a href="{{$brand_ad['url'] ?? 'javascript:void(0)'}}" target="{{$brand_ad['url'] ? '_blank' : '_self'}}">
                                <figure class="image-partner"><img class="rounded img-fluid" src="{{asset($brand_ad['image'] ?? 'uploads/default.png')}}" alt=""></figure>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='testimonial')
    <section class="pt-40 pb-40 testimonial default-dots">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="testimonial-wrap-2 nav-style-1 dot-style-2 dot-style-2-position-2">
                    @foreach($testimonials as $row)
                <div class="item-inner p-3">
                    <div class="card">
                        <div class="card-body">
                            <blockquote class="icon mb-0">
                                <p>{{ $row->content }}</p>
                                <div class="d-flex align-items-center testimonial-thumb">
                                    <img class="rounded rounded-circle w-12" src="{{ $row->image ? asset($row->image) : 'https://ui-avatars.com/api/?name='.$row->name.'&background=random&length=1&color=#fff' }}" alt="">
                                    <div class="info">
                                        <h4 class="mb-1">{{ $row->name }}</h4>
                                        <p class="mb-0">{{ $row->rank }}</p>
                                    </div>
                                </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='service')
    <section class="pb-40 pt-40 default-dots">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-title-tab-wrap">
                        @if($item->hide_title==0)
                        <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                            <h1>{{($item->feature_title)}}</h1>
                            <span>{{($item->feature_subtitle)}}</span>
                            
                        </div>
                        <div class="tab-style-3">
                            <a class="p-2 px-3 active" href="/service">{{__('View All')}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="slider-image-active-4 nav-style-1 dot-style-2 dot-style-2-position-2">
                @foreach ($services as $row)
                    <div class="text-center p-4 m-3 border-slider">
                        <div class="">
                            <a href="/service/{{$row->slug}}">
                                <img src="{{asset($row->image)}}" class="rounded img-fluid lazy" alt="">
                            </a>
                        </div>
                        <h4 class="mt-3 mb-2"><a href="/service/{{$row->slug}}">{{($row->name)}}</a></h4>
                        <p class="text-slide-img">
                        {!! strlen(strip_tags(html_entity_decode($row->content))) > 120 ? mb_substr(strip_tags(html_entity_decode($row->content)), 0, 120, 'utf-8') . '...' : strip_tags(html_entity_decode($row->content)) !!}
                        </p>
                        <a class="read-more" href="/service/{{$row->slug}}">{{__('Learn More')}}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='portfolio')
    <section class="pb-40 pt-40 default-dots">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-title-tab-wrap">
                        @if($item->hide_title==0)
                        <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                            <h1>{{($item->feature_title)}}</h1>
                            <span>{{($item->feature_subtitle)}}</span>
                            
                        </div>
                        <div class="tab-style-3">
                            <a class="p-2 px-3 active" href="/portfolio">{{__('View All')}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="portfolio-slider-active-4 nav-style-1 dot-style-2 dot-style-2-position-2">
                @foreach ($portfolios as $row)
                    <div class="text-center p-4">
                        <div class="">
                            <a href="/portfolio/{{$row->slug}}">
                                <img style="object-fit: cover;" src="{{asset($row->image)}}" height="200px" width="100%" class="rounded lazy" alt="">
                                <!-- <img src="{{asset($row->image)}}" class="rounded img-fluid lazy" alt=""> -->
                            </a>
                        </div>
                        <h4 class="mt-3"><a href="/portfolio/{{$row->slug}}">{{($row->name)}}</a></h4>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='team')
    <section class="pb-40 pt-40 team default-dots">
        <div class="container">
            <div class="row justify-content-center">
                <div class="{{$item->feature_position==1 ? 'col-lg-12' : ($item->feature_position==0 ? 'col-lg-3 order-1' : 'col-lg-3') }}">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ''}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-{{$item->feature_position==1 ? '12' : '9'}}">
                    <div class="team-slider-active-{{$item->feature_position==1 ? '5' : '4'}} nav-style-1 dot-style-2 dot-style-2-position-2">
                        @foreach ($teams as $row)
                            <div class="text-center p-3">
                                <div class="image-member">
                                    <a href="/team/{{$row->id}}">
                                        <img src="{{asset($row->image)}}" class="img-fluid lazy" alt="">
                                    </a>
                                </div>
                                <h4 class="mt-3 mb-2"><a href="/team/{{$row->id}}">{{($row->name)}}</a></h4>
                                <p class="text-slide-img">{{$row->rank}}</p>
                                <nav class="nav social justify-content-center text-center mb-0">
                                    <a class="m-2" href="{{$row->facebook ?? 'javascript:void(0)'}}" target="{{$row->facebook ? '_blank' : '_self'}}">
                                    <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a class="m-2" href="{{$row->twitter ?? 'javascript:void(0)'}}" target="{{$row->facebook ? '_blank' : '_self'}}"><i class="fab fa-twitter"></i></a>
                                    <a class="m-2" href="{{$row->instagram ?? 'javascript:void(0)'}}" target="{{$row->facebook ? '_blank' : '_self'}}">
                                    <i class="fab fa-instagram"></i>
                                    </a>
                                    <a class="m-2" href="{{$row->linkedin ?? 'javascript:void(0)'}}" target="{{$row->facebook ? '_blank' : '_self'}}">
                                    <i class="fab fa-linkedin"></i>
                                    </a>
                                </nav>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='faq')
    <section class="pt-40 pb-40 faq">
        <div class="container">
            <div class="row">
                <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 order-1' : ($item->feature_position==0 ? 'col-lg-6 order-1' : 'col-lg-6') }}">
                    @if (!empty($item->section_elements->first()->image))
                    <img class="rounded img-fluid lazy"  src="{{asset($item->section_elements->first()->image)}}" alt="">
                    @endif
                </div>
                <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 text-center' : 'col-lg-6'}}">
                    <div class="section-title">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                    <div class="mt-20 facilities-two">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="accordion" id="accordionFaqOne">
                                    @foreach ($faqs as $row)
                                        <div class="accordion-item mb-3">
                                            <h4 class="accordion-title active-header collapsed" data-toggle="collapse" aria-expanded="false" data-target="#accordion-{{$row->id}}">
                                                {{$row->question}}
                                            </h4>
                                            <div id="accordion-{{$row->id}}" class="collapse" data-parent="#accordionFaqOne">
                                                <div class="accordion-content">{!!nl2br(($row->answer))!!}</div>
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
    </section>
    @endif

    @if ($item->feature_type=='partner')
    <section class="pt-45 pb-45 partner default-dots">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                    <div class="partner-active-6 nav-style-1 dot-style-2 dot-style-2-position-2">
                        @foreach ($partners as $row)
                        <div class="">
                            <a href="{{$row->url ?? 'javascript:void(0)'}}" target="{{$row->url ? '_blank' : '_self'}}">
                                <figure class="image-partner"><img class="rounded img-fluid" src="{{asset($row->image)}}" alt=""></figure>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($item->feature_type=='package')
    <section class="pt-40 pb-40 package" id="masonry-package">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if($item->hide_title==0)
                        <h1>{{($item->feature_title)}}</h1>
                        <span>{{($item->feature_subtitle)}}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="filter-nav text-center mb-15">
                        <ul class="filter-btn">
                        <li data-filter="*" class="active">{{__('All')}}</li>
                        @foreach ($packages_category as $category)
                            @php
                                $filterValue = "." . Str::slug($category->name);
                            @endphp

                            <li data-filter="{{ $filterValue }}">{{ ($category->name) }}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row masonry-row">
                @foreach ($packages as $row)
                @php
                    $package_features = explode(PHP_EOL, $row->package_feature);
                    $not_package_features = explode(PHP_EOL, $row->not_package_feature);
                    $notes = explode(PHP_EOL, $row->note);
                    $packageCategory = $row->category()->first();
                    if (!empty($packageCategory)) {
                        $categoryName = Str::slug($packageCategory->name);
                    } else {
                        $categoryName = "";
                    }
                @endphp
                <div class="col-md-6 col-lg-4 popular package-column {{$categoryName}}">
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
    </section>
    @endif

@endforeach
@if($page->footer_status == 1)
    @include('frontend/norda/layouts/footer')
@endif
@endsection

@push('js')
<script src="{{ asset('frontend/nest/js/code_editor_scripts.js')}}"></script>
@endpush