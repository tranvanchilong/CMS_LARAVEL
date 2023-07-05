@php
$currency = \App\Useroption::where('key','currency')->where('user_id',Auth::id())->first();
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
				<form method="post" action="{{route('seller.customers.addMoney',$info->id)}}" class="basicform_with_reload">
					@csrf
							
					<div class="form-group">
						<label>{{ __('Amount') }} ({{$currency->currency_name}})*</label>
						<input type="number"  name="amount" required="" value="#" class="form-control"> 
					</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary basicbtn">{{ __('Submit') }}</button>
				</form>
			</div>
		</div>
	</div>
</div>
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush