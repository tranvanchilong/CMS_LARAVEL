<div class="gift-webinar-card bg-white">
    <figure>
        <div class="image-box">
            <a href="/lms{{ $product->getUrl() }}">
                <img src="{{get_path_lms()}}{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}">
            </a>
        </div>

        <figcaption class="mt-10">
            <div class="user-inline-avatar d-flex align-items-center">
                <div class="avatar bg-gray200">
                    <img src="{{ $product->creator->getAvatar() }}" class="img-cover" alt="{{ $product->creator->full_name }}">
                </div>
                <a href="/lms{{ $product->creator->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $product->creator->full_name }}</a>
            </div>

            <a href="/lms{{ $product->getUrl() }}">
                <h3 class="mt-15 webinar-title font-weight-bold font-16 text-dark-blue">{{ ($product->title) }}</h3>
            </a>

            @if($product->getRate())
                @include('lms.web.default.includes.webinar.rate',['rate' => $product->getRate()])
            @endif

            <div class="webinar-price-box mt-15">
                @if(!empty($product->price) and $product->price > 0)
                    @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                        <span class="real">{{ handlePrice($product->getPriceWithActiveDiscountPrice()) }}</span>
                        <span class="off ml-10">{{ handlePrice($product->price) }}</span>
                    @else
                        <span class="real">{{ handlePrice($product->price) }}</span>
                    @endif
                @else
                    <span class="real font-14">{{ trans('lms/public.free') }}</span>
                @endif
            </div>
        </figcaption>
    </figure>
</div>
