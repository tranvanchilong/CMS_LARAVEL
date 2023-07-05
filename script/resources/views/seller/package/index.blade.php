@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>  __('Packages') ])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Packages') }}</h4>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
        <a href="{{ url('/').'/'.permalink_type('package').'' }}" target="_blank" class="text-success">{{ url('/').'/'.permalink_type('package').'' }}</a>
		<br>
		<br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.package.destroys') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex">
                            <div class="single-filter">
                                <div class="form-group">
                                    <select class="form-control selectric" name="status">
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
                        <div class="single-filter">
                            <div class="form-group">
                                <select class="form-control" name="language" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                                    <option value="" selected="">All Language</option>
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{$row == request()->input('language') ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                       
                    </div>
                    <div class="col-lg-4">
                        <div class="add-new-btn">
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i>{{ __('Add Package') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">{{ __('Category') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Price') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packages as $key => $package)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ base64_encode($package->id) }}"></td>
                            <td>{{$package->category->name ?? ''}}</td>
                            <td>{{$package->name}}</td>
                            <td>{{$package->price}}</td>
                            <td>
                                @if ($package->featured == 1)
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-success">Active</span></h5>
                                @else
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-danger">Deactive</span></h5>
                                @endif
                            </td>
                            <td>
                                @foreach(json_decode($package->lang_id) ?? [] as $lang)
                                <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                                @endforeach
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.package.edit', $package->id)}}">
                                  <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </form>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th>{{ __('Language') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $packages->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.package.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Package</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">               
                    <div class="form-group">
                        <label>{{ __('Name') }} **</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name"/>
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Languages') }}</label>              
                        <select name="lang_id[]" multiple  class="form-control select2 multislect">
                            @foreach(languages() ?? [] as $key => $row)                                              
                                <option value="{{ $row }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        <p id="errlang_id" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Package Category') }}</label>
                        <select class="form-control" name="category_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($packages_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="errcategory_id" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Price') }}</label>
                        <input type="text" class="form-control" name="price" placeholder="Enter Price"/>
                        <p id="errprice" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Package Feature') }} </label>
                        <textarea rows="5" class="form-control" name="package_feature"placeholder="Package Feature"></textarea>
                        <p id="errpackage_feature" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Not Package Feature') }} </label>
                        <textarea rows="5" class="form-control" name="not_package_feature"placeholder="Not Package Feature"></textarea>
                        <p id="errnot_package_feature" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Note') }} </label>
                        <textarea rows="5" class="form-control" name="note"placeholder="Note"></textarea>
                        <p id="errnote" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Button Text') }}</label>
                        <input type="text" class="form-control" name="btn_text" placeholder="Enter Button Text"/>
                        <p id="errbtn_text" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Button URL') }}</label>
                        <input type="text" class="form-control" name="btn_url" placeholder="Enter Button URL"/>
                        <p id="errbtn_url" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Button Text 2') }}</label>
                        <input type="text" class="form-control" name="btn_text_2" placeholder="Enter Button Text"/>
                        <p id="errbtn_text_2" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Button URL 2') }}</label>
                        <input type="text" class="form-control" name="btn_url_2" placeholder="Enter Button URL"/>
                        <p id="errbtn_url_2" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Featured') }} **</label>
                        <select id="status" name="featured" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                        <p id="errfeatured" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Serial Number') }} **</label>
                        <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number">
                        <p id="errserial_number" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>{{__('The higher the serial number is, the later the slider will be shown')}}</small></p>
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
