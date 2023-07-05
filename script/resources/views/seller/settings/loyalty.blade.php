@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Loyalty Setting')])
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Loyalty Setting') }}</h4><br>
            </div>
            <div class="card-body">
                <form class="basicform" enctype="multipart/form-data" action="{{route('seller.loyalty_setting.update')}}" method="post">
                    @csrf

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Loyalty Name') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="loyalty_name" value="{{$loyalty_name->value ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Loyalty Point') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="number" class="form-control" name="loyalty_point" value="{{$loyalty_point->value ?? ''}}">
                            <small>{{ __('Note:') }} </small> <small class="text-danger mt-4">ví dụ 1000 được 1 điểm Loyalty Point.</small>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Loyalty Status') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <select class="form-control" name="loyalty_status">
                                <option value="0" @if($loyalty_status==0 ) selected="selected" @endif>{{ __('Disable') }}</option>
                                <option value="1" @if($loyalty_status==1 ) selected="selected" @endif>{{ __('Enable') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                            <button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button><br>
                            <small>{{ __('Note:') }} </small> <small class="text-danger mt-4">{{ __('After You Update Settings The Action Will Work After 5 Minutes') }}</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
