<div class="d-none" id="productShareModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/public.share') }}</h3>

    <div class="text-center">
        <i data-feather="share-2" width="50" height="50" class="webinar-icon"></i>

        <p class="mt-20 font-14">{{ trans('lms/update.share_this_product_with_others') }}</p>

        <div class="position-relative d-flex align-items-center justify-content-between p-15 mt-15 border border-gray250 rounded-sm mt-5">
            <div class="js-product-share-link font-weight-bold px-16 text-ellipsis font-14">{{ $product->getUrl() }}</div>

            <button type="button" class="js-product-share-link-copy btn btn-primary btn-sm font-14 font-weight-500 flex-none" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/public.copy') }}">{{ trans('lms/public.copy') }}</button>
        </div>

        <div class="mt-32 mt-lg-40 row align-items-center font-14">
            <a href="/lms{{ $product->getShareLink('telegram') }}" target="_blank" class="col text-center">
                <img src="/assets/lms/assets/default/img/social/telegram.svg" width="50" height="50" alt="telegram">
                <span class="mt-10 d-block">{{ trans('lms/public.telegram') }}</span>
            </a>

            <a href="/lms{{ $product->getShareLink('whatsapp') }}" target="_blank" class="col text-center">
                <img src="/assets/lms/assets/default/img/social/whatsapp.svg" width="50" height="50" alt="whatsapp">
                <span class="mt-10 d-block">{{ trans('lms/public.whatsapp') }}</span>
            </a>

            <a href="/lms{{ $product->getShareLink('facebook') }}" target="_blank" class="col text-center">
                <img src="/assets/lms/assets/default/img/social/facebook.svg" width="50" height="50" alt="facebook">
                <span class="mt-10 d-block">{{ trans('lms/public.facebook') }}</span>
            </a>

            <a href="/lms{{ $product->getShareLink('twitter') }}" target="_blank" class="col text-center">
                <img src="/assets/lms/assets/default/img/social/twitter.svg" width="50" height="50" alt="twitter">
                <span class="mt-10 d-block">{{ trans('lms/public.twitter') }}</span>
            </a>
        </div>
    </div>

    <div class="mt-30 d-flex align-items-center justify-content-end">
        <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('lms/public.close') }}</button>
    </div>
</div>