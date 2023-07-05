@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Edit Landing Page'])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="{{route('seller.feature_page.section_element.store')}}" id="ajaxFormLoad">
            @csrf
            <input type="hidden" name="id" value="{{$feature->id}}">
            <input type="hidden" name="type" value="{{$feature->feature_type}}">
            <input type="hidden" name="section_element_id" value="{{$section_element->id}}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="card-title d-inline-block">Edit Section Element</h4>
                            <a class="btn btn-primary float-right d-inline-block" href="{{route('seller.feature_page.detail.edit', $feature->id)}}">
                                <span class="btn-label">
                                    <i class="fas fa-backward"></i>
                                </span>
                                Back
                            </a>                    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            @if(!in_array($feature->feature_type,['intro','intro 2','feature list 2','feature list 3','feature list 4','feature list 5',
                            'faq','faq 2']))
                            <div class="form-group">
                                <label for="">{{ __('Image') }} ** </label>
                                <div class="thumb-preview">
                                    <img width="300" src="{{asset($section_element->image ?? 'uploads/default.png')}}" alt="Section Element">
                                </div>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>
                            @endif
            
                            <div class="form-group">
                                <label for="">Title </label>
                                <input type="text" class="form-control" name="title" value="{{$section_element->title}}" placeholder="Enter Title">
                                <p id="errtitle" class="mb-0 text-danger em"></p>
                            </div>
                            
                            @if(!in_array($feature->feature_type,['partner','category','trending blog']))
                            <div class="form-group">
                                <label for="">Text </label>
                                <textarea rows="5" class="form-control" name="text"placeholder="Enter Text">{{$section_element->text}}</textarea>
                                <p id="errtext" class="mb-0 text-danger em"></p>
                            </div> 
                            @endif                         
                            
                            {{-- <div class="form-group">
                                <label for="">Video URL</label>
                                <input type="text" class="form-control" name="video_url" value="{{$section_element->video_url}}" placeholder="Enter Text">
                                <p id="errvideo_url" class="mb-0 text-danger em"></p>
                            </div> --}}
                            
                            @if(in_array($feature->feature_type,['hero slide','hero slide 2','hero slide 3','intro','intro 2',
                            'click action','subscribe','product']))
                            <div class="form-group">
                                <label for="">Button Text </label>
                                <input type="text" class="form-control" name="btn_text" value="{{$section_element->btn_text}}" placeholder="Enter Button Text">
                                <p id="errbtn_text" class="mb-0 text-danger em"></p>
                            </div>
                            @endif
                            
                            @if(!in_array($feature->feature_type,['feature list','feature list 2','feature list 3','feature list 4',
                            'feature list 5','faq','faq 2','service banner']))
                            <div class="form-group">
                                <label for="">Button URL </label>
                                <input type="text" class="form-control ltr" name="btn_url" value="{{$section_element->btn_url}}" placeholder="Enter Button URL">
                                <p id="errbtn_url" class="mb-0 text-danger em"></p>
                            </div>
                            @endif
                           
                            @if(in_array($feature->feature_type,['product']))
                            <div class="form-group">
                                <label for="">Button Text 2</label>
                                <input type="text" class="form-control" name="btn_text_1" value="{{$section_element->btn_text_1}}" placeholder="Enter Button Text 1">
                                <p id="errbtn_text_1" class="mb-0 text-danger em"></p>
                            </div>
                
                            <div class="form-group">
                                <label for="">Button URL 2</label>
                                <input type="text" class="form-control ltr" name="btn_url_1" value="{{$section_element->btn_url_1}}" placeholder="Enter Button URL 1">
                                <p id="errbtn_url_1" class="mb-0 text-danger em"></p>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input type="number" class="form-control ltr" name="serial_number" value="{{ $section_element->serial_number }}" placeholder="Enter Serial Number">
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
                                <button id="basicbtn" type="submit" class="btn btn-primary">Submit</button>
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
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush