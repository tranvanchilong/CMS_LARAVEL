@extends('layouts.app')
@push('style')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
@endpush
@section('head')
@include('layouts.partials.headersection',['title'=>__('Create Page')])
@endsection
@section('content')


<div class="row">
	<div class="col-lg-12">      
		<form enctype="multipart/form-data" method="post" action="{{ route('seller.page.store') }}">
			@csrf
			
			<div class="card">
				<div class="card-body">
					
					<div class="row">
						
						<div class="col-sm-12">
							
							{{ input((array('title'=>__('Page Title'),'name'=>'title','is_required'=>true))) }}
							<div class="form-group">
		                        <label>{{ __('Languages') }}</label>              
		                        <select name="lang_id[]" multiple  class="form-control select2 multislect">
		                            @foreach(languages() ?? [] as $key => $row)                                              
		                                <option value="{{ $row }}">{{ $key }}</option>
		                            @endforeach
		                        </select>
		                    </div>
							{{ textarea(array('title'=>__('Description'),'name'=>'excerpt')) }}

							<div class="form-group">
								<label>{{ __('Page Content') }}</label>
								<textarea type="text" name="content" class="form-control content" required></textarea>
							</div>
							<div class="form-group">
								<button class="btn btn-primary basicbtn" type="submit">{{ __('Save') }}</button>
							</div>		
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>
</form>

@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
<script>
CKFinder.setupCKEditor();
</script>
@endpush