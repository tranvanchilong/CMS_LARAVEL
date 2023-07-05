@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Package')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.package.update',$package->id)}}" id="ajaxForm" >
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Package')}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{$package->name}}" placeholder="Enter Title"/>
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>           
                                <select name="lang_id[]" multiple  class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{in_array($row, json_decode($package->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Package Category') }}</label>
                                <select class="form-control" name="category_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($packages_categories as $category)
                                        <option {{ $package->category_id==$category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <p id="errcategory_id" class="mb-0 text-danger em"></p>
                            </div> 
                            <div class="form-group">
                                <label>{{ __('Price') }}</label>
                                <input type="text" class="form-control" name="price" placeholder="Enter Price" value="{{$package->price}}"/>
                                <p id="errrank" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Package Feature') }} </label>
                                <textarea rows="5" class="form-control" name="package_feature"placeholder="Package Feature">{{$package->package_feature}}</textarea>
                                <p id="errpackage_feature" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Not Package Feature') }} </label>
                                <textarea rows="5" class="form-control" name="not_package_feature"placeholder="Not Package Feature">{{$package->not_package_feature}}</textarea>
                                <p id="errnot_package_feature" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Note') }} </label>
                                <textarea rows="5" class="form-control" name="note"placeholder="Note">{{$package->note}}</textarea>
                                <p id="errnote" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Button Text') }}</label>
                                <input type="text" class="form-control" name="btn_text" placeholder="Enter Button Text" value="{{$package->btn_text}}" />
                                <p id="errbtn_text" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Button URL') }}</label>
                                <input type="text" class="form-control" name="btn_url" placeholder="Enter Button URL" value="{{$package->btn_url}}" />
                                <p id="errbtn_url" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Button Text 2') }}</label>
                                <input type="text" class="form-control" name="btn_text_2" placeholder="Enter Button Text" value="{{$package->btn_text_2}}" />
                                <p id="errbtn_text_2" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Button URL 2') }}</label>
                                <input type="text" class="form-control" name="btn_url_2" placeholder="Enter Button URL" value="{{$package->btn_url_2}}" />
                                <p id="errbtn_url_2" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Featured') }} **</label>
                                <select id="featured" name="featured" class="form-control">
                                    <option {{$package->featured == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$package->featured == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errfeatured" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$package->serial_number}}">
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