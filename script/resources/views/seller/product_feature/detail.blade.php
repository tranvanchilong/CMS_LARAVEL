@extends('layouts.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
@endpush
@section('head')
@include('layouts.partials.headersection',['title'=>__('Page Section')])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="card-title d-inline-block">{{__('Page Section')}}</h4>
                <a class="btn btn-primary float-right d-inline-block" href="{{route('seller.feature_page.index')}}">
                    <span class="btn-label">
                        <i class="fas fa-backward"></i>
                    </span>
                    {{__('Back')}}
                </a>                    
            </div>
        </div>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.feature_page.detail.destroys') }}">
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
                                <button type="submit" class="btn btn-primary btn-lg ml-2">{{ __('Apply') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="add-new-btn">
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Section') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">{{ __('Title') }}</th>
                            <th scope="col">{{ __('Section') }}</th>
                            <th scope="col">{{ __('Hide Title') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Serial Number') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_feature as $key => $feature)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ base64_encode($feature->id) }}"></td>
                                <td>{{$feature->feature_title}}</td>
                                <td>
                                    <div class="category-section d-inline-block">
                                        <div class="rounded img-svg p-2 mb-2">
                                            <img width="100" class="rounded-0 img-fluid" src="{{ asset(find_style($feature->feature_type) ?? 'uploads/default.png') }}" alt="">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($feature->hide_title == 1)
                                        <a class="btn btn-warning btn-sm" href="{{route('seller.feature_page.detail.hide_title', [$feature->id,0])}}">Hide</a>
                                    @else
                                        <a class="btn btn-info btn-sm" href="{{route('seller.feature_page.detail.hide_title', [$feature->id,1])}}">Show</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($feature->feature_status == 1)
                                        <h5 class="d-inline-block"><span class="badge badge-success">Active</span></h5>
                                    @else
                                        <h5 class="d-inline-block"><span class="badge badge-danger">Deactive</span></h5>
                                    @endif
                                </td>
                                <td>{{ $feature->serial_number }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.feature_page.detail.edit', $feature->id)}}">
                                      <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Customize Data') }}</a>
                                    <a onclick="return confirm('Are you sure to delete?')" class="btn btn-danger btn-sm editbtn" href="{{route('seller.feature_page.detail.delete', $feature->id)}}">
                                      <span class="btn-label"><i class="fas fa-trash"></i></span>{{ __('Remove') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </form>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Section') }}</th>
                        <th scope="col">{{ __('Hide Title') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $list_feature->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<form id="ajaxFormRedirect" class="modal-form" action="{{route('seller.feature_page.detail.store', $page->id)}}" method="post">
    @csrf
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Section') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">{{ __('Title') }} **</label>
                    <input type="text" class="form-control" name="feature_title" placeholder="Enter Title" value="">
                    <p id="errfeature_title" class="mb-0 text-danger em"></p>
                </div>
        
                <div class="form-group">
                    <label>{{ __('Subtitle') }}</label>
                    <input type="text" class="form-control" name="feature_subtitle" placeholder="Enter Subtitle"/>
                    <p id="errfeature_subtitle" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                    <label for="">{{ __('Position Title') }}</label>
                    <div>
                        <span class="px-3">
                            <input type="radio" id="position1" name="feature_position" value="0">
                            <label for="position1">Left</label>
                        </span>
                        <span class="px-3">
                            <input type="radio" id="position2" name="feature_position" value="1" checked="">
                            <label for="position2">Mid</label>
                        </span>
                        <span class="px-3">
                            <input type="radio" id="position3" name="feature_position" value="2">
                            <label for="position3">Right</label>
                        </span>
                    </div>
                    <p id="errfeature_position" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                    <label for="">{{ __('Background Color') }}</label>
                    <input type="text" name="background_color" class="form-control rgcolorpicker">
                    <p id="errbackground_color" class="mb-0 text-danger em"></p>
                </div>
    
                <div class="form-group">
                    <label>{{ __('Section Element Data') }} **</label>
                    <div>
                        <a id="btn-style" data-toggle="modal" href="#myModal2" class="btn btn-primary">{{ __('Choose') }}</a>
                        <div class="row">
                            <div class="category-section col-12 col-md-6 mt-2">
                                <div id="img-style" class="rounded img-svg p-2 mb-2" style="display: none">

                                </div>
                            </div>
                        </div>
                        <p id="errfeature_type" class="mb-0 text-danger em"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Data Source') }} **</label>
                    <ul id="data-type" class="list-inline mb-0">
                    </ul>
                    <p id="errdata_type" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Button Text') }}</label>
                    <input type="text" name="btn_text" class="form-control">
                    <p id="errbtn_text" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Button URL') }}</label>
                    <input type="text" name="btn_url" class="form-control">
                    <p id="errbtn_url" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Status') }} **</label>
                    <select id="status" name="feature_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
                    </select>
                    <p id="errstatus" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Serial Number') }} **</label>
                    <input type="number" class="form-control ltr" name="serial_number" value="{{$list_feature->count()+1}}" placeholder="Enter Serial Number">
                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                    <p class="text-warning"><small>{{__('The higher the serial number is, the later the slider will be shown')}}</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="myModal2">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Section Element Data') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div><div class="container"></div>
        <div class="modal-body">
            <div class="form-group">
                <label>{{ __('Category') }} **</label>
                <ul class="nav nav-tabs d-inline-block category-section">
                    @foreach(get_category_sections() as $key => $category)
                    <li class="list-inline-item w-auto">
                        <a data-toggle="tab" href="#category{{$key}}" class="{{$key == 0 ? 'active show' : ''}}">
                            <label class="click-category" for="category-section-{{$key}}">
                                <input hidden id="category-section-{{$key}}" type="radio" class="" value="{{$key}}" name="category" {{$key == 0 ? 'checked' : ''}}>
                                <span class="btn btn-outline-secondary rounded">{{$category['title']}}</span>
                            </label>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach(get_category_sections() as $key => $category)
                    <div id="category{{$key}}" class="tab-pane fade in {{$key == 0 ? 'active show' : ''}}">
                        <div class="form-group">
                            <label>{{ __('Style') }} **</label>
                            <ul class="category-section list-unstyled">
                                <li class="mega-menu-content">
                                    <ul id="list-style" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-6 list-unstyled">
                                        @foreach($category['style'] as $k => $style)
                                            <li class="col col-6 col-md-4">
                                                <label for="style-section-{{$k}}">
                                                    <input data-category="{{$key}}" data-type="{{json_encode($style['data_type'])}}" hidden id="style-section-{{$k}}" type="radio" class="input-checked" value="{{$style['key']}}" name="feature_type">
                                                    <div class="rounded img-svg p-2 mb-2"><img class="rounded-0 img-fluid" src="{{ asset(find_style($k) ?? 'uploads/default.png')}}" alt=""></div>
                                                </label>
                                                <ul id="review-input" class="list-unstyled">
                                                    <label class="text-danger font-weight-bold">Data support: **</label>
                                                    @foreach($style['data_type'] as $value)
                                                    <li class="mb-1 mr-1 text-capitalize">{{$value=='input' ? 'Section Element' : $value}}{{$loop->last ? '' : ', '}}</li>
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
          <a id="choose-style" href="javascript:void(0)" class="btn btn-primary" data-dismiss="modal">{{ __('Done') }}</a>
        </div>
      </div>
    </div>
</div>
</form>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/color.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('input[type=radio][name=feature_type]').change(function() {
            var type_check = $('input[name=data_type]:checked');
            $("#data-type").children().remove();
            var check = $('input[name=feature_type]:checked');
            if(check.attr('data-category')){
                $('input[value='+check.attr('data-category')+'][name=category]').prop('checked', true);
            }
            if(check.length>0){
                var arr_data_type = JSON.parse(check.attr('data-type'));
                jQuery.each(arr_data_type,function( index,value ) {
                    var checked = value==type_check.val() ? 'checked' : '';
                    var data_type = '<li class="list-inline-item me-1 mb-2"><label for="data_type-'+index+'"><input '+checked+' hidden id="data_type-'+index+'" type="radio" class="input-checked" value="'+value+'" name="data_type"><span class="btn btn-outline-secondary rounded">'+(value=='input' ? 'Section Element' : value)+'</span></label></li>';
                    $("#data-type").append(data_type);
                });

                var img_style = check.next().html(); 
                $("#img-style").css("display", "block");
                $('#img-style').html(img_style);
            }
        });
    });
    
    $('#myModal2').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    })
</script>
@endpush
