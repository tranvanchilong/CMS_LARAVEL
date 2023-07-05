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
                                        <th>{{ trans('lms/admin/main.user') }}</th>
                                        <th class="text-left">{{ trans('lms/admin/main.class') }}</th>
                                        <th class="text-center">{{ trans('lms/product.reason') }}</th>
                                        <th class="text-center">{{ trans('lms/public.date') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>
                                    @foreach($reports as $report)
                                        <tr>
                                            @if (!empty($report->user->id))

                                            <td>{{ $report->user->id .' - '.$report->user->full_name }}</td>

                                            @else

                                            <td class="text-danger">Deleted User</td>


                                            @endif

                                            <td class="text-left" width="30%">
                                                <a href="/lms{{ $report->webinar->getUrl() }}" target="_blank">
                                                    {{ $report->webinar->title }}
                                                </a>
                                            </td>

                                            <td class="text-center">
                                                <button type="button" class="js-show-description btn btn-outline-primary">{{ trans('lms/admin/main.show') }}</button>
                                                <input type="hidden" class="report-reason" value="{{ nl2br($report->reason) }}">
                                                <input type="hidden" class="report-description" value="{{ nl2br($report->message) }}">
                                            </td>

                                            <td class="text-center">{{ dateTimeFormat($report->created_at, 'j M Y | H:i') }}</td>

                                            <td width="150px" class="text-center">
                                                @if($authUser->can('admin_webinar_reports_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/reports/webinars/'.$report->id.'/delete','btnClass' => 'btn-sm'])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $reports->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="reportMessage" tabindex="-1" aria-labelledby="reportMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportMessageLabel">{{ trans('lms/panel.report') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <h5 class="font-weight-bold js-reason">{{ trans('lms/product.reason') }}: <span class="font-weight-light"></span></h5>

                        <div class="mt-2 js-description">
                            <h5 class="font-weight-bold js-reason">{{ trans('lms/site.message') }} :</h5>
                            <p class="mt-2">

                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lms/admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/js/admin/webinar_reports.min.js"></script>
@endpush
