@extends('lms.web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ trans('lms/auth.verify_your_email_address') }}</div>
                        <div class="card-body">
                            <div class="alert alert-success" role="alert">
                                {{ trans('lms/auth.verification_link_has_been_sent_to_your_email') }}
                            </div>
                            <a href="/lms{{ url(getAdminPanelUrl('/reset-password/'.$token.'?email='.$email)) }}">{{ trans('lms/auth.click_here') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
