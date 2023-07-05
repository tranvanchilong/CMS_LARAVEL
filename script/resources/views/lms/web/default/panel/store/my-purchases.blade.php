@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/update.purchases_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/physical_product3.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ $totalOrders }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/update.total_orders') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/physical_product2.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ $pendingOrders }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/update.pending_orders') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/physical_product1.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ $canceledOrders }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/update.canceled_orders') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/33.png" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ !empty($totalPurchase) ? handlePrice($totalPurchase) : 0 }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/update.total_purchase') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('lms/update.purchases_report') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/lms" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off" class="form-control @if(!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                           aria-describedby="dateInputGroupPrepend"
                                           value="{{  request()->get('from',null)  }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off" class="form-control @if(!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                           aria-describedby="dateInputGroupPrepend"
                                           value="{{  request()->get('to',null)  }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/update.seller') }}</label>

                                <select name="seller_id" class="form-control select2" data-allow-clear="false">
                                    <option value="all">{{ trans('lms/public.all') }}</option>

                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" @if(request()->get('seller_id',null) == $seller->id) selected @endif>{{ $seller->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.type') }}</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="all"
                                            @if(request()->get('type',null) == 'all') selected="selected" @endif>{{ trans('lms/public.all') }}</option>

                                    @foreach(\App\Models\LMS\Product::$productTypes as $productType)
                                        <option value="{{ $productType }}"
                                                @if(request()->get('type',null) == $productType) selected="selected" @endif>{{ trans('lms/update.product_type_'.$productType) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="all"
                                            @if(request()->get('status',null) == 'all') selected="selected" @endif>{{ trans('lms/public.all') }}</option>

                                    @foreach(\App\Models\LMS\ProductOrder::$status as $orderStatus)
                                        @if($orderStatus != 'pending')
                                            <option value="{{ $orderStatus }}"
                                                    @if(request()->get('status',null) == $orderStatus) selected="selected" @endif>{{ trans('lms/update.product_order_status_'.$orderStatus) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('lms/public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    @if(!empty($orders) and !$orders->isEmpty())
        <section class="mt-35">
            <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                <h2 class="section-title">{{ trans('lms/update.purchases_history') }}</h2>
            </div>

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('lms/update.seller') }}</th>
                                    <th class=" text-left">{{ trans('lms/update.order_id') }}</th>
                                    <th class="text-center">{{ trans('lms/public.price') }}</th>
                                    <th class="text-center">{{ trans('lms/public.discount') }}</th>
                                    <th class="text-center">{{ trans('lms/cart.tax') }}</th>
                                    <th class="text-center">{{ trans('lms/update.delivery_fee') }}</th>
                                    <th class="text-center">{{ trans('lms/financial.total_amount') }}</th>
                                    <th class="text-center">{{ trans('lms/public.type') }}</th>
                                    <th class="text-center">{{ trans('lms/public.status') }}</th>
                                    <th class="text-center">{{ trans('lms/public.date') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-left">
                                            <div class="user-inline-avatar d-flex align-items-center">
                                                <div class="avatar bg-gray200">
                                                    <img src="{{ !empty($order->seller) ? $order->seller->getAvatar() : '' }}" class="img-cover" alt="">
                                                </div>
                                                <div class=" ml-5">
                                                    <span class="d-block">{{ !empty($order->seller) ? $order->seller->full_name : '' }}</span>
                                                    <span class="mt-5 font-12 text-gray d-block">{{ !empty($order->seller) ? $order->seller->email : '' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class=" text-left">
                                            <span class="d-block font-weight-500 text-dark-blue font-16">{{ $order->id }}</span>
                                            <span class="d-block font-12 text-gray">{{ $order->quantity }} {{ trans('lms/update.product') }}</span>
                                        </td>

                                        <td class="align-middle">
                                            <span>{{ handlePrice($order->sale->amount) }}</span>
                                        </td>

                                        <td class="align-middle">
                                            @if(!empty($order->sale->discount) and (int)$order->sale->discount > 0)
                                                {{ handlePrice($order->sale->discount ?? 0) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            @if(!empty($order->sale->tax))
                                                {{ handlePrice($order->sale->tax) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            @if(!empty($order->sale->product_delivery_fee))
                                                {{ handlePrice($order->sale->product_delivery_fee) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            <span>{{ handlePrice($order->sale->total_amount) }}</span>
                                        </td>

                                        <td class="align-middle">
                                            @if(!empty($order) and !empty($order->product))
                                                <span>{{ trans('lms/update.product_type_'.$order->product->type) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($order->status == \App\Models\LMS\ProductOrder::$waitingDelivery)
                                                <span class="text-warning">{{ trans('lms/update.product_order_status_waiting_delivery') }}</span>
                                            @elseif($order->status == \App\Models\LMS\ProductOrder::$success)
                                                <span class="text-dark-blue">{{ trans('lms/update.product_order_status_success') }}</span>
                                            @elseif($order->status == \App\Models\LMS\ProductOrder::$shipped)
                                                <span class="text-warning">{{ trans('lms/update.product_order_status_shipped') }}</span>
                                            @elseif($order->status == \App\Models\LMS\ProductOrder::$canceled)
                                                <span class="text-danger">{{ trans('lms/update.product_order_status_canceled') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ dateTimeFormat($order->created_at, 'j M Y H:i') }}</span>
                                        </td>

                                        <td class="text-center align-middle">
                                            @if(!empty($order) and $order->status != \App\Models\LMS\ProductOrder::$canceled)
                                                <div class="btn-group dropdown table-actions">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="more-vertical" height="20"></i>
                                                    </button>
                                                    <div class="dropdown-menu font-weight-normal">
                                                        <a href="/lms/panel/store/purchases/{{ $order->sale_id }}/productOrder/{{ $order->id }}/invoice" target="_blank" class="webinar-actions d-block mt-10">{{ trans('lms/public.invoice') }}</a>

                                                        @if(!empty($order->product) and $order->status == \App\Models\LMS\ProductOrder::$success)
                                                            <a href="/lms{{ $order->product->getUrl() }}" class="webinar-actions d-block mt-10" target="_blank">{{ trans('lms/public.feedback') }}</a>
                                                        @endif

                                                        @if($order->status == \App\Models\LMS\ProductOrder::$shipped)
                                                            <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-view-tracking-code webinar-actions btn-transparent d-block mt-10">{{ trans('lms/update.view_tracking_code') }}</button>

                                                            <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-got-the-parcel webinar-actions btn-transparent d-block mt-10">{{ trans('lms/update.i_got_the_parcel') }}</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-30">
                {{ $orders->appends(request()->input())->links('lms.vendor.pagination.panel') }}
            </div>

        </section>
    @else
        @include('lms.' . getTemplate() . '.includes.no-result',[
              'file_name' => 'sales.png',
              'title' => trans('lms/update.product_purchases_no_result'),
              'hint' => nl2br(trans('lms/update.product_purchases_no_result_hint')),
          ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var viewTrackingCodeModalTitleLang = '{{ trans('lms/update.view_tracking_code') }}';
        var trackingCodeLang = '{{ trans('lms/update.tracking_code') }}';
        var closeLang = '{{ trans('lms/public.close') }}';
        var confirmLang = '{{ trans('lms/update.confirm') }}';
        var gotTheParcelLang = '{{ trans('lms/update.i_got_the_parcel') }}';
        var gotTheParcelConfirmTextLang = '{{ trans('lms/update.i_got_the_parcel_confirm') }}';
        var gotTheParcelSaveSuccessLang = '{{ trans('lms/update.i_got_the_parcel_success_save') }}';
        var gotTheParcelSaveErrorLang = '{{ trans('lms/update.i_got_the_parcel_error_save') }}';
        var shippingTrackingUrlLang = '{{ trans('lms/update.track_shipping') }}';
        var addressLang = '{{ trans('lms/update.address') }}';
    </script>
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/lms/assets/default/js/panel/store/my-purchase.min.js"></script>
@endpush
