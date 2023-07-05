@extends('frontend.norda.account.layout.app')
<style>
    .text-center{
        text-align: center;
    }
    .center{
        width: 100px;
        height: 100px;
    }
</style>
@section('user_content')
<div class="myaccount-table gutters-10 text-center">
    <img class="center" src="{{ asset('uploads/metamask.svg') }}"> 
    <div class="col-md-9 text-white rounded-lg overflow-hidden">    
        <div class="p-3 rounded mb-3 c-pointer text-center bg-white rule hidden" data-toggle="modal" id="addMoney" data-target="#addMoneyModal">
            <div class="fs-18 text-black"><i class="fas fa-plus"> {{ __('Add Money To Wallet') }}</i></div>
        </div>
        
             
    </div>
    @if($deposit_method->status_add_money ?? '' == 1)
        <button class="btn btn-light btn-lg btn-block mb-3 bg-grad-1" id="connectButton" disabled style="color:#ffff"></button>  
    @endif
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
</div>
@include('frontend.norda.account.add_money_wallet')
@endsection
@push('js')
<script src="{{ asset('assets/js/contract.js') }}" defer></script>
@endpush 