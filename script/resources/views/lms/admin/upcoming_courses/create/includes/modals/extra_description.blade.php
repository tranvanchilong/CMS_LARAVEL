<!-- Modal -->
<div class="d-none" id="extraDescriptionForm">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/update.add_items') }}</h3>

    <div class="js-form" data-action="/lms{{ getAdminPanelUrl('/webinar-extra-description/store') }}">
        <input type="hidden" name="upcoming_course_id" value="{{  !empty($upcomingCourse) ? $upcomingCourse->id :''  }}">
        <input type="hidden" name="type">

        <div class="js-form-groups">
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="js-no-company-input form-group">
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

            <div class="js-no-company-input form-group">
                <label class="input-label">{{ trans('lms/public.title') }}</label>
                <input type="text" name="value" class="js-ajax-title form-control"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" id="saveExtraDescription" class="btn btn-primary">{{ trans('lms/public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl">{{ trans('lms/public.close') }}</button>
        </div>
    </div>
</div>
