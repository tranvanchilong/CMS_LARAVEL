@php
$currency = \App\Useroption::where('key','currency')->where('user_id',domain_info('user_id'))->first();
$currency=json_decode($currency->value ?? '');
@endphp
<div class="modal fade" id="addMoneyModal" tabindex="-1" aria-labelledby="addMoneyModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" style="width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addMoneyModalLabel">{{ __('Add Money') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="formDeposit" method="post" action="{{url('/user/deposit')}}">
				@csrf
					<input type="hidden" id="rate" value="{{$exchange_rate->rate ?? 1}}" />
					<input type="hidden" id="address_to" value="{{$receiver_address ?? ''}}" />
					<input type="hidden" name="transaction" id="transactionInput" />
					<input type="hidden" id="contract_address" value="{{$contract_address ?? ''}}"/>

					<div class="form-group">
						<label>{{ __('Amount') }} ({{$currency->currency_name}})* </label>
						<input type="number" id="amountInput" name="amount" required="" class="form-control" placeholder="Amount"> 
					</div>

					<div class="form-group">
						<label>{{ __('Token Amount') }}</label>
						<input type="text" id="tokenInput" class="form-control" disabled> 
					</div>
					
					<div class="modal-footer">
						<button type="button" id="transferTokens" class="btn btn-primary basicbtn">{{ __('Submit') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addDepositModal" tabindex="-1" aria-labelledby="addDepositModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" style="width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addDepositModalLabel">{{ __('Select Deposit Method') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">		
			
				<div class="form-group">
					@foreach($getways as $key => $row)
						@php
						$data=json_decode($row->content);
						@endphp
						<div class="pay-top sin-payment" style="margin-bottom: 20px;">
							<input id="deposit_method" class="input-radio" type="radio" name="deposit_method" value="{{ $row->category_id  }}"  @if($key==0) checked="checked" @endif style="width: auto;display: inline-block;float: left;height: auto;margin-top: 5px;">
							<label for="deposit_method" style="margin: 0px 0 0 10px;">{{ $data->title }}</label>
						</div>
					@endforeach
				</div>
				@if($deposit_method->status_add_money ?? '' == 1)
				<div style="justify-content: flex-end;display: flex;">
					<a href="{{ url('/user/deposit_metamask') }}" target="_blank" class="btn btn-primary basicbtn">{{ __('Submit') }}</a>
				</div>
				@else
				<div style="justify-content: flex-end;display: flex;">
					<a href="#" target="_blank" class="btn btn-primary basicbtn">{{ __('Submit') }}</a>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>