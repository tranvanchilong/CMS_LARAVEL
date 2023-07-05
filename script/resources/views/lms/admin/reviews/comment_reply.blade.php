@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.reply_comment') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.reply_comment') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header flex-column align-items-start">
                            <h4>{{ trans('lms/admin/main.main_comment') }}</h4>
                            <p class="mt-2">{{ nl2br($review->description) }}</p>

                            <hr class="divider my-2 w-100 border border-gray">

                            @if(!empty($review->comments) and $review->comments->count() > 0)
                                <div class="mt-1 w-100">
                                    <h4>{{ trans('lms/admin/main.reply_list') }}</h4>

                                    <div class="table-responsive">
                                        <table class="table table-striped font-14">
                                            <tr>
                                                <th>{{ trans('lms/admin/main.user') }}</th>
                                                <th>{{ trans('lms/admin/main.comment') }}</th>
                                                <th>{{ trans('lms/public.date') }}</th>
                                                <th>{{ trans('lms/admin/main.status') }}</th>
                                                <th>{{ trans('lms/admin/main.action') }}</th>
                                            </tr>
                                            @foreach($review->comments as $reply)
                                                <tr>
                                                    <td>{{ $reply->user->id .' - '.$reply->user->full_name }}</td>

                                                    <td>
                                                        <button type="button" class="js-show-description btn btn-outline-primary">{{ trans('lms/admin/main.show') }}</button>
                                                        <input type="hidden" value="{{ nl2br($reply->comment) }}">
                                                    </td>

                                                    <td>{{ dateTimeFormat($reply->created_at, 'Y M j | H:i') }}</td>

                                                    <td>
                                                        <span class="text-{{ ($reply->status == 'pending') ? 'warning' : 'success' }}">
                                                            {{ ($reply->status == 'pending') ? trans('lms/admin/main.pending') : trans('lms/admin/main.published') }}
                                                        </span>
                                                    </td>

                                                    <td>

                                                        @if($authUser->can("admin_comments_status"))
                                                            <a href="/lms{{ getAdminPanelUrl("/comments/reviews/{$reply->id}/toggle") }}" class="btn-transparent text-primary">
                                                                @if($reply->status == 'pending')
                                                                    <i class="fa fa-arrow-up"></i>
                                                                @else
                                                                    <i class="fa fa-arrow-down"></i>
                                                                @endif
                                                            </a>
                                                        @endif

                                                        @if($authUser->can("admin_comments_edit"))
                                                            <a href="/lms{{ getAdminPanelUrl("/comments/reviews/{$reply->id}/edit") }}" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        @endif

                                                        @if($authUser->can("admin_comments_delete"))
                                                            @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl("/comments/reviews/{$reply->id}/delete"), 'btnClass' => 'btn-sm mt-2'])
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($authUser->can('admin_comments_reply'))
                            <div class="card-body ">
                                <form action="/lms{{ getAdminPanelUrl("/comments/reviews/{$review->id}/reply") }}" method="post">
                                    {{ csrf_field() }}

                                    <div class="form-group mt-15">
                                        <label class="input-label">{{ trans('lms/admin/main.reply_comment') }}</label>
                                        <textarea id="summernote" name="comment" class="summernote form-control @error('comment')  is-invalid @enderror">{!! old('comment')  !!}</textarea>

                                        @error('comment')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="mt-3 btn btn-primary">{{ trans('lms/admin/main.save_change') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('lms/admin/main.comment') }}</h5>
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
    <script src="/assets/lms/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/lms/assets/default/js/admin/comments.min.js"></script>
@endpush
