@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Loyalty List')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Loyalty List') }}</h4>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
        <br>
        <form method="post" class="basicform_with_reload" action="{{ route('seller.loyalty.destroys') }}">
            <div class="card-action-filter">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex">
                            <div class="single-filter">
                                <div class="form-group">
                                    <select class="form-control selectric" name="action_status">
                                        <option disabled="" selected="">Select Action</option>
                                        <option value="update">{{ __('Update') }}</option>
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> {{__('Add Loyalty')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive custom-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="checkAll"></th>
                                <th scope="col">{{ __('Customer') }}</th>
                                <th scope="col">{{ __('Rank') }}</th>
                                <th scope="col">{{ __('Total Point') }}</th>
                                <th scope="col">{{ __('Curent Point') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loyalties as $row)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ base64_encode($row->id) }}"></td>
                                <td>{{ $row->customer->name ?? '' }}</td>
                                <td>{{ $row->loyaltyRank->name ?? '' }}</td>
                                <td>{{ number_format($row->customer->total_point ?? 0, 0 , ',' ,'.') }}</td>
                                <td>{{ number_format($row->customer->curent_point ?? 0, 0 , ',' ,'.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input type="checkbox" class="checkAll"></th>
                                <th scope="col">{{ __('Customer') }}</th>
                                <th scope="col">{{ __('Rank') }}</th>
                                <th scope="col">{{ __('Total Point') }}</th>
                                <th scope="col">{{ __('Curent Point') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
        {{ $loyalties->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
</div>
!-- Add -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.loyalty.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('New Loyalty') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>{{ __('Customer') }}</label>
                        <select class="form-control selectric" name="customer_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($customers as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <p id="errcustomer_id" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Rank') }}</label>
                        <select class="form-control selectric" name="loyalty_rank_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($ranks as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <p id="errloyalty_rank_id" class="mb-0 text-danger em"></p>
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
