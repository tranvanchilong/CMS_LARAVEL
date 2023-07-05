@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.special_offers') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.special_offers') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.search') }}</label>
                                    <input type="text" class="form-control text-center" name="name" value="{{ request()->get('name') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.expiration_from') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.expiration_to') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.filters') }}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('lms/admin/main.all_users_discount') }}</option>
                                        <option value="percent_asc" @if(request()->get('sort') == 'percent_asc') selected @endif>{{ trans('lms/admin/main.percentage_ascending') }}</option>
                                        <option value="percent_desc" @if(request()->get('sort') == 'percent_desc') selected @endif>{{ trans('lms/admin/main.percentage_descending') }}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{ trans('lms/admin/main.create_date_ascending') }}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{ trans('lms/admin/main.create_date_descending') }}</option>
                                        <option value="expire_at_asc" @if(request()->get('sort') == 'expire_at_asc') selected @endif>{{ trans('lms/admin/main.expire_date_ascending') }}</option>
                                        <option value="expire_at_desc" @if(request()->get('sort') == 'expire_at_desc') selected @endif>{{ trans('lms/admin/main.expire_date_descending') }}</option>
                                    </select>
                                </div>
                            </div>

                            @php
                                $types = [
                                    'courses' => 'webinar_id',
                                    'bundles' => 'bundle_id',
                                    'subscription_packages' => 'subscribe_id',
                                    'registration_packages' => 'registration_package_id',
                                ];
                            @endphp

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.type') }}</label>
                                    <select name="type" class="form-control ">
                                        <option value="">{{ trans('lms/update.select_type') }}</option>

                                        @foreach($types as $type => $typeItem)
                                            <option value="{{ $typeItem }}" {{ (request()->get('type') == $typeItem) ? 'selected' : '' }}>{{ trans('lms/update.'.$type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.class') }}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.status') }}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('lms/admin/main.all_status') }}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{ trans('lms/admin/main.active') }}</option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{ trans('lms/admin/main.inactive') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('lms/admin/main.show_results') }}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 text-center">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('lms/admin/main.item') }}</th>
                                        <th>{{ trans('lms/admin/main.percentage') }}</th>
                                        <th>{{ trans('lms/admin/main.from_date') }}</th>
                                        <th>{{ trans('lms/admin/main.to_date') }}</th>
                                        <th>{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($specialOffers as $specialOffer)
                                        <tr>
                                            <td>{{ $specialOffer->name }}</td>

                                            <td class="text-left">
                                                @if(!empty($specialOffer->webinar_id))
                                                    <span class="d-block font-14">{{ $specialOffer->webinar->title }}</span>
                                                    <span class="d-block font-12 text-muted">{{ trans('lms/admin/main.course') }}</span>
                                                @elseif($specialOffer->bundle_id)
                                                    <span class="d-block font-14">{{ $specialOffer->bundle->title }}</span>
                                                    <span class="d-block font-12 text-muted">{{ trans('lms/update.bundle') }}</span>
                                                @elseif($specialOffer->subscribe_id)
                                                    <span class="d-block font-14">{{ $specialOffer->subscribe->title }}</span>
                                                    <span class="d-block font-12 text-muted">{{ trans('lms/public.subscribe') }}</span>
                                                @elseif($specialOffer->registration_package_id)
                                                    <span class="d-block font-14">{{ $specialOffer->registrationPackage->title }}</span>
                                                    <span class="d-block font-12 text-muted">{{ trans('lms/update.registration_package') }}</span>
                                                @endif
                                            </td>

                                            <td>{{  $specialOffer->percent ?  $specialOffer->percent . '%' : '-' }}</td>

                                            <td>{{  dateTimeFormat($specialOffer->from_date, 'Y/m/d h:i:s') }}</td>

                                            <td>{{  dateTimeFormat($specialOffer->to_date, 'Y/m/d h:i:s') }}</td>

                                            <td>
                                                <span class="{{ ($specialOffer->status == 'active') ? 'text-success' : 'text-danger' }}">{{ trans('lms/admin/main.'.$specialOffer->status) }}</span>
                                            </td>

                                            <td>
                                                @if($authUser->can('admin_product_discount_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/financial/special_offers/{{ $specialOffer->id }}/edit" class="btn-transparent text-primary btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_product_discount_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/financial/special_offers/'. $specialOffer->id.'/delete','btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $specialOffers->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
