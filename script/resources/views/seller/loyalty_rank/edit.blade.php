@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Edit Loyalty Rank'])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="PUT" action="{{route('seller.loyalty-rank.update',$loyaltyRank->id)}}" id="ajaxForm">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">Edit Loyalty Rank</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="form-group">
                                <label for="">Image</label>
                                <div class="thumb-preview">
                                    <img width="200" src="{{$loyaltyRank->image ? asset($loyaltyRank->image) : asset('uploads/default.png')}}" alt="Section Element">
                                </div>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label>{{ __('Name') }} **</label>
                                        <input type="text" class="form-control" name="name" value="{{$loyaltyRank->name}}">
                                        <p id="errname" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <label>{{ __('Point') }} **</label>
                                        <input type="number" class="form-control" name="point" value="{{$loyaltyRank->point}}">
                                        <p id="errpoint" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>{{ __('Free Voucher') }}</label>
                                        <select class="form-control" name="discount_id">
                                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                            @foreach($discounts as $item)
                                            <option {{ $loyaltyRank->discount_id==$item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{ __('Increase Point') }}</label>
                                        <input type="number" step="0.1" class="form-control" name="increase_point" value="{{$loyaltyRank->increase_point}}">
                                        <p id="errincrease_point" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>{{ __('Free Product') }}</label>
                                        <select class="form-control" name="term_id">
                                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                            @foreach($products as $term)
                                            <option {{ $loyaltyRank->term_id==$term->id ? 'selected' : ''}} value="{{ $term->id }}">{{ $term->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{ __('Free Category') }}</label>
                                        <select class="form-control" name="category_id">
                                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                            @foreach($categories as $item)
                                            <option {{ $loyaltyRank->category_id==$item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Benefit') }}</label>
                                <select class="form-control select2 multislect" multiple name="benefit[]">
                                    @foreach($benefits as $row)
                                    <option value="{{ $row }}" {{in_array($row, json_decode($row->lang_id)?? []) ? 'selected' : ''}}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
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