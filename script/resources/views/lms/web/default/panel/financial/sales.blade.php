@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/financial.sales_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/48.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ $studentCount }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/quiz.students') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ $webinarCount }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/panel.content_sales') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/sales.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ $meetingCount }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/panel.appointment_sales') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/download-sales.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5 text-dark-blue">{{ handlePrice($totalSales) }}</strong>
                        <span class="font-16 font-weight-500 text-gray">{{ trans('lms/financial.total_sales') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('lms/financial.sales_report') }}</h2>

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
                                <label class="input-label">{{ trans('lms/webinars.webinar') }}</label>
                                <select name="webinar_id" class="form-control select2">
                                    <option value="all">{{ trans('lms/public.all') }}</option>

                                    @foreach($userWebinars as $webinar)
                                        <option value="{{ $webinar->id }}" @if(request()->get('webinar_id',null) == $webinar->id) selected @endif>{{ $webinar->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7">
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/quiz.student') }}</label>

                                        <select name="student_id" class="form-control select2">
                                            <option value="all">{{ trans('lms/public.all') }}</option>

                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" @if(request()->get('student_id',null) == $student->id) selected @endif>{{ $student->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/public.type') }}</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="all"
                                                    @if(request()->get('type',null) == 'all') selected="selected" @endif>{{ trans('lms/public.all') }}</option>
                                            <option value="webinar"
                                                    @if(request()->get('type',null) == 'webinar') selected="selected" @endif>{{ trans('lms/webinars.webinar') }}</option>
                                            <option value="meeting"
                                                    @if(request()->get('type',null) == 'meeting') selected="selected" @endif>{{ trans('lms/public.meeting') }}</option>

                                        </select>
                                    </div>
                                </div>
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

    @if(!empty($sales) and !$sales->isEmpty())
        <section class="mt-35">
            <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                <h2 class="section-title">{{ trans('lms/financial.sales_history') }}</h2>
            </div>

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('lms/quiz.student') }}</th>
                                    <th class="text-left">{{ trans('lms/product.content') }}</th>
                                    <th class="text-center">{{ trans('lms/public.price') }}</th>
                                    <th class="text-center">{{ trans('lms/public.discount') }}</th>
                                    <th class="text-center">{{ trans('lms/financial.total_amount') }}</th>
                                    <th class="text-center">{{ trans('lms/financial.income') }}</th>
                                    <th class="text-center">{{ trans('lms/public.type') }}</th>
                                    <th class="text-center">{{ trans('lms/public.date') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($sales as $sale)
                                    <tr>
                                        <td class="text-left">
                                            @if(!empty($sale->buyer))
                                                <div class="user-inline-avatar d-flex align-items-center">
                                                    <div class="avatar bg-gray200">
                                                        <img src="{{ $sale->buyer->getAvatar() }}" class="img-cover" alt="">
                                                    </div>
                                                    <div class=" ml-5">
                                                        <span class="d-block">{{ $sale->buyer->full_name }}</span>
                                                        <span class="mt-5 font-12 text-gray d-block">{{ $sale->buyer->email }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-danger">{{ trans('lms/update.deleted_user') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="text-left">
                                                @php
                                                    $content = trans('lms/update.deleted_item');
                                                    $contentId = null;

                                                    if(!empty($sale->webinar)) {
                                                        $content = $sale->webinar->title;
                                                        $contentId =$sale->webinar->id;
                                                    } elseif(!empty($sale->bundle)) {
                                                        $content = $sale->bundle->title;
                                                        $contentId =$sale->bundle->id;
                                                    } elseif(!empty($sale->productOrder) and !empty($sale->productOrder->product)) {
                                                        $content = $sale->productOrder->product->title;
                                                        $contentId =$sale->productOrder->product->id;
                                                    } elseif(!empty($sale->registrationPackage)) {
                                                        $content = $sale->registrationPackage->title;
                                                        $contentId =$sale->registrationPackage->id;
                                                    } elseif(!empty($sale->subscribe)) {
                                                        $content = $sale->subscribe->title;
                                                        $contentId =$sale->subscribe->id;
                                                    } elseif(!empty($sale->promotion)) {
                                                        $content = $sale->promotion->title;
                                                        $contentId =$sale->promotion->id;
                                                    } elseif (!empty($sale->meeting_id)) {
                                                        $content = trans('lms/meeting.reservation_appointment');
                                                    }
                                                @endphp

                                                <span class="d-block">{{ $content }}</span>

                                                @if(!empty($contentId))
                                                    <span class="d-block font-12 text-gray">Id: {{ $contentId }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($sale->payment_method == \App\Models\LMS\Sale::$subscribe)
                                                <span class="">{{ trans('lms/financial.subscribe') }}</span>
                                            @else
                                                <span>{{ handlePrice($sale->amount) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ handlePrice($sale->discount ?? 0) }}</td>
                                        <td class="align-middle">
                                            @if($sale->payment_method == \App\Models\LMS\Sale::$subscribe)
                                                <span class="">{{ trans('lms/financial.subscribe') }}</span>
                                            @else
                                                <span>{{ handlePrice($sale->total_amount) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ handlePrice($sale->getIncomeItem()) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @switch($sale->type)
                                                @case(\App\Models\LMS\Sale::$webinar)
                                                @if(!empty($sale->webinar))
                                                    <span class="text-primary">{{ trans('lms/webinars.'.$sale->webinar->type) }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('lms/update.class') }}</span>
                                                @endif
                                                @break;
                                                @case(\App\Models\LMS\Sale::$meeting)
                                                <span class="text-dark-blue">{{ trans('lms/meeting.appointment') }}</span>
                                                @break;
                                                @case(\App\Models\LMS\Sale::$subscribe)
                                                <span class="text-danger">{{ trans('lms/financial.subscribe') }}</span>
                                                @break;
                                                @case(\App\Models\LMS\Sale::$promotion)
                                                <span class="text-warning">{{ trans('lms/panel.promotion') }}</span>
                                                @break;
                                                @case(\App\Models\LMS\Sale::$registrationPackage)
                                                <span class="text-secondary">{{ trans('lms/update.registration_package') }}</span>
                                                @break;
                                                @case(\App\Models\LMS\Sale::$bundle)
                                                <span class="text-primary">{{ trans('lms/update.bundle') }}</span>
                                                @break;
                                                @case(\App\Models\LMS\Sale::$product)
                                                <span class="text-dark-blue">{{ trans('lms/update.product') }}</span>
                                                @break;
                                            @endswitch
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ dateTimeFormat($sale->created_at, 'j M Y H:i') }}</span>
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
                {{ $sales->appends(request()->input())->links('lms.vendor.pagination.panel') }}
            </div>

        </section>
    @else
        @include('lms.' . getTemplate() . '.includes.no-result',[
              'file_name' => 'sales.png',
              'title' => trans('lms/financial.sales_no_result'),
              'hint' => nl2br(trans('lms/financial.sales_no_result_hint')),
          ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>
@endpush
