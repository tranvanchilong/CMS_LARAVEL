@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Menus'])
@endsection
@section('content')

<div class="row">
	<div class="col-md-12">
        <form method="POST" action="{{route('seller.footer_logo.store')}}" class="basicform_with_reload">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="card-title d-inline-block">{{ __('Footer Logo & Text') }}</h4>                                            
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
                    </div>
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>              
                                <select name="lang_id[]" multiple  class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{in_array($row, json_decode($footerText->lang_id ?? '')?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Image') }} ** </label>
                                <br>
                                <div class="thumb-preview">
                                    <img width="300" src="{{ $footer_logo ? asset($footer_logo) : asset('uploads/default.png')}}" alt="Footer Logo">
                                </div>
                                <br>
                                <br>
                                <input type="file" class="form-control" name="image">
                                <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                                <p class="em text-danger mb-0" id="errimage"></p>
                            </div>
   
                            <div class="form-group">
                                <label for="">Copyright Text </label>
                                <textarea rows="5" class="form-control" name="footer_content" placeholder="Enter Content">{{$footer_text}}</textarea>
                            </div>                          
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button id="basicbtn" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
	</div>
</div>

@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush