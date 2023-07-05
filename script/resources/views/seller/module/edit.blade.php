@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Edit module'])
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="PUT" action="{{ route('seller.course.module.update', $module->id) }}" id="ajaxForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title d-inline-block">Edit module</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>Name Module **</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Title"
                                        value="{{ $module->name }}" />
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                
                                <div class="form-group mb-1">
                                    <label for="">Module Duration **</label>
                                    <input type="text" class="form-control ltr" name="duration"
                                        placeholder="Enter YouTube Video Link" value="{{ $module->duration }}">
                                    <p id="errvideo_link" class="mb-0 text-danger em"></p>
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
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        CKFinder.setupCKEditor();
    </script>
@endpush
