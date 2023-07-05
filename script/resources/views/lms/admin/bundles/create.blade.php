@extends('lms.admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        .bootstrap-timepicker-widget table td input {
            width: 35px !important;
        }

        .select2-container {
            z-index: 1212 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{!empty($bundle) ?trans('lms//admin/main.edit'): trans('lms/admin/main.new') }} {{ trans('lms/update.bundle') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="/lms{{ getAdminPanelUrl() }}/bundles">{{ trans('lms/update.bundles') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($bundle) ?trans('lms//admin/main.edit'): trans('lms/admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="/lms{{ getAdminPanelUrl() }}/bundles/{{ !empty($bundle) ? $bundle->id.'/update' : 'store' }}" id="webinarForm" class="webinar-form">
                                {{ csrf_field() }}
                                <section>
                                    <h2 class="section-title after-line">{{ trans('lms/public.basic_information') }}</h2>

                                    <div class="row">
                                        <div class="col-12 col-md-5">

                                            @if(!empty(getGeneralSettings('content_translate')))
                                                <div class="form-group">
                                                    <label class="input-label">{{ trans('lms/auth.language') }}</label>
                                                    <select name="locale" class="form-control {{ !empty($bundle) ? 'js-edit-content-locale' : '' }}">
                                                        @foreach($userLanguages as $lang => $language)
                                                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('locale')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            @else
                                                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                            @endif


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.title') }}</label>
                                                <input type="text" name="title" value="{{ !empty($bundle) ? $bundle->title : old('title') }}" class="form-control @error('title')  is-invalid @enderror" placeholder=""/>
                                                @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/update.required_points') }}</label>
                                                <input type="text" name="points" value="{{ !empty($bundle) ? $bundle->points : old('points') }}" class="form-control @error('points')  is-invalid @enderror" placeholder="Empty means inactive this mode"/>
                                                <div class="text-muted text-small mt-1">{{ trans('lms/update.product_points_hint') }}</div>
                                                @error('points')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/update.bundle_url') }}</label>
                                                <input type="text" name="slug" value="{{ !empty($bundle) ? $bundle->slug : old('slug') }}" class="form-control @error('slug')  is-invalid @enderror" placeholder=""/>
                                                <div class="text-muted text-small mt-1">{{ trans('lms/update.bundle_url_hint') }}</div>
                                                @error('slug')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            @if(!empty($bundle) and $bundle->creator->isOrganization())
                                                <div class="form-group mt-15 ">
                                                    <label class="input-label d-block">{{ trans('lms/admin/main.organization') }}</label>

                                                    <select class="form-control" disabled readonly data-placeholder="{{ trans('lms/public.search_instructor') }}">
                                                        <option selected>{{ $bundle->creator->full_name }}</option>
                                                    </select>
                                                </div>
                                            @endif


                                            <div class="form-group mt-15 ">
                                                <label class="input-label d-block">{{ trans('lms/admin/main.select_a_instructor') }}</label>


                                                <select name="teacher_id" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                                        data-placeholder="{{ trans('lms/public.select_a_teacher') }}"
                                                >
                                                    @if(!empty($bundle))
                                                        <option value="{{ $bundle->teacher->id }}" selected>{{ $bundle->teacher->full_name }}</option>
                                                    @else
                                                        <option selected disabled>{{ trans('lms/public.select_a_teacher') }}</option>
                                                    @endif
                                                </select>

                                                @error('teacher_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.seo_description') }}</label>
                                                <input type="text" name="seo_description" value="{{ !empty($bundle) ? $bundle->seo_description : old('seo_description') }}" class="form-control @error('seo_description')  is-invalid @enderror"/>
                                                <div class="text-muted text-small mt-1">{{ trans('lms/admin/main.seo_description_hint') }}</div>
                                                @error('seo_description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.thumbnail_image') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="thumbnail" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="thumbnail" id="thumbnail" value="{{ !empty($bundle) ? $bundle->thumbnail : old('thumbnail') }}" class="form-control @error('thumbnail')  is-invalid @enderror"/>
                                                    <div class="input-group-append">
                                                        <button type="button" class="input-group-text admin-file-view" data-input="thumbnail">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('thumbnail')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.cover_image') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="cover_image" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="image_cover" id="cover_image" value="{{ !empty($bundle) ? $bundle->image_cover : old('image_cover') }}" class="form-control @error('image_cover')  is-invalid @enderror"/>
                                                    <div class="input-group-append">
                                                        <button type="button" class="input-group-text admin-file-view" data-input="cover_image">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('image_cover')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mt-25">
                                                <label class="input-label">{{ trans('lms/public.demo_video') }} ({{ trans('lms/public.optional') }})</label>


                                                <div class="">
                                                    <label class="input-label font-12">{{ trans('lms/public.source') }}</label>
                                                    <select name="video_demo_source"
                                                            class="js-video-demo-source form-control"
                                                    >
                                                        @foreach(\App\Models\LMS\Webinar::$videoDemoSource as $source)
                                                            <option value="{{ $source }}" @if(!empty($bundle) and $bundle->video_demo_source == $source) selected @endif>{{ trans('lms/update.file_source_'.$source) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mt-0">
                                                <label class="input-label font-12">{{ trans('lms/update.path') }}</label>
                                                <div class="input-group js-video-demo-path-input">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="js-video-demo-path-upload input-group-text admin-file-manager {{ (empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? '' : 'd-none' }}" data-input="demo_video" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>

                                                        <button type="button" class="js-video-demo-path-links rounded-left input-group-text input-group-text-rounded-left  {{ (empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? 'd-none' : '' }}">
                                                            <i class="fa fa-link"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="video_demo" id="demo_video" value="{{ !empty($bundle) ? $bundle->video_demo : old('video_demo') }}" class="form-control @error('video_demo')  is-invalid @enderror"/>
                                                    @error('video_demo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.description') }}</label>
                                                <textarea id="summernote" name="description" class="form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('lms/forms.webinar_description_placeholder') }}">{!! !empty($bundle) ? $bundle->description : old('description')  !!}</textarea>
                                                @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="mt-3">
                                    <h2 class="section-title after-line">{{ trans('lms/public.additional_information') }}</h2>
                                    <div class="row">
                                        <div class="col-12 col-md-6">

                                            <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                                <label class="" for="subscribeSwitch">{{ trans('lms/public.subscribe') }}</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="subscribe" class="custom-control-input" id="subscribeSwitch" {{ !empty($bundle) && $bundle->subscribe ? 'checked' : ''  }}>
                                                    <label class="custom-control-label" for="subscribeSwitch"></label>
                                                </div>
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/update.access_days') }}</label>
                                                <input type="text" name="access_days" value="{{ !empty($bundle) ? $bundle->access_days : old('access_days') }}" class="form-control @error('access_days')  is-invalid @enderror"/>
                                                @error('access_days')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <p class="mt-1">- {{ trans('lms/update.access_days_input_hint') }}</p>
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.price') }} ({{ $currency }})</label>
                                                <input type="text" name="price" value="{{ !empty($bundle) ? $bundle->price : old('price') }}" class="form-control @error('price')  is-invalid @enderror" placeholder="{{ trans('lms/public.0_for_free') }}"/>
                                                @error('price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label d-block">{{ trans('lms/public.tags') }}</label>
                                                <input type="text" name="tags" data-max-tag="5" value="{{ !empty($bundle) ? implode(',',$bundleTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('lms/public.type_tag_name_and_press_enter') }} ({{ trans('lms/admin/main.max') }} : 5)"/>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('lms/public.category') }}</label>

                                                <select id="categories" class="custom-select @error('category_id')  is-invalid @enderror" name="category_id" required>
                                                    <option {{ !empty($bundle) ? '' : 'selected' }} disabled>{{ trans('lms/public.choose_category') }}</option>
                                                    @foreach($categories as $category)
                                                        @if(!empty($category->subCategories) and count($category->subCategories))
                                                            <optgroup label="{{  $category->title }}">
                                                                @foreach($category->subCategories as $subCategory)
                                                                    <option value="{{ $subCategory->id }}" {{ (!empty($bundle) and $bundle->category_id == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @else
                                                            <option value="{{ $category->id }}" {{ (!empty($bundle) and $bundle->category_id == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('category_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group mt-15 {{ (!empty($bundleCategoryFilters) and count($bundleCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
                                        <span class="input-label d-block">{{ trans('lms/public.category_filters') }}</span>
                                        <div id="categoriesFiltersCard" class="row mt-3">

                                            @if(!empty($bundleCategoryFilters) and count($bundleCategoryFilters))
                                                @foreach($bundleCategoryFilters as $filter)
                                                    <div class="col-12 col-md-3">
                                                        <div class="webinar-category-filters">
                                                            <strong class="category-filter-title d-block">{{ $filter->title }}</strong>
                                                            <div class="py-10"></div>

                                                            @foreach($filter->options as $option)
                                                                <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                                                    <label class="text-gray font-14" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="filters[]" value="{{ $option->id }}" {{ ((!empty($bundleFilterOptions) && in_array($option->id,$bundleFilterOptions)) ? 'checked' : '') }} class="custom-control-input" id="filterOptions{{ $option->id }}">
                                                                        <label class="custom-control-label" for="filterOptions{{ $option->id }}"></label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </section>

                                @if(!empty($bundle))
                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line">{{ trans('lms/admin/main.price_plans') }}</h2>
                                            <button id="webinarAddTicket" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('lms/admin/main.add_price_plan') }}</button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">

                                                @if(!empty($tickets) and !$tickets->isEmpty())
                                                    <div class="table-responsive">
                                                        <table class="table table-striped text-center font-14">

                                                            <tr>
                                                                <th>{{ trans('lms/public.title') }}</th>
                                                                <th>{{ trans('lms/public.discount') }}</th>
                                                                <th>{{ trans('lms/public.capacity') }}</th>
                                                                <th>{{ trans('lms/public.date') }}</th>
                                                                <th></th>
                                                            </tr>

                                                            @foreach($tickets as $ticket)
                                                                <tr>
                                                                    <th scope="row">{{ $ticket->title }}</th>
                                                                    <td>{{ $ticket->discount }}%</td>
                                                                    <td>{{ $ticket->capacity }}</td>
                                                                    <td>{{ dateTimeFormat($ticket->start_date,'j F Y') }} - {{ (new DateTime())->setTimestamp($ticket->end_date)->format('j F Y') }}</td>
                                                                    <td>
                                                                        <button type="button" data-ticket-id="{{ $ticket->id }}" data-webinar-id="{{ !empty($bundle) ? $bundle->id : '' }}" class="edit-ticket btn-transparent text-primary mt-1" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>

                                                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/tickets/'. $ticket->id .'/delete', 'btnClass' => ' mt-1'])
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                @else
                                                    @include('lms.admin.includes.no-result',[
                                                        'file_name' => 'ticket.png',
                                                        'title' => trans('lms/public.ticket_no_result'),
                                                        'hint' => trans('lms/public.ticket_no_result_hint'),
                                                    ])
                                                @endif
                                            </div>
                                        </div>
                                    </section>


                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line">{{ trans('lms/product.courses') }}</h2>
                                            <button id="bundleAddNewCourses" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('lms/update.add_new_course') }}</button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">
                                                @if(!empty($bundleWebinars) and !$bundleWebinars->isEmpty())
                                                    <div class="table-responsive">
                                                        <table class="table table-striped text-center font-14">

                                                            <tr>
                                                                <th>{{ trans('lms/public.title') }}</th>
                                                                <th class="text-left">{{ trans('lms/public.instructor') }}</th>
                                                                <th>{{ trans('lms/public.price') }}</th>
                                                                <th>{{ trans('lms/public.publish_date') }}</th>
                                                                <th></th>
                                                            </tr>

                                                            @foreach($bundleWebinars as $bundleWebinar)
                                                                @if(!empty($bundleWebinar->webinar->title))
                                                                    <tr>
                                                                        <th>{{ $bundleWebinar->webinar->title }}</th>
                                                                        <td class="text-left">{{ $bundleWebinar->webinar->teacher->full_name }}</td>
                                                                        <td>{{  handlePrice($bundleWebinar->webinar->price) }}</td>
                                                                        <td>{{ dateTimeFormat($bundleWebinar->webinar->created_at,'j F Y | H:i') }}</td>

                                                                        <td>
                                                                            <button type="button" data-item-id="{{ $bundleWebinar->id }}" data-bundle-id="{{ !empty($bundle) ? $bundle->id : '' }}" class="edit-bundle-webinar btn-transparent text-primary mt-1" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>

                                                                            @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/bundle-webinars/'. $bundleWebinar->id .'/delete', 'btnClass' => ' mt-1'])
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                @else
                                                    @include('lms.admin.includes.no-result',[
                                                        'file_name' => 'comment.png',
                                                        'title' => trans('lms/update.bundle_webinar_no_result'),
                                                        'hint' => trans('lms/update.bundle_webinar_no_result_hint'),
                                                    ])
                                                @endif
                                            </div>
                                        </div>
                                    </section>

                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line">{{ trans('lms/public.faq') }}</h2>
                                            <button id="webinarAddFAQ" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('lms/public.add_faq') }}</button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">
                                                @if(!empty($faqs) and !$faqs->isEmpty())
                                                    <div class="table-responsive">
                                                        <table class="table table-striped text-center font-14">

                                                            <tr>
                                                                <th>{{ trans('lms/public.title') }}</th>
                                                                <th>{{ trans('lms/public.answer') }}</th>
                                                                <th></th>
                                                            </tr>

                                                            @foreach($faqs as $faq)
                                                                <tr>
                                                                    <th>{{ $faq->title }}</th>
                                                                    <td>
                                                                        <button type="button" class="js-get-faq-description btn btn-sm btn-gray200">{{ trans('lms/public.view') }}</button>
                                                                        <input type="hidden" value="{{ $faq->answer }}"/>
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <button type="button" data-faq-id="{{ $faq->id }}" data-webinar-id="{{ !empty($bundle) ? $bundle->id : '' }}" class="edit-faq btn-transparent text-primary mt-1" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>

                                                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/faqs/'. $faq->id .'/delete', 'btnClass' => ' mt-1'])
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                @else
                                                    @include('lms.admin.includes.no-result',[
                                                        'file_name' => 'faq.png',
                                                        'title' => trans('lms/public.faq_no_result'),
                                                        'hint' => trans('lms/public.faq_no_result_hint'),
                                                    ])
                                                @endif
                                            </div>
                                        </div>
                                    </section>
                                @endif

                                <section class="mt-3">
                                    <h2 class="section-title after-line">{{ trans('lms/public.message_to_reviewer') }}</h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-15">
                                                <textarea name="message_for_reviewer" rows="10" class="form-control">{{ (!empty($bundle) and $bundle->message_for_reviewer) ? $bundle->message_for_reviewer : old('message_for_reviewer') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <input type="hidden" name="draft" value="no" id="forDraft"/>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" id="saveAndPublish" class="btn btn-success">{{ !empty($bundle) ? trans('lms/admin/main.save_and_publish') : trans('lms/admin/main.save_and_continue') }}</button>

                                        @if(!empty($bundle))
                                            <button type="button" id="saveReject" class="btn btn-warning">{{ trans('lms/public.reject') }}</button>

                                            @include('lms.admin.includes.delete_button',[
                                                    'url' => '/lms'.getAdminPanelUrl().'/bundles/'. $bundle->id .'/delete',
                                                    'btnText' => trans('lms/public.delete'),
                                                    'hideDefaultClass' => true,
                                                    'btnClass' => 'btn btn-danger'
                                                    ])
                                        @endif
                                    </div>
                                </div>
                            </form>


                            @include('lms.admin.bundles.modals.bundle-webinar')
                            @include('lms.admin.bundles.modals.ticket')
                            @include('lms.admin.bundles.modals.faq')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('lms/webinars.success_store') }}';
        var titleLang = '{{ trans('lms/admin/main.title') }}';
    </script>

    <script src="/assets/lms/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/lms/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/lms/assets/admin/js/webinar.min.js"></script>
@endpush
