@if(!empty($menus))
	@php
    $mainMenus=$menus;
	$menu_type = \App\Domain::where('user_id',domain_info('user_id'))->first()->menu_type ?? 1;
  	@endphp
	@foreach ($mainMenus as $row) 
		@if (isset($row->children)) 
		<li class="has-dropdown @if(isset($row->mega_menu) && $row->mega_menu=='mega') mega-li @endif">
            <a>{{ $row->text }} <i class="fas fa-angle-down u-s-m-l-6"></i></a>
			<span class="js-menu-toggle"></span>
			@if(isset($row->mega_menu) && $row->mega_menu=='mega')
			<ul class="mega-menu">
				<div class="container">
				    <div class="row">
				    	@foreach($row->children as $childrens)
				        <div class="col-12 col-lg-4">  
				            <a class="d-flex" href="{{ url($childrens->href) }}" target="{{ $childrens->target }}">
							    <img style="object-fit: contain;" class="lazy" width="50px" height="50px" src="{{asset($childrens->image ?? 'uploads/default')}}">
							    <div class="ml-2">
							        {{ $childrens->text }}
							        <p>{{ $childrens->content ?? '' }}</p>
							    </div>
							</a>
				        </div>
				        @endforeach
				    </div>
				</div>
			</ul>
			@else
			<ul class="">
			 @foreach($row->children as $childrens)
			 @include('frontend.norda.components.menu.child', ['childrens' => $childrens])
			 @endforeach
			</ul>
			@endif
		</li>
		@else
		<li>
			<a href="{{ url($row->href) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
		</li>
		@endif			
	@endforeach
@endif