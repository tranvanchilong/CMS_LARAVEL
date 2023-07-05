@php
    $progressSteps = [
        1 => [
            'lang' => 'lms/public.basic_information',
            'icon' => 'basic-info'
        ],

        2 => [
            'lang' => 'lms/public.images',
            'icon' => 'images'
        ],

        3 => [
            'lang' => 'lms/public.about',
            'icon' => 'about'
        ],

        4 => [
            'lang' => 'lms/public.educations',
            'icon' => 'graduate'
        ],

        5 => [
            'lang' => 'lms/public.experiences',
            'icon' => 'experiences'
        ],

        6 => [
            'lang' => 'lms/public.occupations',
            'icon' => 'skills'
        ],

        7 => [
            'lang' => 'lms/public.identity_and_financial',
            'icon' => 'financial'
        ]
    ];

    if(!$user->isUser()) {
        $progressSteps[8] =[
            'lang' => 'lms/public.zoom_api',
            'icon' => 'zoom'
        ];

        $progressSteps[9] =[
            'lang' => 'lms/public.extra_information',
            'icon' => 'extra_info'
        ];
    }

    $currentStep = empty($currentStep) ? 1 : $currentStep;
@endphp


<div class="webinar-progress d-block d-lg-flex align-items-center p-15 panel-shadow bg-white rounded-sm">

    @foreach($progressSteps as $key => $step)
        <div class="progress-item d-flex align-items-center">
            <a href="@if(!empty($organization_id)) /lms/panel/manage/{{ $user_type ?? 'instructors' }}/{{ $user->id }}/edit/step/{{ $key }} @else /lms/panel/setting/step/{{ $key }} @endif" class="progress-icon p-10 d-flex align-items-center justify-content-center rounded-circle {{ $key == $currentStep ? 'active' : '' }}" data-toggle="tooltip" data-placement="top" title="{{ trans($step['lang']) }}">
                <img src="/assets/lms/assets/default/img/icons/{{ $step['icon'] }}.svg" class="img-cover" alt="">
            </a>

            <div class="ml-10 {{ $key == $currentStep ? '' : 'd-lg-none' }}">
                <h4 class="font-16 text-secondary font-weight-bold">{{ trans($step['lang']) }}</h4>
            </div>
        </div>
    @endforeach
</div>
