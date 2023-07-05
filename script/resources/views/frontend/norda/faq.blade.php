@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Faq')}} </li>
            </ul>
        </div>
    </div>
</div>


<div class="blog-area pt-60 pb-60 faq">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12 mb-0">
                        <div class="accordion" id="accordionFaqOne">
                            @foreach ($faqs as $row)
                                <div class="accordion-item mb-3">
                                    <h4 class="accordion-title active-header collapsed" data-toggle="collapse" aria-expanded="false" data-target="#accordion-{{$row->id}}">
                                        {{$row->question}}
                                    </h4>
                                    <div id="accordion-{{$row->id}}" class="collapse" data-parent="#accordionFaqOne">
                                        <div class="accordion-content">{!!nl2br(($row->answer))!!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
