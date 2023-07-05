@php
Helper::autoload_site_data();
$cart_count=Cart::instance('default')->count();
$cart_content=Cart::instance('default')->content();
$cart_subtotal=Cart::instance('default')->subtotal();
$cart_total=Cart::instance('default')->total();
$wishlist=Cart::instance('wishlist')->content()->count();
$notification_count = \App\Models\Notifications::where('user_id', domain_info('user_id'))->where('status',1)->count();
$notification = \App\Models\Notifications::where('user_id', domain_info('user_id'))->where('status',1)->get();
$top_banners = \App\Menu::where('user_id',Auth::id())->where('name','banner')->where('position','banner')->first();
$top_banner = json_decode($top_banners->data ?? '');
$top_banner_status = $top_banner->status ?? '';
$topbar_image = \App\Menu::where('user_id',Auth::id())->where('name','imagebanner')->where('position','imagebanner')->first();
$account_status= \App\Useroption::where('user_id',domain_info('user_id'))->where('key','account_status')->first();
$account_status= $account_status->value ?? 1;
$cart_status= \App\Useroption::where('user_id',domain_info('user_id'))->where('key','cart_status')->first();
$cart_status= $cart_status->value ?? 1;
$wishlist_status= \App\Useroption::where('user_id',domain_info('user_id'))->where('key','wishlist_status')->first();
$wishlist_status= $wishlist_status->value ?? 1;
$socials = \App\Useroption::where('user_id',domain_info('user_id'))->where('key','socials')->first();
$socials = json_decode($socials->value ?? '');
$store_email = \App\Useroption::where('user_id',domain_info('user_id'))->where('key','store_email')->first()->value ?? '';
$menu_type = \App\Domain::where('user_id',domain_info('user_id'))->first()->menu_type ?? 1;
$chats = \App\Models\ContactLists::where('user_id', domain_info('user_id'))->orderBy('serial_number', 'ASC')->get();
$status_contact = \App\Domain::where('user_id',domain_info('user_id'))->first();
$top_bar_contact_status = $status_contact->top_bar_contact_status ?? '1';
$slide_type = Cache::get(domain_info('user_id').'slide_type',0);
@endphp

@if($menu_type==1)
    <header class="header-area {{$slide_type==1 ? 'slide-full-screen' : ''}}">
        <div class="header-large-device">
            @if($top_banner_status == 1 && isset($topbar_image))
            <div class="top-bar-banner">
                <a href="{{$top_banner->url}}">
                    <div class="top-bar-wrap" style="background-image: url({{ asset($topbar_image->data ?? '') }});"></div>
                </a>
            </div>
            @endif
            <div class="header-top header-top-ptb-1 border-bottom-1">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 col-lg-6">
                            <div class="social-offer-wrap">
                            @if(isset($socials))
                                <div class="social-style-1">
                                @foreach($socials as $key => $value)
                                    <a target="_blank" href="{{ url($value->url) }}"><i class="{{ $value->icon }}"></i></a>
                                @endforeach
                                </div>
                            @endif
                                <div class="header-offer-wrap-2">
                                    @if(isset($store_email))
                                    <p><a href="mailto:{{ $store_email }}">{{ $store_email }}</a></p>
                                    @endif
                                </div>
                                <div class="hotline-offer-wrap-2">
                                    @if(Cache::has(domain_info('user_id').'location'))
                                    <?php $location = json_decode(Cache::get(domain_info('user_id').'location')); ?>
                                    <p><a href="tel:{{$location->phone}}"><i class="icon-call-end"></i> Hotline {{$location->phone}}</a></p>
                                    @else
                                    <p><i class="icon-call-end"></i> Hotline (000) 000 0000 </p>
                                    @endif
                                </div>
                            @if($top_bar_contact_status==1)
                                <div class="social-style-1 contact-offer-wrap-2">
                                    @foreach($chats as $chat)
                                    @if($chat->is_show_topbar==1)
                                        <a target="_blank" href="{{$chat->url}}" rel="noopener noreferrer">
                                            <img src="{{asset($chat->image ?? '') }}" class="chat" alt="Contact">
                                        </a>
                                    @endif
                                    @endforeach
                                </div>
                            @endif
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6">
                            <div class="header-top-right">
                                <div class="same-style-wrap">
                                    <div class="same-style same-style-mrg-2 menu-top">
                                        <nav>
                                            <ul>
                                                {{ ThemeMenu('top_bar_header','frontend.norda.components.top_bar_menu') }}
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="same-style same-style-mrg-2 language-wrap">
                                        @if(languages())
                                        <a class="language-dropdown-active" href="#"><img class="mrr-5" alt="{{language_active()}}" width="24px" height="24px" src="{{ asset('uploads/'.language_active().'.png') }}" alt="">{{language_name(language_active())}}<i class="icon-arrow-down"></i></a>
                                        <div class="language-dropdown">
                                            <ul>
                                                @foreach(languages() as $lang_key=> $language)
                                                <li>
                                                    <a href="{{ url('/make_local?'.'lang='.$language.'&full='.$lang_key) }}">
                                                        <img alt="{{$language}}" width="24px" height="24px" src="{{ asset('uploads/'.$language.'.png') }}">
                                                        {{ $lang_key}}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-middle header-middle-padding-1">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-2 col-lg-2">
                            <div class="logo">
                                <a href="{{ url('/') }}"><img src="{{ asset('uploads/'.domain_info('user_id').'/logo.png') }}?v={{time()}}" alt="logo"></a>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-7">
                            <div class="categori-search-wrap">
                                <div class="categori-style-1">
                                    <select class="nice-select nice-select-style-1 cat_search">
                                        <option selected="" value="{{ url('/'.permalink_type('shop').'') }}">{{__('All Categories')}} </option>
                                    </select>
                                </div>
                                <div class="search-wrap-3">
                                    <form action="{{ url('/'.permalink_type('shop').'') }}" id="form_search_header">
                                        <input placeholder="{{__('Search Products...')}}" name="src" type="text" value="{{ $src ?? null }}">
                                        <button><i class="lnr lnr-magnifier"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3">
                            <div class="header-action header-action-flex">
                                @if(env('MULTILEVEL_CUSTOMER_REGISTER') == true)
                                    @if($account_status == 1)
                                        @if(Auth::guard('customer')->check())
                                            <div class="same-style-2 same-style-2-font-inc">
                                                <a href="{{ url('/user/dashboard') }}"><i class="icon-user"></i></a>
                                            </div>

                                            <div class="same-style-2 same-style-2-font-inc">
                                                <a dclass="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false"><i class="icon-bell"></i><span class="pro-count green wishlist_count">{{ $notification_count }}</span></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">
                                                    <div class="p-3 bg-light border-bottom">
                                                        <h6 class="mb-0" style="font-size:1rem;">{{('Notifications') }}</h6>
                                                    </div>
                                                    <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                                        @if($notification_count > 0)
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div style="margin-left: -14px;">
                                                                  @foreach($notification as $noti)
                                                                    <img class="img-fluit" alt="{{$noti->title}}" src="{{asset($noti->image ?? 'uploads/default.png') }}" width="90" height="80">
                                                                    <b>{{$noti->title}}</b>
                                                                    <span>{{$noti->description}}</span>

                                                                    @endforeach

                                                                </div>

                                                            </li>
                                                        </ul>
                                                        @else
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div class="py-4 text-center fs-16">
                                                                {{('No notification found') }}
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        @endif
                                                    </div>
                                                    <div class="text-center border-top">
                                                        <a href="{{ url('/user/all-notification') }}" class="text-reset d-block py-2" style="font-size:12px;">
                                                        {{('View All Notifications') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        @else
                                            <div class="same-style-2 same-style-2-font-inc">
                                                <a href="{{ url('/user/login') }}"><i class="icon-user"></i></a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if($wishlist_status == 1)
                                <div class="same-style-2 same-style-2-font-inc">
                                    <a href="{{ url('/wishlist') }}"><i class="icon-heart"></i><span class="pro-count green wishlist_count">{{ $wishlist }}</span></a>
                                </div>
                                @endif
                                @if($cart_status == 1)
                                <div class="same-style-2 same-style-2-font-inc header-cart">
                                    <a class="cart-active" href="#">
                                        <i class="icon-basket-loaded"></i><span class="pro-count green cart_count">{{ $cart_count }}</span>
                                        <span class="cart-amount cart_sub_total">{{ amount_format($cart_subtotal) }}</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <div class="main-categori-wrap">
                                <a class="categori-show" href="#"><i class="lnr lnr-menu"></i> {{__('All Categories')}} <i class="icon-arrow-down icon-right"></i></a>
                                <div class="category-menu categori-hide" style="display:none">
                                    <nav>
                                        <ul class="cat-menu">

                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="main-menu main-menu-padding-1 main-menu-font-size-14 main-menu-lh-2" style="margin-left: -60px;">
                                <nav>
                                    <ul>
                                        {{ ThemeMenu('header','frontend.norda.components.menu') }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-small-device small-device-ptb-1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="mobile-logo">
                            <a href="{{url('/')}}">
                                <img alt="mobile-logo" src="{{ asset('uploads/'.domain_info('user_id').'/logo.png') }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="header-action header-action-flex">
                            @if($account_status == 1)
                            <div class="same-style-2 same-style-2-font-inc">
                                <a href="{{ url('/user/dashboard') }}"><i class="icon-user"></i></a>
                            </div>
                            @endif
                            @if($wishlist_status == 1)
                            <div class="same-style-2 same-style-2-font-inc">
                                <a href="{{ url('/wishlist') }}"><i class="icon-heart"></i><span class="pro-count green wishlist_count">{{$wishlist}}</span></a>
                            </div>
                            @endif
                            @if($cart_status == 1)
                            <div class="same-style-2 same-style-2-font-inc header-cart">
                                <a class="cart-active" href="#">
                                    <i class="icon-basket-loaded"></i><span class="pro-count green cart_count">{{ $cart_count }}</span>
                                </a>
                            </div>
                            @endif
                            <div class="same-style-2 main-menu-icon">
                                <a class="mobile-header-button-active" href="#"><i class="icon-menu"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
@else
    <header class="header-area {{$slide_type==1 ? 'slide-full-screen' : ''}}">
        <div class="header-large-device">
            @if($top_banner_status == 1 && isset($topbar_image))
            <div class="top-bar-banner">
                <a href="{{$top_banner->url}}">
                    <div class="top-bar-wrap" style="background-image: url({{ asset($topbar_image->data ?? '') }});"></div>
                </a>
            </div>
            @endif
            <div class="header-top header-top-ptb-1 border-bottom-1">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 col-lg-6">
                            <div class="social-offer-wrap">
                            @if(isset($socials))
                                <div class="social-style-1">
                                @foreach($socials as $key => $value)
                                    <a target="_blank" href="{{ url($value->url) }}"><i class="{{ $value->icon }}"></i></a>
                                @endforeach
                                </div>
                            @endif
                                <div class="header-offer-wrap-2">
                                    @if($store_email)
                                    <p><a href="mailto:{{ $store_email }}">{{ $store_email }}</a></p>
                                    @endif
                                </div>
                            @if($top_bar_contact_status==1)
                                <div class="social-style-1 contact-offer-wrap-2">
                                    @foreach($chats as $chat)
                                    @if($chat->is_show_topbar==1)
                                        <a target="_blank" href="{{$chat->url}}" rel="noopener noreferrer">
                                            <img src="{{asset($chat->image ?? '') }}" class="chat" alt="Contact">
                                        </a>
                                    @endif
                                    @endforeach
                                </div>
                            @endif
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6">
                            <div class="header-top-right">
                                <div class="same-style-wrap">
                                    <!--<div class="same-style same-style-mrg-2 track-order">-->
                                    <!--    <a href="#">Store Location </a>-->
                                    <!--</div>-->
                                    <div class="same-style same-style-mrg-2 menu-top">
                                        <nav>
                                            <ul>
                                                {{ ThemeMenu('top_bar_header','frontend.norda.components.top_bar_menu') }}
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="same-style same-style-mrg-2 language-wrap">
                                        @if(languages())
                                        <a class="language-dropdown-active" href="#"><img class="mrr-5" alt="{{language_active()}}" width="24px" height="24px" src="{{ asset('uploads/'.language_active().'.png') }}" alt="">{{language_name(language_active())}}<i class="icon-arrow-down"></i></a>
                                        <div class="language-dropdown">
                                            <ul>
                                                @foreach(languages() as $lang_key=> $language)
                                                <li>
                                                    <a href="{{ url('/make_local?'.'lang='.$language.'&full='.$lang_key) }}">
                                                        <img alt="{{$language}}" width="24px" height="24px" src="{{ asset('uploads/'.$language.'.png') }}">
                                                        {{ $lang_key}}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-2 col-lg-2">
                            <div class="logo pd-4">
                                <a href="{{ url('/') }}"><img src="{{ asset('uploads/'.domain_info('user_id').'/logo.png') }}" alt="logo"></a>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8">
                            <div class="main-menu main-menu-padding-1 main-menu-font-size-14 main-menu-lh-2">
                                <nav>
                                    <ul>
                                        {{ ThemeMenu('header','frontend.norda.components.menu') }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2">
                            <div class="header-action header-action-flex">
                                @if(env('MULTILEVEL_CUSTOMER_REGISTER') == true)
                                    @if($account_status == 1)
                                        @if(Auth::guard('customer')->check())
                                            <div class="same-style-2 same-style-2-font-inc">
                                                <a href="{{ url('/user/dashboard') }}"><i class="icon-user"></i></a>
                                            </div>

                                            <div class="same-style-2 same-style-2-font-inc">
                                                <a dclass="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false"><i class="icon-bell"></i><span class="pro-count green wishlist_count">{{ $notification_count }}</span></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">
                                                    <div class="p-3 bg-light border-bottom">
                                                        <h6 class="mb-0" style="font-size:1rem;">{{('Notifications') }}</h6>
                                                    </div>
                                                    <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                                        @if($notification_count > 0)
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div style="margin-left: -14px;">
                                                                  @foreach($notification as $noti)
                                                                    <img class="img-fluit" alt="{{$noti->title}}" src="{{asset($noti->image ?? 'uploads/default.png') }}" width="90" height="80">
                                                                    <b>{{$noti->title}}</b>
                                                                    <span>{{$noti->description}}</span>

                                                                    @endforeach

                                                                </div>

                                                            </li>
                                                        </ul>
                                                        @else
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div class="py-4 text-center fs-16">
                                                                {{('No notification found') }}
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        @endif
                                                    </div>
                                                    <div class="text-center border-top">
                                                        <a href="{{ url('/user/all-notification') }}" class="text-reset d-block py-2" style="font-size:12px;">
                                                            {{('View All Notifications') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        @else
                                            <div class="same-style-2 same-style-2-font-inc">
                                                <a href="{{ url('/user/login') }}"><i class="icon-user"></i></a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if($wishlist_status == 1)
                                <div class="same-style-2 same-style-2-font-inc">
                                    <a href="{{ url('/wishlist') }}"><i class="icon-heart"></i><span class="pro-count green wishlist_count">{{ $wishlist }}</span></a>
                                </div>
                                @endif
                                @if($cart_status == 1)
                                <div class="same-style-2 same-style-2-font-inc header-cart">
                                    <a class="cart-active" href="#">
                                        <i class="icon-basket-loaded"></i><span class="pro-count green cart_count">{{ $cart_count }}</span>
                                        <span class="cart-amount cart_sub_total">{{ amount_format($cart_subtotal) }}</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-small-device small-device-ptb-1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="mobile-logo">
                            <a href="{{url('/')}}">
                                <img alt="mobile-logo" src="{{ asset('uploads/'.domain_info('user_id').'/logo.png') }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="header-action header-action-flex">
                            @if($account_status == 1)
                            <div class="same-style-2 same-style-2-font-inc">
                                <a href="{{ url('/user/dashboard') }}"><i class="icon-user"></i></a>
                            </div>
                            @endif
                            @if($wishlist_status == 1)
                            <div class="same-style-2 same-style-2-font-inc">
                                <a href="{{ url('/wishlist') }}"><i class="icon-heart"></i><span class="pro-count green">{{$wishlist}}</span></a>
                            </div>
                            @endif
                            @if($cart_status == 1)
                            <div class="same-style-2 same-style-2-font-inc header-cart">
                                <a class="cart-active" href="#">
                                    <i class="icon-basket-loaded"></i><span class="pro-count green cart_count">{{ $cart_count }}</span>
                                </a>
                            </div>
                            @endif
                            <div class="same-style-2 main-menu-icon">
                                <a class="mobile-header-button-active" href="#"><i class="icon-menu"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endif

<!-- mini cart start -->
<div class="sidebar-cart-active">
    <div class="sidebar-cart-all">
        <a class="cart-close" href="#"><i class="icon_close"></i></a>
        <div class="cart-content">
            <h3>{{__('Cart')}}</h3>
            <ul class="cart-list">
                @foreach($cart_content as $row)
                <li class="single-product-cart cart-item" id="cart-row{{$row->rowId}}">
                    <div class="cart-img">
                        <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}"><img src="{{ asset($row->options->preview) }}" alt="{{ $row->name }}"></a>
                    </div>
                    <div class="cart-title">
                        <h4><a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }}</a></h4>
                        <span> {{ $row->qty }} × {{ amount_format($row->price) }}	</span>
                    </div>
                    <div class="cart-delete">
                        <a href="javascript:void(0)" onclick="remove_cart('{{ $row->id }}')">×</a>
                    </div>
                    <input type="hidden" value="{{ $row->rowId }}" id="rowid{{ $row->id }}">

                </li>
                @endforeach
            </ul>
            <div class="cart-total">
                <h4>{{__('Sub Total')}}: <span class="cart_sub_total">{{ amount_format($cart_subtotal) }}</span></h4>
                <h4>{{__('Total')}}: <span class="cart_total">{{ amount_format($cart_total) }}</span></h4>
            </div>
            <div class="cart-checkout-btn">
                <a class="btn-hover cart-btn-style btn-main" href="{{ url('/cart') }}">{{ __('view cart') }}</a>
                <a class="no-mrg btn-hover cart-btn-style btn-main" href="{{ url('/'.permalink_type('checkout').'') }}">{{ __('Proceed to checkout') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- offline payment Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Notifications') }}</h5>
            </div>
            <div class="modal-body">


            </div>
        </div>
    </div>
</div>
<!-- mobile header start -->
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="clickalbe-sidebar-wrap">
        <a class="sidebar-close"><i class="icon_close"></i></a>
        <div class="mobile-header-content-area">
            <div class="mobile-search mobile-header-padding-border-1">
                <form class="search-form" action="{{ url('/'.permalink_type('shop').'') }}">
                    <input type="text" placeholder="Search here…">
                    <button class="button-search"><i class="icon-magnifier"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-padding-border-2">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu">
                        {{ ThemeMenu('header','frontend.norda.components.menu') }}
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-menu-wrap mobile-header-padding-border-2">
                <nav>
                    <ul class="mobile-menu">
                        {{ ThemeMenu('top_bar_header','frontend.norda.components.top_bar_menu') }}
                    </ul>
                </nav>
            </div>
            @if($menu_type==1)
            <div class="main-categori-wrap mobile-menu-wrap mobile-header-padding-border-3">
                <a class="categori-show" href="#">
                    <i class="lnr lnr-menu"></i> {{__('All Categories')}} <i class="icon-arrow-down icon-right"></i>
                </a>
                <div class="categori-hide-2">
                    <nav>
                        <ul class="mobile-menu cat-mobile-menu">

                        </ul>
                    </nav>
                </div>
            </div>
            @endif
            @if(languages())
                <div class="mobile-header-info-wrap mobile-header-padding-border-3">
                    <div class="single-mobile-header-info">
                        <a class="mobile-language-active" href="#">{{__('Language')}} <span><i class="icon-arrow-down"></i></span></a>
                        <div class="lang-curr-dropdown lang-dropdown-active">
                            <ul>
                                @foreach(languages() as $lang_key=> $language)
                                <li><a href="{{ url('/make_local?'.'lang='.$language.'&full='.$lang_key) }}"  >{{ $lang_key  }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mobile-contact-info mobile-header-padding-border-4">
                <ul>
                    @if(Cache::has(domain_info('user_id').'store_email'))
                        <li><i class="icon-envelope-open "></i> {{ Cache::get(domain_info('user_id').'store_email') }}</li>
                    @endif
                    @if(Cache::has(domain_info('user_id').'location'))
                    <?php $location = json_decode(Cache::get(domain_info('user_id').'location')); ?>
                    <li><i class="icon-phone "></i> {{$location->phone}}</li>
                    <li><i class="icon-home"></i> {{$location->address}}</li>
                    @endif
                </ul>
            </div>
            <div class="mobile-social-icon">
                <a rel="noopener noreferrer" class="facebook" title="Facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->full() }}"><i class="icon-social-facebook"></i></a>
                <a rel="noopener noreferrer" class="twitter" title="Twitter" target="_blank" href="https://twitter.com/intent/tweet?url={{ url()->full() }}"><i class="icon-social-twitter"></i></a>
                <a rel="noopener noreferrer" class="instagram" title="Instagram" target="_blank" href="https://www.instagram.com/?url={{url()->full()}}"><i class="icon-social-instagram"></i></a>
                <a rel="noopener noreferrer" class="pinterest" title="Pinterest" target="_blank" href="http://pinterest.com/pin/create/link/?url={{url()->full()}}"><i class="icon-social-pinterest"></i></a>
            </div>
        </div>
    </div>
</div>
