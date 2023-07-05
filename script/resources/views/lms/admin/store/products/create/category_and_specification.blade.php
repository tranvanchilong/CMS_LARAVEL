<section>
    <h2 class="section-title after-line mt-2 mb-4">{{ trans('lms/public.category') }}</h2>

    <div class="row">
        <div class="col-12 col-md-6 mt-30">

            <div class="form-group">
                <label class="input-label">{{ trans('lms/public.category') }}</label>

                <select id="categories" class="custom-select @error('category_id')  is-invalid @enderror" name="category_id" required>
                    <option {{ (!empty($product) and !empty($product->category_id)) ? '' : 'selected' }} disabled>{{ trans('lms/public.choose_category') }}</option>
                    @foreach($productCategories as $productCategory)
                        @if(!empty($productCategory->subCategories) and $productCategory->subCategories->count() > 0)
                            <optgroup label="{{  $productCategory->title }}">
                                @foreach($productCategory->subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}" {{ ((!empty($product) and $product->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                @endforeach
                            </optgroup>
                        @else
                            <option value="{{ $productCategory->id }}" {{ ((!empty($product) and $product->category_id == $productCategory->id) or old('category_id') == $productCategory->id) ? 'selected' : '' }}>{{ $productCategory->title }}</option>
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

        <div class="col-12 mt-20">
            <div class="form-group {{ (!empty($productCategoryFilters) and count($productCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
                <span class="input-label d-block">{{ trans('lms/public.category_filters') }}</span>
                <div id="categoriesFiltersCard" class="row">

                    @if(!empty($productCategoryFilters) and count($productCategoryFilters))
                        @foreach($productCategoryFilters as $filter)
                            <div class="col-12 col-md-3 mt-3">
                                <div class="webinar-category-filters">
                                    <strong class="category-filter-title d-block">{{ $filter->title }}</strong>
                                    <div class="py-2"></div>

                                    @php
                                        $productFilterOptions = $product->selectedFilterOptions->pluck('filter_option_id')->toArray();

                                        if (!empty(old('filters'))) {
                                            $productFilterOptions = array_merge($productFilterOptions, old('filters'));
                                        }
                                    @endphp

                                    @foreach($filter->options as $option)
                                        <div class="form-group d-flex align-items-center justify-content-between">
                                            <label class="cursor-pointer font-14 text-gray" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="filters[]" value="{{ $option->id }}" {{ ((!empty($productFilterOptions) && in_array($option->id, $productFilterOptions)) ? 'checked' : '') }} class="custom-control-input" id="filterOptions{{ $option->id }}">
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
        </div>

        <div class="col-12 mt-20">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="section-title after-line">{{ trans('lms/update.specifications') }}</h2>

                <div class="px-2 mt-3">
                    <button type="button" id="productAddSpecification" class="btn btn-primary btn-sm">{{ trans('lms/update.new_specification') }}</button>
                </div>
            </div>

            <div class="accordion-content-wrapper mt-15" id="specificationsAccordion" role="tablist" aria-multiselectable="true">
                @if(!empty($product->selectedSpecifications) and count($product->selectedSpecifications))
                    <div>
                        @foreach($product->selectedSpecifications as $selectedSpecificationRow)
                            @include('lms.admin.store.products.create.accordions.specification',['selectedSpecification' => $selectedSpecificationRow])
                        @endforeach
                    </div>
                @else
                    @include('lms.' . getTemplate() . '.includes.no-result',[
                        'file_name' => 'files.png',
                        'title' => trans('lms/update.specifications_no_result'),
                        'hint' => trans('lms/update.specifications_no_result_hint'),
                    ])
                @endif
            </div>

            <div id="newSpecificationForm" class="d-none">
                @include('lms.admin.store.products.create.accordions.specification')
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="section-title after-line">{{ trans('lms/public.faq') }}</h2>

                <div class="px-2 mt-3">
                    <button type="button" id="productAddFAQ" class="btn btn-primary btn-sm mt-10">{{ trans('lms/webinars.add_new_faqs') }}</button>
                </div>
            </div>

            <div class="accordion-content-wrapper mt-15" id="faqsAccordion" role="tablist" aria-multiselectable="true">
                @if(!empty($product->faqs) and count($product->faqs))
                    <div>
                        @foreach($product->faqs as $faqRow)
                            @include('lms.admin.store.products.create.accordions.faq',['faq' => $faqRow])
                        @endforeach
                    </div>
                @else
                    @include('lms.' . getTemplate() . '.includes.no-result',[
                        'file_name' => 'faq.png',
                        'title' => trans('lms/update.product_faq_no_result'),
                        'hint' => trans('lms/update.product_faq_no_result_hint'),
                    ])
                @endif
            </div>

            <div id="newFaqForm" class="d-none">
                @include('lms.admin.store.products.create.accordions.faq')
            </div>
        </div>
    </div>
</section>
