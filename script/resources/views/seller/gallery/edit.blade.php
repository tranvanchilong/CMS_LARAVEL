@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=> __('Edit Gallery') ])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="{{route('seller.gallery.update',$template->id)}}" enctype="multipart/form-data" id="ajaxForm" >
            @csrf
            <input type="hidden" name="_method" value="put" />
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Gallery')}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $data = json_decode($template->excerpt->content);
                    @endphp
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>           
                                <select name="lang_id[]" multiple  class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{in_array($row, json_decode($template->lang_id)?? []) ? 'selected' : null}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Image') }} **</label>
                                <input multiple type="file" name="file[]" accept="image/*" class="form-control">
                                <p id="errfile" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Gallery Category') }}</label>
                                <select class="form-control" name="category_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($gallery_category as $category)
                                        <option {{ $template->p_id==$category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <p id="errcategory_id" class="mb-0 text-danger em"></p>
                            </div>
        
                            <div class="form-group">
                                <label>{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="title" value="{{$data->title}}" placeholder="Enter Title"/>
                                <p id="errtitle" class="mb-0 text-danger em"></p>
                            </div>
                            
                            <div class="form-group">
                                <label>{{ __('Text Button') }} (1)</label>
                                <input type="text" class="form-control" name="button_text_1" value="{{$data->button_text_1}}" placeholder="Enter Text Button (1)" />
                                <p id="errbutton_text_1" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Link Button') }} (1)</label>
                                <input type="text" class="form-control" name="button_link_1" value="{{$data->button_link_1}}" placeholder="Enter Link Button (1)" />
                                <p id="errbutton_link_1" class="mb-0 text-danger em"></p>
                            </div>
        
                            <div class="form-group">
                                <label>{{ __('Text Button') }} (2)</label>
                                <input type="text" class="form-control" name="button_text_2" value="{{$data->button_text_2}}" placeholder="Enter Text Button (2)" />
                                <p id="errbutton_text_2" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Link Button') }} (2)</label>
                                <input type="text" class="form-control" name="button_link_2" value="{{$data->button_link_2}}" placeholder="Enter Link Button (2)" />
                                <p id="errbutton_link_2" class="mb-0 text-danger em"></p>
                            </div>
                          
                            <div class="form-group">
                                <label for="">{{ __('Status') }} **</label>
                                <select id="status" name="status" class="form-control">
                                    <option {{$data->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$data->status == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errstatus" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$template->serial_number}}">
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