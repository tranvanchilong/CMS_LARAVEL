<section class="pt-25 pb-25 blog" style="background: {{$item->background_color}}">
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
        @if($item->data_type=='blog')
        <div class="blog-two-wrapper res-padding-reverse" id="load_data">
            <div class="row">
                @foreach ($blogs_new as $key => $data)
                <div class="col-lg-4">
                    <div class="single-trendy mt-30">
                        <div class="trendy-thumb">
                            <img src="{{ asset($data->image ?? 'uploads/default.png') }}" alt="">
                        </div>
                        <div class="trendy-contents">
                            <div class="popular-stories-tag mt-30 mb-10">
                                <span class="tags"><a href="#"> <strong>{{$data->user->name}}</strong></a> </span>
                                <span class="tags">{{date_format($data->created_at, 'M d, Y')}}</span>
                                @if($data->bcategory->name ?? '')
                                <span class="tags"><a href="{{!empty($data->category_id) ? '/'.permalink_type('blog').'?category_id='.$data->category_id : '' ?? '#'}}">{{$data->bcategory->name}}</a></span>
                                @endif
                            </div>
                            <h2 class="trendy-title"> <a href="{{$data->btn_url ?? '#'}}">{{$data->title}}</a> </h2>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {!! $blogs_new->links() !!}
        </div>
        @elseif($item->data_type=='input')
        <div class="blog-two-wrapper res-padding-reverse">
            <div class="row">
                @foreach ($item->section_elements as $key => $data)
                <div class="col-lg-4">
                    <div class="single-trendy mt-30">
                        <div class="trendy-thumb">
                            <img src="{{ asset($data->image ?? 'uploads/default.png') }}" alt="">
                        </div>
                        <div class="trendy-contents">
                            <div class="popular-stories-tag mt-30 mb-10">
                                <span class="tags"><a href="#"> <strong>{{$page->user->name}}</strong></a> </span>
                                <span class="tags">{{DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('M d, Y')}}</span>
                                @if($data->title)
                                <span class="tags"><a href="{{$data->category_url ?? '#'}}">{{$data->title}}</a></span>
                                @endif
                            </div>
                            <h2 class="trendy-title"> <a href="{{$data->btn_url ?? '#'}}">{{$data->text}}</a> </h2>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>