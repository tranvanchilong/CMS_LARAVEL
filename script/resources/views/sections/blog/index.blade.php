@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements;
    }
    elseif($item->data_type=='blog'){
        foreach ($blogs as $key => $value) {
            $data_item['title']=$value->title;
            $data_item['text']=$value->excerpt;
            $data_item['image']=$value->image ?? '';
            $data_item['btn_text']='';
            $data_item['btn_url']='/'.permalink_type('blog_detail').'/'.$value->slug;
            $data_item['created_at']=$value->created_at;
            $data_item['category']=$value->bcategory->name ?? '';
            $data_item['category_url']=!empty($value->category_id) ? '/'.permalink_type('blog').'?category_id='.$value->category_id : '';
            array_push($list_data,(object)$data_item);
        }
    }
    elseif($item->data_type=='service'){
        foreach ($services as $key => $value) {
            $data_item['title']=$value->name;
            $data_item['text']=$value->content;
            $data_item['image']=$value->image;
            $data_item['btn_text']='';
            $data_item['btn_url']='/'.permalink_type('service_detail').'/'.$value->slug;
            array_push($list_data,(object)$data_item);
        }
    }
    elseif($item->data_type=='portfolio'){
        foreach ($portfolios as $key => $value) {
            $data_item['title']=$value->name;
            $data_item['text']='';
            $data_item['image']=$value->image;
            $data_item['btn_text']='';
            $data_item['btn_url']='/'.permalink_type('portfolio_detail').'/'.$value->slug;
            array_push($list_data,(object)$data_item);
        }
    }
@endphp
@if(count($list_data)>0)
<section class="pt-25 pb-25 blog default-dots" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
                {{-- @if($item->data_type=='input')
                @else
                <div class="tab-style-3">
                    <a class="p-2 px-3 active" href="/{{ $item->data_type }}">{{__('View All')}}</a>
                </div>
                @endif --}}
            </div>
        </div>
        <div class="slider-image-active nav-style-1 dot-style-2 dot-style-2-position-2">
            @foreach ($list_data as $key => $data)
                <div class="text-center p-4 {{$item->data_type=='portfolio' ? '' : 'm-3 border-slider'}}">
                    <div class="">
                        <a href="{{$data->btn_url ?? '#'}}">
                            <img src="{{ asset($data->image ?? 'uploads/default.png') }}" class="rounded img-fluid lazy image-blog" alt="">
                        </a>
                    </div>
                    @if($item->data_type=='blog')
                    <div class="mt-2">
                        @if($data->category)
                        <a href="{{$data->category_url ?? '#'}}">{{$data->category}}</a> |
                        @endif
                        <span>{{date_format($data->created_at, 'Y-m-d')}}</span>
                    </div>
                    @endif
                    <h4 class="mt-3 mb-2"><a href="{{$data->btn_url ?? '#'}}">{{($data->title)}}</a> </h4>
                    <p>
                    {!! strlen(strip_tags(html_entity_decode($data->text))) > 120 ? mb_substr(strip_tags(html_entity_decode($data->text)), 0, 120, 'utf-8') . '...' : strip_tags(html_entity_decode($data->text)) !!}
                    </p>
                    @if($item->data_type=='portfolio')
                    @else
                    <a class="read-more d-block" href="{{$data->btn_url ?? '#'}}">{{__('Learn More')}}</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif