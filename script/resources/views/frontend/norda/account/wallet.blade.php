@extends('frontend.norda.account.layout.app')
<style>
    .text-white {
        color: #fff!important;
    }
    .text-black{
        color: #000000!important;
    }
</style>
@section('user_content')
<div class="myaccount-table gutters-10 table_rule">
    <div class="col-md-9 bg-grad-1 text-white rounded-lg overflow-hidden">    
        <div class="col-md-3 ac-768">
            <div class="h4 fw-700 text-center">{{amount_format ($total_wallet_balance)}}</div>
            <div class="opacity-50 text-center">{{ __('Wallet Balance') }}</div>
        </div>
        <div class="p-3 rounded mb-3 c-pointer text-center bg-white rule" data-toggle="modal" id="deposit_method" data-target="#addDepositModal">
            <div class="fs-18 text-black"><i class="fas fa-plus"> {{ __('Add Money To Wallet') }}</i></div>
        </div>
        <div class="p-3 rounded mb-3 c-pointer text-center bg-white rule hidden" data-toggle="modal" id="addMoney" data-target="#addMoneyModal">
            <div class="fs-18 text-black"><i class="fas fa-plus"> {{ __('Add Money To Wallet') }}</i></div>
        </div>
        @if($deposit_method->status_add_money ?? '' == 1)
        <button class="btn btn-light btn-lg btn-block mb-3" id="connectButton" disabled></button>     
        @endif  
    </div>
</div>
<div id="accounts_div" class="single-input-item hidden" style="margin-top: -10px;">
    <h3>{{ __('MetaMark Wallet Address') }}</h3>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th style="background-color: #c0c0c0;" id="accounts" disabled></th>
			</tr>
        </tbody>
    </table>
    <hr>
</div>
<div>
<h3>{{ __('All Transaction Detail') }}</h3>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <tr>
				<th>{{ __('Transaction Type') }}</th>
				<th>{{ __('Amount') }}</th>
                <th>{{ __('Balance')}}</th>
				<th>{{ __('Time') }}</th>
				<th>{{ __('Status') }}</th>
			</tr>
        </thead>
        <tbody>
        @foreach($wallet_transactio_list as $row)
            <tr>
            <th>{{ $row->transaction_type}}</th>
            <th>{{amount_format ($row->amount) }}</th>
            <th>{{amount_format ($row->balance)}}</th>
            <th>{{ $row->created_at->format('d-F-Y') }}</th>
            <th>
                @if($row->status==1)
                    <span class="badge badge-success">{{ __('Complete') }}</span>
                @else
                    <span class="badge badge-danger">{{ __('Incomplete') }}</span> 
                @endif
            </th>
			</tr>
		@endforeach
        </tbody>
    </table>
</div>
{{ $wallet_transactio_list->links('vendor.pagination.bootstrap-4') }}
@include('frontend.norda.account.add_money_wallet')
@endsection
@push('js')
<script src="{{ asset('assets/js/web3.min.js') }}" defer></script>
<script src="{{ asset('assets/js/contract.js?v'. time()) }}" defer></script>
@endpush 