@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Edit Discount'])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="ajaxForm" action="{{ route('seller.discount.update',$info->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">Edit Discount</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="form-group">
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
                                    <div class="col-6">
                                        <label for="">{{__('Start At')}} **</label>
                                        <input type="date" class="form-control" name="start_at" value="{{$info->start_at->format('Y-m-d')}}" />
                                        <p id=" errstart_at" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-6">
                                        <label for="">{{__('End At')}} **</label>
                                        <input type="date" class="form-control" name="end_at" value="{{ $info->end_at->format('Y-m-d') }}" />
                                        <p id=" errend_at" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">{{__('Discount Type')}} **</label>
                                        <select class="form-control selectric" name="discount_type">
                                            <option {{$info->discount_type == 'percent' ? 'selected' : '' }} value="percent">{{ __('Percentage') }}</option>
                                            <option {{$info->discount_type == 'fixed' ? 'selected' : '' }} value="fixed">{{ __('Fixed') }}</option>
                                        </select>
                                        <p id="errdiscount_type" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-6">
                                        <label for="">{{__('Discount Amount')}} **</label>
                                        <input type="number" class="form-control" name="discount_amount" value="{{$info->discount_type == 'percent' ? intval($info->discount_amount) : number_format($info->discount_amount, 0 , ',' ,'.') }}">
                                        <p id="errdiscount_amount" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>{{__('Discount By Product')}}</label>
                                        <select class="form-control" name="term_id">
                                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                            @foreach($products as $term)
                                            @if(isset($info) && $info->term_id != null)
                                            <option {{ $info->term_id==$term->id ? 'selected' : ''}} value="{{ $term->id }}">{{ $term->title }}</option>
                                            @else
                                            <option value="{{ $term->id }}">{{ $term->title }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{__('Discount By Shipping')}}</label>
                                        <select class="form-control" name="shipping_id">
                                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                            @foreach($shippings as $category)
                                            @if(isset($info) && $info->shipping_id == null)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @else
                                            <option {{ $info->shipping_id==$category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">{{__('Discount By Order Amount')}}</label>
                                        <input type="number" class="form-control" name="order_amount" value="{{$info->order_amount ?? 0}}">
                                    </div>
                                    <div class="col-6">
                                        <label for="">{{__('Discount By Order Price')}}</label>
                                        <input type="number" class="form-control" name="order_price" value="{{number_format($info->order_price ?? 0, 0 , ',' ,'.')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Description')}} </label>
                                <textarea rows="5" class="form-control content" name="content" placeholder="Enter Content">{{$info->content}}</textarea>
                                <p id="errcontent" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Status')}} **</label>
                                <select class="form-control selectric" name="status">
                                    <option {{$info->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$info->status == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errserial_number" class="mb-0 text-danger em"></p>
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
