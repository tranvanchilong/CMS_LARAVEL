@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-sm-10">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link @if(url()->current() == route('seller.bookings.status','all')) active @endif" href="{{ route('seller.bookings.status','all') }}">{{ __('All') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$type == '1' ? 'active' : ''}}" href="{{ route('seller.bookings.status',1) }}">{{ __('New') }} <span class="badge badge-secondary">{{ $new }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$type == '2' ? 'active' : ''}}" href="{{ route('seller.bookings.status',2) }}">{{ __('Confirmed') }}<span class="badge badge-secondary">{{ $confirm }}</span> </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$type == '3' ? 'active' : ''}}" href="{{ route('seller.bookings.status',3) }}">{{ __('Completed') }} <span class="badge badge-secondary">{{ $complete }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$type == '4' ? 'active' : ''}}" href="{{ route('seller.bookings.status',4) }}">{{ __('Cancel') }} <span class="badge badge-secondary">{{ $cancel }}</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-2">
                        <div class=" float-right">
                            <button class="btn btn-primary" style="border-radius: 0.25rem; padding: 8px 10px 8px 10px;" data-toggle="modal" data-target="#addModel">{{ __('Create New') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-30">
                        <div class="col-lg-6">
                            <h4>{{ __('Booking') }}</h4>
                        </div>
                        <div class="col-lg-6">

                        </div>
                    </div>
                    <br>
                    <div class="card-action-filter">
                        <form method="post" action="{{ route('seller.booking.destroys') }}" class="basicform_with_reload">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="d-flex">
                                        <div class="single-filter">
                                            <div class="form-group">
                                                <select class="form-control selectric" name="method">
                                                    <option disabled selected="">{{ __('Select Fulfillment') }}</option>
                                                    <option value="1">{{ __('New') }}</option>
                                                    <option value="2">{{ __('Confirmed ') }}</option>
                                                    <option value="3">{{ __('Completed') }}</option>
                                                    <option value="4">{{ __('Cancel') }}</option>
                                                    <option value="delete" class="text-danger">{{ __('Delete Permanently') }}</option>
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
                                        <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#searchmodal"> {{ __('Filter') }} </a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap card-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input checkAll" id="selectAll">
                                            <label class="custom-control-label checkAll" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th class="text-left">{{ __('Booking') }}</th>
                                    <th style="padding: 10px 0px">{{ __('Date') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Shop Location') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Service') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list font-size-base rowlink" data-link="row">

                                @foreach($bookings as $key => $row)
                                <tr>
                                    <td class="text-left">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $row->id }}" value="{{ $row->id }}">
                                            <label class="custom-control-label" for="customCheck{{ $row->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-left"> {{ $row->booking_no }} </td>
                                    <td style="padding: 10px 0px">{{ $row->booking_date }}</td>
                                    <td>{{ $row->name}}</td>
                                    <td>{{ $row->phone}}</td>
                                    <td>{{ $row->customer->name ?? __('Guest User') }}</td>
                                    <td>{{ $row->locations->name ?? ''}}</td>
                                    <td>
                                        @if($row->status==2)
                                        <span class="badge badge-warning">{{ __('Confirmed') }}</span>

                                        @elseif($row->status==1)
                                        <span class="badge badge-primary">{{ __('New') }}</span>

                                        @elseif($row->status==3)
                                        <span class="badge badge-success">{{ __('Completed') }}</span>

                                        @else
                                        <span class="badge badge-danger">{{ __('Canceled') }}</span>

                                        @endif
                                    </td>
                                    <td>{{ $row->category_services->name ?? '' }}</td>
                                    <td>{{ $row->services->name ?? '' }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-left">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input checkAll" id="selectAll">
                                            <label class="custom-control-label checkAll" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th class="text-left">{{ __('Booking') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Shop Location') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Service') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{ $bookings->links('vendor.pagination.bootstrap-4') }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Search -->
<div class="modal fade" id="searchmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="modal-form" method="get" action="{{ url()->current() }}">
                <div class="modal-header">
                    <h4 class="card-header-title">{{ __('Filters') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Starting date') }} **</label>
                        <input type="date" class="form-control" name="start" value="{{ request()->input('start') }}" />
                        <p id="errstart" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Ending date') }} **</label>
                        <input type="date" class="form-control" name="end" value="{{ request()->input('end') }}" />
                        <p id="errend" class="mb-0 text-danger em"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create -->
<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.booking.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Booking')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Customer Name') }} **</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" />
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Phone') }} **</label>
                        <input type="number" class="form-control" name="phone" placeholder="Enter Phone Number" />
                        <p id="errphone" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Date') }} **</label>
                        <input type="datetime-local" class="form-control" name="booking_date" value="<?php echo date('Y-m-d') . 'T' . date('H:i'); ?>" />
                        <p id="errbooking_date" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Category') }}</label>
                        <select class="form-control selectric" name="category_service_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            <?php echo ConfigCategory('booking') ?>
                        </select>
                        <p id="errcategory_service_id" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Service') }}</label>
                        <select class="form-control selectric" name="service_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($services as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <p id="errservice_id" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Location') }}</label>
                        <select class="form-control selectric" name="location_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($locations as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <p id="errlocation_id" class="mb-0 text-danger em"></p>
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
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
