@push('styles_top')
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
    <link href="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush


<section class="mt-50">
    <div class="">
        <h2 class="section-title after-line">{{ trans('lms/product.courses') }}</h2>
    </div>

    <button id="addBundleWebinar" data-bundle-id="{{ $bundle->id }}" type="button" class="btn btn-primary btn-sm mt-15">{{ trans('lms/update.add_course') }}</button>

    <div class="row mt-10">
        <div class="col-12">

            <div class="accordion-content-wrapper mt-15" id="bundleWebinarsAccordion" role="tablist" aria-multiselectable="true">
                @if(!empty($bundle->bundleWebinars) and count($bundle->bundleWebinars))
                    <ul class="draggable-lists" data-order-table="bundle_webinars">
                        @foreach($bundle->bundleWebinars as $bundleWebinarRow)
                            @include('lms.web.default.panel.bundle.create_includes.accordions.bundle-webinars',['bundle' => $bundle, 'bundleWebinar' => $bundleWebinarRow])
                        @endforeach
                    </ul>
                @else
                    @include('lms.' . getTemplate() . '.includes.no-result',[
                        'file_name' => 'comment.png',
                        'title' => trans('lms/update.bundle_webinar_no_result'),
                        'hint' => trans('lms/update.bundle_webinar_no_result_hint'),
                    ])
                @endif
            </div>
        </div>
    </div>
</section>

<div id="newBundleWebinarForm" class="d-none">
    @include('lms.web.default.panel.bundle.create_includes.accordions.bundle-webinars',['bundle' => $bundle])
</div>


@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
