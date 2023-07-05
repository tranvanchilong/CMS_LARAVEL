@extends('frontend.norda.account.affiliate_layout')
@section('affiliate_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ trans('Affiliate withdraw request history')}}</h5>
        </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('Date') }}</th>
                            <th>{{ trans('Amount')}}</th>
                            <th data-breakpoints="lg">{{ trans('Status')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affiliate_withdraw_requests as $affiliate_withdraw_request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $affiliate_withdraw_request->created_at }}</td>
                                <td>{{ amount_format($affiliate_withdraw_request->amount) }}</td>
                                <td>
                                    @if($affiliate_withdraw_request->status == 1)
                                        <span class="badge badge-inline badge-success">{{trans('Approved')}}</span>
                                    @elseif($affiliate_withdraw_request->status == 2)
                                        <span class="badge badge-inline badge-danger">{{trans('Rejected')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-info">{{trans('Pending')}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $affiliate_withdraw_requests->links() }}
                </div>
            </div>
    </div>
@endsection
