@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Affiliate Withdraw Requests')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Affiliate Withdraw Requests') }}</h4>
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
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Email') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affiliate_withdraw_requests as $key => $affiliate_withdraw_request)
                            @php $status = $affiliate_withdraw_request->status ; @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $affiliate_withdraw_request->created_at}}</td>
                                <td>{{ optional($affiliate_withdraw_request->customer)->name}}</td>
                                <td>{{ optional($affiliate_withdraw_request->customer)->email}}</td>
                                <td>{{ amount_format($affiliate_withdraw_request->amount)}}</td>
                                <td>
                                    @if($status == 1)
                                    <span class="badge badge-inline badge-success">{{trans('Approved')}}</span>
                                    @elseif($status == 2)
                                    <span class="badge badge-inline badge-danger">{{trans('Rejected')}}</span>
                                    @else
                                    <span class="badge badge-inline badge-info">{{trans('Pending')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($status == 0)
                                        <a href="#" class="btn btn-primary btn-sm" onclick="show_affiliate_withdraw_modal('{{$affiliate_withdraw_request->id}}');">
                                            <i class="fas fa-money-bill"></i>
                                            {{ trans('Pay Now') }}
                                        </a>
                                        <form method="post" class="basicform_with_reload d-inline" action="{{route('seller.affiliate.withdraw_request.reject', $affiliate_withdraw_request->id)}}">
                                            @csrf
                                            <button class="btn btn-danger btn-sm basicbtn">
                                                <i class="fas fa-trash"></i>
                                                {{ trans('Reject') }}
                                            </button>
                                        </form>
                                        
                                    @else
                                        {{ trans('No Action Available')}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Email') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </tfoot>
                </table>
                {{ $affiliate_withdraw_requests->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="affiliate_withdraw_modal">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script type="text/javascript">
        function show_affiliate_withdraw_modal(id){
            $.post('{{ route('seller.affiliate_withdraw_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#affiliate_withdraw_modal #modal-content').html(data);
                $('#affiliate_withdraw_modal').modal('show', {backdrop: 'static'});
            });
        }
    </script>
@endpush

