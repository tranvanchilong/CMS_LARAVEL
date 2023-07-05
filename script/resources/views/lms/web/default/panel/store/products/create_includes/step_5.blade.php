@push('styles_top')

@endpush


<section class="mt-20">
    <h2 class="section-title after-line">{{ trans('lms/public.message_to_reviewer') }}</h2>
    <div class="row">
        <div class="col-12">
            <div class="form-group mt-15">
                <textarea name="message_for_reviewer" rows="10" class="form-control">{{ (!empty($product) and $product->message_for_reviewer) ? $product->message_for_reviewer : old('message_for_reviewer') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group mt-10">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="cursor-pointer input-label" for="rulesSwitch">{{ trans('lms/public.agree_rules') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="rules" class="custom-control-input " id="rulesSwitch">
                        <label class="custom-control-label" for="rulesSwitch"></label>
                    </div>
                </div>

                @error('rules')
                <div class="text-danger mt-10">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
</section>

@push('scripts_bottom')

@endpush
