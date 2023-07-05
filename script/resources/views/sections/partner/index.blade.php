@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements;
    }
    elseif($item->data_type=='partner'){
        foreach ($partners as $key => $value) {
            $data_item['title']=$value->name;
            $data_item['text']='';
            $data_item['image']=$value->image;
            $data_item['btn_text']='';
            $data_item['btn_url']=$value->url;
            array_push($list_data,(object)$data_item);
        }
    }
@endphp
<section class="pt-25 pb-25 partner default-dots" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
                <div class="partner-active-5 nav-style-1 dot-style-2 dot-style-2-position-2">
                    @if(count($list_data)>0)
                    @foreach ($list_data as $key => $data)
                    <div class="">
                        <a href="{{$data->btn_url ?? 'javascript:void(0)'}}" target="{{$data->btn_url ? '_blank' : '_self'}}">
                            <figure class="image-partner"><img class="rounded img-fluid" src="{{asset($data->image ?? 'uploads/default.png')}}" alt=""></figure>
                        </a>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>