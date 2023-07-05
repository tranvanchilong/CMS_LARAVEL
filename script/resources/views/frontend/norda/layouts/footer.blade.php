@php
    $menu_right= \App\Menu::where('user_id',domain_info('user_id'))->where('position','right')->first();
@endphp
<footer class="footer-area bg-gray-4" style="background: {{Cache::get(domain_info('user_id').'footer_background')}}">
    <div class="subscribe-area pt-95 pb-95">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="section-title-3">
                        <h2>{{__('Our Newsletter')}}</h2>
                        <p>{{__('Get updates by subscribe our weekly newsletter')}}</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div id="mc_embed_signup" class="subscribe-form-2">
                        <form class="validate subscribe-form-style-2" novalidate="" method="post" action="/newsletter">
                            @csrf
                            <div class="mc-form-2">
                                <input class="email" type="email" required="" placeholder="{{__('Email Address')}}" name="email" value="">
                                <div class="clear-2">
                                    <input class="button" type="submit" name="subscribe" value="{{__('Subscribe')}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-top border-bottom-4 pb-55">
        <div class="container">
            <div class="row">
                @if(file_exists('uploads/'.domain_info('user_id').'/logo.png') || !empty(Cache::get(domain_info('user_id').'footer_text')))
                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="footer-widget mb-40">
                        <img class="mb-4" src="{{ asset('uploads/'.domain_info('user_id').'/logo.png') }}" width="100" alt="footer-logo">
                        <div class="footer-info-list ">
                            <p style="white-space:pre-wrap;">{{Cache::get(domain_info('user_id').'footer_text')}}</p>
                        </div>
                    </div>
                </div>
                @endif
                <div class="@if(!empty($menu_right)) col-lg-2 col-md-2 @else col-lg-3 col-md-3 @endif col-sm-6 col-12">
                    <div class="footer-widget mb-40">
                        {{ ThemeFooterMenu('left','frontend.norda.components.footer_menu', 'info-list-50-parcent') }}
                    </div>
                </div>

                <div class="@if(!empty($menu_right)) col-lg-2 col-md-2 @else col-lg-3 col-md-3 @endif col-sm-6 col-12">
                    <div class="footer-widget ml-70 mb-40">
                        {{ ThemeFooterMenu('center','frontend.norda.components.footer_menu') }}
                    </div>
                </div>
                @if(!empty($menu_right))
                <div class="@if(!empty($menu_right)) col-lg-2 col-md-2 @else col-lg-3 col-md-3 @endif col-sm-6 col-12">
                    <div class="footer-widget ml-70 mb-40">
                        {{ ThemeFooterMenu('right','frontend.norda.components.footer_menu') }}
                    </div>
                </div>
                @endif
                @if(Cache::has(domain_info('user_id').'location'))
                <?php $location = json_decode(Cache::get(domain_info('user_id').'location')); ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="footer-widget mb-40 ">
                        <h3 class="footer-title">{{__('Contact Us')}}</h3>
                        <div class="contact-info-2">
                            <div class="single-contact-info-2">
                                <div class="contact-info-2-icon">
                                    <i class="icon-call-end"></i>
                                </div>
                                <div class="contact-info-2-content">
                                    <p>{{__('Got a question? Call us 24/7')}}</p>
                                    <h3><a href="tel:{{$location->phone}}">{{$location->phone}}</a></h3>
                                </div>
                            </div>
                            <div class="single-contact-info-2"> 
                                <div class="contact-info-2-icon">
                                    <i class="icon-cursor icons"></i>
                                </div>
                                <div class="contact-info-2-content">
                                    <p>{{$location->address}}</p>
                                </div>
                            </div>
                            <div class="single-contact-info-2">
                                <div class="contact-info-2-icon">
                                    <i class="icon-envelope-open "></i>
                                </div>
                                <div class="contact-info-2-content">
                                    <p><a href="mailto:{{ Cache::get(domain_info('user_id').'store_email') }}">{{ Cache::get(domain_info('user_id').'store_email') }}</a></p>
                                </div>
                            </div>
                        </div>
                        @if(Cache::has(domain_info('user_id').'socials'))
                            @php
                                $socials=json_decode(Cache::get(domain_info('user_id').'socials',[]));
                            @endphp
                        <div class="social-style-1 social-style-1-font-inc social-style-1-mrg-2">
                            @foreach($socials as $key => $value)
                                <a target="_blank" href="{{ url($value->url) }}"><i class="{{ $value->icon }}"></i></a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if(Cache::has(domain_info('user_id').'certificate'))
        @php $certificate = json_decode(Cache::get(domain_info('user_id').'certificate')); @endphp
        <div class="footer-bottom pt-30 pb-30 ">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-lg-6 col-md-6">
                        <div class="payment-img payment-img-right">
                            <a href="#"><img src="/frontend/norda/images/icon-img/payment.png" alt="payment"></a>
                            @if(data_get($certificate,'certificate_status'))
                                <a class="ml-2" href="http://online.gov.vn/Home/WebDetails/{{ $certificate->certificate_id ?? '' }}"><img src="{{ $certificate->certificate_image ?? '' }}" width="100" alt=""></a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="copyright copyright-center">
                            <p>{{ __('Copyright') }} &copy; {{ date('Y') }}. {{ __('All rights reserved by') }} <a href="{{ url('/') }}">{{ Cache::get(domain_info('user_id').'shop_name') ?? env('APP_NAME') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</footer>
