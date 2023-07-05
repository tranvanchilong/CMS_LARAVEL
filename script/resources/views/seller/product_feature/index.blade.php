@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Landing Page')])
@endsection
@section('content')
<!-- <div id="blockDiv" class="hidden">
    <div class="blockPage"></div>
    <div class="blockUI">
        <img class="height" src="{{ asset('uploads/loading-gif.gif') }}" width="50">
        <p>{{__('The system is importing data')}}<br>{{__('Please wait')}}</p>

    </div>
</div> -->
@if(Session::has('success'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<h4 class="mb-0">{{ Session::get('success') }}</h4>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
@if(Session::has('error'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('error') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
@if(Session::has('warning'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<h4 class="mb-0">{{ Session::get('warning') }}</h4>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
<div class="card"  >
    <div class="card-body">
        <div class="row mb-30">
            <div class="col-lg-6">
                <h4>{{ __('Landing Page') }}</h4>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
        <br>
        <div class="card-action-filter">
            <form method="post" class="basicform_with_reload" action="{{ route('seller.feature_page.destroys') }}">
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
                            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i>{{ __('Add Landing Page')}}</a>
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
                            <th scope="col">{{ __('Slug') }}</th>
                            <th scope="col">{{ __('Meta Description') }}</th>
                            <th scope="col">{{ __('Is Home Page') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $key => $page)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ base64_encode($page->id) }}"></td>
                            <td>{{$page->title}}</td>
                            <td>{{$page->slug}}</td>
                            <td>{{$page->meta_description}}</td>
                            <td>
                                @if ($page->is_home_page == 1)
                                <a class="btn btn-success btn-sm" href="{{route('seller.feature_page.homepage',[$page->id,'is_home_page'=>1])}}">Active</a>
                                @else
                                <a class="btn btn-danger btn-sm" href="{{route('seller.feature_page.homepage',[$page->id,'is_home_page'=>0])}}">Deactive</a>
                                @endif
                            </td>
                            <td>
                                @if ($page->status == 1)
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-success">Active</span></h5>
                                @else
                                    <h5 class="d-inline-block"><span class="badge badge-sm badge-danger">Deactive</span></h5>
                                @endif
                            </td>
                            <td>
                                @foreach(json_decode($page->lang_id) ?? [] as $lang)
                                <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                                @endforeach
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm editbtn" href="{{url('/'.permalink_type('fp').'',$page->slug)}}">
                                  <span class="btn-label"><i class="fas fa-eye"></i></span>{{ __('View') }}</a>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.feature_page.detail', $page->id)}}">
                                  <span class="btn-label"><i class="fas fa-list"></i></span>{{ __('Sections') }}</a>
                                <a class="btn btn-primary btn-sm editbtn" href="{{route('seller.feature_page.edit', $page->id)}}">
                                  <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                                <a onclick="return confirm('Are you sure to delete?')" class="btn btn-danger btn-sm editbtn" href="{{route('seller.feature_page.delete', $page->id)}}">
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
                        <th scope="col">{{ __('Slug') }}</th>
                        <th scope="col">{{ __('Meta Description') }}</th>
                        <th scope="col">{{ __('Is Home Page') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th>{{ __('Language') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            {{ $pages->links('vendor.pagination.bootstrap-4') }}

        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="ajaxFormLoad" class="modal-form" action="{{route('seller.feature_page.store')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Landing Page')}}</h5>
                    <a data-toggle="modal" href="#modalLanding" class="btn btn-primary" style="margin: -4px 0px 0px 10px;">{{__('Import Template Page')}}</a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Title') }} **</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter Title"/>
                        <p id="errtitle" class="mb-0 text-danger em"></p>
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
                        <label for="">{{ __('Status') }} **</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                        <p id="errstatus" class="mb-0 text-danger em"></p>
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
<div class="modal" id="modalLanding">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{__('Import Template Page')}}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                @foreach($import_page as $key => $page)
                    <div class="theme-info my-3">
                        <h5>{{$page->name}}</h5>
                        <div style="height: 410px;overflow: hidden;">
                            <img width="100%" height="auto" src="{{ asset($page->user_domain->thumbnail) }}" alt="">
                        </div>
                        <div class="p-2">
                            <a target="_blank" href="{{ $page->user_domain->full_domain ?? '' }}" class="btn btn-success"><i class="flaticon-link mr-1"></i>{{ __('View Demo') }}</a>
                        
                            <form class="ml-1 d-inline-block" method="post" action="{{route('seller.feature_page.import_page',$page->user_domain->id)}}">
                                @method('POST')
                                @csrf
                                <input type="hidden" name="import" value="1" />
                                <button type="submit" class="btn btn-primary col-12">{{ __('Select and Import template page data') }}</button>
                            </form>
                    
                        </div>
                    </div>
                @endforeach                   
            </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:void(0)" class="btn btn-primary" data-dismiss="modal">{{ __('Close') }}</a>
        </div>
      </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
