<!-- Modal -->
<div class="d-none" id="bundleWebinarsModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/update.add_new_course') }}</h3>

    <div class="js-form" data-action="/lms{{ getAdminPanelUrl() }}/bundle-webinars/store">
        <input type="hidden" name="bundle_id" value="{{  !empty($bundle) ? $bundle->id :''  }}">

        <div class="form-group mt-15">
            <label class="input-label d-block">{{ trans('lms/panel.select_course') }}</label>
            <select name="webinar_id" class="js-ajax-webinar_id form-control bundleWebinars-select" data-bundle-id="{{  !empty($bundle) ? $bundle->id : '' }}" data-placeholder="{{ trans('lms/panel.select_course') }}">

                @if(!empty($userWebinars) and count($userWebinars))
                    @foreach($userWebinars as $userWebinar)
                        <option value="{{ $userWebinar->id }}">{{ $userWebinar->title }}</option>
                    @endforeach
                @endif
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" id="saveBundleWebinar" class="btn btn-primary">{{ trans('lms/public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl">{{ trans('lms/public.close') }}</button>
        </div>
    </div>
</div>
