
<div class="wizard-step-1">
    <h3 class="font-20 text-dark font-weight-bold">{{ trans('lms/update.your_skill_level') }}</h3>

    <span class="d-block mt-30 text-gray wizard-step-num">
        {{ trans('lms/update.step') }} 3/4
    </span>

    <div class="form-group mt-30">
        <label class="input-label font-weight-500">{{ trans('lms/update.which_skill_level_do_you_want_to_learn') }}</label>

        <select name="level_of_training" class="form-control mt-20">
            <option value="beginner" {{ (request()->get('level_of_training') == 'beginner') ? 'selected' : '' }}>{{ trans('lms/update.beginner') }}</option>
            <option value="middle" {{ (empty(request()->get('level_of_training')) or request()->get('level_of_training') == 'middle') ? 'selected' : '' }}>{{ trans('lms/update.middle') }}</option>
            <option value="expert" {{ (request()->get('level_of_training') == 'expert') ? 'selected' : '' }}>{{ trans('lms/update.expert') }}</option>
        </select>
    </div>

</div>
