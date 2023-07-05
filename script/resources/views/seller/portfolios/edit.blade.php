@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Portfolio')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.portfolio.update',$portfolio->id)}}" id="ajaxForm" >
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Portfolio')}}</div>
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
                                    <img width="300" src="{{$portfolio->image ? asset($portfolio->image) : asset('uploads/default.png')}}" alt="Portfolio">
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
                                        <option value="{{ $row }}" {{in_array($row, json_decode($portfolio->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="name" value="{{$portfolio->name}}" placeholder="Enter Title"/>
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea class="form-control" name="description" placeholder="Enter Description">{{$portfolio->description}}</textarea>
                                <p id="errdescription" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Portfolio Category') }}</label>
                                <select class="form-control" name="category_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($portfolio_categories as $category)
                                        <option {{ $portfolio->category_id==$category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <p id="errcategory_id" class="mb-0 text-danger em"></p>
                            </div> 
                            <div class="form-group">
                                <label for="">{{ __('Content') }} </label>
                                <textarea id="content" rows="5" class="form-control content" name="content" placeholder="Enter Content">{{$portfolio->content}}</textarea>
                                <p id="errcontent" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="meta_description" placeholder="Enter Meta Description">{{$portfolio->meta_description}}</textarea>
                                <p id="errmeta_description" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="meta_keyword" value="{{$portfolio->meta_keyword}}" placeholder="Enter Meta Keywords" />
                                <p id="errmeta_keyword" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Website Link') }}</label>
                                <input type="text" class="form-control" name="website_link" placeholder="Enter Website Link" value="{{$portfolio->website_link}}" />
                                <p id="errwebsite_link" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Start Date') }}</label>
                                        <input type="date" class="form-control" name="start_date" placeholder="Enter Website Link" value="{{$portfolio->start_date}}"/>
                                        <p id="errstart_date" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Submission Date') }}</label>
                                        <input type="date" class="form-control" name="submission_date" placeholder="Enter Submission Date" value="{{$portfolio->submission_date}}"/>
                                        <p id="errsubmission_date" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Featured') }} **</label>
                                <select id="featured" name="featured" class="form-control">
                                    <option {{$portfolio->featured == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$portfolio->featured == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errfeatured" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Serial Number') }} **</label>
                                <input min="1" type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$portfolio->serial_number}}">
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
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush