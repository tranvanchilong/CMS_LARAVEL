<section class="pt-25 pb-25" style="background-image: url({{asset($item->section_elements->first()->image ?? '')}}); background-size:cover;background-attachment: fixed;">
    <div class="container">
        <div class="row" style="display: flow-root">
            <div class="col-lg-5 col-md-5 float-right">
                @include('frontend.norda.components.feature_page.section_title')
                <div>
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
            <div class="col-lg-7 col-md-7 float-left">
                <div id="mc_embed_signup" class="subscribe-form-2">
                    <form class="validate subscribe-form-style-2" novalidate="" method="post" action="/newsletter">
                        @csrf
                        <div class="mc-form-2">
                            <input class="email" type="email" required="" placeholder="Email Address" name="email" value="">
                            <div class="clear-2">
                                <input class="button" type="submit" name="subscribe" value="Subscribe">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>