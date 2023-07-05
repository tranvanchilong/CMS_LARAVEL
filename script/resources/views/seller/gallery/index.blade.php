@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Gallery')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Gallery') }}</h4>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
        <a href="{{ url('/').'/'.permalink_type('gallery').'' }}" target="_blank" class="text-success">{{ url('/').'/'.permalink_type('gallery').'' }}</a>
		<br>
		<br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.gallery.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{__('Add Gallery')}}</a>
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
                            <th scope="col">{{__('Title')}}</th>
                            {{-- <th scope="col">Button / Link (1)</th>
                            <th scope="col">Button / Link</th> --}}
                            <th scope="col">{{__('Status')}}</th>
                            <th>{{ __('Language') }}</th>
                            <th scope="col">{{ __('Category') }}</th>
                            <th scope="col">{{ __('Serial Number') }}</th>
                            <th scope="col">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $key => $template)
                        @php
                            $data = json_decode($template->excerpt->content);
                        @endphp
                        <tr>  
                            <td><input type="checkbox" name="ids[]" value="{{ base64_encode($template->id) }}"></td>
                            @if (gettype($data->image)=="string")
                            <td><img src="/{{$data->image}}" style="width: 130px; max-height: 200px;object-fit: cover;object-position: top; padding: 5px; border-radius: 10px" /></td>
                            @else
                            <td><img src="/{{array_values($data->image)[0];}}" style="width: 130px; max-height: 200px;object-fit: cover;object-position: top; padding: 5px; border-radius: 10px" /></td>
                            @endif                            
                            <td>{{$data->title}}</td>
                            {{-- <td>
                                {{$data->button_text_1}} -
                                {{$data->button_link_1}}
                                <br/>
                                {{$data->button_text_2}} -
                                {{$data->button_link_2}}
                            </td> --}}
                            <td>
                                @if ($data->status == 1)
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-success">Active</span></h5>
                                @else
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-danger">Deactive</span></h5>
                                @endif
                            </td>
                            <td>
                                @foreach(json_decode($template->lang_id) ?? [] as $lang)
                                <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                                @endforeach
                            </td>
                            <td>{{$template->category_gallery->name ?? ''}}</td>
                            <td>{{$template->serial_number ?? ''}}</td>
                            <td>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.gallery.edit', $template->id)}}">
                                  <span class="btn-label"><i class="fas fa-edit"></i></span>{{__('Edit')}}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </form>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{__('Image')}}</th>
                        <th scope="col">{{__('Title')}}</th>
                        {{-- <th scope="col">Button / Link</th> --}}
                        {{-- <th scope="col">Button / Link (2)</th> --}}
                        <th scope="col">{{__('Status')}}</th>
                        <th>{{ __('Language') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{__('Actions')}}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $templates->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" enctype="multipart/form-data" action="{{route('seller.gallery.store')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Gallery')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <div class="form-group">
                        <label>{{ __('Languages') }}</label>              
                        <select name="lang_id[]" multiple class="form-control select2 multislect">
                            @foreach(languages() ?? [] as $key => $row)                                              
                                <option value="{{ $row }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        <p id="errlang_id" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Image') }} **</label>
                        <input multiple type="file" name="file[]" required accept="image/*" class="form-control">
                        <p id="errfile" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Gallery Category') }}</label>
                        <select class="form-control" name="category_id">
                            <option selected="" disabled="" hidden="">{{ __('None') }}</option>
                            @foreach($gallerys_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="errcategory_id" class="mb-0 text-danger em"></p>
                    </div> 

                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter Title"/>
                        <p id="errtitle" class="mb-0 text-danger em"></p>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Text Button') }} (1)</label>
                        <input type="text" class="form-control" name="button_text_1" placeholder="Enter Text Button (1)" />
                        <p id="errbutton_text_1" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Link Button') }} (1)</label>
                        <input type="text" class="form-control" name="button_link_1" placeholder="Enter Link Button (1)" />
                        <p id="errbutton_link_1" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Text Button') }} (2)</label>
                        <input type="text" class="form-control" name="button_text_2" placeholder="Enter Text Button (2)" />
                        <p id="errbutton_text_2" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Link Button') }} (2)</label>
                        <input type="text" class="form-control" name="button_link_2" placeholder="Enter Link Button (2)" />
                        <p id="errbutton_link_2" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Status') }} **</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                        <p id="errstatus" class="mb-0 text-danger em"></p>
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
