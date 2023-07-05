@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Google Recaptcha'])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Google Recaptcha') }}</h4><br>
       
      </div>
      <div class="card-body">
        <form class="basicform" action="{{ route('seller.marketing.store') }}" method="post">
          @csrf
          <input type="hidden" name="type" value="google-recaptcha">
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Google Recaptcha Site key') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="site_key" value="{{ $info->site_key ?? '' }}" required>
          </div>
        </div>

        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Google Recaptcha Secret key') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="secret_key" value="{{ $info->secret_key ?? '' }}">
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Google Recaptcha Status') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="form-control selectric" name="status">
              <option value="1" @if(data_get($info,'status')  == 1) selected="" @endif>{{ __('Enable') }}</option>
              <option value="0"  @if(data_get($info,'status')  == 0) selected="" @endif>{{ __('Disable') }}</option>
            </select>
          </div>
        </div>
         
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary basicbtn" type="submit">{{ __('Save') }}</button><br>
            <small>{{ __('Note:') }} </small> <small class="text-danger mt-4">{{ __('After You Update Settings The Action Will Work After 5 Minutes') }}</small>
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