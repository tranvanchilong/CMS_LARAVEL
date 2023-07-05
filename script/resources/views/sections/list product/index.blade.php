@php
    $list_data='';
    if($item->data_type=='random product'){
        $list_data= $random_products;
    }
    elseif($item->data_type=='new product'){
        $list_data= $latest_products;
    }
    elseif($item->data_type=='best selling product'){
        $list_data= $best_selling_products;
    }
    elseif($item->data_type=='trending product'){
        $list_data= $trending_products;
    }
    elseif($item->data_type=='top rate product'){
        $list_data= $top_rate_products;
    }
    $hide_price_product = \App\Useroption::where('user_id',domain_info('user_id'))->where('key','hide_price_product')->first();                               
    $hide_price_product = !empty($hide_price_product) ? $hide_price_product->value : null;    
@endphp
<section class="pt-25 pb-25" style="background: {{$item->background_color}}">
    <div class="product-area">
        <div class="container">
            @include('frontend.norda.components.feature_page.section_title')
            {{-- <div class="tab-style-3">
                <a class="p-2 px-3 active" href="/{{permalink_type('shop')}}">{{__('View All')}}</a>
            </div> --}}

            <div class="tab-content jump">
                <div id="product-1" class="tab-pane active">
                    <div class="product-slider-active-2 dot-style-2 dot-style-2-position-static dot-style-2-active-black">
                    @foreach($list_data as $p)
                    <div class="product-plr-2">
                        <div class="single-product-wrap-2 mb-25 px-0 px-lg-3">
                            <div class="product-img-2 mb-3">
                                <a href="/product/{{$p->slug}}/{{$p->id}}">
                                    @if($p->preview && $p->preview->media)
                                    <img src="{{asset(ImageSize($p->preview->media->url,'medium'))}}" alt="">
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
                                @if($hide_price_product == 0)
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
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>