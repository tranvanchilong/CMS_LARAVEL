@foreach(\App\Models\LMS\WebinarExtraDescription::$types as $extraDescriptionType)
    <section class="mt-30">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="section-title after-line">{{ trans('lms/update.'.$extraDescriptionType) }}</h2>
            <button id="add_new_{{ $extraDescriptionType }}" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('lms/update.add_'.$extraDescriptionType) }}</button>
        </div>

        @php
            $webinarExtraDescriptionValues = $upcomingCourse->extraDescriptions->where('type',$extraDescriptionType);
        @endphp

        <div class="row mt-10">
            <div class="col-12">
                @if(!empty($webinarExtraDescriptionValues) and count($webinarExtraDescriptionValues))
                    <div class="table-responsive">
                        <table class="table table-striped text-center font-14">

                            <tr>
                                @if($extraDescriptionType == \App\Models\LMS\WebinarExtraDescription::$COMPANY_LOGOS)
                                    <th>{{ trans('lms/admin/main.icon') }}</th>
                                @else
                                    <th>{{ trans('lms/public.title') }}</th>
                                @endif
                                <th></th>
                            </tr>

                            @foreach($webinarExtraDescriptionValues as $extraDescription)
                                <tr>
                                    @if($extraDescriptionType == \App\Models\LMS\WebinarExtraDescription::$COMPANY_LOGOS)
                                        <td>
                                            <img src="{{get_path_lms()}}{{ $extraDescription->value }}" class="webinar-extra-description-company-logos" alt="">
                                        </td>
                                    @else
                                        <td>{{ $extraDescription->value }}</td>
                                    @endif

                                    <td class="text-right">
                                        <button type="button" data-item-id="{{ $extraDescription->id }}" data-webinar-id="{{ !empty($upcomingCourse) ? $upcomingCourse->id : '' }}" class="edit-extraDescription btn-transparent text-primary mt-1" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/webinar-extra-description/'. $extraDescription->id .'/delete', 'btnClass' => ' mt-1'])
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                @else
                    @include('lms.admin.includes.no-result',[
                         'file_name' => 'faq.png',
                         'title' => trans("lms/update.{$extraDescriptionType}_no_result"),
                         'hint' => trans("lms/update.{$extraDescriptionType}_no_result_hint"),
                    ])
                @endif
            </div>
        </div>
    </section>
@endforeach
