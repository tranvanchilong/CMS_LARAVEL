<section class="pt-45 pb-45" style="background-image: url({{asset($item->section_elements->first()->image ?? '')}}); background-size:cover;background-attachment: fixed;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                @if (!empty($item->section_elements->first()->title))
                <h1>{{($item->section_elements->first()->title)}}</h1>
                @endif
                @if (!empty($item->section_elements->first()->text))
                    <p>{!!nl2br(($item->section_elements->first()->text))!!}</p>
                @endif
                @if (!empty($item->section_elements->first()->btn_text) && !empty($item->section_elements->first()->btn_url))
                <div class="btn-style-1">
                    <a class="p-3 px-4" href="{{$item->section_elements->first()->btn_url}}">{{($item->section_elements->first()->btn_text)}}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>