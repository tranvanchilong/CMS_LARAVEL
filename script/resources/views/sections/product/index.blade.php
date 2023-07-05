<section class="pt-25 pb-25 product" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
            </div>
        </div>
        <div class="row">
            @if ($item->section_elements->count()>0)
                @foreach ($item->section_elements as $key => $section_element)
                    <div class="col-lg-6 col-md-6 product-feature-product">
                        <div class="item">
                            <div class="title">
                                <div class="thumb">
                                    <a href="{{$section_element->btn_url ?? '#'}}">
                                        <img class="img-fluid" src="{{asset($section_element->image ?? 'uploads/default.png')}}">
                                    </a>
                                </div>
                                <div>
                                    <h4 class="mt-3"><a href="{{$section_element->btn_url ?? '#'}}">{{($section_element->title)}}</a></h4>
                                </div>
                            </div>
                            <div class="content">
                                <p class="mb-3">{!!nl2br(($section_element->text))!!}</p>
                                @if (!empty($section_element->btn_text) && !empty($section_element->btn_url))
                                    <a href="{{$section_element->btn_url ?? '#'}}" class="mb-2 btn-main float-right">{{($section_element->btn_text)}}</a>
                                @endif
                                @if (!empty($section_element->btn_text_1) && !empty($section_element->btn_url_1))
                                    <a href="{{$section_element->btn_url_1 ?? '#'}}" style="line-height: 44px" class="mb-2 read-more float-left">{{($section_element->btn_text_1)}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>