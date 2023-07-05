@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li><a href="{{url('/'.permalink_type('blog').'')}}">{{__('Blog')}}</a></li>
                <li class="active">{{$blog->title}}</li>
            </ul>
        </div>
    </div>
</div>

<div class="blog-area pt-60 pb-60">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
               <div class="blog-details-wrapper">
                    <div class="blog-details-top">    
                        <div class="blog-meta-2 mb-3">
                            <h1 class="blog-title">{{$blog->title}}</h1>
                            <p class="blog-description">{{$blog->excerpt}}</p>
                            <ul>
                                @if($blog->bcategory)
                                <li><a href="/{{permalink_type('blog')}}/category/{{$blog->bcategory->slug}}">{{$blog->bcategory->name ?? ''}}</a></li>
                                @endif
                                <li>{{date_format($blog->created_at, 'Y-m-d')}}</li>
                            </ul>
                        </div>               
                        <div class="blog-details-img">
                            <img alt="" src="{{ asset(ImageThumnail($blog->image ?? 'uploads/default.png')) }}">
                        </div>
                        <div class="toc_body mt-4">
                            <h3 class="mb-2 d-inline">{{__('Table of Contents')}}</h3>
                            [<a class="toc_toggle" href="#">{{__('Hide')}}</a>]
                            <div id="toc">
                            </div>
                        </div>
                        <div class="blog-details-content" id="blog-details-content">
                            {!!html_entity_decode($blog->content)!!}
                        </div>
                    </div>
                    <div class="tag-share">
                        <div class="dec-tag">
                            <ul>
                                @foreach($keyword as $key=>$k)
                                    <li><a href="/blog?keyword={{trim($k)}}">{{trim($k)}} {{count($keyword) - 1 == $key ? '' : ','}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="blog-share">
                            <span>{{__('Share')}} :</span>
                            <div class="share-social">
                                <ul>
                                    <li>
                                        <a class="facebook" href="//www.facebook.com/sharer/sharer.php?u=">
                                            <i class="icon-social-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="twitter" href="#">
                                            <i class="icon-social-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="instagram" href="#">
                                            <i class="icon-social-instagram"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="next-previous-post btn-style-1">
                        @if($previous)
                            <a class="p-2 px-3" href="/{{permalink_type('blog_detail')}}/{{$previous->slug}}"> <i class="fa fa-angle-left"></i> {{__('Prev Post')}}</a>
                        @endif

                        @if($next)
                            <a class="p-2 px-3" href="/{{permalink_type('blog_detail')}}/{{$next->slug}}"> <i class="fa fa-angle-right"></i> {{__('Next Post')}} </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
                    <div class="sidebar-widget mb-40">
                        <h4 class="sidebar-widget-title">{{__('Search')}} </h4>
                        <div class="sidebar-search">
                            <form class="sidebar-search-form" action="/{{permalink_type('blog')}}">
                                <input type="text" value="{{request()->input('keyword')}}" name="keyword" placeholder="{{__('Search Post')}}">
                                <button>
                                    <i class="icon-magnifier"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Categories')}} </h4>
                        <div class="shop-catigory">
                            <ul>
                                @foreach ($blogs_category as $key => $blog_category)
                                    <li><a @if($blog_category->id == $blog->category_id) class="filter-item" @endif href="/{{permalink_type('blog')}}/category/{{$blog_category->slug}}">{{$blog_category->name}}</a></li>
				                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">
                        <h4 class="sidebar-widget-title">{{__('Random Posts')}} </h4>
                        <div class="recent-post">
                            @foreach($blogs_random as $blog)
                            <div class="single-sidebar-blog">
                                <div class="sidebar-blog-img">
                                    <a href="/{{permalink_type('blog_detail')}}/{{$blog->slug}}"><img src="{{ asset($blog->image ?? 'uploads/default.png') }}" alt=""></a>
                                </div>
                                <div class="sidebar-blog-content">
                                    <h5><a href="/{{permalink_type('blog_detail')}}/{{$blog->slug}}">{{$blog->title}}</a></h5>
                                    <span>{{date_format($blog->created_at, 'Y-m-d')}}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
<script type="text/javascript">
    var toc = $('#toc').initTOC({
        selector: 'h1, h2, h3, h4, h5, h6',
        scope: '#blog-details-content',
        overwrite: false,
        prefix: 'toc',
        depth: 2
    });
    
    if(toc.children().children().length == 0) {
        $('.toc_body').hide();
    }
    $('.toc_toggle').click(function(e){
        e.preventDefault();
        $('#toc').toggle('slow');
        if($(this).text() == 'Hide') {
            $(this).text('Show');
        } else {
            $(this).text('Hide');
        }
    });
</script>
@endpush
