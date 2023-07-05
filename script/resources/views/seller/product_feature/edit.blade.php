@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Landing Page')])
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="{{route('seller.feature_page.edit',$page->id)}}" id="ajaxForm" >
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title d-inline-block">{{__('Edit Landing Page')}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label>{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="title" value="{{$page->title}}" placeholder="Enter Title"/>
                                <p id="errtitle" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Languages') }}</label>           
                                <select name="lang_id[]" multiple  class="form-control select2 multislect">
                                    @foreach(languages() ?? [] as $key => $row)                                              
                                        <option value="{{ $row }}" {{in_array($row, json_decode($page->lang_id)?? []) ? 'selected' : ''}}>{{ $key }}</option>
                                    @endforeach
                                </select>
                                <p id="errlang_id" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Slug') }}</label>
                                <input type="text" class="form-control" name="slug" value="{{$page->slug}}" placeholder="Enter Slug"/>
                                <p id="errslug" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="meta_description" placeholder="Enter Meta Description">{{$page->meta_description}}</textarea>
                                <p id="errmeta_description" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="meta_keyword" value="{{$page->meta_keyword}}" placeholder="Enter Meta Keywords" />
                                <p id="errmeta_keyword" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">{{ __('Hide/Show Header') }}</label>
                                <select id="header_status" name="header_status" class="form-control">
                                    <option {{$page->header_status == '0' ? 'selected' : '' }} value="0">Hide</option>
                                    <option {{$page->header_status == '1' ? 'selected' : '' }} value="1">Show</option>
                                </select>
                                <p id="errheader_status" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">{{ __('Hide/Show Footer') }}</label>
                                <select id="footer_status" name="footer_status" class="form-control">
                                    <option {{$page->footer_status == '0' ? 'selected' : '' }} value="0">Hide</option>
                                    <option {{$page->footer_status == '1' ? 'selected' : '' }} value="1">Show</option>
                                </select>
                                <p id="errfooter_status" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">{{ __('Status') }} **</label>
                                <select id="status" name="status" class="form-control">
                                    <option {{$page->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{$page->status == '0' ? 'selected' : '' }} value="0">Deactive</option>
                                </select>
                                <p id="errstatus" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
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
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush