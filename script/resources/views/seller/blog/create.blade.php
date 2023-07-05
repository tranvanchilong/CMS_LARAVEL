@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Blog')])
@endsection
@section('content')
<div class="row">
	<div class="col-lg-9">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Add new Blog') }}</h4>
		    </div>
			<div class="card-body">
				<form method="post" action="{{ route('seller.blog.store') }}" enctype="multipart/form-data" id="ajaxFormLoad">
					@csrf
					<div class="custom-form pt-20">

						<div class="form-group">
							<label>{{ __('Blog Media') }}</label>
							<input type="file" name="file" class="form-control"> 
							<p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
							<p class="em text-danger mb-0" id="errfile"></p>
						</div>

						<div class="form-group">
							<label>{{ __('Languages') }}</label>
							<select name="lang_id[]" multiple  class="form-control select2 multislect">
				                @foreach(languages() ?? [] as $key => $row)                                              
				                    <option value="{{ $row }}">{{ $key }}</option>
				                @endforeach
				             </select>
						</div> 

						<div class="form-group">
							<label>{{ __('Blog Title') }}</label>
							<input type="text" name="title" class="form-control" placeholder="Blog Title"> 
							<p id="errtitle" class="mb-0 text-danger em"></p>
						</div> 
						
						<div class="form-group">
							<label>{{ __('Blog Description') }}</label>
							<textarea type="text"  name="excerpt" class="form-control"> </textarea>
						</div>

						<div class="form-group">
							<label>{{ __('Blog Category') }}</label>
							<select class="form-control selectric" name="category" id="category">
				            	<option value="">{{ __('None') }}</option>
				            	@foreach($bcategory as $bcate)
				            	    <option value="{{ $bcate->id }}">{{ $bcate->name }}</option>
				            	@endforeach
				            </select>
						</div> 

						<div class="form-group">
							<label>{{ __('Blog Content') }}</label>
							<textarea type="text" name="content" class="form-control content" required></textarea>
						</div>
						
						<div class="form-group">
							<label>{{ __('Featured') }}</label>
							<select class="form-control" name="featured">
								<option value="0">{{ __('None') }}</option>
								<option value="1">{{ __('Trending Blog') }}</option>								
							</select>
						</div>

						<div class="form-group">
							<label>{{ __('Meta Description') }}</label>
							<textarea type="text"  name="meta_description" class="form-control"> </textarea>
						</div>

						<div class="form-group">
							<label>{{ __('Meta Keywords') }}</label>
							<input type="text"  name="meta_keyword" class="form-control">
						</div>
						
					</div>
				</div>
			</div>

		</div>
		<div class="col-lg-3">
			<div class="single-area">
				<div class="card">
					<div class="card-body">
						
						
						<div class="btn-publish">
							<button type="submit" class="btn btn-primary col-12"><i class="fa fa-save"></i> {{ __('Save') }}</button>
						</div>
					</div>
				</div>
			</div>
		<div class="single-area">
				<div class="card sub">
					<div class="card-body">
						<h5>{{ __('Status') }}</h5>
						<hr>
						<select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="status">
							<option value="1">{{ __('Published') }}</option>
							<option value="2">{{ __('Draft') }}</option>

						</select>
					</div>
				</div>
			</div>
		</div>



	<input type="hidden" name="type" value="1">
	<input type="hidden"  name="post_type" value="blog">
</form>
@endsection
@push('js')
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush