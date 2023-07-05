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
        @if(file_exists('uploads/'.domain_info('user_id').'/favicon.ico'))
            <link rel="icon" href="{{ asset('uploads/'.domain_info('user_id').'/favicon.ico') }}">
        @else
            <link rel="icon" href="{{ asset('uploads/'.domain_info('user_id').'/logo.png') }}">
        @endif
        @php
            Helper::autoload_site_data();
            $chats = \App\Models\ContactLists::where('user_id', domain_info('user_id'))->orderBy('serial_number', 'ASC')->get();
            $position = \App\Useroption::where('user_id', domain_info('user_id'))->where('key', 'contact_list')->first()->value ?? '1';
            $basefont = Cache::get(domain_info('user_id').'font','Manrope');
            $fill_theme = Cache::get(domain_info('user_id').'fill_title','disable');
            $status_contact = \App\Domain::where('user_id',domain_info('user_id'))->first();
            $float_contact_status = $status_contact->float_contact_status ?? '1';
            $icon = \App\Useroption::where('user_id', domain_info('user_id'))->where('key', 'icon_contact')->first()->value ?? '';
            $slide_type = Cache::get(domain_info('user_id').'slide_type',1);
        @endphp
        <style type="text/css">
            :root {
                --main-theme-color: {{ !empty(Cache::get(domain_info('user_id').'theme_color')) ? Cache::get(domain_info('user_id').'theme_color') : '#3b97b7' }};
                --main-title-color: {{ !empty(Cache::get(domain_info('user_id').'title_color')) ? Cache::get(domain_info('user_id').'title_color') : '#343F52' }};
                --main-text-color: {{ !empty(Cache::get(domain_info('user_id').'text_color')) ? Cache::get(domain_info('user_id').'text_color') : '#60697B' }};
                --main-font: {{ !empty(Cache::get(domain_info('user_id').'font')) ? Cache::get(domain_info('user_id').'font') : 'Manrope' }};
                --main-title-fill-color: {{ Cache::get(domain_info('user_id').'fill_title','disable')=="disable" ? Cache::get(domain_info('user_id').'title_color','#343F52') : '#fff' }};
                --main-theme-fill-color: {{ Cache::get(domain_info('user_id').'fill_theme','disable')=="disable" ? Cache::get(domain_info('user_id').'title_color','#343F52') : '#fff' }};
                --main-theme-fill: {{ Cache::get(domain_info('user_id').'fill_theme','disable')=="disable" ? '' : Cache::get(domain_info('user_id').'theme_color','#343F52') }};
                --main-title-fill-bg: {{ Cache::get(domain_info('user_id').'fill_title','disable')=="disable" ? '' : Cache::get(domain_info('user_id').'title_background','#343F52') }};
                --main-menu-size: {{ !empty(Cache::get(domain_info('user_id').'menu_size')) ? Cache::get(domain_info('user_id').'menu_size') : '16' }}px;
                --main-title-size: {{ !empty(Cache::get(domain_info('user_id').'title_size')) ? Cache::get(domain_info('user_id').'title_size') : '30' }}px;
                --main-subtitle-size: {{ !empty(Cache::get(domain_info('user_id').'subtitle_size')) ? Cache::get(domain_info('user_id').'subtitle_size') : '22' }}px;
                --main-text-size: {{ !empty(Cache::get(domain_info('user_id').'text_size')) ? Cache::get(domain_info('user_id').'text_size') : '16'}}px;
                --main-hero-title-size: {{ !empty(Cache::get(domain_info('user_id').'hero_title_size')) ? Cache::get(domain_info('user_id').'hero_title_size') : '34' }}px;
                --main-hero-subtitle-size: {{ !empty(Cache::get(domain_info('user_id').'hero_subtitle_size')) ? Cache::get(domain_info('user_id').'hero_subtitle_size') : '26' }}px;
            }
        </style>
       <link rel="stylesheet" href="{{ asset('assets/css/fontawsome/all.min.css') }}">
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
        <link rel="stylesheet" href="{{asset('frontend/norda/css/style.css?v' . time())}}">
        <link rel="stylesheet" href="{{asset('frontend/norda/css/toastr.min.css')}}" />
        <link rel="stylesheet" href="{{asset('frontend/norda/css/base-css.php?font_family='.$basefont.'&fill_theme='.$fill_theme)}}">
         @stack('css')
        {{ load_header() }}

        <script data-host="https://analytics.di4l.vn" data-dnt="false" src="https://analytics.di4l.vn/js/script.js" id="ZwSg9rf6GA" async defer></script>
    </head>
<body>
<div class="main-wrapper">
<div class="body-overlay-1"></div>
<div class="body-overlay"></div>


@yield('content')

{{-- end load --}}
@if($float_contact_status == 1)
<div class="@if($position == 1) list-item-chat-right @else list-item-chat-left @endif">
    <ul>
        @if(count($chats) == 1)
            @foreach($chats as $chat)
            <li>
                <a href="{{$chat->url}}" target="_blank" rel="noopener noreferrer">
                    <img src="{{asset($chat->image ?? '') }}" class="chat" alt="Contact">
                </a>
            </li>
            @endforeach
        @elseif(count($chats) > 1)
            <ul class="has-drop dropdown-menu">
                @foreach($chats as $chat)
                @if($chat->is_show_float_content==1)
                <li>
                    <a href="{{$chat->url}}" target="_blank" rel="noopener noreferrer">
                        <img src="{{asset($chat->image ?? '') }}" class="chat" alt="Contact">
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
            <a data-toggle="dropdown">
                <img src="{{$icon ? asset($icon) : asset('uploads/icon_default.png')}}" class="chat" alt="Contact">
            </a>
        @endif
    </ul>
</div>
@endif
{{-- load whatsapp api --}}
{{ load_whatsapp() }}
{{-- end whatsapp api loading --}}

@php
$currency_info=currency_info();
@endphp
<input type="hidden" id="currency_position" value="{{ $currency_info['currency_position'] ?? ''}}">
<input type="hidden" id="currency_name" value="{{ $currency_info['currency_name'] ?? ''}}">
<input type="hidden" id="currency_icon" value="{{ $currency_info['currency_icon'] ?? ''}}">
<input type="hidden" id="preloader" value="{{ asset('uploads/preload.webp') ?? ''}}">
<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="theme_color" value="{{ Cache::get(domain_info('user_id').'theme_color','#dc3545') }}">

</div>
<!--=====================================
             JS LINK PART START
 =======================================-->
 <!-- FOR BOOTSTRAP -->
<script src="{{asset('frontend/norda/js/cart.js?v=' . time())}}"></script>
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
<script src="{{asset('frontend/norda/js/toastr.min.js')}}"></script>
<script src="{{ asset('frontend/norda/js/index.js?v=' . time())}}"></script>
<script src="{{asset('frontend/norda/js/main.js')}}"></script>
<script src="{{asset('frontend/norda/js/plugins/imagesloaded.js')}}"></script>
@if (session()->has('success'))
    <script>
        "use strict";
        toastr['success']("{{ __(session('success')) }}");
    </script>
@endif

@if (session()->has('error'))
    <script>
        "use strict";
        toastr['error']("{{ __(session('error')) }}");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        "use strict";
        toastr['warning']("{{ __(session('warning')) }}");
    </script>
@endif
@yield('scripts')
 <!-- FOR INTERACTION -->


@stack('js')

 {{ load_footer() }}
<!--=====================================
    JS LINK PART END
=======================================-->
    </body>
</html>
