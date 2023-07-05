@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Affiliate Logs')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Affiliate Logs') }}</h4>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
        <div class="card-action-filter">
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Referred By') }}</th>
                            <th scope="col">{{ __('Referral User') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Order Id') }}</th>
                            <th scope="col">{{ __('Referral Type') }}</th>
                            <th scope="col">{{ __('Product') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affiliate_logs as $key => $affiliate_log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ optional(\App\Models\Customer::where('id', $affiliate_log->referred_by_user)->first())->name }}
                                </td>
                                <td>
                                    @if($affiliate_log->customer_id !== null)
                                        {{ optional($affiliate_log->customer)->name }}
                                    @else
                                        {{ trans('Guest').' ('. $affiliate_log->guest_id.')' }}
                                    @endif
                                </td>
                                <td>{{ amount_format($affiliate_log->amount) }}</td>
                                <td>
                                    @if($affiliate_log->order_id != null)
                                        {{ optional($affiliate_log->order)->order_no }}
                                    @endif
                                </td>
                                <td> {{ ucwords(str_replace('_',' ', $affiliate_log->affiliate_type)) }}</td>
                                <td>
                                    {{ $affiliate_log->order_item->term->title ?? '' }}
                                </td>
                                <td>{{ $affiliate_log->created_at }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Referred By</th>
                            <th scope="col">Referral User</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">Order Id</th>
                            <th scope="col">Referral Type</th>
                            <th scope="col">Product</th>
                            <th scope="col">{{ __('Date') }}</th>
                        </tr>
                    </tfoot>
                </table>
                {{ $affiliate_logs->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
