@push('styles_top')
    <link href="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush


<section class="mt-50">
    <div class="">
        <h2 class="section-title after-line">{{ trans('lms/public.faq') }} ({{ trans('lms/public.optional') }})</h2>
    </div>

    <button id="webinarAddFAQ" data-bundle-id="{{ $bundle->id }}" type="button" class="btn btn-primary btn-sm mt-15">{{ trans('lms/public.add_faq') }}</button>

    <div class="row mt-10">
        <div class="col-12">

            <div class="accordion-content-wrapper mt-15" id="faqsAccordion" role="tablist" aria-multiselectable="true">
                @if(!empty($bundle->faqs) and count($bundle->faqs))
                    <ul class="draggable-lists" data-order-table="faqs">
                        @foreach($bundle->faqs as $faqInfo)
                            @include('lms.web.default.panel.bundle.create_includes.accordions.faq',['bundle' => $bundle,'faq' => $faqInfo])
                        @endforeach
                    </ul>
                @else
                    @include('lms.' . getTemplate() . '.includes.no-result',[
                        'file_name' => 'faq.png',
                        'title' => trans('lms/public.faq_no_result'),
                        'hint' => trans('lms/public.faq_no_result_hint'),
                    ])
                @endif
            </div>
        </div>
    </div>
</section>

<div id="newFaqForm" class="d-none">
    @include('lms.web.default.panel.bundle.create_includes.accordions.faq',['bundle' => $bundle])
</div>


@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
