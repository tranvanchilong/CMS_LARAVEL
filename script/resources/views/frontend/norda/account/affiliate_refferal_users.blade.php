@extends('frontend.norda.account.affiliate_layout')
@section('affiliate_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ __('Referral Customer')}}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="row py-3 border-bottom">
                        <div class="col font-weight-bold">#</div>
                        <div class="col font-weight-bold">Name</div>
                        <div class="col font-weight-bold">Phone</div>
                        <div class="col font-weight-bold">Email Address</div>
                        <div class="col font-weight-bold">Reffered By</div>
                    </div>
                </div>
                @foreach ($refferal_users as $refferal_user)
                <div class="col-12">
                    <div class="row py-3 border-bottom">
                        <div class="col">{{ $loop->iteration }}</div>
                        <div class="col">{{$refferal_user->name}}</div>
                        <div class="col">{{$refferal_user->phone}}</div>
                        <div class="col">{{$refferal_user->email}}</div>
                        <div class="col">
                            @if ($refferal_user->refferal)
                                {{ $refferal_user->refferal->name }} ({{ $refferal_user->refferal->email }})
                            @endif
                        </div>
                    </div>
                    @if ($refferal_user->refferals->count()>0)
                    <div class="text-center py-3 border-bottom">
                        <button class="btn btn-sm btn-refferal" data-id="{{$refferal_user->id}}"><i class="fas fa-angle-double-down"></i> Refferal Users</button>
                    </div>
                    @include('frontend.norda.account.affiliate_list_refferal_user')
                    @endif
                </div>
                @endforeach
                {{ $refferal_users->links('vendor.pagination.bootstrap-4') }}
            </div>
            {{ $refferal_users->links() }}
        </div>
    </div>
@endsection
@push('js')
<script type="text/javascript">
    $(".btn-refferal").click(function(){
        var refferal_id = '#refferal_id_'+$(this).attr('data-id');
        $(refferal_id).toggle();
    });
</script>
@endpush
