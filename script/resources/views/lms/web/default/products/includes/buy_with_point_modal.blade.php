<div class="d-none" id="buyWithPointModal">
    <h3 class="section-title font-16 text-dark-blue mb-25">{{ trans('lms/update.buy_with_points') }}</h3>

    @if(!empty($user))
        <div class="text-center">
            <img src="/assets/lms/assets/default/img/rewards/medal-2.png" class="buy-with-points-modal-img" alt="medal">

            <p class="font-14 font-weight-500 text-gray mt-30">
                <span class="js-product-require-point-text d-block">{!! trans('lms/update.this_product_requires_n_points',['points' => $product->point]) !!}</span>
                <span class="d-block">{{ trans('lms/update.you_have_n_points',['points' => $user->getRewardPoints()]) }}</span>

                @if($user->getRewardPoints() >= $product->point)
                    <span class="d-block">{{ trans('lms/update.do_you_want_to_proceed') }}</span>
                @else
                    <span class="d-block text-danger">{{ trans('lms/update.you_have_no_enough_points_for_this_course') }}</span>
                @endif
            </p>
        </div>

        <div class="d-flex align-items-center mt-25">
            <a href="/lms#!" class="btn btn-sm flex-grow-1 {{ ($user->getRewardPoints() >= $product->point) ? 'btn-primary js-buy-product-with-point-modal-btn' : 'bg-gray300 text-gray disabled' }}" data-action="/lms{{ ($user->getRewardPoints() >= $product->point) ? '/products/'. $product->slug .'/points/apply' : '#' }}">{{ trans('lms/update.buy') }}</a>
            <a href="/lms/panel/rewards" class="btn btn-outline-primary ml-15 btn-sm flex-grow-1">{{ trans('lms/update.my_points') }}</a>
        </div>
    @endif
</div>
