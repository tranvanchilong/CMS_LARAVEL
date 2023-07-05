@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Affiliate Users')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Affiliate Users') }}</h4>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
        <div class="card-action-filter">
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Email Address') }}</th>
                            <th scope="col">{{ __('Approval') }}</th>
                            <th scope="col">{{ __('Due Amount') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affiliate_users as $key => $affiliate_user)
                        <tr>
                            <td>{{$affiliate_user->customer->name}}</td>
                            <td>{{$affiliate_user->customer->phone}}</td>
                            <td>{{$affiliate_user->customer->email}}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input onchange="update_approved(this)" id="enabled_product_sharing" class="custom-control-input" name="status" type="checkbox" value="{{$affiliate_user->id}}" {{$affiliate_user->status == 1 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="enabled_product_sharing">Enable</label>
                                </div>
                            </td>
                            <td>{{ amount_format($affiliate_user->balance)}}</td>
                            <td>
                                <a class="btn btn-primary btn-sm editbtn" href="#" onclick="show_payment_modal('{{$affiliate_user->id}}');">
                                  <span class="btn-label"><i class="fas fa-money-bill"></i></span> Pay Now</a>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.affiliate_user.payment_history',$affiliate_user->id)}}">
                                  <span class="btn-label"><i class="fas fa-history"></i></span> Payment History</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                        <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Email Address') }}</th>
                            <th scope="col">{{ __('Approval') }}</th>
                            <th scope="col">{{ __('Due Amount') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
                {{ $affiliate_users->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="payment_modal">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript">
    function show_payment_modal(id){
        $.post('{{ route('seller.affiliate_user.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
            $('#payment_modal #modal-content').html(data);
            $('#payment_modal').modal('show', {backdrop: 'static'});
            AIZ.plugins.bootstrapSelect('refresh');
        });
    }

    function update_approved(el){
        
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('seller.affiliate_user.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
			Sweet('success',data);
        });
    }
</script>
@endpush
