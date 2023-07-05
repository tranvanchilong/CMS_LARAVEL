<!-- Modal -->
<div class="d-none" id="quizzesModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/public.add_quiz') }}</h3>

    <div class="js-form" data-action="/lms{{ getAdminPanelUrl() }}/webinar-quiz/store">
        <input type="hidden" name="webinar_id" value="{{  !empty($webinar) ? $webinar->id :''  }}">

        <div class="form-group mt-15">
            <label class="input-label d-block">{{ trans('lms/public.select_a_quiz') }}</label>

            <select name="quiz_id" class="js-ajax-quiz_id form-control quiz-select2" data-placeholder="{{ trans('lms/public.add_quiz') }}">
                <option disabled selected></option>
                @if(!empty($webinar))
                    @foreach($teacherQuizzes as $teacherQuiz)
                        <option value="{{ $teacherQuiz->id }}">{{ $teacherQuiz->title }}</option>
                    @endforeach
                @endif
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('lms/public.chapter') }}</label>
            <select class="js-ajax-chapter_id custom-select" name="chapter_id">
                <option value="">{{ trans('lms/admin/main.no_chapter') }}</option>

                @if(!empty($chapters))
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                @endif
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" id="saveQuiz" class="btn btn-primary">{{ trans('lms/public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl">{{ trans('lms/public.close') }}</button>
        </div>
    </div>
</div>
