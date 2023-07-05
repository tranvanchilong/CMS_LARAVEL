@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Partner')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60 partner">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
                <div class="row">
                    @foreach ($partners as $row)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 m-0 p-0">
                        <a href="{{$row->url ?? 'javascript:void(0)'}}" target="{{$row->url ? '_blank' : '_self'}}">
                            <figure class="image-partner"><img class="img-fluid" src="{{asset($row->image)}}" alt=""></figure>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
