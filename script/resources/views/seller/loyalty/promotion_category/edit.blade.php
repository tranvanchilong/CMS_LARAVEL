@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Promotion Category'])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="ajaxForm" action="{{ route('seller.loyalty-promotion-category.update',$info->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{ __('Edit Category') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label for="">{{ __('Thumbnail') }} ** </label>
                                <br>
                                <div class="thumb-preview">
                                    <img width="100" src="{{asset($info->preview->content) ?? asset('uploads/default.png')}}" alt="Booking Category">
                                </div>
                                <br>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ $info->name }}">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button id="basicbtn" class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
