@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Logo & Favicon')])
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Logo & Favicon') }}</h4><br>
      </div>
      <div class="card-body">
        <div class="col-lg-6 offset-lg-3">
          <form id="ajaxFormLoad" enctype="multipart/form-data" action="{{route('seller.logo_favicon.update')}}" method="post">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Logo') }}</label>
              <br>
              <div class="thumb-preview">
                  <img width="100" src="{{ asset('uploads/'.auth()->id().'/logo.png') }}?v={{time()}}" alt="">
              </div>
              <br>
              <input type="file" name="logo" accept="image/*" class="form-control">
              <p class="text-warning mb-0">PNG images are allowed</p>
              <p class="em text-danger mb-0" id="errlogo"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Favicon') }}</label>
              <br>
              <div class="thumb-preview">
                  <img width="100" src="{{ asset('uploads/'.auth()->id().'/favicon.ico') }}?v={{time()}}" alt="">
              </div>
              <br>
              <input type="file" name="favicon" accept="image/*" class="form-control">
              <p class="text-warning mb-0">ICO images are allowed</p>
              <p class="em text-danger mb-0" id="errfavicon"></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button>
            </div>
          </form>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush