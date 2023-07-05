@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
    <style>
        .select2-container {
            z-index: 1212 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $bundle->title }} - {{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item"><a>{{ $pageTitle }}</a></div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-sm-6 col-lg-4 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ trans('lms/admin/main.total_students') }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalStudents }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-briefcase"></i></div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ trans('lms/update.active_students') }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalActiveStudents }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-info-circle"></i></div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ trans('lms/update.expire_students') }}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalExpireStudents }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <form method="get" class="mb-0">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.search') }}</label>
                            <input name="full_name" type="text" class="form-control" value="{{ request()->get('full_name') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.start_date') }}</label>
                            <div class="input-group">
                                <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.end_date') }}</label>
                            <div class="input-group">
                                <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.filters') }}</label>
                            <select name="sort" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('lms/admin/main.filter_type') }}</option>
                                <option value="rate_asc" @if(request()->get('sort') == 'rate_asc') selected @endif>{{ trans('lms/update.rate_ascending') }}</option>
                                <option value="rate_desc" @if(request()->get('sort') == 'rate_desc') selected @endif>{{ trans('lms/update.rate_descending') }}</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.users_group') }}</label>
                            <select name="group_id" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('lms/admin/main.select_users_group') }}</option>
                                @foreach($userGroups as $userGroup)
                                    <option value="{{ $userGroup->id }}" @if(request()->get('group_id') == $userGroup->id) selected @endif>{{ $userGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.role') }}</label>
                            <select name="role_id" class="form-control">
                                <option value="">{{ trans('lms/admin/main.all_roles') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($role->id == request()->get('role_id')) selected @endif>{{ $role->caption }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/admin/main.status') }}</label>
                            <select name="status" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('lms/admin/main.all_status') }}</option>
                                <option value="active" @if(request()->get('status') == 'active') selected @endif>{{ trans('lms/admin/main.active') }}</option>
                                <option value="expire" @if(request()->get('status') == 'expire') selected @endif>{{ trans('lms/panel.expired') }}</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group mt-1">
                            <label class="input-label mb-4"> </label>
                            <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('lms/admin/main.show_results') }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="card">
        <div class="card-header">
            @if($authUser->can('admin_webinar_notification_to_students'))
                <a href="/lms{{ getAdminPanelUrl() }}/bundles/{{ $bundle->id }}/sendNotification" class="btn btn-primary mr-2">{{ trans('lms/notification.send_notification') }}</a>
            @endif

            @if($authUser->can('admin_enrollment_add_student_to_items'))
                <button type="button" id="addStudentToCourse" class="btn btn-primary mr-2">{{ trans('lms/update.add_student_to_bundle') }}</button>
            @endif

            <div class="h-10"></div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table table-striped font-14">
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left">{{ trans('lms/admin/main.name') }}</th>
                        <th>{{ trans('lms/admin/main.rate') }}(5)</th>
                        <th>{{ trans('lms/update.learning') }}</th>
                        <th>{{ trans('lms/admin/main.user_group') }}</th>
                        <th>{{ trans('lms/panel.purchase_date') }}</th>
                        <th>{{ trans('lms/admin/main.status') }}</th>
                        <th width="120">{{ trans('lms/admin/main.actions') }}</th>
                    </tr>

                    @foreach($students as $student)

                        <tr>
                            <td class="text-left">{{ $student->id ?? '-' }}</td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="{{ $student->getAvatar() }}" alt="{{ $student->full_name }}">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1 font-weight-bold">{{ $student->full_name }}</div>

                                        @if($student->mobile)
                                            <div class="text-primary text-small font-600-bold">{{ $student->mobile }}</div>
                                        @endif

                                        @if($student->email)
                                            <div class="text-primary text-small font-600-bold">{{ $student->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span>{{ $student->rates ?? '-' }}</span>
                            </td>

                            <td>
                                <span>{{ $student->learning }}%</span>
                            </td>

                            <td>
                                @if(!empty($student->getUserGroup()))
                                    <span>{{ $student->getUserGroup()->name }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ dateTimeFormat($student->purchase_date, 'j M Y | H:i') }}</td>

                            <td>
                                @if(empty($student->id))
                                    {{-- Gift recipient who has not registered yet --}}
                                    <div class="mt-0 mb-1 font-weight-bold text-warning">{{ trans('lms/update.unregistered') }}</div>
                                @elseif(!empty($bundle->access_days) and !$bundle->checkHasExpiredAccessDays($student->purchase_date, $student->gift_id))
                                    <div class="mt-0 mb-1 font-weight-bold text-warning">{{ trans('lms/panel.expired') }}</div>
                                @else
                                    <div class="mt-0 mb-1 font-weight-bold text-success">{{ trans('lms/admin/main.active') }}</div>
                                @endif
                            </td>

                            <td class="text-center mb-2" width="120">
                                @if(!empty($student->id))
                                    {{-- null id => Gift recipient who has not registered yet --}}
                                    @if($authUser->can('admin_users_impersonate'))
                                        <a href="/lms{{ getAdminPanelUrl() }}/users/{{ $student->id }}/impersonate" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.login') }}">
                                            <i class="fa fa-user-shield"></i>
                                        </a>
                                    @endif

                                    @if($authUser->can('admin_users_edit'))
                                        <a href="/lms{{ getAdminPanelUrl() }}/users/{{ $student->id }}/edit" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif

                                    @if($authUser->can('admin_users_delete'))
                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/users/'.$student->id.'/delete' , 'btnClass' => ''])
                                    @endif
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="card-footer text-center">
            {{ $students->appends(request()->input())->links() }}
        </div>

    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('lms/admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('lms/admin/main.students_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('lms/admin/main.students_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('lms/admin/main.students_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('lms/admin/main.students_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('lms/admin/main.students_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('lms/admin/main.students_hint_description_3')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <div id="addStudentToCourseModal" class="d-none">
        <h3 class="section-title after-line">{{ trans('lms/update.add_student_to_bundle') }}</h3>
        <div class="mt-25">
            <form action="/lms{{ getAdminPanelUrl() }}/enrollments/store" method="post">
                <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">

                <div class="form-group">
                    <label class="input-label d-block">{{ trans('lms/admin/main.user') }}</label>
                    <select name="user_id" class="form-control user-search" data-placeholder="{{ trans('lms/public.search_user') }}">

                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">
                    <button type="button" class="js-save-manual-add btn btn-sm btn-primary">{{ trans('lms/public.save') }}</button>
                    <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('lms/public.close') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>

    <script>
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
    </script>

    <script src="/assets/lms/assets/default/js/admin/webinar_students.min.js"></script>
@endpush
