@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Portfolios')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Portfolios') }}</h4>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
        <a href="{{ url('/').'/'.permalink_type('portfolio').'' }}" target="_blank" class="text-success">{{ url('/').'/'.permalink_type('portfolio').'' }}</a>
		<br>
		<br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.portfolio.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{__('Add Portfolio')}}</a>
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
                            <th scope="col">{{ __('Title') }}</th>
                            <th scope="col">{{ __('Category') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th scope="col">{{ __('Start Date') }}</th>
                            <th scope="col">{{ __('Submission Date') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($portfolios as $key => $portfolio)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ base64_encode($portfolio->id) }}"></td>
                            <td><img src="{{asset($portfolio->image)}}" alt="" width="100"></td>
                            <td>{{$portfolio->name}}</td>
                            <td>{{$portfolio->category->name ?? ''}}</td>
                            <td>
                                @if ($portfolio->featured == 1)
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-success">Active</span></h5>
                                @else
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-danger">Deactive</span></h5>
                                @endif
                            </td>
                            <td>
                                @foreach(json_decode($portfolio->lang_id) ?? [] as $lang)
                                <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                                @endforeach
                            </td>
                            <td>{{ $portfolio->start_date ? $portfolio->start_date->format('d-m-Y') : ''}}</td>
                            <td>{{ $portfolio->submission_date ? $portfolio->start_date->format('d-m-Y') : ''}}</td>
                            <td>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.portfolio.edit', $portfolio->id)}}">
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
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th>{{ __('Language') }}</th>
                        <th scope="col">{{ __('Start Date') }}</th>
                        <th scope="col">{{ __('Submission Date') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $portfolios->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.portfolio.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Portfolio')}}</h5>
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
                        <label>{{ __('Title') }} **</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Title"/>
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" placeholder="Enter Description"></textarea>
                        <p id="errdescription" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Portfolio Category') }}</label>
                        <select class="form-control" name="category_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($portfolio_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="errcategory_id" class="mb-0 text-danger em"></p>
                    </div> 
                    <div class="form-group">
                        <label for="">{{ __('Content') }} </label>
                        <textarea rows="5" class="form-control content" name="content"placeholder="Enter Content"></textarea>
                        <p id="errcontent" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Meta Description') }}</label>
                        <textarea class="form-control" name="meta_description" placeholder="Enter Meta Description"></textarea>
                        <p id="errmeta_description" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Meta Keywords') }}</label>
                        <input type="text" class="form-control" name="meta_keyword" placeholder="Enter Meta Keywords" />
                        <p id="errmeta_keyword" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Website Link') }}</label>
                        <input type="text" class="form-control" name="website_link" placeholder="Enter Website Link" />
                        <p id="errwebsite_link" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Start Date') }}</label>
                                <input type="date" class="form-control" name="start_date" placeholder="Enter Website Link" />
                                <p id="errstart_date" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Submission Date') }}</label>
                                <input type="date" class="form-control" name="submission_date" placeholder="Enter Submission Date" />
                                <p id="errsubmission_date" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
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
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush
