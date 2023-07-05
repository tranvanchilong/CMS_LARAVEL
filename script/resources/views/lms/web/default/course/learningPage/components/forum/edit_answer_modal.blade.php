<div id="editQuestionAnswerModal" class="d-none">
    <div class="custom-modal-body">
        <h2 class="section-title after-line">{{ trans('lms/update.edit_answer') }}</h2>

        <form action="/lms" class="mt-20">

            <div class="form-group">
                <label class="input-label">{{ trans('lms/public.description') }}</label>
                <textarea name="description" rows="5" class="form-control"></textarea>
                <span class="invalid-feedback"></span>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-3">
                <button type="button" class="js-save-question-answer btn btn-sm btn-primary">{{ trans('lms/admin/main.post') }}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('lms/public.close') }}</button>
            </div>
        </form>
    </div>
</div>
