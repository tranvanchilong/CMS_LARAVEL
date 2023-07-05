<div class="tab-pane mt-3 fade" id="purchased_courses" role="tabpanel" aria-labelledby="purchased_courses-tab">
    <div class="row">

        @if($authUser->can('admin_enrollment_add_student_to_items'))
            <div class="col-12 col-md-6">
                <h5 class="section-title after-line">{{ trans('lms/update.add_student_to_course') }}</h5>

                <form action="/lms{{ getAdminPanelUrl() }}/enrollments/store" method="Post">

                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="form-group">
                        <label class="input-label">{{trans('lms/admin/main.class')}}</label>
                        <select name="webinar_id" class="form-control search-webinar-select2"
                                data-placeholder="{{trans('lms/panel.choose_webinar')}}">

                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class=" mt-4">
                        <button type="button" class="js-save-manual-add btn btn-primary">{{ trans('lms/admin/main.submit') }}</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="col-12">
            <div class="mt-5">
                <h5 class="section-title after-line">{{ trans('lms/update.manual_added') }}</h5>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-md">
                        <tr>
                            <th>{{ trans('lms/admin/main.class') }}</th>
                            <th>{{ trans('lms/admin/main.type') }}</th>
                            <th>{{ trans('lms/admin/main.price') }}</th>
                            <th>{{ trans('lms/admin/main.instructor') }}</th>
                            <th class="text-center">{{ trans('lms/update.added_date') }}</th>
                            <th class="text-right">{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @if(!empty($manualAddedClasses))
                            @foreach($manualAddedClasses as $manualAddedClass)

                                <tr>
                                    <td width="25%">
                                        <a href="/lms{{ !empty($manualAddedClass->webinar) ? $manualAddedClass->webinar->getUrl() : '#1' }}" target="_blank" class="">{{ !empty($manualAddedClass->webinar) ? $manualAddedClass->webinar->title : trans('lms/update.deleted_item') }}</a>
                                    </td>

                                    <td>
                                        @if(!empty($manualAddedClass->webinar))
                                            {{ trans('lms/admin/main.'.$manualAddedClass->webinar->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($manualAddedClass->webinar))
                                            {{ !empty($manualAddedClass->webinar->price) ? handlePrice($manualAddedClass->webinar->price) : '-' }}
                                        @else
                                            {{ !empty($manualAddedClass->amount) ? handlePrice($manualAddedClass->amount) : '-' }}
                                        @endif
                                    </td>

                                    <td width="25%">
                                        @if(!empty($manualAddedClass->webinar))
                                            <p>{{ $manualAddedClass->webinar->creator->full_name  }}</p>
                                        @else
                                            <p>{{ $manualAddedClass->seller->full_name  }}</p>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($manualAddedClass->created_at,'j M Y | H:i') }}</td>
                                    <td class="text-right">
                                        @if($authUser->can('admin_enrollment_block_access'))
                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/enrollments/'. $manualAddedClass->id .'/block-access',
                                                    'tooltip' => trans('lms/update.block_access'),
                                                    'btnIcon' => 'fa-times-circle'
                                                ])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <p class="font-12 text-gray mt-1 mb-0">{{ trans('lms/update.manual_add_hint') }}</p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="mt-5">
                <h5 class="section-title after-line">{{ trans('lms/update.manual_disabled') }}</h5>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-md">
                        <tr>
                            <th>{{ trans('lms/admin/main.class') }}</th>
                            <th>{{ trans('lms/admin/main.type') }}</th>
                            <th>{{ trans('lms/admin/main.price') }}</th>
                            <th>{{ trans('lms/admin/main.instructor') }}</th>
                            <th class="text-right">{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @if(!empty($manualDisabledClasses))
                            @foreach($manualDisabledClasses as $manualDisabledClass)

                                <tr>
                                    <td width="25%">
                                        <a href="/lms{{ !empty($manualDisabledClass->webinar) ? $manualDisabledClass->webinar->getUrl() : '#1' }}" target="_blank" class="">{{ !empty($manualDisabledClass->webinar) ? $manualDisabledClass->webinar->title : trans('lms/update.deleted_item') }}</a>
                                    </td>

                                    <td>
                                        @if(!empty($manualDisabledClass->webinar))
                                            {{ trans('lms/admin/main.'.$manualDisabledClass->webinar->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($manualDisabledClass->webinar))
                                            {{ !empty($manualDisabledClass->webinar->price) ? handlePrice($manualDisabledClass->webinar->price) : '-' }}
                                        @else
                                            {{ !empty($manualDisabledClass->amount) ? handlePrice($manualDisabledClass->amount) : '-' }}
                                        @endif
                                    </td>

                                    <td width="25%">
                                        @if(!empty($manualDisabledClass->webinar))
                                            <p>{{ $manualDisabledClass->webinar->creator->full_name  }}</p>
                                        @else
                                            <p>{{ $manualDisabledClass->seller->full_name  }}</p>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        @if($authUser->can('admin_enrollment_block_access'))
                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/enrollments/'. $manualDisabledClass->id .'/enable-access',
                                                    'tooltip' => trans('lms/update.enable-student-access'),
                                                ])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <p class="font-12 text-gray mt-1 mb-0">{{ trans('lms/update.manual_remove_hint') }}</p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="mt-5">
                <h5 class="section-title after-line">{{ trans('lms/panel.purchased') }}</h5>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-md">
                        <tr>
                            <th>{{ trans('lms/admin/main.class') }}</th>
                            <th>{{ trans('lms/admin/main.type') }}</th>
                            <th>{{ trans('lms/admin/main.price') }}</th>
                            <th>{{ trans('lms/admin/main.instructor') }}</th>
                            <th class="text-center">{{ trans('lms/panel.purchase_date') }}</th>
                            <th>{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @if(!empty($purchasedClasses))
                            @foreach($purchasedClasses as $purchasedClass)

                                <tr>
                                    <td width="25%">
                                        <a href="/lms{{ !empty($purchasedClass->webinar) ? $purchasedClass->webinar->getUrl() : '#1' }}" target="_blank" class="">{{ !empty($purchasedClass->webinar) ? $purchasedClass->webinar->title : trans('lms/update.deleted_item') }}</a>
                                    </td>

                                    <td>
                                        @if(!empty($purchasedClass->webinar))
                                            {{ trans('lms/admin/main.'.$purchasedClass->webinar->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($purchasedClass->webinar))
                                            {{ !empty($purchasedClass->webinar->price) ? handlePrice($purchasedClass->webinar->price) : '-' }}
                                        @else
                                            {{ !empty($purchasedClass->amount) ? handlePrice($purchasedClass->amount) : '-' }}
                                        @endif
                                    </td>

                                    <td width="25%">
                                        @if(!empty($purchasedClass->webinar))
                                            <p>{{ $purchasedClass->webinar->creator->full_name  }}</p>
                                        @else
                                            <p>{{ $purchasedClass->seller->full_name  }}</p>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($purchasedClass->created_at,'j M Y | H:i') }}</td>

                                    <td class="text-right">
                                        @if($authUser->can('admin_enrollment_block_access'))
                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/enrollments/'. $purchasedClass->id .'/block-access',
                                                    'tooltip' => trans('lms/update.block_access'),
                                                    'btnIcon' => 'fa-times-circle'
                                                ])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <p class="font-12 text-gray mt-1 mb-0">{{ trans('lms/update.purchased_hint') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
