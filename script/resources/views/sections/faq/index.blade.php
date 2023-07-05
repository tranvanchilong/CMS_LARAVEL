@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements_content;
    }
    elseif($item->data_type=='faq'){
        foreach ($faqs as $key => $value) {
            $data_item['id']=$value->id;
            $data_item['title']=$value->question;
            $data_item['text']=$value->answer;
            $data_item['image']=$value->image;
            $data_item['btn_text']='';
            $data_item['btn_url']='';
            array_push($list_data,(object)$data_item);
        }
    }
@endphp
<section class="pt-25 pb-25 faq mt-20" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-1">
                <img class="rounded img-fluid lazy mb-3" src="{{asset($item->image ?? 'uploads/default.png')}}" alt="">
            </div>
            <div class="col-lg-6">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="mt-20 facilities-two">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="accordion" id="accordionFaqOne">
                                @if(count($list_data)>0)
                                @foreach ($list_data as $key => $data)
                                    @if($key==6) @break @endif
                                    <div class="accordion-item mb-3">
                                        <h4 class="accordion-title active-header collapsed" data-toggle="collapse" aria-expanded="false" data-target="#accordion-{{$data->id}}">
                                            {{$data->title}}
                                        </h4>
                                        <div id="accordion-{{$data->id}}" class="collapse" data-parent="#accordionFaqOne">
                                            <div class="accordion-content">{!!nl2br(($data->text))!!}</div>
                                        </div>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                            @include('frontend.norda.components.feature_page.section_button')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>