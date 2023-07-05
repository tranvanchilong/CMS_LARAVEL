@extends('frontend.arafa-cart.layouts.app')
@section('content')
    @foreach ($feature as $key => $item)
        @if ($item->feature_type=='hero slide')
            <!-- HERO PART START -->
            <section class="pb-60">
                <div class="s-skeleton s-skeleton--h-600 s-skeleton--bg-grey slider_area content-placeholder slider_preload">
                    <div class="owl-carousel primary-style-1 hero-slider-active">
                        @if ($item->section_elements->count()>0)
                            @foreach ($item->section_elements as $key => $section_element)
                            <div class="hero-slide hero-slide--7" style="background-image:url({{asset($section_element->image)}})">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                                            <div class="slider-content slider-content--animation">
                                                <span style="font-size: 30px" class="m-0 content-span-2 u-c-secondary w-auto">{{($item->feature_subtitle)}}</span>
                                                <span class="m-0 content-span-2 u-c-secondary w-auto">{{$section_element->title}}</span>
                                                <p class="content-span-4 u-c-secondary">{!!nl2br(($section_element->text))!!}</p>
                                                @if (!empty($section_element->btn_url) && !empty($section_element->btn_text))
                                                <span class="content-span-4 u-c-secondary">
                                                    <a class="shop-now-link btn--e-brand" href="{{$section_element->btn_url}}">{{$section_element->btn_text}}</a>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
            <!-- HERO PART END -->
        @endif
        <!-- SERVICE PART START -->
        @if ($item->feature_type=='slide image')
        <section class="pb-60 pt-60 section__content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="{{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                            <span>{{($item->feature_title)}}</span>
                            <h1 class="u-c-secondary">{{($item->feature_subtitle)}}</h1>
                        </div>
                    </div>
                </div>
                <div class="owl-carousel slider-image-active">
                    @if ($item->section_elements->count()>0)
                        @foreach ($item->section_elements as $key => $section_element)
                            <div class="text-center p-3">
                                <div class="">
                                    <a href="{{$section_element->btn_url ?? '#'}}">
                                        <img src="{{asset($section_element->image)}}" class="img-fluid lazy" alt="">
                                    </a>
                                </div>
                                <a href="{{ $section_element->btn_url }}">
                                    <h4 class="mt-3 mb-2 u-c-secondary">{{($section_element->title)}}</h4>
                                </a>
                                <p class="text-slide-img">{!!nl2br(($section_element->text))!!}</p>
                                @if (!empty($section_element->btn_text) && !empty($section_element->btn_url))
                                <div class="btn-style-1 mt-3">
                                    <a class="btn btn--e-brand" href="{{$section_element->btn_url}}"><span>{{($section_element->btn_text)}}</span></a>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        @endif
        <!-- SERVICE PART END -->


        <!-- ABOUT US PART START -->
        @if ($item->feature_type=='intro')
        <section class="pt-60 pb-60">
            <div class="container">
                <div class="row clearfix">
                    <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 order-1' : ($item->feature_position==0 ? 'col-lg-6 order-1' : 'col-lg-6') }}">
                        @if (!empty($item->section_elements->first()->image))
                        <img class="img-fluid lazy" src="{{asset($item->section_elements->first()->image)}}" alt="">
                        @endif
                    </div>
                    <div class="py-2 px-5 {{$item->feature_position==1 ? 'col-lg-12 text-center' : 'col-lg-6'}}">
                        <div class="inner-column">
                            <div class="">
                                <span>{{($item->feature_title)}}</span>
                                <h1 class="u-c-secondary">{{($item->feature_subtitle)}}</h1>
                            </div>
                            @if (!empty($item->section_elements->first()->title))
                            <h4>{{$item->section_elements->first()->title}}</h4>
                            @endif
                            @if (!empty($item->section_elements->first()->text))
                            <p>{!!nl2br(($item->section_elements->first()->text))!!}</p>
                            @endif
                            @if (!empty($item->section_elements->first()->btn_text) && !empty($item->section_elements->first()->btn_url))
                            <div class="mt-3">
                                <a class="btn btn--e-brand" href="{{$item->section_elements->first()->btn_url}}"><span class="txt">{{($item->section_elements->first()->btn_text)}}</span></a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- ABOUT US PART END -->

        <!-- ABOUT PART START -->
        @if ($item->feature_type=='list image')
        <section class="pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 order-1' : ($item->feature_position==0 ? 'col-lg-5 order-1' : 'col-lg-5') }}">
                        @if (!empty($item->section_elements->first()->image))
                        <img class="img-fluid lazy"  src="{{asset($item->section_elements->first()->image)}}" alt="">
                        @endif
                    </div>
                    <div class="py-2 {{$item->feature_position==1 ? 'col-lg-12 text-center' : 'col-lg-7'}}">
                        <div class="">
                            <span>{{($item->feature_title)}}</span>
                            <h1 class="u-c-secondary">{{($item->feature_subtitle)}}</h1>
                        </div>
                        <div class="mt-3 facilities-two">
                            <div class="row">
                                @if ($item->section_elements->count()>0)
                                    @foreach ($item->section_elements as $key => $section_element)
                                        <div class="col-lg-6 mb-30 md-mb-25 col-md-6">
                                            <div class="d-flex approach_point">
                                                <span class="icon" style="font-size: 12px;color: #FBB63C;"><i style="background: #FBB63C2a;border-radius:50%;padding:5px" class="fas fa-check"></i></span>
                                                <div class="pl-3">
                                                    <h4 class="mb-1 u-c-secondary">{{($section_element->title)}}</h4>
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
        <!-- ABOUT PART END -->

        <!-- ABOUT PART START -->
        @if ($item->feature_type=='only image')
        <section class="pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="{{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                            <span>{{($item->feature_title)}}</span>
                            <h1 class="u-c-secondary">{{($item->feature_subtitle)}}</h1>
                            @if ($item->section_elements->count()>0)
                                @if (!empty($item->section_elements->first()->btn_text) && !empty($item->section_elements->first()->btn_url))
                                    <div class="mt-3">
                                        <a class="btn btn--e-brand" href="{{$item->section_elements->first()->btn_url}}">
                                            <span class="txt">{{($item->section_elements->first()->btn_text)}}</span>
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
                                    <img class="img-fluid lazy" src="{{asset($section_element->image)}}" alt="">
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- ABOUT PART END -->

        <!-- FEATURE LIST PART START -->
        @if ($item->feature_type=='feature list')
        <section class="pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="{{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                            <span>{{($item->feature_title)}}</span>
                            <h1 class="u-c-secondary">{{($item->feature_subtitle)}}</h1>
                        </div>
                        <div class="mt-3 facilities-two">
                            <div class="row">
                                @if ($item->section_elements->count()>0)
                                    @foreach ($item->section_elements as $key => $section_element)
                                        <div class="col-lg-{{$item->section_elements->count()>4 ? '4' : '6'}} col-md-6 col-12 mb-40 md-mb-25 px-4">
                                            <div class="d-flex">
                                                <img style="object-fit: contain;" class="lazy" width="50px" height="50px" src="{{asset($section_element->image)}}">
                                                <div class="mt-1 ml-3">
                                                    <h4 class="p-1 u-c-secondary">{{$section_element->title}}</h4>
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
        <!-- FEATURE LIST PART END -->

        <!-- CTA PART START -->
        @if ($item->feature_type=='click action')
        <section class="pt-60 pb-60" style="background-image: url({{asset($item->section_elements->first()->image ?? '')}}); background-size:cover;background-attachment: fixed;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="{{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                            <span>{{($item->feature_title)}}</span>
                            <h1 class="u-c-secondary">{{($item->feature_subtitle)}}</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
                        @if (!empty($item->section_elements->first()->title))
                        <h1 class="u-c-secondary">{{($item->section_elements->first()->title)}}</h1>
                        @endif
                        @if (!empty($item->section_elements->first()->text))
                            <p>{!!nl2br(($item->section_elements->first()->text))!!}</p>
                        @endif
                        @if (!empty($item->section_elements->first()->btn_text) && !empty($item->section_elements->first()->btn_url))
                        <div class="mt-3">
                            <a class="btn btn--e-brand" href="{{$item->section_elements->first()->btn_url}}"><span class="txt">{{($item->section_elements->first()->btn_text)}}</span></a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- CTA PART END -->

    @endforeach
@endsection
@push('js')
<script src="{{ asset('frontend/arafa-cart/js/index.js') }}"></script>
<script type="text/javascript">
    $('.hero-slider-active').owlCarousel({
        items: 1,
        autoplayTimeout: 3000,
        loop: true,
        margin: -1,
        dots: false,
        smartSpeed: 1500,
        rewind: false, // Go backwards when the boundary has reached
        nav: false,
        responsive: {
            992: {
                dots: true
            }
        }
    });
    $('.slider-image-active').owlCarousel({
        autoplay: false,
        loop: false,
        dots: true,
        rewind: true,
        smartSpeed: 1500,
        nav: true,
        navElement: 'div',
        navClass: ['p-prev', 'p-next'],
        navText: ['<i class="fas fa-long-arrow-alt-left"></i>', '<i class="fas fa-long-arrow-alt-right"></i>'],
        responsive: {
            0: {
              items: 1
            },
            768: {
              items: 2
            },
            991: {
              items: 2
            },
            1200: {
              items: 3
            }
        }
    });
</script>
@endpush
