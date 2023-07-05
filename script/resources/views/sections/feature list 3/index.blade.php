<section class="pt-25 pb-25" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img class="rounded img-fluid lazy mb-3" src="{{asset($item->image ?? 'uploads/default.png')}}" alt="">
            </div>
            <div class="mt-2 col-lg-6">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="row">
                    @if ($item->section_elements_content->count()>0)
                        @foreach ($item->section_elements_content as $key => $section_element)
                            @if($key==6) @break @endif
                            <div class="col-lg-6 mb-30 md-mb-25 col-md-6">
                                <div class="d-flex">
                                    <span class="icon" style="font-size: 12px;color: #FBB63C;"><i style="background: #FBB63C2a;border-radius:50%;padding:5px" class="fas fa-check"></i></span>
                                    <div class="pl-3">
                                        <h4 class="mb-1">{{($section_element->title)}}</h4>
                                        <span class="text-justify">{{($section_element->text)}}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                @include('frontend.norda.components.feature_page.section_button')
            </div>
        </div>
    </div>
</section>