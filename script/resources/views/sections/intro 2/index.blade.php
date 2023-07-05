<section class="pt-25 pb-25 intro" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-1">
                <img class="rounded img-fluid lazy mb-3" src="{{asset($item->image ?? 'uploads/default.png')}}" alt="">
            </div>
            <div class="px-5 col-lg-6">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="">
                    <h4>{{$item->section_elements_content->first()->title ?? ''}}</h4>
                    <p>{!!nl2br($item->section_elements_content->first()->text ?? '')!!}</p>
                </div>
                @if (!empty($item->section_elements_content->first()->btn_text) && !empty($item->section_elements_content->first()->btn_url))
                <div class="btn-style-1 mt-30">
                    <a class="p-3 px-4" href="{{$item->section_elements_content->first()->btn_url}}">{{($item->section_elements_content->first()->btn_text)}}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>