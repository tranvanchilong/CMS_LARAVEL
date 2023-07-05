@extends('lms.admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.certificates_templates') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.certificates_templates') }}</div>
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
                                        <th class="text-left">{{ trans('lms/admin/main.title') }}</th>
                                        <th>{{ trans('lms/admin/main.type') }}</th>
                                        <th>{{ trans('lms/admin/main.status') }}</th>
                                        <th>{{ trans('lms/admin/main.action') }}</th>
                                    </tr>

                                    @foreach($templates as $template)
                                        <tr>
                                            <td>
                                                <span>{{ $template->title }}</span>
                                            </td>

                                            <td>
                                                @if($template->type == 'quiz')
                                                    <span class="">{{ trans('lms/update.quiz_related') }}</span>
                                                @else
                                                    <span class="">{{ trans('lms/update.course_completion') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="text-{{ ($template->status == 'publish') ? 'success' : '' }}">{{ trans('lms/admin/main.'.$template->status) }}</span>
                                            </td>

                                            <td>
                                                @if($authUser->can('admin_certificate_template_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/certificates/templates/{{ $template->id }}/edit" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_certificate_template_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/certificates/templates/'. $template->id .'/delete','btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $templates->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

