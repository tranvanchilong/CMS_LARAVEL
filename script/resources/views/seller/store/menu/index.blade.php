@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Menus'])
@endsection
@section('content')
<div class="card"  >
    <div class="card-body">
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.menu.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{__('Add Menu')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th>{{ __('Menu Position') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $key => $menu)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ base64_encode($menu->id) }}"></td>
                            <td>{{$menu->name}} @if($menu->feature_page)<span class="badge badge-sm badge-warning">{{$menu->feature_page->title}}</span>@endif</td>
                            <td>
                                <h5 class="d-inline-block">
                                <span class="badge badge-sm badge-success">
                                  @switch($menu->position)
                                    @case('header')
                                    {{ __('Header Menu') }}
                                    @break
                                    @case('feature_page')
                                    {{ __('Feature Page Menu') }}
                                    @break
                                    @case('left')
                                    {{ __('Footer Left Menu') }}
                                    @break
                                    @case('right')
                                    {{ __('Footer Right Menu') }}
                                    @break
                                    @case('center')
                                    {{ __('Footer Center Menu') }}
                                    @break
                                    @case('top_bar_header')
                                    {{ __('Top Bar Menu Header') }}
                                    @break
                                  @endswitch
                                </span>
                                </h5>
                            </td>
                            <td>
                                @foreach(json_decode($menu->lang_id) ?? [] as $lang)
                                <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                                @endforeach
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('seller.menu.show',$menu->id) }}">
                                 <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </form>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="checkAll"></th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th>{{ __('Menu Position') }}</th>
                        <th>{{ __('Language') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.menu.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Menu')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">              
                    <div class="form-group">
                        <label>{{ __('Name') }} **</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" required="" />
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
                        <label>{{ __('Menu Position') }}</label>              
                        <select name="position"  class="form-control" required="">
                            <option value="" disabled="" selected="">{{ __('None') }}</option>
                            <option value="header">{{ __('Header Menu') }}</option>
                            <option value="feature_page">{{ __('Feature Page Menu') }}</option>
                            <option value="left">{{ __('Footer Left Menu') }}</option>
                            <option value="center">{{ __('Footer Center Menu') }}</option>
                            <option value="right">{{ __('Footer Right Menu') }}</option>
                            <option value="top_bar_header">{{ __('TopBar Menu') }}</option>
                        </select>
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
