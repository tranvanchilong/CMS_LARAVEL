@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Blog Categories')])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        @php
        $url=my_url();
        @endphp
       

          <form method="post" action="{{ route('seller.bcategories.destroys') }}" class="basicform_with_reload">
            @csrf
            <div class="row">           
              <div class="col-lg-4"> 
                <div class="float-left mb-1">
                  <div class="input-group">
                    <select class="form-control" name="type">
                      <option value="" >{{ __('Select Action') }}</option>
                      <option value="delete" >{{ __('Delete Permanently') }}</option>

                    </select>
                    <div class="input-group-append">                                            
                      <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </div>
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
                <div class="float-right mb-1">
                  <a href="{{ route('seller.bcategory.create') }}" class="btn btn-primary">{{ __('Create Blog Category') }}</a>
                </div>
              </div>
            </div>
        
          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th><input type="checkbox" class="checkAll"></th>

                  <th><i class="fa fa-image"></i></th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Url') }}</th>
                  <th>{{ __('Featured') }}</th>
                  <th>{{ __('Language') }}</th>
                  <th>{{ __('Created at') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                  <td><input type="checkbox" name="ids[]" value="{{ base64_encode($row->id) }}"></td>
                  <td><img src="{{ asset($row->preview->content ?? 'uploads/default.png') }}" height="50"></td>
                  <td>{{ $row->name  }}</td>
                  <td><a href="{{ $url.'/blog/category/'.$row->slug }}">{{ $url.'/blog/category/'.$row->slug }}</a></td>
                  <td>@if($row->featured==1) <span class="badge badge-success  badge-sm">Yes</span> @else <span class="badge badge-danger  badge-sm">No</span> @endif</td>
                  <td>
                      @foreach(json_decode($row->lang_id) ?? [] as $lang)
                      <span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
                      @endforeach
                  </td>
                  <td>{{ $row->created_at->diffforHumans()  }}</td>
                  <td>
                    <a href="{{ route('seller.bcategory.edit',$row->id) }}" class="btn btn-primary btn-sm text-center"><i class="far fa-edit"></i></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                 <th><input type="checkbox" class="checkAll"></th>
                 <th><i class="fa fa-image"></i></th>
                <th>{{ __('Name') }}</th>
                  <th>{{ __('Url') }}</th>
                  <th>{{ __('Featured') }}</th>
                  <th>{{ __('Language') }}</th>
                  <th>{{ __('Created at') }}</th>
                  <th>{{ __('Action') }}</th>
               </tr>
             </tfoot>
           </table>
           {{ $posts->links('vendor.pagination.bootstrap-4') }}
         </div>
       </form>
     </div>
   </div>
 </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush