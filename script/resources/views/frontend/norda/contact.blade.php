@extends('frontend.norda.layouts.app')
@section('content')

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Contact Us')}}</li>
            </ul>
        </div>
    </div>
</div>
<div class="contact-area pt-60 pb-60">
    <div class="container">
        <div class="contact-info-wrap-3">
            <h3>{{__('Contact Info')}}</h3>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="single-contact-info-3 text-center mb-30">
                        <i class="icon-location-pin "></i>
                        <h4>{{__('Our Address')}}</h4>
                        <p>{{$location->address}}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="single-contact-info-3 extra-contact-info text-center mb-30">
                        <ul>
                            <li><i class="icon-screen-smartphone"></i>
                                @if(isset($location->phone))
                                {{$location->phone}}
                                @else
                                (000) 000 0000
                                @endif
                            </li>
                            <li><i class="icon-envelope "></i> <a href="#"> {{ $location->email }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="single-contact-info-3 text-center mb-30">
                        <i class="icon-clock "></i>
                        <h4>{{__('Openning Hour')}}</h4>
                        <p>{{$work_times->work_time ?? __('Monday - Friday')}} {{ $work_times->open_hour ?? '8:00 AM' }} - {{ $work_times->close_hour ?? '5:00 PM' }} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="contact-area contact-area-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                @php
                    $contact_page = \App\Useroption::where('user_id',domain_info('user_id'))->where('key','contact_page')->first();
                    $contact_page = json_decode($contact_page->value ?? '')
                @endphp
                <span>{{$contact_page->title ?? __('Contact')}}</span>
                <h2>{{$contact_page->subtitle ?? __('Contact Information')}}</h2>
                <form action="{{ url('/send-contact') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="name" type="text" placeholder="{{__('Name')}}" required>
                            </div>
                            @if ($errors->has('name'))
                            <p class="text-danger mb-0">{{$errors->first('name')}}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="email" type="email" placeholder="{{__('Email')}}" required>
                            </div>
                            @if ($errors->has('email'))
                            <p class="text-danger mb-0">{{$errors->first('email')}}</p>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control" name="subject" type="text" placeholder="{{__('Subject')}}" required>
                            </div>
                            @if ($errors->has('subject'))
                            <p class="text-danger mb-0">{{$errors->first('subject')}}</p>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" name="message" id="comment" cols="30" rows="10" placeholder="{{__('Comment')}}" required></textarea>
                            </div>
                            @if ($errors->has('message'))
                            <p class="text-danger mb-0">{{$errors->first('message')}}</p>
                            @endif
                        </div>
                        @php
                            $google_captcha = \App\Useroption::where('user_id',domain_info('user_id'))->where('key','google-captcha')->first();
                            $info = json_decode($google_captcha->value ?? '');
                        @endphp
                        @if(data_get($info,'status') == 1)
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                        </div>
                        @else
                        <div class="col-md-12">
                            <div class="form-group">
                                <span class="captcha-image">{!! Captcha::img() !!}</span> &nbsp;&nbsp;
                                <button type="button" class="btn btn-danger refresh-button">
                                    &#x21bb;
                                </button>
                            </div>
                            <div class="form-group">
                                <input id="captcha" type="text" class="form-control" name="captcha" required>
                            </div>
                            @if ($errors->has('captcha'))
                            <p class="text-danger mb-0">{{$errors->first('captcha')}}</p>
                            @endif
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="btn-main-message" name="submit" type="submit" value="{{__('Send Messege')}}"></input>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="map-wrapper" style="height: 97%;position: relative;">
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{$contact_page->latitude ?? '10.766545'}},%20{{$contact_page->longitude ?? '106.703516'}}+(My%20Business%20Name)&amp;t=&amp;z={{$contact_page->map_zoom ?? '17'}}&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
<script type="text/javascript">
    $(document).ready(function() {
        $('.refresh-button').click(function() {
            $.ajax({
                type: 'get',
                url: 'refresh-captcha',
                success:function(data) {
                    $('.captcha-image').html(data.captcha);
                }
            });
        });
    });
</script>
@endpush
