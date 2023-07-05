@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Testimonial')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60 testimonial">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
                <div class="row">
                    @foreach($testimonials as $row)
                        <div class="col-lg-4 col-md-6 col-12 item-inner p-3">
                            <div class="card">
                                <div class="card-body">
                                    <blockquote class="icon mb-0">
                                        <p>{{ $row->content }}</p>
                                        <div class="d-flex align-items-center testimonial-thumb">
                                            <img class="rounded-circle w-12" src="{{ $row->image ? asset($row->image) : 'https://ui-avatars.com/api/?name='.$row->name.'&background=random&length=1&color=#fff' }}" alt="">
                                            <div class="info">
                                                <h4 class="mb-1">{{ $row->name }}</h4>
                                                <p class="mb-0">{{ $row->rank }}</p>
                                            </div>
                                        </div>
                                    </blockquote>
                                </div>
                            </div>
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
