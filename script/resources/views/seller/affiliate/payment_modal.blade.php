<form class="basicform_with_reload" action="{{ route('seller.affiliate_user.payment_store') }}" method="POST">
    @csrf
    <div class="modal-header">
    	<h5 class="modal-title h6">{{ __('Affiliate Payment')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
      <table class="table table-striped table-bordered" >
          <tbody>
            <tr>
                @if($affiliate_user->balance >= 0)
                    <td>{{  trans('Due Amount') }}</td>
                    <td><strong>{{ amount_format($affiliate_user->balance) }}</strong></td>
                @endif
            </tr>
            <tr>
                <td>{{ trans('Paypal Email') }}</td>
                <td>{{ $affiliate_user->paypal_email }}</td>
            </tr>
            <tr>
                <td>{{ trans('Bank Information') }}</td>
                <td>{{ $affiliate_user->bank_information }}</td>
            </tr>
          </tbody>
      </table>

      @if ($affiliate_user->balance > 0)
          <input type="hidden" name="affiliate_user_id" value="{{ $affiliate_user->id }}">
          <div class="form-group row">
              <label class="col-sm-3 col-from-label" for="amount">{{ trans('Amount')}}</label>
              <div class="col-sm-9">
                  <input type="number" min="0" max="{{ $affiliate_user->balance }}" step="0.01" name="amount" id="amount" value="{{ $affiliate_user->balance }}" class="form-control" required>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-sm-3 col-from-label" for="payment_method">{{ trans('Payment Method')}}</label>
              <div class="col-sm-9">
                  <select name="payment_method" id="payment_method" class="form-control aiz-selectpicker" required>
                      <option value="">{{ trans('Select Payment Method')}}</option>
                      <option value="Paypal">{{ trans('Paypal')}}</option>
                      <option value="Bank">{{ trans('Bank')}}</option>
                  </select>
              </div>
          </div>
      @endif
    </div>
    <div class="modal-footer">
      @if ($affiliate_user->balance > 0)
          <button class="btn btn-primary basicbtn" type="submit">{{ trans('Pay')}}</button>
      @endif
      <button type="button" class="btn btn-danger" data-dismiss="modal">{{trans('Cancel')}}</button>
    </div>
</form>
<script src="{{ asset('assets/js/form.js') }}"></script>
