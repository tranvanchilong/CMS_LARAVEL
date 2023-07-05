@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Loyalty Benefit')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Loyalty Benefit') }}</h4>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
        <br>
        <br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.loyalty-benefit.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{__('Add Benefit')}}</a>
                        </div>
                    </div>
                </div>
        </div>
        <div class="table-responsive custom-table">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Created at') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($infos as $row)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ base64_encode($row->id) }}"></td>
                        <td><img src="{{ asset($row->image ?? 'uploads/default.png') }}" alt="" width="100"></td>
                        <td>{{ $row->name }}</td>

                        <td>{{ $row->created_at->diffforHumans()  }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm editbtn" href="{{ route('seller.loyalty-benefit.edit',$row->id) }}">
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
                        <th scope="col">{{ __('Created at') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $infos->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<!-- Add -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.loyalty-benefit.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Benefit')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{ __('Image') }} ** </label>
                        <div class="thumb-preview">
                            <img width="100" src="{{asset('uploads/default.png')}}" alt="Section Element">
                        </div>
                        <br>
                        <input type="file" class="form-control" name="image">
                        <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                        <p class="em text-danger mb-0" id="errimage"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Name') }} **</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" />
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{__('Content')}}</label>
                        <textarea rows="5" class="form-control" name="content" placeholder="Enter Content"></textarea>
                        <p id="errcontent" class="mb-0 text-danger em"></p>
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