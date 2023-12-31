@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.feature_webinars') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.feature_webinars') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form action="/lms{{ getAdminPanelUrl() }}/webinars/features" method="get" class="row mb-0">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/admin/main.page') }}</label>
                                        <select class="custom-select" name="page">
                                            <option selected disabled>{{ trans('lms/admin/main.select_page') }}</option>
                                            <option value="">{{ trans('lms/admin/main.all') }}</option>
                                            @foreach(\App\Models\LMS\FeatureWebinar::$pages as $page)
                                                <option value="{{ $page }}" @if(request()->get('page', null) == $page) selected="selected" @endif>{{ trans('lms/admin/main.page_'.$page) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/admin/main.status') }}</label>
                                        <select class="custom-select" name="status">
                                            <option selected disabled>{{ trans('lms/admin/main.status') }}</option>
                                            <option value="">{{ trans('lms/admin/main.all') }}</option>
                                            <option value="pending" @if(request()->get('status', null) == 'pending') selected="selected" @endif>{{ trans('lms/admin/main.pending') }}</option>
                                            <option value="publish" @if(request()->get('status', null) == 'publish') selected="selected" @endif>{{ trans('lms/admin/main.published') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/admin/main.webinar_title') }}</label>
                                        <input type="text" name="webinar_title" class="form-control" value="{{ request()->get('webinar_title',null) }}"/>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/public.category') }}</label>

                                        <select id="categories" class="custom-select" name="category_id">
                                            <option {{ !empty($webinar) ? '' : 'selected' }} disabled>{{ trans('lms/public.choose_category') }}</option>
                                            <option value="">{{ trans('lms/admin/main.all') }}</option>
                                            @foreach($categories as $category)
                                                @if(!empty($category->subCategories) and count($category->subCategories))
                                                    <optgroup label="{{  $category->title }}">
                                                        @foreach($category->subCategories as $subCategory)
                                                            <option value="{{ $subCategory->id }}" {{ (request()->get('category_id',null) == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{ $category->id }}" {{ (!empty($webinar) and $webinar->category_id == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100">{{ trans('lms/admin/main.show_results') }}</button>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header">
                            @if($authUser->can('admin_feature_webinars_export_excel'))
                                <div class="text-right">
                                    <a href="/lms{{ getAdminPanelUrl() }}/webinars/features/excel?{{ http_build_query(request()->all()) }}" class="btn btn-primary">{{ trans('lms/admin/main.export_xls') }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.webinar_title') }}</th>
                                        <th>{{ trans('lms/admin/main.webinar_status') }}</th>
                                        <th class="text-center">{{ trans('lms/public.date') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.instructor') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.category') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.page') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($features as $feature)

                                        <tr>
                                            <td>
                                                <a href="/lms{{ $feature->webinar->getUrl() }}" target="_blank">{{ $feature->webinar->title }}</a>
                                            </td>

                                            <td class="text-center">{{ trans('lms/admin/main.'.$feature->webinar->status) }}</td>

                                            <td class="text-center">{{ dateTimeFormat($feature->updated_at, 'j M Y | H:i') }}</td>
                                            <td class="text-center">{{ $feature->webinar->teacher->full_name }}</td>
                                            <td class="text-center">{{ $feature->webinar->category->title }}</td>
                                            <td class="text-center">{{ trans('lms/admin/main.page_'.$feature->page) }}</td>
                                            <td class="text-center">
                                                <span class="text-{{ ($feature->status == 'publish') ? 'success' : 'warning' }}">
                                                    {{ ($feature->status == 'publish') ? trans('lms/admin/main.published') : trans('lms/admin/main.pending') }}
                                                </span>
                                            </td>
                                            <td width="150">
                                                <a href="/lms{{ getAdminPanelUrl() }}/webinars/features/{{ $feature->id }}/{{ ($feature->status == 'publish') ? 'pending' : 'publish' }}" class="btn-transparent btn-sm text-primary">
                                                    @if($feature->status == 'publish')
                                                        <i class="fa fa-eye-slash" data-toggle="tooltip" title="{{ trans('lms/admin/main.pending') }}"></i>
                                                    @else
                                                        <i class="fa fa-eye" data-toggle="tooltip" title="{{ trans('lms/admin/main.publish') }}"></i>
                                                    @endif
                                                </a>

                                                <a href="/lms{{ getAdminPanelUrl() }}/webinars/features/{{ $feature->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/webinars/features/'. $feature->id .'/delete','btnClass' => 'btn-sm','icon' => true])
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $features->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
