@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Location')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.shop-location.update',$location->id)}}" id="ajaxForm">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Location')}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label for="">{{ __('Thumbnail') }} ** </label>
                                <div class="thumb-preview">
                                    <img width="200" src="{{$location->image ? asset($location->image) : asset('uploads/default.png')}}" alt="Section Element">
                                </div>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Location Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{$location->name}}">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Country') }}</label>
                                <input type="text" class="form-control" name="country" value="{{$location->country}}">
                                <p id="errcountry" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('City') }}</label>
                                <input type="text" class="form-control" name="city" value="{{$location->city}}">
                                <p id="errcity" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('State') }}</label>
                                <input type="text" class="form-control" name="state" value="{{$location->state}}">
                                <p id="errstate" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Location Address') }}</label>
                                <input type="text" class="form-control" name="address" value="{{$location->address}}">
                                <p id="erraddress" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Phone') }}</label>
                                <input type="number" class="form-control" name="phone" value="{{$location->phone}}">
                                <p id="errphone" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>{{ __('Latitude') }}</label>
                                    <input type="number" step="any" class="form-control" name="latitude" value="{{$location->latitude}}">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>{{ __('Longitude') }}</label>
                                    <input type="number" step="any" class="form-control" name="longitude" value="{{$location->longitude}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select class="form-control selectric" name="status">
                                    <option value="1" @if($location->status===1) selected="" @endif>{{ __('Active') }}</option>
                                    <option value="2" @if($location->status===2) selected="" @endif>{{ __('Deactive') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Slot') }}</label>
                                <input type="number" class="form-control" name="slot" value="{{$location->slot}}">
                                <p id="errslot" class="mb-0 text-danger em"></p>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>{{ __('Work Time') }}</label>
                                <input class="form-control" name="work_time" placeholder="{{__('Monday - Friday') }}" type="text" value="{{$location->work_time}}">
                            </div>

                            <div class="form-row">                                   
                                <div class="col-12 col-md-6">                                     
                                    <div class="form-group">   
                                    <label>{{ __('Open Hour') }}</label>                                 
                                        <input class="form-control" name="open_hour" value="{{$location->open_hour}}" placeholder="8:00 AM" type="text" >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                    <label>{{ __('Close Hour') }}</label>                                    
                                        <input class="form-control" name="close_hour" value="{{$location->close_hour}}" placeholder="5:00 PM" type="text">
                                    </div>
                                </div>
                            </div>
                            <hr>
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
