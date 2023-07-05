<section class="pt-25 pb-25" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-1">
                <img class="rounded img-fluid lazy mb-3" src="{{asset($item->image ?? 'uploads/default.png')}}" alt="">
            </div>
            <div class="mt-2 col-lg-6">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="row">
                    @if ($item->section_elements_content->count()>0)
                        @foreach ($item->section_elements_content as $key => $section_element)
                            @if($key==6) @break @endif
                            <div class="d-flex mb-4">
                                <div>
                                    <div class="number-intro text-center">
                                        <h4 class="text-white">0{{$loop->iteration}}</h4>
                                    </div>
                                </div>
                                <div class="mt-1 ml-3">
                                    <h4 class="mb-2">{{$section_element->title}}</h4>
                                    <p>{!!nl2br($section_element->text)!!}</p>
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