<div class="tab-pane mt-3 fade" id="purchased_bundles" role="tabpanel" aria-labelledby="purchased_bundles-tab">
    <div class="row">

        @if($authUser->can('admin_enrollment_add_student_to_items'))
            <div class="col-12 col-md-6">
                <h5 class="section-title after-line">{{ trans('lms/update.add_student_to_bundle') }}</h5>

                <form action="/lms{{ getAdminPanelUrl() }}/enrollments/store" method="Post">

                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="form-group">
                        <label class="input-label">{{trans('lms/update.bundle')}}</label>
                        <select name="bundle_id" class="form-control search-bundle-select2"
                                data-placeholder="{{ trans('lms/update.search_bundle') }}">

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
                <h5 class="section-title after-line">{{ trans('lms/update.manual_added_bundles') }}</h5>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-md">
                        <tr>
                            <th>{{trans('lms/update.bundle')}}</th>
                            <th>{{ trans('lms/admin/main.type') }}</th>
                            <th>{{ trans('lms/admin/main.price') }}</th>
                            <th>{{ trans('lms/admin/main.instructor') }}</th>
                            <th class="text-center">{{ trans('lms/update.added_date') }}</th>
                            <th class="text-right">{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @if(!empty($manualAddedBundles))
                            @foreach($manualAddedBundles as $manualAddedBundle)

                                <tr>
                                    <td width="25%">
                                        <a href="/lms{{ !empty($manualAddedBundle->bundle) ? $manualAddedBundle->bundle->getUrl() : '#1' }}" target="_blank" class="">{{ !empty($manualAddedBundle->bundle) ? $manualAddedBundle->bundle->title : trans('lms/update.deleted_item') }}</a>
                                    </td>

                                    <td>
                                        @if(!empty($manualAddedBundle->bundle))
                                            {{ trans('lms/admin/main.'.$manualAddedBundle->bundle->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($manualAddedBundle->bundle))
                                            {{ !empty($manualAddedBundle->bundle->price) ? handlePrice($manualAddedBundle->bundle->price) : '-' }}
                                        @else
                                            {{ !empty($manualAddedBundle->amount) ? handlePrice($manualAddedBundle->amount) : '-' }}
                                        @endif
                                    </td>

                                    <td width="25%">
                                        @if(!empty($manualAddedBundle->bundle))
                                            <p>{{ $manualAddedBundle->bundle->creator->full_name  }}</p>
                                        @else
                                            <p>{{ $manualAddedBundle->seller->full_name  }}</p>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($manualAddedBundle->created_at,'j M Y | H:i') }}</td>
                                    <td class="text-right">
                                        @if($authUser->can('admin_enrollment_block_access'))
                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/enrollments/'. $manualAddedBundle->id .'/block-access',
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
                <h5 class="section-title after-line">{{ trans('lms/update.manual_disabled_bundles') }}</h5>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-md">
                        <tr>
                            <th>{{trans('lms/update.bundle')}}</th>
                            <th>{{ trans('lms/admin/main.type') }}</th>
                            <th>{{ trans('lms/admin/main.price') }}</th>
                            <th>{{ trans('lms/admin/main.instructor') }}</th>
                            <th class="text-right">{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @if(!empty($manualDisabledBundles))
                            @foreach($manualDisabledBundles as $manualDisabledBundle)

                                <tr>
                                    <td width="25%">
                                        <a href="/lms{{ !empty($manualDisabledBundle->bundle) ? $manualDisabledBundle->bundle->getUrl() : '#1' }}" target="_blank" class="">{{ !empty($manualDisabledBundle->bundle) ? $manualDisabledBundle->bundle->title : trans('lms/update.deleted_item') }}</a>
                                    </td>

                                    <td>
                                        @if(!empty($manualDisabledBundle->bundle))
                                            {{ trans('lms/admin/main.'.$manualDisabledBundle->bundle->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($manualDisabledBundle->bundle))
                                            {{ !empty($manualDisabledBundle->bundle->price) ? handlePrice($manualDisabledBundle->bundle->price) : '-' }}
                                        @else
                                            {{ !empty($manualDisabledBundle->amount) ? handlePrice($manualDisabledBundle->amount) : '-' }}
                                        @endif
                                    </td>

                                    <td width="25%">
                                        @if(!empty($manualDisabledBundle->bundle))
                                            <p>{{ $manualDisabledBundle->bundle->creator->full_name  }}</p>
                                        @else
                                            <p>{{ $manualDisabledBundle->seller->full_name  }}</p>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        @if($authUser->can('admin_enrollment_block_access'))
                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/enrollments/'. $manualDisabledBundle->id .'/enable-access',
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
                            <th>{{trans('lms/update.bundle')}}</th>
                            <th>{{ trans('lms/admin/main.type') }}</th>
                            <th>{{ trans('lms/admin/main.price') }}</th>
                            <th>{{ trans('lms/admin/main.instructor') }}</th>
                            <th class="text-center">{{ trans('lms/panel.purchase_date') }}</th>
                            <th>{{ trans('lms/admin/main.actions') }}</th>
                        </tr>

                        @if(!empty($purchasedBundles))
                            @foreach($purchasedBundles as $purchasedBundle)

                                <tr>
                                    <td width="25%">
                                        <a href="/lms{{ !empty($purchasedBundle->bundle) ? $purchasedBundle->bundle->getUrl() : '#1' }}" target="_blank" class="">{{ !empty($purchasedBundle->bundle) ? $purchasedBundle->bundle->title : trans('lms/update.deleted_item') }}</a>
                                    </td>

                                    <td>
                                        @if(!empty($purchasedBundle->bundle))
                                            {{ trans('lms/admin/main.'.$purchasedBundle->bundle->type) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($purchasedBundle->bundle))
                                            {{ !empty($purchasedBundle->bundle->price) ? handlePrice($purchasedBundle->bundle->price) : '-' }}
                                        @else
                                            {{ !empty($purchasedBundle->amount) ? handlePrice($purchasedBundle->amount) : '-' }}
                                        @endif
                                    </td>

                                    <td width="25%">
                                        @if(!empty($purchasedBundle->bundle))
                                            <p>{{ $purchasedBundle->bundle->creator->full_name  }}</p>
                                        @else
                                            <p>{{ $purchasedBundle->seller->full_name  }}</p>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($purchasedBundle->created_at,'j M Y | H:i') }}</td>
                                    <td class="text-right">
                                        @if($authUser->can('admin_enrollment_block_access'))
                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/enrollments/'. $purchasedBundle->id .'/block-access',
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
