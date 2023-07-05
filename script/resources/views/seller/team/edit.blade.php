@extends('layouts.app')
@push('style')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Team')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.team.update',$team->id)}}" id="ajaxForm" >
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Team')}}</div>
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
                                    <img width="300" src="{{$team->image ? asset($team->image) : asset('uploads/default.png')}}" alt="team">
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
                                        <option value="{{ $row }}" {{in_array($row, json_decode($team->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div> 
                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{$team->name}}" placeholder="Enter Title"/>
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Rank') }}</label>
                                <input type="text" class="form-control" name="rank" placeholder="Enter Rank" value="{{$team->rank}}"/>
                                <p id="errrank" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Content') }} </label>
                                <textarea rows="5" class="form-control content" name="content"placeholder="Enter Content">{{$team->content}}</textarea>
                                <p id="errcontent" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="text" class="form-control" name="facebook" placeholder="Enter Facebook" value="{{$team->facebook}}"/>
                                <p id="errfacebook" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>Twitter</label>
                                <input type="text" class="form-control" name="twitter" placeholder="Enter Twitter" value="{{$team->twitter}}"/>
                                <p id="errtwitter" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="text" class="form-control" name="instagram" placeholder="Enter Instagram" value="{{$team->instagram}}"/>
                                <p id="errinstagram" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>Linkedin</label>
                                <input type="text" class="form-control" name="linkedin" placeholder="Enter Linkedin" value="{{$team->linkedin}}"/>
                                <p id="errlinkedin" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Featured') }} **</label>
                                <select id="featured" name="featured" class="form-control">
                                    <option {{$team->featured == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$team->featured == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errfeatured" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$team->serial_number}}">
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
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush