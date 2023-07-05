@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.categories') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/categories.categories') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.icon') }}</th>
                                        <th class="text-left">{{ trans('lms/update.order') }}</th>
                                        <th class="text-left">{{ trans('lms/admin/main.title') }}</th>
                                        <th>{{ trans('lms/admin/main.sub_category') }}</th>
                                        <th>{{ trans('lms/panel.classes') }}</th>
                                        <th>{{ trans('lms/home.teachers') }}</th>
                                        <th>{{ trans('lms/admin/main.action') }}</th>
                                    </tr>
                                    @foreach($categories as $category)

                                        <tr>
                                            <td>
                                                <img src="{{get_path_lms()}}{{ $category->icon }}" width="30" alt="">
                                            </td>
                                            <td class="text-left">{{ $category->order }}</td>
                                            <td class="text-left">{{ $category->title }}</td>
                                            <td>{{ $category->subCategories->count() }}</td>
                                            <td>{{ count($category->getCategoryCourses()) }}</td>
                                            <td>{{ count($category->getCategoryInstructorsIdsHasMeeting()) }}</td>
                                            <td>
                                                @if($authUser->can('admin_categories_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/categories/{{ $category->id }}/edit"
                                                       class="btn-transparent btn-sm text-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if($authUser->can('admin_categories_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/categories/'.$category->id.'/delete', 'deleteConfirmMsg' => trans('lms/update.category_delete_confirm_msg')])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $categories->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
