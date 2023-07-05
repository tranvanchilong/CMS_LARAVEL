@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements;
    }
    elseif($item->data_type=='testimonial'){
        foreach ($testimonials as $key => $value) {
            $data_item['title']=$value->name;
            $data_item['text']=$value->content;
            $data_item['image']=$value->image;
            $data_item['btn_text']='';
            $data_item['btn_url']='/testimonial/'.$value->id;
            $data_item['rank']=$value->rank;
            array_push($list_data,(object)$data_item);
        }
    }
@endphp
<section class="pt-25 pb-25 testimonial default-dots" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
            </div>
        </div>
        <div class="testimonial-wrap-2 nav-style-1 dot-style-2 dot-style-2-position-2">
            @if(count($list_data)>0)
            @foreach ($list_data as $key => $data)
            <div class="item-inner p-3">
                <div class="card">
                    <div class="card-body">
                        <blockquote class="icon mb-0">
                            <p>{{ $data->text }}</p>
                            <div class="d-flex align-items-center testimonial-thumb">
                                <img class="rounded rounded-circle w-12" src="{{ $data->image ? asset($data->image) : 'https://ui-avatars.com/api/?name='.$data->title.'&background=random&length=1&color=#fff' }}" alt="">
                                <div class="info">
                                    <h4 class="mb-1">{{ $data->title }}</h4>
                                    <p class="mb-0">{{ $data->rank ?? '' }}</p>
                                </div>
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>