@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Faq')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.faq.update',$faq->id)}}" id="ajaxForm" >
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Faq')}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label>{{ __('Question') }} **</label>
                                <input type="text" class="form-control" name="question" placeholder="Enter Question" value="{{$faq->question}}"/>
                                <p id="errquestion" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>           
                                <select name="lang_id[]" multiple  class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{in_array($row, json_decode($faq->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Answer') }} </label>
                                <textarea rows="5" class="form-control" name="answer"placeholder="Enter Answer">{{$faq->answer}}</textarea>
                                <p id="erranswer" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Featured') }} **</label>
                                <select id="featured" name="featured" class="form-control">
                                    <option {{$faq->featured == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$faq->featured == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errfeatured" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input min="1" type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$faq->serial_number}}">
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
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush