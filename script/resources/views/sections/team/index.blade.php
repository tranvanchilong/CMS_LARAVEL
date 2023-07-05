@php
    if($item->data_type=='input'){
        $list_data=$item->section_elements;
    }
    elseif($item->data_type=='team'){
        foreach ($teams as $key => $value) {
            $data_item['title']=$value->name;
            $data_item['text']=$value->rank;
            $data_item['image']=$value->image;
            $data_item['btn_text']='';
            $data_item['btn_url']='/'.permalink_type('team_detail').'/'.$value->id;
            $data_item['facebook']=$value->facebook;
            $data_item['twitter']=$value->twitter;
            $data_item['instagram']=$value->instagram;
            $data_item['linkedin']=$value->linkedin;
            array_push($list_data,(object)$data_item);
        }
    }
@endphp
<section class="pb-25 pt-25 team default-dots" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="team-content col-lg-12">
                @include('frontend.norda.components.feature_page.section_title')
            </div>
            <div class="col-lg-12">
                <div class="team-slider-active-4 nav-style-1 dot-style-2 dot-style-2-position-2">
                    @if(count($list_data)>0)
                    @foreach ($list_data as $key => $data)
                        <div class="text-center p-3">
                            <div class="image-member">
                                <a href="{{$data->btn_url ?? '#'}}">
                                    <img src="{{asset($data->image ?? 'uploads/default.png')}}" class="img-fluid lazy" alt="">
                                </a>
                            </div>
                            <h4 class="mt-3 mb-2"><a href="{{$data->btn_url ?? '#'}}">{{($data->title)}}</a></h4>
                            <p class="text-slide-img">{{$data->text}}</p>
                            <nav class="nav social justify-content-center text-center mb-0">
                                <a class="m-2" href="{{$data->facebook ? $data->facebook : ($data->btn_url ?? '#') }}">
                                <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="m-2" href="{{$data->twitter ? $data->twitter : ($data->btn_url ?? '#') }}"><i class="fab fa-twitter"></i></a>
                                <a class="m-2" href="{{$data->instagram ? $data->instagram : ($data->btn_url ?? '#') }}">
                                <i class="fab fa-instagram"></i>
                                </a>
                                <a class="m-2" href="{{$data->linkedin ? $data->linkedin : ($data->btn_url ?? '#') }}">
                                <i class="fab fa-linkedin"></i>
                                </a>
                            </nav>
                        </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>