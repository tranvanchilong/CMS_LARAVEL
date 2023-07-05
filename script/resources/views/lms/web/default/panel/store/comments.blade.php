@extends('lms.'.getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('lms/panel.comments_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/39.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $comments->count() }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.comments') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/41.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $repliedCommentsCount }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.replied') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/lms/assets/default/img/activity/40.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ ($comments->count() - $repliedCommentsCount) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('lms/panel.not_replied') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('lms/panel.filter_comments') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/lms/panel/store/products/comments" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off" value="{{ request()->get('from') }}" class="form-control {{ !empty(request()->get('from')) ? 'datepicker' : 'datefilter' }}" aria-describedby="dateInputGroupPrepend"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off" value="{{ request()->get('to') }}" class="form-control {{ !empty(request()->get('to')) ? 'datepicker' : 'datefilter' }}" aria-describedby="dateInputGroupPrepend"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/panel.user') }}</label>
                                <input type="text" name="user" value="{{ request()->get('user') }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7">
                            <div class="form-group">
                                <label class="input-label">{{ trans('lms/update.product') }}</label>
                                <input type="text" name="product" value="{{ request()->get('product') }}" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('lms/public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('lms/update.product_comments_list') }}</h2>
        </div>

        @if(!empty($comments) and !$comments->isEmpty())

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table custom-table text-center ">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ trans('lms/panel.user') }}</th>
                                    <th class="text-left">{{ trans('lms/update.product') }}</th>
                                    <th class="text-center">{{ trans('lms/panel.comment') }}</th>
                                    <th class="text-center">{{ trans('lms/public.status') }}</th>
                                    <th class="text-center">{{ trans('lms/public.date') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($comments as $comment)
                                    <tr>
                                        <th class="text-left">
                                            <div class="user-inline-avatar d-flex align-items-center">
                                                <div class="avatar bg-gray200">
                                                    <img src="{{ $comment->user->getAvatar() }}" class="img-cover" alt="">
                                                </div>
                                                <span class="user-name ml-5 text-dark-blue font-weight-500">{{ $comment->user->full_name }}</span>
                                            </div>
                                        </th>
                                        <td class=" text-left align-middle" width="35%">
                                            <a href="/lms{{ $comment->product->getUrl() }}" target="_blank" class="text-dark-blue font-weight-500">{{ $comment->product->title }}</a>
                                        </td>
                                        <td class="align-middle">
                                            <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-sm btn-gray200">{{ trans('lms/public.view') }}</button>
                                        </td>
                                        <td class="align-middle">
                                            @if(empty($comment->reply_id))
                                                <span class="text-primary font-weight-500">{{ trans('lms/public.open') }}</span>
                                            @else
                                                <span class="text-dark-blue font-weight-500">{{ trans('lms/panel.replied') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>
                                        <td class="align-middle text-right">
                                            <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button type="button" data-comment-id="{{ $comment->id }}" class="js-reply-comment btn-transparent">{{ trans('lms/panel.reply') }}</button>
                                                    <button type="button" data-item-id="{{ $comment->product_id }}" data-comment-id="{{ $comment->id }}" class="btn-transparent webinar-actions d-block mt-10 text-hover-primary report-comment">{{ trans('lms/panel.report') }}</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        @else
            @include('lms.' . getTemplate() . '.includes.no-result',[
                'file_name' => 'comment.png',
                'title' => trans('lms/panel.comments_no_result'),
                'hint' =>  nl2br(trans('lms/panel.comments_no_result_hint')) ,
            ])
        @endif
    </section>

    <div class="my-30">
        {{ $comments->appends(request()->input())->links('lms.vendor.pagination.panel') }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var commentLang = '{{ trans('lms/panel.comment') }}';
        var replyToCommentLang = '{{ trans('lms/panel.reply_to_the_comment') }}';
        var saveLang = '{{ trans('lms/public.save') }}';
        var closeLang = '{{ trans('lms/public.close') }}';
        var reportLang = '{{ trans('lms/panel.report') }}';
        var reportSuccessLang = '{{ trans('lms/panel.report_success') }}';
        var messageToReviewerLang = '{{ trans('lms/public.message_to_reviewer') }}';
    </script>
    <script src="/assets/lms/assets/default/js/panel/comments.min.js"></script>
@endpush
