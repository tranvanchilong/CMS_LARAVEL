@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => __('Shop Sync Token')])
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Shop Sync Token') }}</h4><br>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.save_token_mydi4sell.update')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <input class="form-control" type="text" name="token" value="{{$shopSyncToken}}">
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
