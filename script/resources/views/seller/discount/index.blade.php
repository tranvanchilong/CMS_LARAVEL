@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Discount')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Discount') }}</h4>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
        <br>
        <br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.discount.destroys') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex">
                            <div class="single-filter">
                                <div class="form-group">
                                    <select class="form-control selectric" name="type">
                                        <option disabled="" selected="">Select Action</option>
                                        <option value="delete">{{ __('Delete Permanently') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="single-filter">
                                <button type="submit" class="btn btn-primary btn-lg ml-2">{{ __('Apply') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-4">
                        <div class="add-new-btn">
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i>{{ __('Add Discount')}}</a>
                        </div>
                    </div>
                </div>
        </div>
        <div class="table-responsive custom-table">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{ __('Image')}}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Code') }}</th>
                        <th scope="col">{{ __('End Date') }}</th>
                        <th scope="col">{{ __('Discount Type') }}</th>
                        <th scope="col">{{ __('Discount Amount') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($posts as $row)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ base64_encode($row->id) }}"></td>
                        <td><img src="{{ asset($row->image ?? 'uploads/default.png') }}" alt="" width="70"></td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->code }}</td>
                        <td>{{ date('d/m/Y',strtotime($row->end_at)) }}</td>
                        <td>@if ($row->discount_type=='percent') {{ __('Percentage') }} @else Fixed @endif</td>
                        <td>
                            @if ($row->discount_type=='percent') {{ intval($row->discount_amount) }}%
                            @else {{ number_format($row->discount_amount, 0 , ',' ,'.') }}
                            @endif
                        </td>
                        <td>
                            @if ($row->status == 1)
                            <h5 class="d-inline-block"><span class="badge badge-sm badge-success">Active</span></h5>
                            @else
                            <h5 class="d-inline-block"><span class="badge badge-sm badge-danger">Deactive</span></h5>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm editbtn" href="{{ route('seller.discount.edit',$row->id) }}">
                                <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </form>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Code') }}</th>
                        <th scope="col">{{ __('End Date') }}</th>
                        <th scope="col">{{ __('Discount Type') }}</th>
                        <th scope="col">{{ __('Discount Amount') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $posts->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<!-- Add -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.discount.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Discount')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{__('Image')}} ** </label>
                        <div class="thumb-preview">
                            <img width="200" src="{{asset('uploads/default.png')}}" alt="Section Element">
                        </div>
                        <br>
                        <input type="file" class="form-control" name="image">
                        <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                        <p class="em text-danger mb-0" id="errimage"></p>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-8">
                                <label>{{__('Name')}} **</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" />
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-lg-4">
                                <label>{{__('Discount Code')}} **</label>
                                <input type="text" class="form-control" name="code" placeholder="Enter Code" />
                                <p id="errcode" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="">{{__('Start Date')}} **</label>
                                <input type="date" class="form-control" name="start_at">
                                <p id="errstart_at" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-6">
                                <label for="">{{__('End Date')}} **</label>
                                <input type="date" class="form-control" name="end_at">
                                <p id="errend_at" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="">{{__('Discount Type')}} **</label>
                                <select class="form-control selectric" name="discount_type">
                                    <option value="percent">{{ __('Percentage') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                </select>
                                <p id="errdiscount_type" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-6">
                                <label for="">{{__('Discount Amount')}} **</label>
                                <input type="number" class="form-control" name="discount_amount">
                                <p id="errdiscount_amount" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>{{ __('Discount By Product') }}</label>
                                <select class="form-control" name="term_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($products as $term)
                                    <option value="{{ $term->id }}">{{ $term->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>{{ __('Discount By Shipping') }}</label>
                                <select class="form-control" name="shipping_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($shippings as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="">{{ __('Discount By Order Amount')}} </label>
                                <input type="number" class="form-control" name="order_amount" value="0">
                            </div>
                            <div class="col-6">
                                <label for="">{{ __('Discount By Order Price')}}</label>
                                <input type="number" class="form-control" name="order_price" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">{{__('Description')}} </label>
                        <textarea rows="5" class="form-control content " name="content" placeholder="Enter Content"></textarea>
                        <p id="errcontent" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{__('Status')}} **</label>
                        <select class="form-control selectric" name="status">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Deactivate') }}</option>
                        </select>
                        <p id="errserial_number" class="mb-0 text-danger em"></p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
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
