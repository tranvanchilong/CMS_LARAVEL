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

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('lms/admin/main.icon') }}</th>
                                        <th>{{ trans('lms/public.title') }}</th>
                                        <th>{{ trans('lms/update.topics') }}</th>
                                        <th class="text-center">{{ trans('lms/public.date') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($recommendedTopics as $recommended)

                                        <tr>
                                            <td>
                                                <img src="{{get_path_lms()}}{{ $recommended->icon }}" alt="" width="48" height="48" class="">
                                            </td>

                                            <td class="text-center">{{ $recommended->title }}</td>

                                            <td class="text-center">{{ $recommended->topics->count() }}</td>

                                            <td class="text-center">{{ dateTimeFormat($recommended->created_at, 'j M Y | H:i') }}</td>

                                            <td width="150">

                                                @if($authUser->can('admin_featured_topics_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/recommended-topics/{{ $recommended->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_featured_topics_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/recommended-topics/'. $recommended->id .'/delete','btnClass' => 'btn-sm','icon' => true])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $recommendedTopics->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
