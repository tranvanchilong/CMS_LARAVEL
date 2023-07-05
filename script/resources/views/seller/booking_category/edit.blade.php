@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=> __('Edit Category')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="ajaxForm" action="{{ route('seller.booking-category.update',$info->id) }}" method="post" enctype="multipart/form-data">
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
                                    <img width="300" src="{{asset($info->preview->content) ?? asset('uploads/default.png')}}" alt="Booking Category">
                                </div>
                                <br>
                                <br>
                                <input type="file" class="form-control" name="image" require="">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>
                                <select name="lang_id[]" multiple class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)
                                    <option value="{{ $row }}" {{in_array($row, json_decode($info->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>

                            </div>

                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ $info->name }}">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Featured') }}</label>
                                <select class="form-control" name="featured">
                                    <option value="1" @if($info->featured==1) selected="" @endif>{{ __('Yes') }}</option>
                                    <option value="0" @if($info->featured==0) selected="" @endif>{{ __('No') }}</option>
                                </select>
                                <p id="errfeatured" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input min="1" type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{ $info->serial_number }}">
                                <p id="errserial_number" class="mb-0 text-danger em"></p>
                                <p class="text-warning"><small>{{__('The higher the serial number is, the later the slider will be shown')}}</small></p>
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
