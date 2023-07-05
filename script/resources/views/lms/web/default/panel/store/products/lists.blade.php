@extends('lms.web.default.panel.layouts.panel_layout')

@push('styles_top')

@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/update.products_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $physicalProducts }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.physical_products') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/hours.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $virtualProducts }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.virtual_products') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/sales.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ !empty($physicalSales) ? handlePrice($physicalSales) : 0 }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.physical_sales') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/download-sales.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ !empty($virtualSales) ? handlePrice($virtualSales) : 0 }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/update.virtual_sales') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-25">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('lms/update.my_products') }}</h2>
        </div>

        @if(!empty($products) and !$products->isEmpty())
            @foreach($products as $product)

                @php
                    $hasDiscount = $product->getActiveDiscount();
                @endphp

                <div class="row mt-30">
                    <div class="col-12">
                        <div class="webinar-card webinar-list panel-product-card d-flex">
                            <div class="image-box">
                                <img src="{{get_path_lms()}}{{ $product->thumbnail }}" class="img-cover" alt="">

                                @if($product->ordering and !empty($product->inventory) and $product->getAvailability() < 1)
                                    <span class="badge badge-danger">{{ trans('lms/update.out_of_stock') }}</span>
                                @elseif(!$product->ordering and $product->getActiveDiscount())
                                    <span class="badge badge-info">{{ trans('lms/update.ordering_off') }}</span>
                                @elseif($hasDiscount)
                                <span class="badge badge-danger">{{ trans('lms/public.offer',['off' => $hasDiscount->percent]) }}</span>
                                @else
                                    @switch($product->status)
                                        @case(\App\Models\LMS\Product::$active)
                                        <span class="badge badge-primary">{{ trans('lms/public.active') }}</span>
                                        @break
                                        @case(\App\Models\LMS\Product::$draft)
                                        <span class="badge badge-danger">{{ trans('lms/public.draft') }}</span>
                                        @break
                                        @case(\App\Models\LMS\Product::$pending)
                                        <span class="badge badge-warning">{{ trans('lms/public.waiting') }}</span>
                                        @break
                                        @case(\App\Models\LMS\Product::$inactive)
                                        <span class="badge badge-danger">{{ trans('lms/public.rejected') }}</span>
                                        @break
                                    @endswitch
                                @endif
                            </div>

                            <div class="webinar-card-body w-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="/lms{{ $product->getUrl() }}" target="_blank">
                                        <h3 class="font-16 text-dark-blue font-weight-bold">{{ $product->title }}</h3>
                                    </a>

                                    @if($authUser->id == $product->creator_id)
                                        <div class="btn-group dropdown table-actions">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu ">
                                                <a href="/lms/panel/store/products/{{ $product->id }}/edit" class="webinar-actions d-block mt-10">{{ trans('lms/public.edit') }}</a>

                                                @if($product->creator_id == $authUser->id)
                                                    <a href="/lms/panel/store/products/{{ $product->id }}/delete" class="webinar-actions d-block mt-10 text-danger delete-action">{{ trans('lms/public.delete') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @include('lms.web.default.includes.webinar.rate',['rate' => $product->getRate()])

                                <div class="webinar-price-box mt-15">
                                    @if($product->price > 0)
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

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.item_id') }}:</span>
                                        <span class="stat-value">{{ $product->id }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.category') }}:</span>
                                        <span class="stat-value">{{ !empty($product->category_id) ? $product->category->title : '' }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/public.type') }}:</span>
                                        <span class="stat-value">{{ trans('lms/update.product_type_'.$product->type) }}</span>
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/update.availability') }}:</span>
                                        @if($product->unlimited_inventory)
                                            {{ trans('lms/update.unlimited') }}
                                        @else
                                            <span class="stat-value">{{ $product->getAvailability() }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">{{ trans('lms/panel.sales') }}:</span>
                                        @if(!empty($product->sales()) and count($product->sales()))
                                            <span class="stat-value">{{ $product->salesCount() }} ({{ handlePrice($product->sales()->sum('total_amount')) }})</span>
                                        @else
                                            <span class="stat-value">0</span>
                                        @endif
                                    </div>

                                    @if($product->isPhysical())
                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/update.shipping_cost') }}:</span>
                                            <span class="stat-value">{{ !empty($product->delivery_fee) ? handlePrice($product->delivery_fee) : 0 }}</span>
                                        </div>

                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                            <span class="stat-title">{{ trans('lms/update.waiting_orders') }}:</span>
                                            <span class="stat-value">{{ $product->productOrders->whereIn('status',[\App\Models\LMS\ProductOrder::$waitingDelivery,\App\Models\LMS\ProductOrder::$shipped])->count() }}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="my-30">
                {{ $products->appends(request()->input())->links('lms.vendor.pagination.panel') }}
            </div>

        @else
            @include('lms.' . getTemplate() . '.includes.no-result',[
                'file_name' => 'webinar.png',
                'title' => trans('lms/panel.you_not_have_any_webinar'),
                'hint' =>  trans('lms/panel.no_result_hint') ,
                'btn' => ['url' => '/lms/panel/webinars/new','text' => trans('lms/panel.create_a_webinar') ]
            ])
        @endif
    </section>
@endsection
