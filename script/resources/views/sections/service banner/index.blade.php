@if($item->data_type=='input')
    <section class="pt-25 pb-25" style="background: {{$item->background_color}}">
        <div class="service-area">
            <div class="container">
                <div class="row">
                    @if ($item->section_elements->count()>0)
                    @foreach ($item->section_elements as $key => $section_element)
                    @if($key==4) @break @endif
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 service-border-1">
                        <div class="single-service-wrap-2 mb-30">
                            <div class="service-icon-2"><img src="{{asset($section_element->image)}}"></div>
                            <div class="service-content-2">
                                <h3>{{($section_element->title)}}</h3>
                                <p>{{($section_element->text)}}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@elseif($item->data_type=='service banner')
    <section class="pt-25 pb-25" style="background: {{$item->background_color}}">
        <div class="service-area">
            <div class="container">
                <div class="row" id="service-area">

                </div>
            </div>
        </div>
    </section>
@endif