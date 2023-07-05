@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Social Login')])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Google') }}</h4><br>
       
      </div>
      <div class="card-body">
        <form class="basicform_with_reload" enctype="multipart/form-data" action="{{ route('seller.social_login.update') }}" method="post">
          @csrf
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3 text-primary" >{{ __('Client_ID') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="client_id" placeholder="289.apps.googleusercontent.com" value="{{ $social_login->client_id ?? '' }}">
          </div>
        </div>

        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3 text-primary" >{{ __('Client Secret') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="client_secret" placeholder="GOCSPX-" value="{{ $social_login->client_secret ?? '' }}">
          </div>
        </div>
      
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3 text-primary">{{ __('Status') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="form-control selectric" name="status">
              @if(!empty($social_login))
              <option value="1" @if($social_login->status  == 1) selected="" @endif>{{ __('Active') }}</option>
              <option value="0"  @if($social_login->status  == 0) selected="" @endif>{{ __('Deactive') }}</option>
              @else
              <option value="1">{{ __('Active') }}</option>
              <option value="0" >{{ __('Deactive') }}</option>
              @endif
            </select>
          </div>
        </div>
         
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary basicbtn" type="submit">{{ __('Save') }}</button><br>
          </div>
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