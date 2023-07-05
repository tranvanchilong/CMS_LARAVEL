<div id="upcomingAssignCourseModal" class="" data-action="/lms/panel/upcoming_courses/{{ $upcomingCourse->id }}/assign-course">
    <div class="custom-modal-body">
        <h2 class="section-title after-line">{{ trans('lms/update.assign_published_course') }}</h2>

        <div class="mt-20">
            <input type="hidden" name="upcoming_id" value="{{ $upcomingCourse->id }}">

            <div class="form-group">
                <label class="input-label">{{ trans('lms/product.course') }}</label>
                <select name="course" class="js-ajax-course form-control js-select2">
                    <option value="">{{ trans('lms/update.select_a_course') }}</option>
                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback d-block"></div>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-20">
                <button type="button" class="js-save-assign-course btn btn-sm btn-primary">{{ trans('lms/public.save') }}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('lms/public.close') }}</button>
            </div>
        </div>
    </div>
</div>
