@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements;
    }
    elseif($item->data_type=='slider'){
        foreach ($sliders as $key => $value) {
            $data_item['title']=$value['meta']->title;
            $data_item['text']=$value['meta']->title_2;
            $data_item['image']=$value['slider'];
            $data_item['btn_text']=$value['meta']->btn_text;
            $data_item['btn_url']=$value['url'];
            array_push($list_data,(object)$data_item);
        }
    }

@endphp
@if(count($list_data)>0)
<section class="pb-25" style="background: {{$item->background_color}}">
    <div class="slider-area">
        <div class="hero-slider-active-mid nav-style-1 dot-style-2 dot-style-2-position-2 dot-style-2-active-black">
            @foreach ($list_data as $key => $data)
                <div class="single-hero-slider single-animation-wrap slider-height-2 custom-d-flex custom-align-item-center bg-img hm2-slider-bg res-white-overly-xs" style="height:auto;width:100%">
                <img class="img-fluid" src="{{asset($data->image)}}" style="width:100%">
                    <div class="container" style="position: absolute;right:auto;">
                        <div class="row">
                            <div class="col-12">
                                <div class="hero-slider-content-4 slider-animated-1">
                                    @if($item->hide_title==0 && ($data->title || $data->text))
                                    <div class="section-title hero-size">
                                        <h1 class="animated">{{$data->title}}</h1>
                                        <span class="animated">{!!nl2br(($data->text))!!}</span>
                                    </div>
                                    @if (!empty($data->btn_url) && !empty($data->btn_text))
                                    <div class="btn-style-1">
                                        <a class="animated btn-1-padding-1" href="{{$data->btn_url}}">{{$data->btn_text}}</a>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
{{-- 
<link rel="stylesheet" href="{{asset('frontend/norda/css/slide-full-screen.css')}}" /> --}}