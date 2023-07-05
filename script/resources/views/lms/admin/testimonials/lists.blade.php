@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.testimonials') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.testimonials') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($authUser->can('admin_testimonials_create'))
                                <a href="/lms{{ getAdminPanelUrl() }}/testimonials/create" class="btn btn-primary">{{ trans('lms/admin/main.add_new') }}</a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('lms/admin/main.user_name') }}</th>
                                        <th>{{ trans('lms/admin/main.rate') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.content') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.created_at') }}</th>
                                        <th>{{ trans('lms/admin/main.action') }}</th>
                                    </tr>
                                    @foreach($testimonials as $testimonial)
                                        <tr>
                                            <td>
                                                <img src="{{get_path_lms()}}{{ $testimonial->user_avatar }}" alt="" width="56" height="56" class="rounded-circle">
                                            </td>
                                            <td>{{ $testimonial->user_name }}</td>
                                            <td>{{ $testimonial->rate }}</td>
                                            <td class="text-center" width="30%">{{ nl2br(truncate($testimonial->comment, 150, true)) }}</td>

                                            <td class="text-center">
                                                @if($testimonial->status == 'active')
                                                    <span class="text-success">{{ trans('lms/admin/main.active') }}</span>
                                                @else
                                                    <span class="text-warning">{{ trans('lms/admin/main.disable') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ dateTimeFormat($testimonial->created_at, 'j M Y | H:i') }}</td>
                                            <td width="150px">

                                                @if($authUser->can('admin_supports_reply'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/testimonials/{{ $testimonial->id }}/edit" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_supports_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/testimonials/'.$testimonial->id.'/delete' , 'btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $testimonials->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
