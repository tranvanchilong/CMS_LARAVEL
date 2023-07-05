@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

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

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_physical_products')}}</h4>
                            </div>
                            <div class="card-body d-flex flex-column p-0">
                                <span>{{ $totalPhysicalProducts }}</span>
                                <span class="font-12 font-weight-normal mt-1">{{ trans('lms/admin/main.sales') }}: {{ $totalPhysicalSales }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-file-download"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ trans('lms/update.total_virtual_products') }}</h4>
                            </div>
                            <div class="card-body d-flex flex-column p-0">
                                <span>{{ $totalVirtualProducts }}</span>
                                <span class="font-12 font-weight-normal mt-1">{{ trans('lms/admin/main.sales') }}: {{ $totalVirtualSales }}</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-store"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_sellers')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalSellers }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_buyers')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalBuyers }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <input type="hidden" name="type" value="{{ request()->get('type') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('lms/admin/main.filter_type')}}</option>
                                        <option value="has_discount" @if(request()->get('sort') == 'has_discount') selected @endif>{{trans('lms/admin/main.discounted_classes')}}</option>
                                        <option value="sales_asc" @if(request()->get('sort') == 'sales_asc') selected @endif>{{trans('lms/admin/main.sales_ascending')}}</option>
                                        <option value="sales_desc" @if(request()->get('sort') == 'sales_desc') selected @endif>{{trans('lms/admin/main.sales_descending')}}</option>
                                        <option value="price_asc" @if(request()->get('sort') == 'price_asc') selected @endif>{{trans('lms/admin/main.Price_ascending')}}</option>
                                        <option value="price_desc" @if(request()->get('sort') == 'price_desc') selected @endif>{{trans('lms/admin/main.Price_descending')}}</option>
                                        <option value="income_asc" @if(request()->get('sort') == 'income_asc') selected @endif>{{trans('lms/admin/main.Income_ascending')}}</option>
                                        <option value="income_desc" @if(request()->get('sort') == 'income_desc') selected @endif>{{trans('lms/admin/main.Income_descending')}}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{trans('lms/admin/main.create_date_ascending')}}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{trans('lms/admin/main.create_date_descending')}}</option>
                                        <option value="updated_at_asc" @if(request()->get('sort') == 'updated_at_asc') selected @endif>{{trans('lms/admin/main.update_date_ascending')}}</option>
                                        <option value="updated_at_desc" @if(request()->get('sort') == 'updated_at_desc') selected @endif>{{trans('lms/admin/main.update_date_descending')}}</option>
                                        <option value="inventory_asc" @if(request()->get('sort') == 'inventory_asc') selected @endif>{{trans('lms/update.inventory_asc')}}</option>
                                        <option value="inventory_desc" @if(request()->get('sort') == 'inventory_desc') selected @endif>{{trans('lms/update.inventory_desc')}}</option>
                                        <option value="no_inventory" @if(request()->get('sort') == 'no_inventory') selected @endif>{{trans('lms/update.no_inventory')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/update.seller')}}</label>
                                    <select name="creator_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="{{trans('lms/update.search_seller')}}">

                                        @if(!empty($teachers) and $teachers->count() > 0)
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.category')}}</label>
                                    <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('lms/admin/main.all_categories')}}</option>

                                        @foreach($categories as $category)
                                            @if(!empty($category->subCategories) and count($category->subCategories))
                                                <optgroup label="{{  $category->title }}">
                                                    @foreach($category->subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('lms/admin/main.all_status')}}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('lms/admin/main.pending')}}</option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{trans('lms/admin/main.rejected')}}</option>
                                        <option value="draft" @if(request()->get('status') == 'draft') selected @endif>{{trans('lms/admin/main.draft')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('lms/admin/main.show_results')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header text-right">
                            @if($authUser->can('admin_store_export_products'))
                                <a href="/lms{{ getAdminPanelUrl() }}/store/products/excel?{{ !empty($inHouseProducts) ? 'in_house_products=true&' : '' }}{{ http_build_query(request()->all()) }}" class="btn btn-primary">{{ trans('lms/admin/main.export_xls') }}</a>
                            @endif

                            @if(!empty($inHouseProducts))
                                @if($authUser->can('admin_store_new_product'))
                                    <a href="/lms{{ getAdminPanelUrl() }}/store/products/create?in_house_product=true" target="_blank" class="btn btn-primary ml-2">{{ trans('lms/update.create_new_product') }}</a>
                                @endif
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th>{{trans('lms/admin/main.id')}}</th>
                                        <th class="text-left">{{trans('lms/admin/main.title')}}</th>
                                        <th class="text-left">{{trans('lms/admin/main.creator')}}</th>
                                        <th>{{trans('lms/admin/main.type')}}</th>
                                        <th>{{trans('lms/update.inventory')}}</th>
                                        <th>{{trans('lms/admin/main.price')}}</th>
                                        <th>{{trans('lms/update.delivery_fee')}}</th>
                                        <th>{{trans('lms/admin/main.sales')}}</th>
                                        <th>{{trans('lms/admin/main.income')}}</th>
                                        <th>{{trans('lms/admin/main.updated_at')}}</th>
                                        <th>{{trans('lms/admin/main.created_at')}}</th>
                                        <th>{{trans('lms/admin/main.status')}}</th>
                                        <th width="120">{{trans('lms/admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($products as $product)
                                        <tr class="text-center">
                                            <td>{{ $product->id }}</td>
                                            <td width="18%" class="text-left">
                                                <a class="text-primary mt-0 mb-1 font-weight-bold" href="{{ $product->getUrl() }}">{{ $product->title }}</a>
                                                @if(!empty($product->category->title))
                                                    <div class="text-small">{{ $product->category->title }}</div>
                                                @else
                                                    <div class="text-small text-warning">{{trans('lms/admin/main.no_category')}}</div>
                                                @endif
                                            </td>

                                            <td class="text-left">{{ $product->creator->full_name }}</td>

                                            <td>
                                                {{ trans('lms/update.'.$product->type) }}
                                            </td>

                                            <td>
                                                <span class="text-primary mt-0 mb-1 font-weight-bold">
                                                    @php
                                                        $getAvailability = $product->getAvailability();
                                                    @endphp

                                                    {{ ($getAvailability == 99999) ? trans('lms/update.unlimited') : $getAvailability }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ !empty($product->price) ? handlePrice($product->price) : '-' }}
                                            </td>

                                            <td>
                                                {{ $product->delivery_fee ? handlePrice($product->delivery_fee) : '-' }}
                                            </td>

                                            <td>
                                                <span class="text-primary mt-0 mb-1 font-weight-bold">
                                                    {{ $product->salesCount() }}
                                                </span>
                                            </td>

                                            <td>{{ handlePrice($product->sales()->sum('total_amount')) }}</td>

                                            <td class="font-12">{{ dateTimeFormat($product->updated_at, 'Y M j | H:i') }}</td>

                                            <td class="font-12">{{ dateTimeFormat($product->created_at, 'Y M j | H:i') }}</td>

                                            <td>
                                                @switch($product->status)
                                                    @case(\App\Models\LMS\Product::$active)
                                                    <div class="text-success font-600-bold">{{ trans('lms/admin/main.published') }}</div>
                                                    @break
                                                    @case(\App\Models\LMS\Product::$draft)
                                                    <span class="text-dark">{{ trans('lms/admin/main.is_draft') }}</span>
                                                    @break
                                                    @case(\App\Models\LMS\Product::$pending)
                                                    <span class="text-warning">{{ trans('lms/admin/main.waiting') }}</span>
                                                    @break
                                                    @case(\App\Models\LMS\Product::$inactive)
                                                    <span class="text-danger">{{ trans('lms/public.rejected') }}</span>
                                                    @break
                                                @endswitch
                                            </td>

                                            <td width="120" class="btn-sm">

                                                @if($authUser->can('admin_store_edit_product'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/store/products/{{ $product->id }}/edit" target="_blank" class="btn-transparent btn-sm text-primary mt-1" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_store_delete_product'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/store/products/'.$product->id.'/delete', 'btnClass' => 'btn-sm mt-1'])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
