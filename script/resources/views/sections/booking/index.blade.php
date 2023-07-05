<section class="pt-25 pb-25" style="background: {{$item->background_color}}">
    <div class="booking">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @include('frontend.norda.components.feature_page.section_title')
                </div>
            </div>
            <form action="/booking" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="border-booking">
                            <div class="mb-4">
                                <div class="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="">
                                                <label>{{__('Phone')}} **</label>
                                                <input type="text" class="form-control" name="mobile" placeholder="{{__('Enter Phone')}}" required/>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="">
                                                <label>{{__('Full Name')}} **</label>
                                                <input type="text" class="form-control" name="name" placeholder="{{__('Enter Full Name')}}" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($locations->count()>0)
                            <div class="mb-4">
                                <div class="">
                                    <div class="">
                                        <label>{{__('Location')}} **</label>
                                        <div class="booking-active-5 nav-style-1 dot-style-2 dot-style-2-position-2">
                                            @foreach ($locations as $key => $location)
                                                <label class="p-1 mx-2 text-center" for="location{{$location->id}}">
                                                    <figure class="image-booking">
                                                        <img class="rounded img-fluid" src="{{asset($location->image ?? 'uploads/default.png')}}" alt="">
                                                    </figure>
                                                    <h5>{{$location->name}}</h5>
                                                    <p>{{$location->address}}</p>
                                                    <input type="radio" id="location{{$location->id}}" name="location_id" value="{{$location->id}}" required="required">
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if ($booking_category->count()>0 || $booking_service->count()>0)
                            <div class="mb-4">
                                <div class="">
                                    <div class="">
                                        <label>{{__('Sevice')}} **</label>
                                        <div class="booking-active-5 nav-style-1 dot-style-2 dot-style-2-position-2">
                                            @if($booking_category->count()>0)
                                                @foreach ($booking_category as $key => $category)
                                                <label class="p-2 mx-2 text-center" for="category{{$category->id}}">
                                                    <figure class="image-booking">
                                                        <img class="rounded img-fluid" src="{{ asset($category->preview->content ?? 'uploads/default.png') }}" alt="">
                                                    </figure>
                                                    <h5>{{$category->name}}</h5>
                                                    <input {{$loop->first ? 'checked' : ''}} data-service="{{json_encode($category->services)}}" type="radio" id="category{{$category->id}}" name="category_service_id" value="{{$category->id}}" required="required">
                                                </label>
                                                @endforeach
                                            @else
                                                @foreach ($booking_service as $key => $category)
                                                <label class="p-2 mx-2 text-center" for="category{{$category->id}}">
                                                    <figure class="image-booking">
                                                        <img class="rounded img-fluid" src="{{ asset($category->image ?? 'uploads/default.png') }}" alt="">
                                                    </figure>
                                                    <h5>{{$category->name}}</h5>
                                                    <input {{$loop->first ? 'checked' : ''}} type="radio" id="category{{$category->id}}" name="service_id" value="{{$category->id}}" required="required">
                                                </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="">
                                        <div id="services-booking">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="mb-4">
                                <div class="">
                                    <div class="">
                                        <div class="booking-page">
                                            <label>{{__('Date')}} **</label>
                                            <div class="item_demo demohetcho"><span></span>Hết chỗ</div>
                                            <div class="item_demo democoncho"><span></span>Còn chỗ</div>
                                            <div class="item_demo demodangchon"><span></span>Đang chọn</div>
                                        </div>
                                        <div class="boder-label day mb-2">
                                            @foreach ($booking_dates as $key => $date)
                                                <label class="text-center d-inline-block" for="day{{$loop->index}}">
                                                    <input data-hour="{{json_encode($date)}}" {{$loop->first ? 'checked' : ''}} class="input-day" hidden type="radio" id="day{{$loop->index}}" name="day" value="{{$key}}" required="required">
                                                    <div class="bg-checked">
                                                        <span>{{date('l',strtotime($key))}}</span>
                                                        <p>{{date('d/m',strtotime($key))}}</p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="boder-label hour d-flex flex-wrap" id="hours-booking">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="">
                                    @if(!empty($booking_setting->value))
                                        <p class="mb-3">{{$booking_setting->value}}</p>
                                    @endif
                                    <div class="text-center">
                                        <a href="/" class="btn-main">{{__('Back')}}</a>
                                        <button type="submit" class="btn-main">{{__('Submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>