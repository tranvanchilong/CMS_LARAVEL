<div class="d-none" id="joinMeetingLinkModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/panel.join_live_meeting') }}</h3>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="form-group">
                <label class="input-label">{{ trans('lms/panel.url') }}</label>
                <input type="text" readonly name="link" class="form-control"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label class="input-label">{{ trans('lms/auth.password') }} ({{ trans('lms/public.optional') }})</label>
                <input type="text" readonly name="password" class="form-control"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <p class="font-weight-500 text-gray">{{ trans('lms/panel.add_live_meeting_link_hint') }}</p>

    <div class="mt-30 d-flex align-items-center justify-content-end">
        <a href="/lms" target="_blank" class="js-join-meeting-link btn btn-sm btn-primary">{{ trans('lms/footer.join') }}</a>
        <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('lms/public.close') }}</button>
    </div>
</div>
@push('scripts_bottom')
    <script src="/assets/lms/assets/default/js/panel/meeting/join_modal.min.js"></script>
@endpush
