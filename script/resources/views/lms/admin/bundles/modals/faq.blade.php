<!-- Modal -->
<div class="d-none" id="webinarFaqModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/public.add_faq') }}</h3>

    <div class="js-faq-form" data-action="/lms{{ getAdminPanelUrl() }}/faqs/store">
        <input type="hidden" name="bundle_id" value="{{  !empty($bundle) ? $bundle->id :''  }}">

        @if(!empty(getGeneralSettings('content_translate')))
            <div class="form-group">
                <label class="input-label">{{ trans('lms/auth.language') }}</label>
                <select name="locale" class="form-control ">
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


        <div class="form-group">
            <label class="input-label">{{ trans('lms/public.title') }}</label>
            <input type="text" name="title" class="js-ajax-title form-control" placeholder="{{ trans('lms/forms.maximum_255_characters') }}"/>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('lms/public.answer') }}</label>
            <textarea name="answer" class="js-ajax-answer form-control" rows="6"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" id="saveFAQ" class="btn btn-primary">{{ trans('lms/public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl">{{ trans('lms/public.close') }}</button>
        </div>
    </div>
</div>
