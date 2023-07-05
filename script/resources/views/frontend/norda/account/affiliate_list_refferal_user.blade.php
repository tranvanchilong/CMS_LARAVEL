@if ($refferal_user->refferals->count()>0)
    <div id="refferal_id_{{$refferal_user->id}}" data-id="{{$refferal_user->id}}" class="refferal-hide">
    @foreach ($refferal_user->refferals as $refferal_user)
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
        <div class="text-center py-2 border-bottom">
            <button class="btn btn-sm btn-refferal" data-id="{{$refferal_user->id}}"><i class="fas fa-angle-double-down"></i> Refferal Users</button>
        </div>
        @include('frontend.norda.account.affiliate_list_refferal_user')
        @endif
    </div>
    @endforeach
    </div>
@endif