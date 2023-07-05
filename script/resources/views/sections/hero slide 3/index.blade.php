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
        <div class="hero-slider-active nav-style-1 dot-style-2 dot-style-2-position-hero">
            @foreach ($list_data as $key => $data)
            <div class="single-animation-wrap">
                <div class="col-lg-6 float-left">
                    <img class="rounded img-slide" src="{{asset($data->image ?? 'uploads/default.png')}}" style="height:{{($item->hero_image_size) ?? 450}}px;">
                </div>
                <div class="container" style="max-width: 1350px">
                    <div class="row d-block">
                        <div class="col-lg-6 float-right">
                            <div class="hero-slider-content-4 slider-animated-1 text-left">
                                @if($item->hide_title==0 && ($data->title || $data->text))
                                <div class="section-title hero-size">
                                    <h1 class="animated">{{$data->title}}</h1>
                                    <span class="animated">{!!nl2br(($data->text))!!}</span>
                                </div>
                                @if (!empty($data->btn_url) && !empty($data->btn_text))
                                <div class="btn-style-1" style="text-align:justify;"><a class="animated p-3 px-4" href="{{$data->btn_url}}">{{$data->btn_text}}</a></div>
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