@extends('frontend.norda.account.affiliate_layout')
@section('affiliate_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ trans('Affiliate payment history')}}</h5>
        </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('Date') }}</th>
                            <th>{{trans('Amount')}}</th>
                            <th>{{trans('Payment Method')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affiliate_payments as $affiliate_payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $affiliate_payment->created_at }}</td>
                                <td>{{ amount_format($affiliate_payment->amount) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $affiliate_payment->payment_method)) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $affiliate_payments->links() }}
                </div>
            </div>
    </div>
@endsection

