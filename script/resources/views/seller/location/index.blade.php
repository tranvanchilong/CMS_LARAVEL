@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Location')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Location List') }}</h4>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
        <br>
        <form method="post" class="basicform_with_reload" action="{{ route('seller.shop-location.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> {{__('Add Location')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive custom-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="checkAll"></th>
                                <th scope="col"><i class="fa fa-image"></i></th>
                                <th scope="col">{{ __('Location Name') }}</th>
                                <th scope="col">{{ __('Phone') }}</th>
                                <th scope="col">{{ __('Address') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Default') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $location)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ base64_encode($location->id) }}"></td>
                                <td><img src="{{$location->image ? asset($location->image) : asset('uploads/default.png')}}" alt="" width="50" height="65"></td>
                                <td>{{ $location->name ?? '' }}</td>
                                <td>{{ $location->phone ?? '' }}</td>
                                <td>{{ $location->getAddress() ?? '' }}</td>
                                <td>@if($location->status==1)
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                    <span class="badge badge-danger">{{ __('Deactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($location->is_default == 1)
                                    <a href="{{route('seller.shop-location.is_default',[$location->id,'is_default'=>1])}}"><span class="badge badge-success">{{ __('Default') }}</span></a>
                                    @else
                                    <a class="badge badge-default" href="{{route('seller.shop-location.is_default',[$location->id,'is_default'=>0])}}" style="color:#fff;background:#999">{{ __('No') }}</a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.shop-location.edit', $location->id)}}"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input type="checkbox" class="checkAll"></th>
                                <th><i class="fa fa-image"></i></th>
                                <th scope="col">{{ __('Location Name') }}</th>
                                <th scope="col">{{ __('Phone') }}</th>
                                <th scope="col">{{ __('Address') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Default') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
        {{ $locations->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
</div>


<!-- Add -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.shop-location.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('New Location') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{ __('Thumbnail') }} ** </label>
                        <div class="thumb-preview">
                            <img width="200" src="{{asset('uploads/default.png')}}" alt="Section Element">
                        </div>
                        <br>
                        <input type="file" class="form-control" name="image">
                        <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                        <p class="em text-danger mb-0" id="errimage"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Location Name') }}</label>
                        <input type="text" class="form-control" name="name">
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Country') }}</label>
                        <input type="text" class="form-control" name="country">
                        <p id="errcountry" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('City') }}</label>
                        <input type="text" class="form-control" name="city">
                        <p id="errcity" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('State') }}</label>
                        <input type="text" class="form-control" name="state">
                        <p id="errstate" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Location Address') }}</label>
                        <input type="text" class="form-control" name="address">
                        <p id="erraddress" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Phone') }}</label>
                        <input type="number" class="form-control" name="phone">
                        <p id="errphone" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>{{ __('Latitude') }}</label>
                            <input type="number" step="any" class="form-control" name="latitude" id="latitude">
                        </div>

                        <div class="form-group col-lg-6">
                            <label>{{ __('Longitude') }}</label>
                            <input type="number" step="any" class="form-control" name="longitude" id="longitude">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select class="form-control selectric" name="status">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="2">{{ __('Deactive') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Slot') }}</label>
                        <input type="number" class="form-control" name="slot">
                        <p id="errslot" class="mb-0 text-danger em"></p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>{{ __('Work Time') }}</label>
                        <input class="form-control" name="work_time" placeholder="{{__('Monday - Friday') }}" type="text">
                    </div>

                    <div class="form-row">                                   
                        <div class="col-12 col-md-6">                                     
                            <div class="form-group">   
                            <label>{{ __('Open Hour') }}</label>                                 
                                <input class="form-control" name="open_hour" placeholder="8:00 AM" type="text" >
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                            <label>{{ __('Close Hour') }}</label>                                    
                                <input class="form-control" name="close_hour" placeholder="5:00 PM" type="text">
                            </div>
                        </div>
                    </div>
                    <hr>
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
