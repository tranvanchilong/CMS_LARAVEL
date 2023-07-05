<div class="webinar-card webinar-list webinar-list-2 d-flex mt-30">
    <div class="image-box">
        @if($webinar->bestTicket() < $webinar->price)
            <span class="badge badge-danger">{{ trans('lms/public.offer',['off' => $webinar->bestTicket(true)['percent']]) }}</span>
        @elseif(empty($isFeature) and !empty($webinar->feature))
            <span class="badge badge-warning">{{ trans('lms/home.featured') }}</span>
        @elseif($webinar->type == 'webinar')
            @if($webinar->start_date > time())
                <span class="badge badge-primary">{{  trans('lms/panel.not_conducted') }}</span>
            @elseif($webinar->isProgressing())
                <span class="badge badge-secondary">{{ trans('lms/webinars.in_progress') }}</span>
            @else
                <span class="badge badge-secondary">{{ trans('lms/public.finished') }}</span>
            @endif
        @else
            <span class="badge badge-primary">{{ trans('lms/webinars.'.$webinar->type) }}</span>
        @endif

        <a href="/lms{{ $webinar->getUrl() }}">
            <img src="{{get_path_lms()}}{{ $webinar->getImage() }}" class="img-cover" alt="{{ $webinar->title }}">
        </a>

        <div class="progress-and-bell d-flex align-items-center">

            @if($webinar->type == 'webinar')
                <a href="/lms{{ $webinar->addToCalendarLink() }}" target="_blank" class="webinar-notify d-flex align-items-center justify-content-center">
                    <i data-feather="bell" width="20" height="20" class="webinar-icon"></i>
                </a>
            @endif

            @if($webinar->type == 'webinar')
                <div class="progress ml-10">
                    <span class="progress-bar" style="width: {{ $webinar->getProgress() }}%"></span>
                </div>
            @endif
        </div>
    </div>

    <div class="webinar-card-body w-100 d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/lms{{ $webinar->getUrl() }}">
                <h3 class="mt-15 webinar-title font-weight-bold font-16 text-dark-blue">{{ ($webinar->title) }}</h3>
            </a>
        </div>

        @if(!empty($webinar->category))
            <span class="d-block font-14 mt-10">{{ trans('lms/public.in') }} <a href="/lms{{ $webinar->category->getUrl() }}" target="_blank" class="text-decoration-underline">{{ $webinar->category->title }}</a></span>
        @endif

        <div class="user-inline-avatar d-flex align-items-center mt-10">
            <div class="avatar bg-gray200">
                <img src="{{ $webinar->teacher->getAvatar() }}" class="img-cover" alt="{{ $webinar->teacher->full_name }}">
            </div>
            <a href="/lms{{ $webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $webinar->teacher->full_name }}</a>
        </div>

        @include('lms.' . getTemplate() . '.includes.webinar.rate',['rate' => $webinar->getRate()])

        <div class="d-flex justify-content-between mt-auto">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <i data-feather="clock" width="20" height="20" class="webinar-icon"></i>
                    <span class="duration ml-5 font-14">{{ convertMinutesToHourAndMinute($webinar->duration) }} {{ trans('lms/home.hours') }}</span>
                </div>

                <div class="vertical-line h-25 mx-15"></div>

                <div class="d-flex align-items-center">
                    <i data-feather="calendar" width="20" height="20" class="webinar-icon"></i>
                    <span class="date-published ml-5 font-14">{{ dateTimeFormat(!empty($webinar->start_date) ? $webinar->start_date : $webinar->created_at,'j M Y') }}</span>
                </div>
            </div>

            <div class="webinar-price-box d-flex flex-column justify-content-center align-items-center">
            @if(!empty($webinar->price) and $webinar->price > 0)
                    @if($webinar->bestTicket() < $webinar->price)
                        <span class="off">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                        <span class="real">{{ handlePrice($webinar->bestTicket(), true, true, false, null, true) }}</span>
                    @else
                        <span class="real">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                    @endif
                @else
                    <span class="real font-14">{{ trans('lms/public.free') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
