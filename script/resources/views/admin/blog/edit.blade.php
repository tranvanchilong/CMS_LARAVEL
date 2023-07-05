@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Blog'])
@endsection
@section('content')
<div class="row">
	<div class="col-lg-9">      
		<div class="card">
			<div class="card-header">
		        <h4>{{ __('Edit blog') }}</h4>
		    </div>
			<div class="card-body">
				<form method="post" action="{{ route('admin.blog.update',$info->id) }}" enctype="multipart/form-data">
					@csrf
					@method('PUT')

					<div class="custom-form pt-20">

						<div class="form-group">
							<label>{{ __('Blog Media') }}</label>
							<input type="file" name="file" class="form-control"> 
						</div>

						<div class="form-group">
							<label>{{ __('Blog Title') }}</label>
							<input type="text" name="title" class="form-control" required placeholder="Blog Title" value="{{$info->title}}">
						</div> 

						<div class="form-group">
							<label>{{ __('Blog Category') }}</label>
							<select class="form-control selectric" name="category" id="category">
				            	<option value="">{{ __('None') }}</option>
				            	@foreach($bcategory as $bcate)
				            	    <option {{ $bcate->id==$info->category_id ? 'selected' : ''}} value="{{ $bcate->id }}">{{ $bcate->name }}</option>
				            	@endforeach
				            </select>
						</div> 

						<div class="form-group">
							<label>{{ __('Blog Content') }}</label>
							<textarea type="text" name="content" class="form-control content" required>{!! $info->content !!}</textarea>
						</div>
						

						<div class="form-group">
							<label>{{ __('Meta Description') }}</label>
							<textarea type="text"  name="meta_description" class="form-control">{{ $info->meta_description }}</textarea>
						</div>

						<div class="form-group">
							<label>{{ __('Meta Keywords') }}</label>
							<input type="text"  name="meta_keyword" class="form-control" value="{{ $info->meta_keyword }}">
						</div>


					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h4>{{ __('Search Engine Optimization') }}</h4>
					<div class="search-engine">
						<h6 class="pt-15 blog-title-seo" id="seotitle">{{ $info->title }}</h6>
						<a href="#" class="text-success" id="seourl">{{ url('/').'/blog/'.$info->slug }}</a>
						<p id="seodescription"></p>
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
							<option value="1" @if($info->status==1) selected="" @endif>{{ __('Published') }}</option>
							<option value="2" @if($info->status==2) selected="" @endif>{{ __('Draft') }}</option>

						</select>
					</div>
				</div>
			</div>
	</div>		
		
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