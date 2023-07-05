@extends('lms.admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('lms/admin/main.reviews')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('lms/admin/main.reviews')}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('lms/admin/main.total_reviews')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalReviews }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-eye"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('lms/admin/main.published_reviews')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $publishedReviews }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-calculator"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('lms/admin/main.rates_average')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $ratesAverage }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-comment-slash"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('lms/admin/main.classes_without_review')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $classesWithoutReview }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.search')}}</label>
                                    <input type="text" class="form-control" name="search" placeholder="" value="{{ request()->get('search') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.class')}}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.status')}}</label>
                                    <select name="status" class="form-control populate">
                                        <option value="">{{trans('lms/admin/main.all_status')}}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('lms/admin/main.published')}}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('lms/admin/main.hidden')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('lms/admin/main.show_results')}}">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <div class="card-body">
                    <table class="table table-striped font-14" id="datatable-details">

                        <tr>
                            <th class="text-left">{{trans('lms/admin/main.title')}}</th>
                            <th class="text-left">{{trans('lms/admin/main.student')}}</th>
                            <th class="">{{trans('lms/admin/main.type')}}</th>
                            <th class="">{{trans('lms/admin/main.comment')}}</th>
                            <th class="">{{trans('lms/admin/main.reply')}}</th>
                            <th class="">{{trans('lms/admin/main.rate')}} (5)</th>
                            <th class="">{{trans('lms/admin/main.created_at')}}</th>
                            <th class="">{{trans('lms/admin/main.status')}}</th>
                            <th class="">{{trans('lms/admin/main.actions')}}</th>
                        </tr>

                        @foreach($reviews as $review)
                            <tr>
                                <td class="text-left">
                                    @if(!empty($review->webinar_id))
                                        <a href="/lms{{ $review->webinar->getUrl() }}" target="_blank">{{ $review->webinar->title }}</a>
                                    @elseif(!empty($review->bundle_id))
                                        <a href="/lms{{ $review->bundle->getUrl() }}" target="_blank">{{ $review->bundle->title }}</a>
                                    @endif
                                </td>

                                <td class="text-left">{{ $review->creator->full_name }}</td>

                                <td class="">
                                    @if(!empty($review->webinar_id))
                                        <span class="">{{ trans('lms/admin/main.course') }}</span>
                                    @elseif(!empty($review->bundle_id))
                                        <span class="">{{ trans('lms/update.bundle') }}</span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button" class="js-show-description btn btn-outline-primary">{{ trans('lms/admin/main.show') }}</button>
                                    <input type="hidden" value="{{ nl2br($review->description) }}">
                                </td>

                                <td class="">{{ $review->comments_count }}</td>

                                <td class="">{{ $review->rates }}</td>

                                <td class="">{{ dateTimeFormat($review->created_at,'j M Y | H:i') }}</td>

                                <td class="">
                                    @if($review->status == 'active')
                                        <b class="f-w-b text-success">{{trans('lms/admin/main.published')}}</b>
                                    @else
                                        <b class="f-w-b text-warning">{{trans('lms/admin/main.hidden')}}</b>
                                    @endif
                                </td>
                                <td class="" width="50">
                                    @if($authUser->can('admin_reviews_status_toggle'))
                                        <a href="/lms{{ getAdminPanelUrl() }}/reviews/{{ $review->id }}/toggleStatus" class="btn-transparent text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ ($review->status == 'active') ? 'Hidden' : 'Publish' }}">
                                            @if($review->status == 'active')
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            @endif
                                        </a>
                                    @endif

                                    @if($authUser->can('admin_reviews_detail_show'))
                                        <input type="hidden" class="js-content_quality" value="{{ $review->content_quality }}">
                                        <input type="hidden" class="js-instructor_skills" value="{{ $review->instructor_skills }}">
                                        <input type="hidden" class="js-purchase_worth" value="{{ $review->purchase_worth }}">
                                        <input type="hidden" class="js-support_quality" value="{{ $review->support_quality }}">


                                        <button type="button" class="js-show-review-details btn-transparent text-primary mr-1" data-toggle="tooltip" data-placement="top" title="Rate Detail">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </button>
                                    @endif

                                    @if($authUser->can('admin_reviews_reply'))
                                        <a href="/lms{{ getAdminPanelUrl("/reviews/{$review->id}/reply") }}" target="_blank" class="btn-transparent text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.reply') }}">
                                            <i class="fa fa-reply"></i>
                                        </a>
                                    @endif

                                    @if($authUser->can('admin_reviews_delete'))
                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/reviews/'. $review->id.'/delete','btnClass' => ''])
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>

                <div class="card-footer text-center">
                    {{ $reviews->appends(request()->input())->links() }}
                </div>
            </section>
        </div>
    </section>

    <div class="modal fade" id="reviewRateDetail" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{trans('lms/admin/main.view_rates_details')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('lms/product.content_quality') }}:</span>
                        <span class="js-content_quality"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('lms/product.instructor_skills') }}:</span>
                        <span class="js-instructor_skills"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('lms/product.purchase_worth') }}:</span>
                        <span class="js-purchase_worth"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('lms/product.support_quality') }}:</span>
                        <span class="js-support_quality"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lms/admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('lms/admin/main.message') }}</h5>
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
    <script src="/assets/lms/assets/default/js/admin/reviews.min.js"></script>
@endpush
