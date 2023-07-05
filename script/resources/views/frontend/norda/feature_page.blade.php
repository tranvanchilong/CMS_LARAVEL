@extends('frontend.norda.layouts.feature_page')
@section('content')
@if ($page->header_status == 1)
    @include('frontend/norda/layouts/header')
@endif
@if ($menu_fp)
    <section>
        <div class="container">
            <div class="row">
                <div class="main-menu main-menu-padding-1 main-menu-lh-2 px-3">
                    <nav>
                        <ul>
                            {{ ThemeMenuFp($menu_fp, 'frontend.norda.components.menu') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endif
@foreach ($feature as $key => $item)
    @php
        $list_data=[];
    @endphp
    @if(check_file_in_sections($item->feature_type))
        @include('sections/'.$item->feature_type.'.index')
    @endif
@endforeach

@if($page->footer_status == 1)
    @include('frontend/norda/layouts/footer')
@endif
@endsection
@push('js')
{{--    <script src="{{ asset('frontend/norda/js/category.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('frontend/norda/js/booking.js') }}"></script>
    <script src="{{ asset('frontend/norda/js/time.js') }}"></script>
    <script>
       $('.pagination a').unbind('click').on('click', function(e) {
             e.preventDefault();
             var page = $(this).attr('href').split('page=')[1];
             getPosts(page);
       });

       function getPosts(page)
       {
            $.ajax({
                 type: "GET",
                 url: '?page='+ page
            })
            .success(function(data) {
                 $('body').html(data);
            });
       }
    });
    </script>
@endpush
