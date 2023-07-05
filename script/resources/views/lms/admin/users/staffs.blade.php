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

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.search')}}</label>
                                    <input name="full_name" type="text" class="form-control" value="{{ request()->get('full_name') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('lms/admin/main.role') }}</label>
                                    <select name="role_id" class="form-control">
                                        <option value="">{{ trans('lms/public.all') }}</option>
                                        @foreach($staffsRoles as $role)
                                            <option value="{{ $role->id }}" @if(!empty(request()->get('role_id')) and request()->get('role_id') == $role->id) selected @endif>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
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
                        <div class="card-header">

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.id') }}</th>
                                        <th class="text-left">{{ trans('lms/admin/main.name') }}</th>
                                        <th>{{ trans('lms/admin/main.role_name') }}</th>
                                        <th>{{ trans('lms/admin/main.register_date') }}</th>
                                        <th>{{ trans('lms/admin/main.status') }}</th>
                                        <th width="120">{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1 font-weight-bold">{{ $user->full_name }}</div>

                                                        @if($user->mobile)
                                                            <div class="text-primary text-small font-600-bold">{{ $user->mobile }}</div>
                                                        @endif

                                                        @if($user->email)
                                                            <div class="text-primary text-small font-600-bold">{{ $user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-center">{{ $user->role->caption }}</td>
                                            <td>{{ dateTimeFormat($user->created_at, 'j M Y | H:i') }}</td>

                                            <td>
                                                <div class="media-body">
                                                    @if($user->ban and !empty($user->ban_end_at) and $user->ban_end_at > time())
                                                        <div class="mt-0 mb-1 font-weight-bold text-danger">{{ trans('lms/admin/main.ban') }}</div>
                                                        <div class="text-small font-600-bold">Until {{ dateTimeFormat($user->ban_end_at, 'Y/m/j') }}</div>
                                                    @else
                                                        <div class="mt-0 mb-1 font-weight-bold {{ ($user->status == 'active') ? 'text-success' : 'text-warning' }}">{{ trans('lms/admin/main.'.$user->status) }}</div>
                                                        <div class="text-small font-600-bold {{ ($user->verified ? ' text-success ' : ' text-warning ') }}">({{ trans('lms/public.'.($user->verified ? 'verified' : 'not_verified')) }})</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center mb-2" width="120">

                                                @if($authUser->can('admin_users_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_users_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/users/'.$user->id.'/delete' , 'btnClass' => '', 'deleteConfirmMsg' => trans('lms/update.user_delete_confirm_msg')])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $users->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
