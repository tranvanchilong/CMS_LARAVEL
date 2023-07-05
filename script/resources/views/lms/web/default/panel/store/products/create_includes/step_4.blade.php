@push('styles_top')
    <link href="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/lms/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

<div class="row">
    <div class="col-12 mt-20">
        <div class="">
            <h2 class="section-title after-line">{{ trans('lms/update.specifications') }}</h2>

            <button type="button" id="productAddSpecification" class="btn btn-primary btn-sm mt-10">{{ trans('lms/update.new_specification') }}</button>
        </div>

        <div class="accordion-content-wrapper mt-15" id="specificationsAccordion" role="tablist" aria-multiselectable="true">
            @if(!empty($product->selectedSpecifications) and count($product->selectedSpecifications))
                <ul class="draggable-lists" data-order-path="/panel/store/products/specifications/order-items">
                    @foreach($product->selectedSpecifications as $selectedSpecificationRow)
                        @include('lms.web.default.panel.store.products.create_includes.accordions.specification',['selectedSpecification' => $selectedSpecificationRow])
                    @endforeach
                </ul>
            @else
                @include('lms.' . getTemplate() . '.includes.no-result',[
                    'file_name' => 'files.png',
                    'title' => trans('lms/update.specifications_no_result'),
                    'hint' => trans('lms/update.specifications_no_result_hint'),
                ])
            @endif
        </div>

        <div id="newSpecificationForm" class="d-none">
            @include('lms.web.default.panel.store.products.create_includes.accordions.specification')
        </div>
    </div>


    <div class="col-12 mt-40">
        <div class="">
            <h2 class="section-title after-line">{{ trans('lms/public.faq') }}</h2>

            <button type="button" id="productAddFAQ" class="btn btn-primary btn-sm mt-10">{{ trans('lms/webinars.add_new_faqs') }}</button>
        </div>

        <div class="accordion-content-wrapper mt-15" id="faqsAccordion" role="tablist" aria-multiselectable="true">
            @if(!empty($product->faqs) and count($product->faqs))
                <ul class="draggable-lists2" data-order-path="/panel/store/products/faqs/order-items">
                    @foreach($product->faqs as $faqRow)
                        @include('lms.web.default.panel.store.products.create_includes.accordions.faq',['faq' => $faqRow])
                    @endforeach
                </ul>
            @else
                @include('lms.' . getTemplate() . '.includes.no-result',[
                    'file_name' => 'faq.png',
                    'title' => trans('lms/update.product_faq_no_result'),
                    'hint' => trans('lms/update.product_faq_no_result_hint'),
                ])
            @endif
        </div>

        <div id="newFaqForm" class="d-none">
            @include('lms.web.default.panel.store.products.create_includes.accordions.faq')
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/lms/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
