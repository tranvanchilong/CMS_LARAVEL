@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Edit Promotion'])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="ajaxForm" action="{{ route('seller.loyalty-promotion.update',$info->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">Edit Promotion</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">{{__('Image')}} ** </label>
                                        <br>
                                        <div class="thumb-preview">
                                            <img width="300" src="{{$info->image ? asset($info->image) : asset('uploads/default.png')}}" alt="info">
                                        </div>
                                        <br>
                                        <br>
                                        <input type="file" class="form-control" name="image">
                                        <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                        <p class="em text-danger mb-0" id="errimage"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">{{__('Background')}} ** </label>
                                        <br>
                                        <div class="thumb-preview">
                                            <img width="300" src="{{$info->background ? asset($info->background) : asset('uploads/default.png')}}" alt="info">
                                        </div>
                                        <br>
                                        <br>
                                        <input type="file" class="form-control" name="background">
                                        <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                        <p class="em text-danger mb-0" id="errbackground"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{__('Category')}} **</label>
                                <select class="form-control" name="category_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($info_categories as $category)
                                    <option {{ $info->category_id==$category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <p id="errcategory_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label>{{__('Name')}} **</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{$info->name}}" />
                                        <p id=" errname" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <label>{{__('Code')}} **</label>
                                        <input type="text" class="form-control" name="code" placeholder="Enter Code" value="{{$info->code}}" />
                                        <p id=" errcode" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">{{__('Start Date')}} **</label>
                                        <input type="date" class="form-control" name="start_at" value="{{$info->start_at}}" />
                                        <p id=" errstart_at" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">{{__('End Date')}} **</label>
                                        <input type="date" class="form-control" name="end_at" value="{{ $info->end_at }}" />
                                        <p id=" errend_at" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">{{__('Expiry')}} **</label>
                                        <input type="number" class="form-control" name="expiry" value="{{ $info->expiry }}">
                                        <p id="errexpiry" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-6">
                                        <label for="">{{__('Point')}} **</label>
                                        <input type="number" class="form-control" name="point" value="{{ $info->point }}">
                                        <p id="errpoint" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{__('Product')}} **</label>
                                <select class="form-control" name="term_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($products as $term)
                                    @if($info->term_id != null)
                                    <option {{ $info->term_id==$term->id ? 'selected' : ''}} value="{{ $term->id }}">{{ $term->title }}</option>
                                    @else
                                    <option value="{{ $term->id }}">{{ $term->title }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <p id="errterm_id" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>{{__('Discount Type')}} **</label>
                                        <select class="form-control selectric" name="type">
                                            <option {{ $info->type == 'percent' ? 'selected' : '' }} value="percent">{{ __('Percentage') }}</option>
                                            <option {{ $info->type == 'fixed' ? 'selected' : '' }} value="fixed">{{ __('Fixed') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{__('Discount Amount')}} **</label>
                                        <input type="number" class="form-control" name="reduction_rate" value="{{$info->type == 'percent' ? intval($info->reduction_rate) : number_format($info->reduction_rate, 0 , ',' ,'.') }}">
                                        <p id="errreduction_rate" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">{{__('Featured')}}</label>
                                        <select class="form-control selectric" name="featured">
                                            <option {{$info->featured == '1' ? 'selected' : '' }} value="1">Active</option>
                                            <option {{$info->featured == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                        </select>
                                        <p id="errfeaturedr" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">{{__('Source')}}</label>
                                        <select class="form-control selectric" name="source">
                                            <option {{$info->source == 'shop' ? 'selected' : '' }} value="shop">Shop</option>
                                            <option {{$info->source == 'partner' ? 'selected' : '' }} value="partner">Partner</option>
                                        </select>
                                        <p id="errsource" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{__('Description')}} </label>
                                <textarea rows="5" class="form-control" name="description" placeholder="Enter description">{{$info->description}}</textarea>
                                <p id="errdescription" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{__('Content')}} </label>
                                <textarea rows="5" class="form-control content" name="content" placeholder="Enter Content">{{$info->content}}</textarea>
                                <p id="errcontent" class="mb-0 text-danger em"></p>
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
