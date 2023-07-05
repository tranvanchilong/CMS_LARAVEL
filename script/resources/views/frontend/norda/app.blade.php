<!DOCTYPE html>
<html class="no-js" lang="{{ App::getlocale() }}" >
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        {{-- generate seo info --}}
        {!! SEO::generate() !!}
        {!! JsonLdMulti::generate() !!}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--=====================================
                    CSS LINK PART START
        =======================================-->
        <!-- FOR PAGE ICON -->
        <link rel="icon" href="{{ asset('uploads/'.domain_info('user_id').'/favicon.ico') }}">
        @php
        Helper::autoload_site_data();
        @endphp
        <style type="text/css">
           :root {
              --main-theme-color: {{ Cache::get(domain_info('user_id').'theme_color','#dc3545') }};
          }
        </style>

        <link rel="stylesheet" href="{{asset('frontend/norda/css/vendor/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/vendor/signericafat.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/vendor/cerebrisans.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/vendor/simple-line-icons.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/vendor/elegant.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/vendor/linear-icon.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/plugins/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/plugins/easyzoom.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/plugins/slick.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/plugins/animate.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/plugins/magnific-popup.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/plugins/jquery-ui.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/style.css')}}">

         @stack('css')

        {{ load_header() }}

        <script data-host="https://analytics.di4l.vn" data-dnt="false" src="https://analytics.di4l.vn/js/script.js" id="ZwSg9rf6GA" async defer></script>
    </head>
<body>
<div class="main-wrapper">
<div class="body-overlay-1"></div>
<div class="body-overlay"></div>

{{-- load partials views --}}
@include('frontend/norda/layouts/header')

@yield('content')
@include('frontend/norda/layouts/footer')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5 col-md-6 col-12 col-sm-12">
                        <div class="tab-content quickview-big-img">
                            <div id="pro-1" class="tab-pane fade show active">
                                <img src="assets/images/product/product-1.jpg" alt="">
                            </div>
                            <div id="pro-2" class="tab-pane fade">
                                <img src="assets/images/product/product-3.jpg" alt="">
                            </div>
                            <div id="pro-3" class="tab-pane fade">
                                <img src="assets/images/product/product-6.jpg" alt="">
                            </div>
                            <div id="pro-4" class="tab-pane fade">
                                <img src="assets/images/product/product-3.jpg" alt="">
                            </div>
                        </div>
                        <div class="quickview-wrap mt-15">
                            <div class="quickview-slide-active nav-style-6">
                                <a class="active" data-toggle="tab" href="#pro-1"><img src="assets/images/product/quickview-s1.jpg" alt=""></a>
                                <a data-toggle="tab" href="#pro-2"><img src="assets/images/product/quickview-s2.jpg" alt=""></a>
                                <a data-toggle="tab" href="#pro-3"><img src="assets/images/product/quickview-s3.jpg" alt=""></a>
                                <a data-toggle="tab" href="#pro-4"><img src="assets/images/product/quickview-s2.jpg" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6 col-12 col-sm-12">
                        <div class="product-details-content quickview-content">
                            <h2>Simple Black T-Shirt</h2>
                            <div class="product-ratting-review-wrap">
                                <div class="product-ratting-digit-wrap">
                                    <div class="product-ratting">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                    </div>
                                    <div class="product-digit">
                                        <span>5.0</span>
                                    </div>
                                </div>
                                <div class="product-review-order">
                                    <span>62 Reviews</span>
                                    <span>242 orders</span>
                                </div>
                            </div>
                            <p>Seamlessly predominate enterprise metrics without performance based process improvements.</p>
                            <div class="pro-details-price">
                                <span class="new-price">$75.72</span>
                                <span class="old-price">$95.72</span>
                            </div>
                            <div class="pro-details-color-wrap">
                                <span>Color:</span>
                                <div class="pro-details-color-content">
                                    <ul>
                                        <li><a class="dolly" href="#">dolly</a></li>
                                        <li><a class="white" href="#">white</a></li>
                                        <li><a class="azalea" href="#">azalea</a></li>
                                        <li><a class="peach-orange" href="#">Orange</a></li>
                                        <li><a class="mona-lisa active" href="#">lisa</a></li>
                                        <li><a class="cupid" href="#">cupid</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pro-details-size">
                                <span>Size:</span>
                                <div class="pro-details-size-content">
                                    <ul>
                                        <li><a href="#">XS</a></li>
                                        <li><a href="#">S</a></li>
                                        <li><a href="#">M</a></li>
                                        <li><a href="#">L</a></li>
                                        <li><a href="#">XL</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pro-details-quality">
                                <span>Quantity:</span>
                                <div class="cart-plus-minus">
                                    <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1">
                                </div>
                            </div>
                            <div class="product-details-meta">
                                <ul>
                                    <li><span>Categories:</span> <a href="#">Woman,</a> <a href="#">Dress,</a> <a href="#">T-Shirt</a></li>
                                    <li><span>Tag: </span> <a href="#">Fashion,</a> <a href="#">Mentone</a> , <a href="#">Texas</a></li>
                                </ul>
                            </div>
                            <div class="pro-details-action-wrap">
                                <div class="pro-details-add-to-cart">
                                    <a title="Add to Cart" href="#">Add To Cart </a>
                                </div>
                                <div class="pro-details-action">
                                    <a title="Add to Wishlist" href="#"><i class="icon-heart"></i></a>
                                    <a title="Add to Compare" href="#"><i class="icon-refresh"></i></a>
                                    <a class="social" title="Social" href="#"><i class="icon-share"></i></a>
                                    <div class="product-dec-social">
                                        <a class="facebook" title="Facebook" href="#"><i class="icon-social-facebook"></i></a>
                                        <a class="twitter" title="Twitter" href="#"><i class="icon-social-twitter"></i></a>
                                        <a class="instagram" title="Instagram" href="#"><i class="icon-social-instagram"></i></a>
                                        <a class="pinterest" title="Pinterest" href="#"><i class="icon-social-pinterest"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end load --}}





{{-- load whatsapp api --}}
{{ load_whatsapp() }}
{{-- end whatsapp api loading --}}

@php
$currency_info=currency_info();
@endphp
<input type="hidden" id="currency_position" value="{{ $currency_info['currency_position'] }}">
<input type="hidden" id="currency_name" value="{{ $currency_info['currency_name'] }}">
<input type="hidden" id="currency_icon" value="{{ $currency_info['currency_icon'] }}">
<input type="hidden" id="preloader" value="{{ asset('uploads/preload.webp') }}">
<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="theme_color" value="{{ Cache::get(domain_info('user_id').'theme_color','#dc3545') }}">

</div>
<!--=====================================
             JS LINK PART START
 =======================================-->
 <!-- FOR BOOTSTRAP -->
<script src="{{asset('frontend/norda/js/cart.js?v' . time())}}"></script>
<script src="{{asset('frontend/norda/js/vendor/modernizr-3.11.7.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/vendor/jquery-v3.6.0.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/vendor/jquery-migrate-v3.3.2.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/slick.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/jquery.syotimer.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/jquery.instagramfeed.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/wow.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/jquery-ui-touch-punch.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/jquery-ui.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/magnific-popup.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/sticky-sidebar.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/easyzoom.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/scrollup.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/ajax-mail.js')}}"></script>

  @stack('js')
 <!-- FOR INTERACTION -->
 <script src="{{asset('frontend/norda/js/main.js')}}"></script>
 {{ load_footer() }}
<!--=====================================
    JS LINK PART END
=======================================-->
    </body>
</html>
