@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Service Banner'])
@endsection
@section('content')
@if(Session::has('error'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('error') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-lg-4">     
				<div class="float-left mb-2">
					<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">{{ __('Create New') }}</button>				
				</div>
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
		<div class="table-responsive custom-table">
			<table class="table">
				<thead>
					<tr>
						<th class="am-title">{{ __('Image') }}</th>
						<th class="am-title">{{ __('Title') }} \ {{ __('Url') }}</th>
						<th>{{ __('Language') }}</th>
						<th class="text-right">{{ __('Created At') }}</th>
						<th class="text-right">{{ __('Action') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($posts as $row)
					<?php $content = $row->excerpt ? json_decode($row->excerpt->content) : [];?>
					<tr>
						<td class="text-left"><img src="{{ asset($row->name) }}" height="100"></td>
						<td class="text-left">{!! isset($content->title) ? $content->title . '<br/>' : '' !!}
						    {!! isset($content->title_2) ? $content->title_2 . '<br/>' : '' !!}
						    {!! isset($content->title_3) ? $content->title_3 . '<br/>' : '' !!}
						    URL: {{ $row->slug }}</td>
						<td>
		                    @foreach(json_decode($row->lang_id) ?? [] as $lang)
		                    	<span class="badge badge-sm badge-info mb-1">{{language_name($lang)}}</span>
		                    @endforeach
		                </td>
						<td class="text-right">{{ $row->updated_at->diffForHumans() }}</td>
						<td class="text-right">
						    <a href="javascript:void(0);" id="editBtn" data-toggle="modal" data-target="#editModal" data-id="{{$row->id}}" data-url="{{$row->slug}}" data-title="{{$content->title ?? ''}}" data-title2="{{$content->title_2 ?? ''}}" data-title3="{{$content->title_3 ?? ''}}" data-btntext="{{$content->btn_text ?? ''}}" class="btn btn-warning"><i class="fa fa-pen"></i></a>
						    <a href="{{ route('seller.ad.destroy',$row->id) }}" class="btn btn-danger  cancel"><i class="fa fa-trash"></i></a>
						    </td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{ __('Add New Ads') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{ route('seller.ads.store') }}" class="basicform_with_reload">
					@csrf
					
					<input type="hidden" name="type" value="{{$type}}" />
					<div class="form-group">
						<label>{{ __('Select Image') }}</label>
						<input type="file" accept="Image/*" name="file" required="" class="form-control"> 
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
						<label>{{ __('Title')}} 1 (*)</label>
						<input type="text" required name="title" class="form-control"> 
					</div>
					<div class="form-group">
						<label>{{ __('Title')}} 2</label>
						<input type="text"  name="title_2" class="form-control"> 
					</div>
					<div class="form-group">
						<label>{{ __('Title')}} 3</label>
						<input type="text"  name="title_3" class="form-control"> 
					</div>
					<div class="form-group">
						<label>{{ __('Button Text') }}</label>
						<input type="text"  name="btn_text" class="form-control"> 
					</div>					
					<div class="form-group">
						<label>{{ __('Url') }}</label>
						<input type="text"  name="url" value="#" class="form-control"> 
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary basicbtn">{{ __('Submit') }}</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Banner') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="PUT" action="" class="basicform_with_reload" id="editForm">
				    @method('PUT')
					@csrf
					<div class="form-group">
						<label>{{ __('Select Image') }}</label>
						<input type="file" accept="Image/*" name="file" class="form-control"> 
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
						<label>{{ __('Title')}} 1 (*)</label>
						<input type="text" required id="edit_title" name="title" class="form-control"> 
					</div>
					<div class="form-group">
						<label>{{ __('Title')}} 2</label>
						<input type="text" id="edit_title_2" name="title_2" class="form-control"> 
					</div>
					<div class="form-group">
						<label>{{ __('Title')}} 3</label>
						<input type="text" id="edit_title_3" name="title_3" class="form-control"> 
					</div>
					<div class="form-group">
						<label>{{ __('Button Text') }}</label>
						<input type="text" id="edit_btn_text" name="btn_text" class="form-control"> 
					</div>					
					<div class="form-group">
						<label>{{ __('Url') }}</label>
						<input type="text" id="edit_url" name="url" value="#" class="form-control"> 
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary basicbtn">{{ __('Submit') }}</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
    $(document).on('click','#editBtn',function(){
        var id = $(this).data('id');
        var action = '{{ route('seller.ads.update', '') }}' + '/' + id;
        
        $('#editForm').attr('action', action);
        
        $('#edit_title').val($(this).data('title'));
        $('#edit_title_2').val($(this).data('title2'));
        $('#edit_title_3').val($(this).data('title3'));
        $('#edit_url').val($(this).data('url'));
        $('#edit_btn_text').val($(this).data('btntext'));
    });
</script>
@endpush