@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements;
    }
    elseif($item->data_type=='category'){
        foreach ($categories as $key => $value) {
            $data_item['title']=$value->name;
            $data_item['text']='';
            $data_item['image']=$value->preview->content ?? '';
            $data_item['btn_text']='';
            $data_item['btn_url']='/category/'.$value->slug.'/'.$value->id;
            array_push($list_data,(object)$data_item);
        }
    }
    elseif($item->data_type=='brand banner'){
    foreach ($brand_ads as $key => $value) {
            $data_item['title']=$value['meta']->title;
            $data_item['text']='';
            $data_item['image']=$value['image'];
            $data_item['btn_text']='';
            $data_item['btn_url']=$value['url'];
            array_push($list_data,(object)$data_item);
        }
    }
@endphp
<section class="pt-25 pb-25 category default-dots" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
                {{-- @if($item->data_type=='category')
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/{{permalink_type('shop')}}">{{__('View All')}}</a>
                </div>
                @endif --}}
                <div class="partner-active-6-1 nav-style-1 dot-style-2 dot-style-2-position-2">
                    @if(count($list_data)>0)
                    @foreach ($list_data as $key => $data)
                    <div class="">
                        <a href="{{$data->btn_url ?? '#'}}">
                            <figure class="image-partner"><img class="rounded img-fluid" src="{{asset($data->image ?? 'uploads/default.png')}}" alt=""></figure>
                        </a>
                        <h6>
                            <a href="{{$data->btn_url ?? '#'}}">{{$data->title}}</a>
                        </h6>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>