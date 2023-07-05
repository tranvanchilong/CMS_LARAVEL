@extends('frontend.norda.layouts.app')
@section('content')
    <div class="breadcrumb-area bg-gray">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    <li><a href="{{ url('/' . permalink_type('course') . '') }}">{{ __('course') }}</a></li>
                    <li class="active">{{ $course->title }}</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- <div class="blog-area pt-60 pb-60">
    <div class="container">

            <div class="row">

                    <div class="col-lg-6">
                        <div class="course-details-img" >
                            <img alt="" style="object-fit: cover;" src="{{ asset($course->image ?? 'uploads/default.png') }}">
                        </div>


                    </div>
                    <div class="col-lg-6">
                        <div class="blog-details-content" style="margin-left:20px;">

                            <h1>{{$course->title}}</h1>
                            <div class="d-flex" style="justify-space-between;">
                                <span>{{ number_format($course->current_price,0,',','.') }} VND</span>
                                @if (!empty($course->previous_price))
                                    <span style="text-decoration: line-through; color: #f16001; margin-left:20px;">{{ number_format($course->previous_price,0,',','.') }} VND</span>
                                @endif
                            </div>
                            <span>{{$course->summary}}</span>
                            <div class="blog-meta-2">
                                <ul>
                                    <li>Danh mục: {{$course->category->name ?? ''}}</li>
                                    <li>{{date_format($course->created_at, 'Y-m-d')}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="tag-share" style="margin-left:20px;">
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
                    </div>


            </div>
            <div class="">
                {!!html_entity_decode($course->overview)!!}
            </div>

    </div>
</div> --}}
    <div class="product-details-area pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    @if ($course->video_link)
                        <div class="image-container">
                            <img width="100%" height="380" src="{{ asset($course->image) }}" alt="Video thumbnail"
                                data-bs-toggle="modal" data-bs-target="#videoModal">
                            <a class="play-button"><i class="fas fa-play-circle"
                                    style="font-size: 50px;color:beige; "></i></a>
                        </div>
                        {{-- <div class="modal fade" style="z-index: 99999;" id="videoModal" tabindex="-1"
                            aria-labelledby="videoModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item"
                                            src="https://www.youtube.com/embed/{{ $course->video_link }}"
                                            allowfullscreen></iframe>
                                    </div>
                                </div>

                            </div>
                        </div> --}}
                        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-content">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item"
                                                src="https://www.youtube.com/embed/{{ $course->video_link }}"
                                                allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="image-container">
                            <img width="100%" height="380" src="{{ asset($course->image) }}" alt="Video thumbnail"
                                data-bs-toggle="modal" data-bs-target="#videoModal">
                            {{-- <a class="play-button"><i class="fas fa-play-circle" style="font-size: 50px;color:beige; "></i></a> --}}
                        </div>
                    @endif

                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product-details-content pro-details-content-mrg" style="margin-left: 20px;">
                        <h1>{{ $course->title }}</h1>
                        <div>

                            <div class="pro-details-price" id="product_price" data-price="{{ $course->prices }}"
                                data-currency="{{ currency_info()['currency_icon'] ?? '' }}">
                                @if ($course->current_price == 0)
                                    <span>{{ __('Free') }}</span>
                                @else
                                    <span>{{ number_format($course->current_price, 0, ',', '.') }}
                                        VND</span>
                                @endif
                                @if (!empty($course->previous_price))
                                    <span
                                        style="text-decoration: line-through; color: #f16001; margin-left:20px;">{{ number_format($course->previous_price, 0, ',', '.') }}
                                        VND</span>
                                @endif
                            </div>

                        </div>
                        <span>{{ $course->summary }}</span>
                        <div class="product-details-meta">
                            @if ($course->category)
                                <ul>
                                    <li>Danh mục: {{ $course->category->name ?? '' }}</li>
                                    {{-- <li>{{date_format($course->created_at, 'Y-m-d')}}</li> --}}
                                </ul>
                            @endif
                        </div>
                        {{-- <div class="tag-share">
                            <div class="blog-share">
                                <span>{{ __('Share') }} :</span>
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
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="description-review-wrapper pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dec-review-topbar nav mb-45">
                        <a class="active" data-toggle="tab" href="#des-details1">{{ __('Description') }}</a>
                        <a data-toggle="tab" href="#des-details4">{{ __('Lesson') }} </a>
                        <a data-toggle="tab" href="#des-details5">{{ __('Review & Ratings') }}</a>
                        @if ($course->instructor)
                        <a data-toggle="tab" href="#des-details6">{{ __('Instructor') }}</a>
                        @endif
                    </div>
                    <div class="tab-content dec-review-bottom">
                        <div id="des-details1" class="tab-pane active">
                            <div class="description-wrap">
                                {!! html_entity_decode($course->overview) !!}
                            </div>
                        </div>
                        <div id="des-details4" class="tab-pane">
                            @foreach ($modules as $module)
                                <ul class="list-group">
                                    <li class="list-group-item collapsed bg-primary text-white mt-2"
                                        data-bs-toggle="collapse" data-bs-target="{{ '#collapse' . $module->id }}">
                                        {{ $module->name }}
                                        <i class="px-2 border rounded-lg bg-warning text-dark"
                                            style="float: right;">{{ $module->duration }}</i>
                                    </li>
                                    <div class="collapse" id="{{ 'collapse' . $module->id }}">
                                        <div class="card card-body">
                                            @php
                                                $lessons = App\Lesson::where('module_id', $module->id)->get();
                                            @endphp
                                            <ul>
                                                @foreach ($lessons as $lesson)
                                                    <li>
                                                        @if ($lesson->video_link)
                                                            <a data-bs-toggle="modal"
                                                                data-bs-target="#videoModal{{ $lesson->id }}"
                                                                href="{{ !empty($lesson->video_link) ? $lesson->video_link : asset('assets/front/video/lesson_videos/' . $lesson->video_file) }}"
                                                                class="video-popup d-flex justify-content-between">
                                                                <div class="row d-flex justify-content-center ml-2"><i
                                                                        class="fas fa-play mr-2 "></i>
                                                                    {{ $lesson->name }}</div>

                                                                <span class="duration">{{ $lesson->duration }}</span>
                                                            </a>
                                                            <div class="modal fade" style="z-index: 99999;"
                                                                id="videoModal{{ $lesson->id }}" tabindex="-1"
                                                                aria-labelledby="videoModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div
                                                                            class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item"
                                                                                src="https://www.youtube.com/embed/{{ $lesson->video_link }}"
                                                                                allowfullscreen></iframe>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @else
                                                            <a data-bs-toggle="modal"
                                                                data-bs-target="#videoModal{{ $lesson->id }}"
                                                                href="{{ !empty($lesson->video_link) ? $lesson->video_link : asset('assets/front/video/lesson_videos/' . $lesson->video_file) }}"
                                                                class="video-popup d-flex justify-content-between">
                                                                <div class="row d-flex justify-content-center ml-2"><i
                                                                        class="fas fa-play mr-2 "></i>
                                                                    {{ $lesson->name }}</div>

                                                                <span class="duration">{{ $lesson->duration }}</span>
                                                            </a>
                                                        @endif

                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </ul>
                            @endforeach


                        </div>
                        <div id="des-details5" class="tab-pane">
                            <div class="review-wrapper review-list">
                                <h2><span id="review_count">{{ count($course->reviews) }}</span> {{ __('Reviews') }}</h2>
                            </div>
                            <div class="ratting-form-wrapper">
                                <span>{{ __('Leave Your Review') }}</span>
                                <p>{{ __('Required fields are marked') }}<span>*</span></p>
                                <div class="ratting-form">
                                    <form action="{{ url('/make-review-course', $course->id) }}" method="post"
                                        id="some-form">
                                        @csrf
                                        {{-- <input type="hidden" name="user_id" value="{{$user_id}}"> --}}
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="rating-form-style mb-20">
                                                    <label>{{ __('Name') }} <span>*</span></label>
                                                    <input type="text"
                                                        value="{{ Auth::guard('customer')->user()->name ?? '' }}"
                                                        name="name" placeholder="Your name" required readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="rating-form-style mb-20">
                                                    <label>{{ __('Email Address') }} <span>*</span></label>
                                                    <input type="email" name="email" placeholder="Your email"
                                                        required readonly
                                                        value="{{ Auth::guard('customer')->user()->email ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="star-rating">
                                                    <input type="checkbox" value="5" name="rating"
                                                        id="star1"><label for="star1"></label>
                                                    <input type="checkbox" value="4" name="rating"
                                                        id="star2"><label for="star2"></label>
                                                    <input type="checkbox" value="3" name="rating"
                                                        id="star3"><label for="star3"></label>
                                                    <input type="checkbox" value="2" name="rating"
                                                        id="star4"><label for="star4"></label>
                                                    <input type="checkbox" value="1" name="rating"
                                                        id="star5"><label for="star5"></label>
                                                    @if ($errors->has('rating'))
                                                        <div class="error" style="color: red;">
                                                            {{ $errors->first('rating') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="rating-form-style mb-20">
                                                    <label>{{ __('Your review') }} <span>*</span></label>
                                                    <textarea placeholder="{{ __('Your review') }}" name="comment" maxlength="200"></textarea>
                                                    @if ($errors->has('comment'))
                                                        <div class="error" style="color: red;">
                                                            {{ $errors->first('comment') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-submit">
                                                    @if (Auth::guard('customer')->check())
                                                        <input type="submit" value="{{ __('Send Review') }}">
                                                    @else
                                                        <a href="{{ url('/user/login') }}" class="btn">
                                                            {{ __('Please Login') }}
                                                            <i class="fas fa-sign-in-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if ($course->instructor)
                        <div id="des-details6" class="tab-pane">
                            <div class="instructor-box">
                                <div class="instructor-image">
                                    <img src="{{ asset($course->instructor->image) }}" alt="">
                                </div>
                                <div class="instructor-info">
                                    <h4>{{ $course->instructor->name }}</h4>
                                    <span class="position d-block">{{ $course->instructor->rank }}</span>
                                    <p>{!! html_entity_decode($course->instructor->content) !!}</p>
                                    <div class="tag-share">
                                        <div class="blog-share">
                                            <span>{{ __('Contact') }} :</span>
                                            <div class="share-social">
                                                <ul>
                                                    <li>
                                                        <a class="facebook" href="{{ $course->instructor->facebook }}">
                                                            <i class="icon-social-facebook"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="twitter" href="{{ $course->instructor->twitter }}">
                                                            <i class="icon-social-twitter"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="instagram" href="{{ $course->instructor->instagram }}">
                                                            <i class="icon-social-instagram"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="linkedin" href="{{ $course->instructor->linkedin }}">
                                                            <i class="icon-social-linkedin"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <link rel="stylesheet" href="{{ asset('frontend/custom.css') }}">
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        CKFinder.setupCKEditor();
    </script>
    {{-- <script src="{{ asset('frontend/norda/js/category.js')}}"></script> --}}{{-- popupvideo --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var playButton = document.querySelector('.play-button');
        var videoModal = new bootstrap.Modal(document.getElementById('videoModal'));

        playButton.addEventListener('click', function() {
            videoModal.show();
        });
        var modalBackdrop = document.querySelector('.modal-backdrop');
        modalBackdrop.addEventListener('click', function() {
            videoModal.hide();
        });
    </script>
    {{-- <script>
        const stars = document.querySelectorAll(".star-rating input[type='checkbox']");

stars.forEach(function(star) {
  star.addEventListener("click", function() {
    const rating = this.value;
    for (let i = 0; i < stars.length; i++) {
      if (i < rating) {
        stars[i].checked = true;
        stars[i].classList.add("checked");
      } else {
        stars[i].checked = false;
        stars[i].classList.remove("checked");
      }
    }
  });
});
    </script> --}}
@endpush
