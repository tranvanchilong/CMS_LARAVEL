@if($item->data_type=='input')
    <section class="pt-25 pb-25" style="background: {{$item->background_color}}">
        <div class="banner-area">
            <div class="container">
                <div class="row">
                    @if ($item->section_elements->count()>0)
                    @foreach ($item->section_elements as $key => $section_element)
                    @if($key==3) @break @endif
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="banner-wrap mb-10">
                            <div class="banner-img banner-img-border banner-img-zoom"><a href="{{$section_element->btn_url ?? '#'}}"><img src="{{asset($section_element->image)}}" alt=""></a></div>
                            <div class="banner-content-5">
                                <!-- <span>Cổ tròn IT</span> -->
                                <h2>{{($section_element->title)}}</h2>
                                <p>{{($section_element->text)}}</p>
                                @if(!empty($section_element->btn_text) && !empty($section_element->btn_url))
                                <div class="btn-style-4"><a href="{{$section_element->btn_url}}">{{($section_element->btn_text)}} <i class="icon-arrow-right"></i></a></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@elseif($item->data_type=='ads banner')
    <section class="pt-25 pb-25" style="background: {{$item->background_color}}">
        <div class="banner-area">
            <div class="container">
                <div class="row banner_ad">

                </div>
            </div>
        </div>
    </section>
@endif