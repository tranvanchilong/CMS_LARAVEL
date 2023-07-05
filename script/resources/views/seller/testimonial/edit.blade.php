@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Testimonial')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.testimonial.update',$testimonial->id)}}" id="ajaxForm" >
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Testimonial')}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label for="">{{ __('Image') }} ** </label>
                                <br>
                                <div class="thumb-preview">
                                    <img width="300" src="{{$testimonial->image ? asset($testimonial->image) : asset('uploads/default.png')}}" alt="testimonial">
                                </div>
                                <br>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>           
                                <select name="lang_id[]" multiple  class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{in_array($row, json_decode($testimonial->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{$testimonial->name}}" placeholder="Enter Title"/>
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Rank') }}</label>
                                <input type="text" class="form-control" name="rank" placeholder="Enter Rank" value="{{$testimonial->rank}}"/>
                                <p id="errrank" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Content') }}</label>
                                <textarea class="form-control" name="text" placeholder="Enter Content">{{$testimonial->content}}</textarea>
                                <p id="errtext" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Featured') }} **</label>
                                <select id="featured" name="featured" class="form-control">
                                    <option {{$testimonial->featured == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$testimonial->featured == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errfeatured" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input min="1" type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$testimonial->serial_number}}">
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
                                <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
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
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush