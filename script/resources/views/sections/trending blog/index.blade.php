<section class="pt-25 pb-25 trending-blog" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content">
                    @if($item->hide_title==0)
                    <div class="left-content">
                        <div class="heading">
                            <span>{{($item->feature_title)}}</span>
                        </div>
                        <div class="marquee">
                            <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="5"><span>☞</span> {{($item->feature_subtitle)}}</marquee>
                        </div>
                    </div>
                    <div class="right-content">
                        <span class="date-now">{{date_format(now(), 'M d, Y')}}  </span><span class="time-now"></span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@if($item->data_type=='input'){
<section class="pt-25 pb-25 trending-blog" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row py">
            <div class="col-lg-6 r-p">
                <div class="intro-carousel hero-slider-active nav-style-1 dot-style-2 dot-style-2-trending">
                    @foreach($item->section_elements as $data)
                    <a href="{{$data->btn_url ?? '#'}}" class="trending big">
                        <div class="content-wrapper">

                            <img src="{{ asset($data->image ?? 'uploads/default.png') }}" alt="Blog" width="100%">
                            <div class="inner-content">
                                <span>
                                    <h4 class="title" > {{($data->title)}}</h4>
                                </span>
                                <ul class="post-meta">
                                    <li>
                                        <span> {{DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('M d, Y')}} </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-3 r-p mycol">
                @foreach($item->section_elements_blog_old as $data)
                <a href="{{$data->slug ?? '#'}}" class="trending-news animation-trending">
                    <div class="content-wrapper">

                        <img src="{{ asset($data->image ?? 'uploads/default.png') }}" alt="Blog">
                        <div class="inner-content">
                            <span>
                                <h4 class="title">{{($data->title)}}</h4>
                            </span>
                            <ul class="post-meta">
                                <li>
                                    <span> {{DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('M d, Y')}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="col-lg-3 r-p mycol">
                @foreach($item->section_elements_blog_new as $data)
                <a href="{{$data->slug ?? '#'}}" class="trending-news animation-trending">
                    <div class="content-wrapper">
                        <img src="{{ asset($data->image ?? 'uploads/default.png') }}" alt="Blog">
                        <div class="inner-content">
                            <span>
                                <h4 class="title">{{($data->title)}}</h4>
                            </span>
                            <ul class="post-meta">
                                <li>
                                    <span> {{DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('M d, Y')}}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@elseif($item->data_type=='trending blog')
<section class="pt-25 pb-25 trending-blog" style="background: {{$item->background_color}}">
    <div class="container">
        <div class="row py">
            <div class="col-lg-6 r-p">
                <div class="intro-carousel hero-slider-active nav-style-1 dot-style-2 dot-style-2-trending">
                    @foreach($trending_blogs as $trending_blog)
                    <a href="{{'/'.permalink_type('blog_detail').'/'.$trending_blog->slug ?? '#'}}" class="trending big">
                        <div class="content-wrapper">
                            @if($trending_blog->bcategory->name ?? '')
                            <div class="tag" style="background: #74b9ff;position: absolute;top: 15px;left: 15px;/* background: #9C27B0; */color: #fff;font-size: 13px;font-weight: 600;padding: 1px 10px;z-index: 1;">
                                {{$trending_blog->bcategory->name}}
                            </div>
                            @endif
                            <img src="{{ asset($trending_blog->image ?? 'uploads/default.png') }}" alt="Blog" width="100%">
                            <div class="inner-content">
                                <span>
                                    <h4 class="title" > {{($trending_blog->title)}}</h4>
                                </span>
                                <ul class="post-meta">
                                    <li>
                                        <span> {{date_format($trending_blog->created_at, 'M d, Y')}} </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-3 r-p mycol">
                @foreach($trending_blogs_old as $trending_blog)
                <a href="{{'/'.permalink_type('blog_detail').'/'.$trending_blog->slug ?? '#'}}" class="trending-news animation-trending">
                    <div class="content-wrapper">
                        @if($trending_blog->bcategory->name ?? '')
                        <div class="tag" style="background: #74b9ff;position: absolute;top: 15px;left: 15px;/* background: #9C27B0; */color: #fff;font-size: 13px;font-weight: 600;padding: 1px 10px;z-index: 2;">
                        {{$trending_blog->bcategory->name}}
                        </div>
                        @endif
                        <img src="{{ asset($trending_blog->image ?? 'uploads/default.png') }}" alt="Blog">
                        <div class="inner-content">
                            <span>
                                <h4 class="title">{{($trending_blog->title)}}</h4>
                            </span>
                            <ul class="post-meta">
                                <li>
                                    <span> {{date_format($trending_blog->created_at, 'M d, Y')}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="col-lg-3 r-p mycol">
                @foreach($trending_blogs_new as $trending_blog)
                <a href="{{'/'.permalink_type('blog_detail').'/'.$trending_blog->slug ?? '#'}}" class="trending-news animation-trending">
                    <div class="content-wrapper">
                        @if($trending_blog->bcategory->name ?? '')
                        <div class="tag" style="background: #74b9ff;position: absolute;top: 15px;left: 15px;/* background: #9C27B0; */color: #fff;font-size: 13px;font-weight: 600;padding: 1px 10px;z-index: 2;">
                        {{$trending_blog->bcategory->name}}
                        </div>
                        @endif
                        <img src="{{ asset($trending_blog->image ?? 'uploads/default.png') }}" alt="Blog">
                        <div class="inner-content">
                            <span>
                                <h4 class="title">{{($trending_blog->title)}}</h4>
                            </span>
                            <ul class="post-meta">
                                <li>
                                    <span> {{date_format($trending_blog->created_at, 'M d, Y')}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif