@if(!empty($menus))
	@php
    $mainMenus=$menus;
  	@endphp
	@foreach ($mainMenus as $row) 
			
		@if (isset($row->children)) 
		<li class="has-dropdown pd-10">
            <a>{{ $row->text }} <i class="fas fa-angle-down u-s-m-l-6"></i></a>
			<span class="js-menu-toggle"></span>
			<ul class="w-200">
			 @foreach($row->children as $childrens)
			 @include('frontend.norda.components.top_bar_menu.child', ['childrens' => $childrens])
			 @endforeach
			</ul>
		</li>
		@else
		<li class="pd-10">
			<a href="{{ url($row->href) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
		</li>
		@endif			
	@endforeach
@endif