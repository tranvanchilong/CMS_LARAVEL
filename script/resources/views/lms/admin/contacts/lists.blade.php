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

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('lms/admin/main.user_name') }}</th>
                                <th class="text-left">{{ trans('lms/admin/main.email') }}</th>
                                <th class="text-center">{{ trans('lms/public.phone') }}</th>
                                <th class="text-left">{{ trans('lms/site.subject') }}</th>
                                <th class="text-center">{{ trans('lms/site.message') }}</th>
                                <th class="text-center">{{ trans('lms/admin/main.status') }}</th>
                                <th class="text-center">{{ trans('lms/admin/main.created_at') }}</th>
                                <th>{{ trans('lms/public.controls') }}</th>
                            </tr>

                            @foreach($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->name }}</td>
                                    <td class="text-left">{{ $contact->email }}</td>
                                    <td class="text-center">{{ $contact->phone }}</td>
                                    <td class="text-left">{{ $contact->subject }}</td>

                                    <td class="text-center">
                                        <button type="button" class="js-show-description btn btn-outline-primary">{{ trans('lms/admin/main.show') }}</button>
                                        <input type="hidden" value="{{ nl2br($contact->message) }}">
                                    </td>

                                    <td class="text-center">
                                        @if($contact->status =='replied')
                                            <span class="text-success">{{ trans('lms/admin/main.replied') }}</span>
                                        @else
                                            <span class="text-danger">{{ trans('lms/panel.not_replied') }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($contact->created_at,'Y M j | H:i') }}</td>

                                    <td width="100">
                                        @if($authUser->can('admin_contacts_reply'))
                                            <a href="/lms{{ getAdminPanelUrl() }}/contacts/{{ $contact->id }}/reply" class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.reply') }}">
                                                <i class="fa fa-reply"></i>
                                            </a>
                                        @endif

                                        @if($authUser->can('admin_contacts_delete'))
                                            @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/contacts/'. $contact->id.'/delete','btnClass' => 'btn-sm'])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $contacts->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('lms/admin/main.contacts_message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lms/admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/js/admin/contacts.min.js"></script>
@endpush
