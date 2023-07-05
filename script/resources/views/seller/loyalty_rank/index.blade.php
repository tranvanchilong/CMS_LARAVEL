@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Loyalty Rank List')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Loyalty Rank List') }}</h4>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
        <br>
        <form method="post" class="basicform_with_reload" action="{{ route('seller.loyalty-rank.destroys') }}">
            <div class="card-action-filter">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex">
                            <div class="single-filter">
                                <div class="form-group">
                                    <select class="form-control selectric" name="action_status">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> {{__('Add Loyalty Rank')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive custom-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="checkAll"></th>
                                <th scope="col"><i class="fa fa-image"></i></th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Point') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loyaltyRanks as $row)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ base64_encode($row->id) }}"></td>
                                <td><img src="{{$row->image ? asset($row->image) : asset('uploads/default.png')}}" alt="" width="50" height="65"></td>
                                <td>{{ $row->name ?? '' }}</td>
                                <td>{{ $row->point ?? '' }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.loyalty-rank.edit', $row->id)}}"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input type="checkbox" class="checkAll"></th>
                                <th><i class="fa fa-image"></i></th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Point') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
        {{ $loyaltyRanks->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
</div>


<!-- Add -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.loyalty-rank.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('New Loyalty Rank') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Image</label>
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
                                <label>{{ __('Name') }} **</label>
                                <input type="text" class="form-control" name="name">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-lg-4">
                                <label>{{ __('Point') }} **</label>
                                <input type="number" class="form-control" name="point">
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
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>{{ __('Increase Point') }}</label>
                                <input type="number" step="0.1" class="form-control" name="increase_point">
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
                                    <option value="{{ $term->id }}">{{ $term->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>{{ __('Free Category') }}</label>
                                <select class="form-control" name="category_id">
                                    <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                    @foreach($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Benefit') }}</label>
                        <select class="form-control select2 multislect" multiple name="benefit[]">
                            @foreach($benefits as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
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
<script src="{{ asset('assets/js/form.js') }}"></script>

@endpush