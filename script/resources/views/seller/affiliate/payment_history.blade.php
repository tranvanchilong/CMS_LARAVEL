@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Affiliate Payment History')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Affiliate Payment History') }}</h4>
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
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Payment method') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affiliate_payments as $key => $affiliate_payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$affiliate_payment->created_at}}</td>
                            <td>{{amount_format($affiliate_payment->amount)}}</td>
                            <td>{{$affiliate_payment->payment_method}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Payment method') }}</th>
                        </tr>
                    </tfoot>
                </table>
                {{ $affiliate_payments->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
