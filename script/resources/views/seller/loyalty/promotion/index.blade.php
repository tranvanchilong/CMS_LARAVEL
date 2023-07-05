@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Loyalty Promotion')])
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Loyalty Promotion') }}</h4>
            </div>
            <div class="col-lg-6">
            </div>
        </div>

        <br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.loyalty-promotion.destroys') }}">
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

                    </div>
                    <div class="col-lg-4">
                        <div class="add-new-btn">
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{__('Add Promotion')}}</a>
                        </div>
                    </div>
                </div>
        </div>
        <div class="table-responsive custom-table">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{__('Image')}}</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col">{{__('Category')}}</th>
                        <th scope="col">{{__('Expiry')}}</th>
                        <th scope="col">{{__('Code')}}</th>
                        <th scope="col">{{__('End Date')}}</th>
                        <th scope="col">{{__('Actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($infos as $key => $row)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ base64_encode($row->id) }}"></td>
                        <td><img src="{{asset($row->image)}}" alt="" width="100"></td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->category->name ?? '' }}</td>
                        <td>{{ $row->expiry }}</td>
                        <td>{{ $row->code }}</td>
                        <td>{{ $row->end_at }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.loyalty-promotion.edit', $row->id)}}">
                               <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </form>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{__('Image')}}</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col">{{__('Category')}}</th>
                        <th scope="col">{{__('Expiry')}}</th>
                        <th scope="col">{{__('Code')}}</th>
                        <th scope="col">{{__('End Date')}}</th>
                        <th scope="col">{{__('Actions')}}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $infos->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.loyalty-promotion.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Promotion')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">{{__('Image')}} ** </label>
                                <div class="thumb-preview">
                                    <img width="200" src="{{asset('uploads/default.png')}}" alt="Section Element">
                                </div>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="">{{__('Background')}} ** </label>
                                <div class="thumb-preview">
                                    <img width="200" src="{{asset('uploads/default.png')}}" alt="Section Element">
                                </div>
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
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="errcategory_id" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-8">
                                <label>{{__('Name')}} **</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" />
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-lg-4">
                                <label>{{__('Code')}} **</label>
                                <input type="text" class="form-control" name="code" placeholder="Enter Code" />
                                <p id="errcode" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">{{__('Start Date')}} **</label>
                                <input type="date" class="form-control" name="start_at">
                                <p id="errstart_at" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="">{{__('End Date')}} **</label>
                                <input type="date" class="form-control" name="end_at">
                                <p id="errend_at" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">{{__('Expiry')}} **</label>
                                <input type="number" class="form-control" name="expiry" value="30">
                                <p id="errexpiry" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="">{{__('Point')}} **</label>
                                <input type="number" class="form-control" name="point">
                                <p id="errpoint" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Product')}} **</label>
                        <select class="form-control" name="term_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($products as $term)
                            <option value="{{ $term->id }}">{{ $term->title }}</option>
                            @endforeach
                        </select>
                        <p id="errterm_id" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>{{__('Discount Type')}} **</label>
                                <select class="form-control selectric" name="type">
                                    <option value="percent">{{ __('Percentage') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>{{__('Discount Amount')}} **</label>
                                <input type="number" class="form-control" name="reduction_rate">
                                <p id="errreduction_rate" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">{{__('Description')}} </label>
                        <textarea rows="5" class="form-control" name="description" placeholder="Enter Description"></textarea>
                        <p id="errDescription" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{__('Content')}}</label>
                        <textarea rows="5" class="form-control content" name="content" placeholder="Enter Content"></textarea>
                        <p id="errcontent" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">{{__('Featured')}}</label>
                                <select class="form-control selectric" name="featured">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactivate') }}</option>
                                </select>
                                <p id="errfeaturedr" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="col-lg-6">
                                <label for="">{{__('Source')}}</label>
                                <select class="form-control selectric" name="source">
                                    <option value="shop">{{ __('Shop') }}</option>
                                    <option value="partner">{{ __('Partner') }}</option>
                                </select>
                                <p id="errsource" class="mb-0 text-danger em"></p>
                            </div>
                        </div>

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
