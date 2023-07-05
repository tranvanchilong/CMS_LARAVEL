@extends('frontend.norda.layouts.app')

@section('content')

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{ $info->slug }} </li>
            </ul>
        </div>
    </div>
</div>

<div class="page-area pt-60 pb-60">
    <div class="container">
		{!! $info->content !!}
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
