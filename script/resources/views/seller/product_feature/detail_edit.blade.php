@extends('layouts.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
@endpush
@section('head')
    @include('layouts.partials.headersection', ['title' => __('Edit Page Section')])
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" id="ajaxFormUpdateLoad"
                action="{{ route('seller.feature_page.detail.update', $feature->id) }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="card-title d-inline-block">{{ $feature->feature_title }}</h4>
                                <a class="btn btn-primary float-right d-inline-block"
                                    href="{{ route('seller.feature_page.detail', $page->id) }}">
                                    <span class="btn-label">
                                        <i class="fas fa-backward"></i>
                                    </span>
                                    {{__('Back')}}
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 offset-3">
                                @if (in_array($feature->feature_type, [
                                        'intro',
                                        'intro 2',
                                        'feature list 2',
                                        'feature list 3',
                                        'feature list 4',
                                        'feature list 5',
                                        'faq',
                                        'faq 2',
                                        'ads_mobile',
                                    ]))
                                    <div class="form-group">
                                        <label for="">{{__('Image')}} ** </label>
                                        <div class="thumb-preview">
                                            <img width="300" src="{{ asset($feature->image ?? 'uploads/default.png') }}">
                                        </div>
                                        <br>
                                        <input type="file" class="form-control" name="image">
                                        <p class="text-warning mb-0">{{__('JPG, PNG, JPEG, SVG images are allowed')}}</p>
                                        <p class="em text-danger mb-0" id="eerrimage"></p>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="">{{__('Title')}} **</label>
                                    <input type="text" class="form-control" name="feature_title"
                                        placeholder="Enter Title" value="{{ $feature->feature_title }}">
                                    <p id="eerrfeature_title" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{__('Subtitle')}}</label>
                                    <input type="text" class="form-control" name="feature_subtitle"
                                        placeholder="Enter Subtitle" value="{{ $feature->feature_subtitle }}" />
                                    <p id="eerrfeature_subtitle" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('Position Title')}}</label>
                                    <div>
                                        <span class="px-3">
                                            <input type="radio" id="position1" name="feature_position" value="0" {{$feature->feature_position == 0 ? 'checked' : ''}}>
                                            <label for="position1">Left</label>
                                        </span>
                                        <span class="px-3">
                                            <input type="radio" id="position2" name="feature_position" value="1" {{$feature->feature_position == 1 ? 'checked' : ''}}>
                                            <label for="position2">Mid</label>
                                        </span>
                                        <span class="px-3">
                                            <input type="radio" id="position3" name="feature_position" value="2" {{$feature->feature_position == 2 ? 'checked' : ''}}>
                                            <label for="position3">Right</label>
                                        </span>
                                    </div>
                                    <p id="errfeature_position" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('Background Color')}}</label>
                                    <input type="text" name="background_color" class="form-control rgcolorpicker" value="{{ $feature->background_color }}">
                                    <p id="errbackground_color" class="mb-0 text-danger em"></p>
                                </div>
                                
                                <div class="form-group">
                                    <label>{{__('Section Element Data')}} **</label>
                                    <div>
                                        <a data-toggle="modal" href="#myModal2" class="btn btn-primary">{{__('Choose')}}</a>
                                        <div class="row">
                                            <div class="category-section col-12 col-md-6 mt-2">
                                                <div id="img-style" class="rounded img-svg p-2 mb-2">
                                                    <img class="rounded-0 img-fluid"
                                                        src="{{ asset(find_style($feature->feature_type) ?? 'uploads/default.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <p id="eerrfeature_type" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                                <div class="modal" id="myModal2">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Section Element Data</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="container"></div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>{{__('Category')}} **</label>
                                                    <ul class="nav nav-tabs d-inline-block category-section">
                                                        @foreach (get_category_sections() as $key => $category)
                                                            <li class="list-inline-item w-auto">
                                                                <a data-toggle="tab" href="#category{{ $key }}"
                                                                    class="{{ $feature->category == $key ? 'active show' : '' }}">
                                                                    <label for="category-section-{{ $key }}">
                                                                        <input hidden
                                                                            id="category-section-{{ $key }}"
                                                                            type="radio" class=""
                                                                            value="{{ $key }}" name="category"
                                                                            {{ $feature->category == $key ? 'checked' : '' }}>
                                                                        <span
                                                                            class="btn btn-outline-secondary rounded">{{ $category['title'] }}</span>
                                                                    </label>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="tab-content">
                                                        @foreach (get_category_sections() as $key => $category)
                                                            <div id="category{{ $key }}"
                                                                class="tab-pane fade in {{ $feature->category == $key ? 'active show' : '' }}">
                                                                <div class="form-group">
                                                                    <label>{{__('Style')}} **</label>
                                                                    <ul class="category-section list-unstyled">
                                                                        <li class="mega-menu-content">
                                                                            <ul id="list-style"
                                                                                class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-6 list-unstyled">
                                                                                @foreach ($category['style'] as $k => $style)
                                                                                    <li class="col col-6 col-md-4">
                                                                                        <label
                                                                                            for="style-section-{{ $k }}">
                                                                                            <input
                                                                                                data-category="{{ $key }}"
                                                                                                data-type="{{ json_encode($style['data_type']) }}"
                                                                                                hidden
                                                                                                id="style-section-{{ $k }}"
                                                                                                type="radio"
                                                                                                class="input-checked"
                                                                                                value="{{ $style['key'] }}"
                                                                                                name="feature_type"
                                                                                                {{ $feature->feature_type == $k ? 'checked' : '' }}>
                                                                                            <div
                                                                                                class="rounded img-svg p-2 mb-2">
                                                                                                <img class="rounded-0 img-fluid"
                                                                                                    src="{{ asset(find_style($k) ?? 'uploads/default.png') }}"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </label>
                                                                                        <ul id="review-input"
                                                                                            class="list-unstyled">
                                                                                            <label
                                                                                                class="text-warning font-weight-bold">{{__('Data support')}}: **</label>
                                                                                            @foreach ($style['data_type'] as $value)
                                                                                                <li
                                                                                                    class="mb-1 mr-1 text-capitalize">
                                                                                                    {{ $value == 'input' ? 'Section Element' : $value }}{{ $loop->last ? '' : ', ' }}
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a id="choose-style" href="javascript:void(0)" class="btn btn-primary"
                                                    data-dismiss="modal">{{__('Done')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Data Source')}} **</label>
                                    <ul id="data-type" data-active="{{ $feature->data_type }}" class="list-inline mb-0">
                                    </ul>
                                    <p id="eerrdata_type" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Button Text')}}</label>
                                    <input type="text" name="btn_text" class="form-control" value="{{$feature->btn_text}}">
                                    <p id="eerrbtn_text" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Button URL')}}</label>
                                    <input type="text" name="btn_url" class="form-control" value="{{$feature->btn_url}}">
                                    <p id="eerrbtn_url" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Status')}} **</label>
                                    <select id="status" name="feature_status" class="form-control">
                                        <option {{ $feature->feature_status == '1' ? 'selected' : '' }} value="1">
                                            Active</option>
                                        <option {{ $feature->feature_status == '0' ? 'selected' : '' }} value="0">
                                            Deactive</option>
                                    </select>
                                    <p class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('Serial Number')}} **</label>
                                    <input type="number" class="form-control ltr" name="serial_number"
                                        value="{{ $feature->serial_number }}" placeholder="Enter Serial Number">
                                    <p id="eerrserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning"><small>The higher the serial number is, the later the slider
                                            will be shown.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button id="basicbtn2" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @if (!in_array($feature->feature_type, ['package', 'list product', 'booking']))
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-30">
                            <div class="col-lg-6">
                                <h4>{{ __('Section Element') }}</h4>
                            </div>
                            <div class="col-lg-6">

                            </div>
                        </div>
                        <br>
                        <div class="card-action-filter">
                            <form method="post" class="basicform_with_reload"
                                action="{{ route('seller.feature_page.section_element.destroys') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
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
                                                <button type="submit"
                                                    class="btn btn-primary btn-lg ml-2">{{ __('Apply') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="add-new-btn">
                                            <a href="#" class="btn btn-primary float-right" data-toggle="modal"
                                                data-target="#createModal"><i class="fas fa-plus"></i> Add Section
                                                Element</a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="table-responsive custom-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        @if (
                                            !in_array($feature->feature_type, [
                                                'intro',
                                                'intro 2',
                                                'feature list 2',
                                                'feature list 3',
                                                'feature list 4',
                                                'feature list 5',
                                                'faq',
                                                'faq 2',
                                            ]))
                                            <th scope="col">{{ __('Image') }}</th>
                                        @endif
                                        <th scope="col">Title</th>
                                        @if (!in_array($feature->feature_type, ['partner', 'category', 'trending blog']))
                                            <th scope="col">Text</th>
                                        @endif
                                        <th scope="col">Serial Number</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($section_element as $key => $item)
                                        <tr>
                                            <td><input type="checkbox" name="ids[]"
                                                    value="{{ base64_encode($item->id) }}"></td>
                                            @if (
                                                !in_array($feature->feature_type, [
                                                    'intro',
                                                    'intro 2',
                                                    'feature list 2',
                                                    'feature list 3',
                                                    'feature list 4',
                                                    'feature list 5',
                                                    'faq',
                                                    'faq 2',
                                                ]))
                                                <td><img src="{{ asset($item->image) }}" alt="" width="100">
                                                </td>
                                            @endif
                                            <td>{{ $item->title }}</td>
                                            @if (!in_array($feature->feature_type, ['trending blog']))
                                                <td>{{ $item->text }}</td>
                                            @endif
                                            <td>{{ $item->serial_number }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('seller.feature_page.section_element.edit', $item->id) }}">
                                                    <span class="btn-label">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    Edit
                                                </a>
                                                <a onclick="return confirm('Are you sure to delete?')"
                                                    class="btn btn-danger btn-sm editbtn"
                                                    href="{{ route('seller.feature_page.section_element.delete', $item->id) }}"><span
                                                        class="btn-label"><i class="fas fa-trash"></i></span>Remove</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </form>
                                <tfoot>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        @if (
                                            !in_array($feature->feature_type, [
                                                'intro',
                                                'intro 2',
                                                'feature list 2',
                                                'feature list 3',
                                                'feature list 4',
                                                'feature list 5',
                                                'faq',
                                                'faq 2',
                                            ]))
                                            <th scope="col">{{ __('Image') }}</th>
                                        @endif
                                        <th scope="col">Title</th>
                                        @if (!in_array($feature->feature_type, ['partner', 'category', 'trending blog']))
                                            <th scope="col">Text</th>
                                        @endif
                                        <th scope="col">Serial Number</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="ajaxFormLoad" class="modal-form"
                    action="{{ route('seller.feature_page.section_element.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $feature->id }}">
                    <input type="hidden" name="type" value="{{ $feature->feature_type }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Section Element</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (
                            !in_array($feature->feature_type, [
                                'intro',
                                'intro 2',
                                'feature list 2',
                                'feature list 3',
                                'feature list 4',
                                'feature list 5',
                                'faq',
                                'faq 2',
                            ]))
                            <div class="form-group">
                                <label for="">{{__('Image')}} ** </label>
                                <input type="file" class="form-control" name="image" placeholder="Enter Image">
                                <p id="errimage" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="">{{__('Title')}} ** </label>
                            <input type="text" class="form-control" name="title" placeholder="Enter Title">
                            <p id="errtitle" class="mb-0 text-danger em"></p>
                        </div>

                        @if (!in_array($feature->feature_type, ['partner', 'category', 'trending blog']))
                            <div class="form-group">
                                <label for="">Text </label>
                                <textarea rows="5" class="form-control" name="text"placeholder="Enter Text"></textarea>
                                <p id="errtext" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        {{-- <div class="form-group">
                        <label for="">{{__('Video URL')}}</label>
                        <input type="text" class="form-control" name="video_url" placeholder="Enter Text">
                        <p id="errvideo_url" class="mb-0 text-danger em"></p>
                    </div> --}}

                        @if (in_array($feature->feature_type, [
                                'hero slide',
                                'hero slide 2',
                                'hero slide 3',
                                'intro',
                                'intro 2',
                                'click action',
                                'subscribe',
                                'product',
                            ]))
                            <div class="form-group">
                                <label for="">{{__('Button Text')}} </label>
                                <input type="text" class="form-control" name="btn_text"
                                    placeholder="Enter Button Text">
                                <p id="errbtn_text" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        @if (
                            !in_array($feature->feature_type, [
                                'feature list',
                                'feature list 2',
                                'feature list 3',
                                'feature list 4',
                                'feature list 5',
                                'faq',
                                'faq 2',
                                'service banner',
                            ]))
                            <div class="form-group">
                                <label for="">{{__('Button URL')}} </label>
                                <input type="text" class="form-control ltr" name="btn_url"
                                    placeholder="Enter Button URL">
                                <p id="errbtn_url" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        @if (in_array($feature->feature_type, ['product']))
                            <div class="form-group">
                                <label for="">{{__('Button Text 2')}}</label>
                                <input type="text" class="form-control" name="btn_text_1"
                                    placeholder="Enter Button Text 2">
                                <p id="errbtn_text_1" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Button URL 2')}}</label>
                                <input type="text" class="form-control ltr" name="btn_url_1"
                                    placeholder="Enter Button URL 2">
                                <p id="errbtn_url_1" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="">{{__('Serial Number')}} **</label>
                            <input type="number" class="form-control ltr" name="serial_number"
                                value="{{ $section_element->count() + 1 }}" placeholder="Enter Serial Number">
                            <p id="errserial_number" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" id="basicbtn" class="btn btn-primary">{{__('Submit')}}</button>
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
    <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/color.js') }}"></script>
    <script>
        CKFinder.setupCKEditor();
    </script>
    <script type="text/javascript">
        if ($('input[name=feature_type]:checked')) {
            var check = $('input[name=feature_type]:checked');
            if (check.length > 0) {
                var arr_data_type = JSON.parse(check.attr('data-type'));
                jQuery.each(arr_data_type, function(index, value) {
                    var active_data = $("#data-type").attr('data-active');
                    var checked = value == active_data ? 'checked' : '';
                    var data_type = '<li class="list-inline-item me-1 mb-2"><label for="data_type-' + index +
                        '"><input ' + checked + ' hidden id="data_type-' + index +
                        '" type="radio" class="input-checked" value="' + value +
                        '" name="data_type"><span class="btn btn-outline-secondary rounded">' + (value == 'input' ?
                            'Section Element' : value) + '</span></label></li>';
                    $("#data-type").append(data_type);
                });
            }
        }

        $(document).ready(function() {
            $('input[type=radio][name=feature_type]').change(function() {
                var type_check = $('input[name=data_type]:checked');
                $("#data-type").children().remove();
                var check = $('input[name=feature_type]:checked');
                if (check.attr('data-category')) {
                    $('input[value=' + check.attr('data-category') + '][name=category]').prop('checked',
                        true);
                }
                if (check.length > 0) {
                    var arr_data_type = JSON.parse(check.attr('data-type'));
                    jQuery.each(arr_data_type, function(index, value) {
                        var checked = value == type_check.val() ? 'checked' : '';
                        var data_type =
                            '<li class="list-inline-item me-1 mb-2"><label for="data_type-' +
                            index + '"><input ' + checked + ' hidden id="data_type-' + index +
                            '" type="radio" class="input-checked" value="' + value +
                            '" name="data_type"><span class="btn btn-outline-secondary rounded">' +
                            (value == 'input' ? 'Section Element' : value) + '</span></label></li>';
                        $("#data-type").append(data_type);
                    });

                    var img_style = check.next().html();
                    $("#img-style").css("display", "block");
                    $('#img-style').html(img_style);
                }

            });
        });
    </script>
@endpush
