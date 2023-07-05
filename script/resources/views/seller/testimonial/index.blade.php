@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Testimonials')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Testimonials') }}</h4>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
        <a href="{{ url('/').'/'.permalink_type('testimonial').'' }}" target="_blank" class="text-success">{{ url('/').'/'.permalink_type('testimonial').'' }}</a>
		<br>
		<br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.testimonial.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{__('Add Testimonial')}}</a>
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
                            <th scope="col">{{ __('Rank') }}</th>
                            <th scope="col">{{ __('Content') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testimonials as $key => $testimonial)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ base64_encode($testimonial->id) }}"></td>
                            <td><img src="{{asset($testimonial->image)}}" alt="" width="100"></td>
                            <td>{{$testimonial->name}}</td>
                            <td>{{$testimonial->rank}}</td>
                            <td>{{$testimonial->content}}</td>
                            <td>
                                @if ($testimonial->featured == 1)
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-success">Active</span></h5>
                                @else
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-danger">Deactive</span></h5>
                                @endif
                            </td>
                            <td>
                                @foreach(json_decode($testimonial->lang_id) ?? [] as $lang)
                                <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                                @endforeach
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.testimonial.edit', $testimonial->id)}}">
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
                        <th scope="col">{{ __('Rank') }}</th>
                        <th scope="col">{{ __('Content') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th>{{ __('Language') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $testimonials->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.testimonial.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Testimonial')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">      
                    <div class="form-group">
                        <label for="">{{ __('Image') }} ** </label>
                        <div class="thumb-preview">
                            <img width="200" src="{{asset('uploads/default.png')}}" alt="Section Element">
                        </div>
                        <br>
                        <input type="file" class="form-control" name="image">
                        <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                        <p class="em text-danger mb-0" id="errimage"></p>
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
                        <label>{{ __('Name') }} **</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name"/>
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Rank') }}</label>
                        <input type="text" class="form-control" name="rank" placeholder="Enter Rank"/>
                        <p id="errrank" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Content') }}</label>
                        <textarea class="form-control" name="text" placeholder="Enter Content"></textarea>
                        <p id="errtext" class="mb-0 text-danger em"></p>
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
                        <input min="1" type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number">
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
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
