@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => __('Edit Course')])
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="PUT" action="{{ route('seller.course.update', $course->id) }}" id="ajaxForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title d-inline-block">{{__('Edit Course')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>Title **</label>
                                    <input type="text" class="form-control" name="title" placeholder="Enter Title"
                                        value="{{ $course->title }}" />
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Image')}} ** </label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img width="300"
                                            src="{{ $course->image ? asset($course->image) : asset('uploads/default.png') }}"
                                            alt="Portfolio">
                                    </div>
                                    <br>
                                    <br>
                                    <input type="file" class="form-control" name="image">
                                    <p class="text-warning mb-0">{{__('JPG, PNG, JPEG, SVG images are allowed')}}</p>
                                    <p class="em text-danger mb-0" id="errimage"></p>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">{{__('Course Video')}} **</label>
                                    <input type="text" class="form-control ltr" name="video_link"
                                        placeholder="Enter YouTube Video Link" value="{{ $course->video_link }}">
                                    <p id="errvideo_link" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Languages') }}</label>
                                    <select name="lang_id[]" multiple class="form-control select2 multislect">
                                        @foreach (languages() ?? [] as $key => $row)
                                            <option value="{{ $row }}"
                                                {{ in_array($row, json_decode($course->lang_id) ?? []) ? 'selected' : '' }}>
                                                {{ $key }}</option>
                                        @endforeach
                                    </select>
                                    <p id="errlang_id" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Summary') }}</label>
                                    <textarea class="form-control" name="summary" placeholder="Enter Description">{{ $course->summary }}</textarea>
                                    <p id="errdescription" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Course Category') }}</label>
                                    <select class="form-control" name="category_id">
                                        <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                        @foreach($course_categories as $category)
                                            <option {{ $course->category_id==$category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="errcategory_id" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Overview') }} </label>
                                    <textarea rows="5" class="form-control content" name="overview" placeholder="Enter Summary">{{ $course->overview }}</textarea>
                                    <p id="errcontent" class="mb-0 text-danger em"></p>
                                </div>

                                {{-- <div class="form-group">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="meta_description" placeholder="Enter Meta Description"></textarea>
                                <p id="errmeta_description" class="mb-0 text-danger em"></p>
                            </div>  --}}
                                {{-- <div class="form-group">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="meta_keyword" placeholder="Enter Meta Keywords" />
                                <p id="errmeta_keyword" class="mb-0 text-danger em"></p>
                            </div>  --}}
                                {{-- <div class="form-group">
                                <label>{{ __('Website Link') }}</label>
                                <input type="text" class="form-control" name="website_link" placeholder="Enter Website Link" />
                                <p id="errwebsite_link" class="mb-0 text-danger em"></p>
                            </div>  --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Current Price') }}</label>
                                            <input type="number" class="form-control" name="current_price"
                                                placeholder="Enter Current Price" value="{{ $course->current_price }}" />
                                            <p id="errcurrent_price" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Previous Price') }}</label>
                                            <input type="number" class="form-control" name="previous_price"
                                                placeholder="Enter Previous Price" value="{{ $course->current_price }}" />
                                            <p id="errprevious_price" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Instructor') }}</label>
                                    <select class="form-control" name="instructor_id">
                                        <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                        @foreach($course_instructors as $instructor)
                                            <option {{ $course->instructor_id==$instructor->id ? 'selected' : ''}} value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="errcategory_id" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">{{ __('Duration') }} **</label>
                                    <input type="text" class="form-control ltr" name="duration"
                                        placeholder="Enter Duration" value="{{$course->duration}}">
                                    <p id="errduration" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Course Type') }}</label>
                                    <select class="form-control" name="type">
                                        <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                                            <option value="Free Course">Free Course</option>
                                            <option value="Premium Course">Premium Course</option>
                                    </select>
                                    <p id="errcategory_id" class="mb-0 text-danger em"></p>
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
                                    <input min="1" type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{$course->serial_number}}">
                                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning"><small>{{__('The higher the serial number is, the later the slider will be shown')}}</small></p>
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
