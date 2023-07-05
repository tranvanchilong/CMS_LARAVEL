@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Booking Setting'])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Booking Setting') }}</h4><br>
      </div>
      <div class="card-body">
        <form class="basicform" enctype="multipart/form-data" action="{{route('seller.booking_setting.update')}}" method="post">
          @csrf
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Note') }}*</label>
          <div class="col-sm-12 col-md-7">
            <textarea rows="5" class="form-control" name="content"placeholder="Enter Content">{{$booking_setting->value ?? ''}}</textarea>
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