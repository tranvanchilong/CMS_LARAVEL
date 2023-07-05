@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Contact Page')])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Contact Page') }}</h4><br>
       
      </div>
      <div class="card-body">
        <form class="basicform_with_reload" enctype="multipart/form-data" action="{{ route('seller.contact_page.update') }}" method="post">
          @csrf
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Form Title') }}*</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="title" value="{{ $contact_page->title ?? '' }}">
          </div>
        </div>
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Form Subtitle') }}*</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="subtitle" value="{{ $contact_page->subtitle ?? '' }}">
          </div>
        </div>
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Latitude')}}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="latitude" value="{{ $contact_page->latitude ?? '' }}">
          </div>
        </div>
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Longitude')}}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="longitude" value="{{ $contact_page->longitude ?? '' }}">
          </div>
        </div>
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Map Zoom')}}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="map_zoom" value="{{ $contact_page->map_zoom ?? '' }}">
          </div>
        </div>

       
       
       
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button><br>
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