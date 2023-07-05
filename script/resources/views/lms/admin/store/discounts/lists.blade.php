@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.search') }}</label>
                                    <input type="text" class="form-control text-center" name="name" value="{{ request()->get('name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.expiration_from') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/update.product') }}</label>
                                    <select name="product_ids[]" multiple="multiple" class="form-control search-product-select2"
                                            data-placeholder="{{ trans('lms/update.search_product') }}">

                                        @if(!empty($products) and $products->count() > 0)
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" selected>{{ $product->title }}</option>
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
                        <div class="card-header">
                            @if($authUser->can('admin_store_discounts_create'))
                                <a href="/lms{{ getAdminPanelUrl() }}/store/discounts/create" class="btn btn-primary">{{ trans('lms/admin/main.add_new') }}</a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 text-center">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('lms/update.product') }}</th>
                                        <th>{{ trans('lms/admin/main.percentage') }}</th>
                                        <th>{{ trans('lms/admin/main.start_date') }}</th>
                                        <th>{{ trans('lms/admin/main.end_date') }}</th>
                                        <th width="150">{{ trans('lms/admin/main.usable_times') }}</th>
                                        <th>{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($discounts as $discount)
                                        <tr>
                                            <td>{{ $discount->name }}</td>
                                            <td class="text-left">
                                                <a href="/lms{{ $discount->product->getUrl() }}" target="_blank">{{ $discount->product->title }}</a>
                                            </td>

                                            <td>{{  $discount->percent ?  $discount->percent . '%' : '-' }}</td>

                                            <td>{{  dateTimeFormat($discount->start_date, 'Y/m/d h:i:s') }}</td>

                                            <td>{{  dateTimeFormat($discount->end_date, 'Y/m/d h:i:s') }}</td>

                                            <td>
                                                @if(!empty($discount->count))
                                                    <div class="media-body">
                                                        <div class=" mt-0 mb-1 font-weight-bold">{{ $discount->count }}</div>
                                                        <div class="text-primary text-small">{{ trans('lms/admin/main.remain') }} : {{ $discount->discountRemain() }}</div>
                                                    </div>
                                                @else
                                                    {{ trans('lms/update.unlimited') }}
                                                @endif
                                            </td>

                                            <td>
                                                <span class="{{ ($discount->status == 'active') ? 'text-success' : 'text-danger' }}">{{ trans('lms/admin/main.'.$discount->status) }}</span>
                                            </td>

                                            <td>
                                                @if($authUser->can('admin_store_discounts_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/store/discounts/{{ $discount->id }}/edit" class="btn-transparent text-primary btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_store_discounts_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/store/discounts/'. $discount->id.'/delete','btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $discounts->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

