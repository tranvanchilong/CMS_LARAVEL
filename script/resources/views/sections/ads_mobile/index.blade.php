<section class="pt-25 pb-25" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="mt-20 text-center">
                    <img class="rounded img-fluid lazy mb-3" width="400px" src="{{asset($item->image ?? 'uploads/default.png')}}" alt="">
                    {{-- <img style="object-fit: contain;" class="lazy" width="400px" src="http://artisq.di4lsell.local/uploads/22/team/22/10/1665138007.tmp"> --}}
                    <div class="row justify-content-center">
                        @if ($item->section_elements->count()>0)
                            @foreach ($item->section_elements as $key => $section_element)
                                @if($key==2) @break @endif
                                <a class="mx-2" href="{{$section_element->btn_url ?? '#'}}" target="_blank">
                                    <img style="object-fit: contain;" class="lazy" width="150px" src="{{asset($section_element->image)}}">
                                </a>        
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>