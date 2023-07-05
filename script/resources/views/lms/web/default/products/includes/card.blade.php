<div class="product-card">
    <figure>
        <div class="image-box">
            <a href="/lms{{ $product->getUrl() }}" class="image-box__a">
                @php
                    $hasDiscount = $product->getActiveDiscount();
                @endphp

                @if($product->getAvailability() < 1)
                    <span class="out-of-stock-badge">
                    <span>{{ trans('lms/update.out_of_stock') }}</span>
                </span>
                @elseif($hasDiscount)
                <span class="badge badge-danger">{{ trans('lms/public.offer',['off' => $hasDiscount->percent]) }}</span>
                @elseif($product->isPhysical() and empty($product->delivery_fee))
                    <span class="badge badge-warning">{{ trans('lms/update.free_shipping') }}</span>
                @endif

                <img src="{{get_path_lms()}}{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}">
            </a>

            @if($product->getAvailability() > 0)
                <div class="hover-card-action">
                    <button type="button" data-id="{{ $product->id }}" class="btn-add-product-to-cart d-flex align-items-center justify-content-center border-0 cursor-pointer">
                        <i data-feather="shopping-cart" width="20" height="20" class=""></i>
                    </button>
                </div>
            @endif
        </div>

        <figcaption class="product-card-body">
            <div class="user-inline-avatar d-flex align-items-center">
                <div class="avatar bg-gray200">
                    <img src="{{ $product->creator->getAvatar() }}" class="img-cover" alt="{{ $product->creator->full_name }}">
                </div>
                <a href="/lms{{ $product->creator->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $product->creator->full_name }}</a>
            </div>

            <a href="/lms{{ $product->getUrl() }}">
                <h3 class="mt-15 product-title font-weight-bold font-16 text-dark-blue">{{ ($product->title) }}</h3>
            </a>

            @if(!empty($product->category))
                <span class="d-block font-14 mt-10">{{ trans('lms/public.in') }} <a href="/lms/products?category_id={{ $product->category->id }}" target="_blank" class="text-decoration-underline">{{ $product->category->title }}</a></span>
            @endif

            @include('lms.web.default.includes.webinar.rate',['rate' => $product->getRate()])


            <div class="product-price-box mt-25">
            @if(!empty($isRewardProducts) and !empty($product->point))
                    <span class="text-warning real font-14">{{ $product->point }} {{ trans('lms/update.points') }}</span>
                @elseif($product->price > 0)
                    @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                        <span class="real">{{ handlePrice($product->getPriceWithActiveDiscountPrice(), true, true, false, null, true) }}</span>
                        <span class="off ml-10">{{ handlePrice($product->price, true, true, false, null, true) }}</span>
                    @else
                        <span class="real">{{ handlePrice($product->price, true, true, false, null, true) }}</span>
                    @endif
                @else
                    <span class="real">{{ trans('lms/public.free') }}</span>
                @endif
            </div>
        </figcaption>
    </figure>
</div>
