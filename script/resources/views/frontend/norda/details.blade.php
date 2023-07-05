@extends('frontend.norda.layouts.app')
@section('content')

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('shop').'')}}">{{__('Product')}}</a></li>
                <li class="active">{{ $info->title }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="product-details-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product-details-tab">
                    <div class="pro-dec-big-img-slider">
                        @foreach($info->medias as $row)
                        <div class="easyzoom-style">
                            <div class="easyzoom easyzoom--overlay">
                                <a href="{{ asset($row->url) }}">
                                    <img src="{{ asset($row->url) }}" alt="">
                                </a>
                            </div>
                            <a class="easyzoom-pop-up img-popup" href="{{ asset($row->url) }}"><i class="icon-size-fullscreen"></i></a>
                        </div>
                        @endforeach
                        @if(count($info->medias) == 0)
                        <div class="easyzoom-style">
                            <div class="easyzoom easyzoom--overlay">
                                <a href="{{ asset('uploads/default.png') }}">
                                    <img src="{{ asset('uploads/default.png') }}" alt="">
                                </a>
                            </div>
                            <a class="easyzoom-pop-up img-popup" href="{{ asset('uploads/default.png') }}"><i class="icon-size-fullscreen"></i></a>
                        </div>
                        @endif
                    </div>
                    <div class="product-dec-slider-small product-dec-small-style1">
                        @foreach($info->medias as $key=>$row)
                        <div class="product-dec-small {{$key==0 ? 'active' : ''}}">
                            <img src="{{ asset($row->url) }}" alt="">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product-details-content pro-details-content-mrg">
                    <h2>{{ $info->title }}</h2>
                    @if($info->stock->stock_manage == 1)
                    <h4 style="margin-top: 20px;">{{ __('SKU') }}: <span id="sku_area">{{ $info->stock->sku }}</span></h4>
                    @endif
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
                                <span>0</span>
                            </div>
                        </div>
                        <div class="product-review-order">
                            <span>{{$info->reviews_count ?? 0}} {{__('Reviews')}}</span>
                            <!--<span>{{ __('Available Quantity') }} : @if($info->stock->stock_manage == 1) {{ $info->stock->stock_qty }} @endif</span>-->
                        </div>
                    </div>
                    <p>{{ content($content->excerpt ?? '') }}</p>
                    @if($hide_price_product == 0)
                    <div class="pro-details-price" id="product_price" data-price-status="{{$info->price_status}}" data-price="{{$info->prices}}" data-currency="{{currency_info()['currency_icon'] ?? ''}}">
                        @if($info->price_single)
                            @if(\Carbon\Carbon::now()->between($info->price_single->starting_date,$info->price_single->ending_date))
                            <span  id="change_price" class="new-price">{{ amount_format($info->price_single->price) }}</span>
                            <span  id="change_price_regular" class="old-price">{{ amount_format($info->price_single->regular_price) }}</span>
                            @elseif($info->price_single->regular_price == $info->price_single->price)
                            <span id="change_price">{{ amount_format($info->price_single->price) }}</span>
                            <span id="change_price_regular" class="old-price" style="display:none">{{ amount_format($info->price_single->price) }}</span>
                            @else
                            <span id="change_price">{{ amount_format($info->price_single->price) }}</span>
                            <span id="change_price_regular" class="old-price">{{ amount_format($info->price_single->regular_price) }}</span>
                            @endif
                        @endif
                    </div>
                   
                    <p id="product_stock"  @if($info->stock->stock_manage == 0) style="display:none" @endif data-stock="{{$info->stocks}}" class="pb-20">{{ __('Available Quantity') }} : <span id="change_qty"> {{ $info->stock->stock_qty }}</span></p>
                   
                    <input type="hidden" class="" name="stock_manage" value="{{$info->stock->stock_manage}}">

                    @endif

                    <form method="post" action="{{ url('/addtocart') }}" id="cart-form">
                         @csrf
                        <input type="hidden" name="id" value="{{ $info->id }}">
                        @if($hide_price_product == 0)
                            @foreach ($variations as $key => $item)
                                <div class="pro-details-size count_var">
                                    <span>{{ $key }} :</span>
                                    <div class="pro-details-size-content">
                                        <ul>
                                            @if(count($item) > 0)
                                                <input type="hidden" class="have_variation" name="have_variation" value="true">
                                            @else
                                                <input type="hidden" class="have_variation" name="have_variation" value="false">
                                            @endif
                                            @foreach ($item as $row)
                                            <li><label class="attribute attr{{ $row->variation->id }}"><a>{{ $row->variation->name }} <input type="radio" class="variation" hidden value="{{ $row->variation->id }}" name="variation[{{ $row->attribute->id }}]"></a></label></li>
                                            @endforeach
                                        </ul>
                                </div>
                            </div>
                            @endforeach

                            @if(empty($info->affiliate))
                            <div class="pro-details-quality">
                                    <span>{{ __('Product Quantity') }} :</span>
                                    <div class="cart-plus-minus">
                                    <div class="dec qtybutton"  onclick="imposeMinMax()">-</div>
                                    <input class="cart-plus-minus-box" type="number" name="qty"  id="qty"@if($info->stock->stock_manage == 1) @if($info->stock->stock_status == 0) disabled max="0" min="0" @else max="{{ $info->stock->stock_qty }}" min="1"  value="1" @endif @else min="1" value="1" max="999"   @endif />
                                    <div class="inc qtybutton">+</div>
                                    </div>
                            </div>
                            @endif

                            <p class="text-danger none required_option" style="display:none">{{ __('Please Select A Option From Required Field') }}</p>
                            @if(count($info->options) > 0)
                            <hr>
                            @endif
                            @foreach ($info->options as $key => $option)

                            <div class="single-product-widget product-tags">
                                <h5>{{ $option->name }} @if($option->is_required == 1) <span class="text-danger">*</span> @endif </h5>
                                <ul>
                                    @foreach ($option->childrenCategories as $row)

                                    <li><label class="selectgroup-item option option{{ $row->id }}">
                                        <input hidden  data-amount="{{ $row->amount }}" data-amounttype="{{ $row->amount_type }}"  @if($option->select_type == 1) type="checkbox" name="option[]" @else type="radio" name="option[{{ $key }}]" @endif  value="{{ $row->id }}" class="selectgroup-input options @if($option->is_required == 1) req @endif" >
                                        <span class="selectgroup-button">{{ $row->name }}</span>
                                        </label></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        @endif

                        <div class="product-details-meta">
                        <ul>
                            @if(count($info->brands) > 0)
                                <li><span>{{__('Brands')}}:</span>
                                    @foreach($info->brands as $row)
                                        <a href="#{{ url('/brand/'.$row->slug.'/'.$row->id) }}">{{ $row->name }},</a>
                                        <input type="hidden" class="cat_id" value="{{ $row->id}}">
                                    @endforeach
                                </li>
                            @endif
                            <li>
                                <span>{{__('Categories')}}:</span>
                                @foreach($info->categories as $c)
                                <a href="#">{{$c->name}},</a>
                                @endforeach
                            </li>
                            <li><span>{{__('Tag')}}: </span>
                                @foreach($keyword as $key=>$k)
                                    <a href="/blog?keyword={{trim($k)}}">{{trim($k)}} {{count($keyword) - 1 == $key ? '' : ','}}</a>
                                @endforeach
                            </li>
                        </ul>
                        </div>
                        @if($hide_price_product == 0)
                        <div class="pro-details-action-wrap">
                            <!--@if(empty($info->affiliate))-->
                            <!--    <button type="submit"  class="btn btn-outline submit_btn"  @if($info->stock->stock_status == 0) disabled @endif>-->
                            <!--        <i class="fas fa-shopping-basket"></i>-->
                            <!--        <span class="submit_text"> @if($info->stock->stock_status == 0) {{ __('Out Of Stock') }} @else {{ __('Add to Cart') }} @endif</span>-->
                            <!--    </button>-->
                            <!--@else-->
                            <!--     <a href="{{ url($info->affiliate->value ?? '') }}" target="_blank"  class="btn btn-outline"  @if($info->stock->stock_status == 0) disabled @endif>-->
                            <!--        <i class="fas fa-shopping-basket"></i>-->
                            <!--        <span class="submit_text"> @if($info->stock->stock_status == 0) {{ __('Out Of Stock') }} @else {{ __('Purchase Now') }} @endif</span>-->
                            <!--    </a>-->
                            <!--@endif-->
                            <div class="pro-details-add-to-cart btn-style-1">
                            @if($info->stock->stock_status == 0)
                                <a title="Out Stock" class="submit_btn" href="#">{{__('Out Stock')}} </a>
                            @else
                            <a title="Add to Cart" class="submit_btn" href="javascript:$('#cart-form').submit();">{{__('Add To Cart')}} </a>
                            @endif
                            </div>
                            <div class="pro-details-action">
                                <a title="Add to Wishlist" href="javascript:void(0);"  data-id="{{ $info->id }}" class="wishlist-icon wishlist_{{ $info->id }}" onclick="add_to_wishlist({{ $info->id }})"><i class="icon-heart"></i></a>
                                {{-- <a title="Add to Compare" href="#"><i class="icon-refresh"></i></a> --}}
                                <a class="social" title="Social" href="javascript:void(0);"><i class="icon-share"></i></a>
                                @if (Auth::guard('customer')->user() && feature_is_activated('affiliate_status', domain_info('user_id')))
                                        @php
                                            // if (Auth::check()) {
                                                // if (Auth::user()->referral_code == null) {
                                                //     Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                                                //     Auth::user()->save();
                                                // }
                                                $referral_code = Auth::guard('customer')->user()->referral_code;
                                                $referral_code_url = URL::to('/product') . '/' . $info->slug.'/'.$info->id . "?product_referral_code=$referral_code";
                                            // }
                                        @endphp
                                            <a title="Copy the Promote Link" id="ref-cpurl-btn" href="javascript:void(0);"  data-url="{{ $referral_code_url }}" 
                                            data-attrcpy="{{ __('Copied') }}" onclick="CopyToClipboard(this)">
                                                <i id="ref-cpurl-icon" class="fa fa-copy"></i></a>
                                    @endif
                                <div class="product-dec-social">
                                    <a rel="noopener noreferrer" class="facebook" title="Facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->full() }}"><i class="icon-social-facebook"></i></a>
                                    <a rel="noopener noreferrer" class="twitter" title="Twitter" target="_blank" href="https://twitter.com/intent/tweet?url={{ url()->full() }}"><i class="icon-social-twitter"></i></a>
                                    <a rel="noopener noreferrer" class="instagram" title="Instagram" target="_blank" href="https://www.instagram.com/?url={{url()->full()}}"><i class="icon-social-instagram"></i></a>
                                    <a rel="noopener noreferrer" class="pinterest" title="Pinterest" target="_blank" href="http://pinterest.com/pin/create/link/?url={{url()->full()}}"><i class="icon-social-pinterest"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="description-review-wrapper pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dec-review-topbar nav mb-45">
                    <a class="active" data-toggle="tab" href="#des-details1">{{__('Description')}}</a>
                    <a data-toggle="tab" href="#des-details4">{{__('Review & Ratings')}} </a>
                </div>
                <div class="tab-content dec-review-bottom">
                    <div id="des-details1" class="tab-pane active">
                        <div class="description-wrap">
                            {{ content($content->content ?? '') }}
                        </div>
                    </div>
                    <div id="des-details4" class="tab-pane">
                        <div class="review-wrapper review-list">
                            <h2><span id="review_count">0</span> {{__('Reviews')}}</h2>
                        </div>
                        <div class="ratting-form-wrapper">
                            <span>{{ __('Leave Your Review') }}</span>
                            <p>{{__('Required fields are marked')}}<span>*</span></p>
                            <div class="ratting-form">
                                <form action="{{ url('/make-review',$info->id) }}" method="post" id="some-form">
                                     @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="rating-form-style mb-20">
                                                <label>{{__('Name')}} <span>*</span></label>
                                                <input type="text" value="{{ Auth::guard('customer')->user()->name ?? '' }}" name="name" placeholder="Your name" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="rating-form-style mb-20">
                                                <label>{{__('Email Address')}} <span>*</span></label>
                                                <input type="email" name="email" placeholder="Your email" required readonly value="{{ Auth::guard('customer')->user()->email ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="star-rating">
                                                <input type="checkbox" value="5" name="rating" id="star1"><label for="star1"></label>
                                                <input type="checkbox" value="4" name="rating" id="star2"><label for="star2"></label>
                                                <input type="checkbox" value="3" name="rating" id="star3"><label for="star3"></label>
                                                <input type="checkbox" value="2" name="rating" id="star4"><label for="star4"></label>
                                                <input type="checkbox" value="1" name="rating" id="star5"><label for="star5"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="rating-form-style mb-20">
                                                <label>{{__('Your review')}} <span>*</span></label>
                                                <textarea placeholder="{{__('Your review')}}" name="comment" maxlength="200"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-submit">
                                                @if(Auth::guard('customer')->check())
                                                    <input type="submit" value="{{ __('Send Review') }}">
                                                @else
                                                    <a href="{{ url('/user/login') }}" class="btn">
                                                       {{ __('Please Login') }}
                                                       <i class="fas fa-sign-in-alt"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="related-product pb-115">
    <div class="container">
        <div class="section-title mb-45 text-center">
            <h2>Related Product</h2>
        </div>
        <div class="related-product-active">
            @foreach($latest_products as $p)
            <div class="product-plr-1">
                <div class="single-product-wrap">
                    <div class="product-img product-img-zoom mb-15">
                        <a href="/product/{{$p->slug}}/{{$p->id}}">
                            @if($p->preview && $p->preview->media)
                            <img src="{{asset(ImageSize($p->preview->media->url,'medium'))}}" alt="">
                            @else
                            <img src="{{asset('uploads/default.png')}}" alt="">
                            @endif
                        </a>
                        @if($hide_price_product == 0)
                        <div class="product-action-2 tooltip-style-2">
                            <button title="Wishlist" onclick="add_to_wishlist({{$p->id}})"><i class="icon-heart"></i></button>
                        </div>
                        @endif
                    </div>
                    <div class="product-content-wrap-2 text-center">
                        <div class="product-rating-wrap">
                            <div class="product-rating">
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                            </div>
                            <span>({{$p->reviews_count}})</span>
                        </div>
                        <h3><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h3>
                        @if($hide_price_product == 0)
                            <div class="product-price-2">
                            @if($p->price)
                                @if($p->price->starting_date == null || $p->price->ending_date == null)
                                <span>{{amount_format($p->price->price)}}</span>
                                @elseif($p->price->price == $p->price->regular_price)
                                <span>{{amount_format($p->price->price)}}</span>
                                @else
                                    <span class="new-price">{{amount_format($p->price->price)}}</span>
                                    <span class="old-price">{{amount_format($p->price->regular_price)}}</span>
                                @endif
                            @endif
                            </div>
                        @endif
                    </div>
                    <div class="product-content-wrap-2 product-content-position text-center">
                        <div class="product-rating-wrap">
                            <div class="product-rating">
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                                <i class="icon_star"></i>
                            </div>
                            <span>({{$p->reviews_count}})</span>
                        </div>
                        <h3><a href="/product/{{$p->slug}}/{{$p->id}}">{{$p->title}}</a></h3>
                        @if($hide_price_product == 0)
                            <div class="product-price-2">
                            @if($p->price)
                                @if($p->price->starting_date == null || $p->price->ending_date == null)
                                <span>{{amount_format($p->price->price)}}</span>
                                @elseif($p->price->price == $p->price->regular_price)
                                <span>{{amount_format($p->price->price)}}</span>
                                @else
                                    <span class="new-price">{{amount_format($p->price->price)}}</span>
                                    <span class="old-price">{{amount_format($p->price->regular_price)}}</span>
                                @endif
                            @endif
                            </div>
                        
                            <div class="pro-add-to-cart btn-style-1">
                            @if($info->stock->stock_status == 0)
                                <button title="{{ __('Out Stock') }}">{{ __('Out Stock') }}</button>
                                @else    
                            <button title="{{ __('Add to Cart') }}" onclick="add_to_cart({{$p->id}})">{{ __('Add to Cart') }}</button>
                            @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<input type="hidden" id="max_qty"  @if($info->stock->stock_manage == 1) value="{{ $info->stock->stock_qty }}" @else value="999" @endif>
<input type="hidden" id="term" value="{{ $info->id }}">
@endsection
@push('js')
<script>
function imposeMinMax(){
    const value = $("#qty").val();

    if(value <= 1)
    {
      $("#qty").val() = "2";
    }

    
}
function CopyToClipboard(e) {
    var url = $(e).data('url');
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(url).select();
    try {
        document.execCommand("copy");
    } catch (err) {
    }
    $temp.remove();
    $("#ref-cpurl-icon").removeClass("fa-copy").addClass("fa-check");
}
</script>
<script src="{{ asset('frontend/norda/js/details.js')}}"></script>
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
<script>

  
</script>
@endpush
