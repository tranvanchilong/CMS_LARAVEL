@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' =>  __('Module')])
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-30">
                <div class="col-lg-6">
                    <h4>{{ __('Module') }}</h4>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
            <br>
            <br>
            <div class="card-action-filter">
                
                <form method="post" class="basicform_with_reload" action="{{ route('seller.course.module.destroys') }}">
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
                                <a href="#" class="btn btn-primary float-right" data-toggle="modal"
                                    data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add module') }}</a>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">{{ __('Module Name') }}</th>
                            <th scope="col">{{ __('Module Duration') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modules as $key => $module)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ base64_encode($module->id) }}"></td>

                                <td>{{ $module->name }}</td>
                                <td>{{ $module->duration }}</td>

                                <td>
                                    <a class="btn btn-primary btn-sm editbtn"
                                        href="{{ route('seller.course.module.edit', $module->id) }}">
                                        <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                                        <a class="btn btn-success btn-sm"
                                        href="{{ route('seller.module.lesson.index', $module->id) }}">
                                        <span class="btn-label">
                                            <i class="fas fa-book"></i>
                                        </span>
                                        Lessons
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </form>
                    <tfoot>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">{{ __('Module Name') }}</th>
                            <th scope="col">{{ __('Module Duration') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>
    {{-- ADD MODULE --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.course.module.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Module</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Module Name **</label>
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="Enter Module Name">
                            <p id="errname" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label for="">Module Duration **</label>
                            <input type="text" class="form-control" name="duration" value="" placeholder="eg: 10h 15m">
                            <p id="errduration" class="mb-0 text-danger em"></p>
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
    {{-- EDIT MODULE --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Course Module</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.course.module.update') }}" method="POST">
                        @csrf
                        <input id="inmodule_id" type="hidden" name="module_id" value="">
                        <div class="form-group">
                            <label for="">Module Name **</label>
                            <input id="inname" type="name" class="form-control" name="name" value=""
                                placeholder="Enter Name">
                            <p id="eerrname" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label for="">Module Duration **</label>
                            <input id="induration" type="text" class="form-control" name="duration" value=""
                                placeholder="eg: 10h 15m">
                            <p id="eerrduration" class="mb-0 text-danger em"></p>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.module.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Module</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="ajaxForm" class="modal-form create" action="{{ route('seller.module.store') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">

                            <div class="form-group">
                                <label for="">Module Name **</label>
                                <input type="text" class="form-control" name="name" value=""
                                    placeholder="Enter Module Name">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">Module Duration **</label>
                                <input type="text" class="form-control" name="duration" value=""
                                    placeholder="eg: 10h 15m">
                                <p id="errduration" class="mb-0 text-danger em"></p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection
@push('js')
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        CKFinder.setupCKEditor();
    </script>
@endpush
