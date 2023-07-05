<section class="pt-25 pb-25" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="mt-20 facilities-two">
                    <div class="row">
                        @if ($item->section_elements->count()>0)
                            @foreach ($item->section_elements as $key => $section_element)
                                @if($key==6) @break @endif
                                <div class="col-lg-4 col-md-6 col-12 mb-40 md-mb-25 px-4">
                                    <div class="d-flex">
                                        <img style="object-fit: contain;" class="lazy" width="50px" height="50px" src="{{asset($section_element->image)}}">
                                        <div class="mt-1 ml-3">
                                            <h4 class="p-1">{{$section_element->title}}</h4>
                                            <p class="p-1">{!!nl2br($section_element->text)!!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>