@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Edit Menu')])
@endsection
@section('content')

<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-20">{{ __('Menu List') }}</h4>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger none">
							<ul id="errors"></ul>
						</div>	
						<form id="frmEdit" class="form-horizontal">
							<div class="custom-form">
								<div class="form-group none">
									<input type="text" name="image" class="form-control item-menu" id="image">
								</div>
								<div class="form-group">
									<label>{{ __('Image') }}</label>
									<input type="file" class="form-control">
									<img style="display: none" height="50px" width="auto" class="mt-2" id="myImg" src="#" alt="your image" />
								</div>
								<div class="form-group">
									<label for="text">{{ __('Text') }}</label>
									<div class="input-group">
										<input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label for="content">{{ __('Content') }}</label>
									<input type="content" class="form-control item-menu" name="content" id="content" placeholder="Content" autocomplete="off">
								</div>

								<div class="form-group">
									<label for="url_configuration">{{ __('URL') }}</label>
									<select id="url_configuration" name="type_href" class="custom-select mr-sm-2 item-menu">
										<option disabled selected="" >Select</option>
										<option value="0" >{{ __('Page') }}</option>
										<option value="1" >{{ __('Custom Link') }}</option>
									</select>
								</div> 

								<div class="page none form-group pt-20">
									<select id="href" name="href" class="custom-select mr-sm-2 item-menu">
										<option selected="" value="#">___</option>
										<option value="{{permalink_type('blog')}}">Blog</option>
										<option value="{{permalink_type('shop')}}">Shop</option>
										<option value="{{permalink_type('service')}}">Service</option>
										<option value="{{permalink_type('portfolio')}}">Portfolio</option>

										<option value="{{permalink_type('career')}}">Career</option>
										<option value="{{permalink_type('team')}}">Team</option>
										<option value="{{permalink_type('package')}}">Package</option>
										<option value="{{permalink_type('faq')}}">Faq</option>

										<option value="{{permalink_type('testimonial')}}">Testimonial</option>
										<option value="{{permalink_type('partner')}}">Partner</option>
										<option value="{{permalink_type('gallery')}}">Gallery</option>
										<option value="{{permalink_type('booking')}}">Booking</option>

										<option value="{{permalink_type('contact_us')}}">Contact</option>	
										<option value="cart">Cart</option>											
										<option value="wishlist">Wishlist</option>

										@foreach($page as $row)
										<option value="{{permalink_type('page').'/'.$row->slug }}">{{ $row->title }} (Page)</option>
										@endforeach
										@foreach($landing_page as $row)
										<option value="{{permalink_type('fp').'/'.$row->slug }}">{{ $row->title }} (Landing Page)</option>
										@endforeach
									</select>
									
								</div>

								<div class="configuration none form-group">
									<input type="href" class="form-control item-menu" name="href" id="href" placeholder="Url" autocomplete="off">
								</div>
															
								<div class="form-group">
									<label for="target">{{ __('Target') }}</label>
									<select name="target" id="target" class="custom-select mr-sm-2 item-menu">
										<option value="_self">{{ __('Self') }}</option>
										<option value="_blank">{{ __('Blank') }}</option>
										<option value="_top">{{ __('Top') }}</option>
									</select>
								</div>
								<div class="form-group">
									<label for="mega_menu">{{ __('Mega Menu') }}</label>
									<select name="mega_menu" id="mega_menu" class="custom-select mr-sm-2 item-menu">
										<option value="none">{{ __('None') }}</option>
										<option value="mega">{{ __('Mega') }}</option>
									</select>
								</div>
								<div class="form-group none">
									<label for="title">{{ __('Tooltip') }}</label>
									<input type="text" name="title" class="form-control item-menu" id="title" placeholder="Tooltip">
								</div>
							</div>
						</form>
						<div class="menu-add-update d-flex">
							<button type="button" id="btnUpdate" class="btn btn-update  btn-warning text-white col-6 mr-2" disabled><i class="fas fa-sync-alt"></i> {{ __('Update') }}</button>
							<button type="button" id="btnAdd" class="btn btn-success col-6 "><i class="fas fa-plus"></i> {{ __('Add') }}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card mb-3">
			<div class="card-body">
				<form  class="basicform_with_reload" method="post" action="{{ route('seller.menu.update',$info->id) }}"> @csrf @method('PUT') <input type="hidden" name="data" id="data">
				<div class="row mb-10" >
						<div class="col-lg-4">
							<h4>{{ __('Menu structure') }}</h4>
						</div>
						<div class="col-lg-4">
							<div class="form-group">            
		                        <select name="lang_id[]" multiple  class="form-control select2 multislect">
		                            @foreach(languages() ?? [] as $key => $row)                                              
		                                <option value="{{ $row }}" {{in_array($row, json_decode($info->lang_id)??[]) ? 'selected' : ''}}>{{ $key }}</option>
		                            @endforeach
		                        </select>
		                    </div>
						</div>
						<div class="col-lg-4">
							<div class="save-menu float-right" >
								
									
									<div class="input-group mb-2">

										<input type="text" id="name" class="form-control" placeholder="Name" value="{{ $info->name }}" required="" name="name" autocomplete="off" value="">

										<div class="input-group-append">                                            
											<button class="btn btn-primary basicbtn" id="form-button"  type="submit">{{ __('Save') }}</button>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						@if($info->position=='feature_page')
						<div class="col-lg-4">
							<div class="form-group">            
		                        <select name="fp_id"  class="form-control">
		                        	<option value="" selected="">{{ __('None') }}</option>
		                            @foreach($fps as $key => $fp)                                              
		                                <option value="{{ $fp->id }}" {{ $info->fp_id==$fp->id ? 'selected' : ''}}>{{ $fp->title }}</option>
		                            @endforeach
		                        </select>
		                    </div>
						</div>
						@endif
					
					<ul id="myEditor" class="sortableLists list-group">
					</ul>	

				</div>
				</form>
			</div>
		</div>
	</div>

<input type="hidden" value="{{ $info->data }}" id="arrayjson">
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-menu-editor.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-iconpicker/js/menu.js') }}"></script>
<script>
	(function ($) {
	"use strict";
	$('#url_configuration').on('change',function(){
		var status= $(this).val();
		var configuration = $('.configuration').children();
		var page = $('.page').children();
		if(status == 1){
			
			$('.custom-form .configuration').show();
			$('.custom-form .page').hide();
			page.removeAttr('id','none');
			page.removeAttr('name','none');
			configuration.attr('id','href');
			configuration.attr('name','href');
		}
		else{
			$('.custom-form .page').show();
			$('.custom-form .configuration').hide();
			configuration.removeAttr('id','none');
			configuration.removeAttr('name','none');
			page.attr('id','href');
			page.attr('name','href');
		}
	});
	})(jQuery);
</script>
@endpush