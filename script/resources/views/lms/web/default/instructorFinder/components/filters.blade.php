<div class="mt-20 p-20 rounded-sm shadow-lg border border-gray300 filters-container">
    <h3 class="category-filter-title font-20 font-weight-bold text-dark-blue">{{ trans('lms/update.filters') }}</h3>

    <div class="form-group mt-20">
        <label for="category_id">{{ trans('lms/public.category') }}</label>

        <select name="category_id" id="category_id" class="form-control">
            <option value="">{{ trans('lms/webinars.select_category') }}</option>

            @if(!empty($categories))
                @foreach($categories as $category)
                    @if(!empty($category->subCategories) and count($category->subCategories))
                        <optgroup label="{{  $category->title }}">
                            @foreach($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group">
        <label for="level_of_training">{{ trans('lms/update.student_level') }}</label>

        <select name="level_of_training" class="form-control">
            <option value="">{{ trans('lms/update.not_preferenced') }}</option>
            <option value="beginner" {{ (request()->get('level_of_training') == 'beginner') ? 'selected' : '' }}>{{ trans('lms/update.beginner') }}</option>
            <option value="middle" {{ (request()->get('level_of_training') == 'middle') ? 'selected' : '' }}>{{ trans('lms/update.middle') }}</option>
            <option value="expert" {{ (request()->get('level_of_training') == 'expert') ? 'selected' : '' }}>{{ trans('lms/update.expert') }}</option>
        </select>
    </div>

    <div class="form-group">
        <label for="gender">{{ trans('lms/update.instructor_gender') }}</label>

        <select name="gender" id="gender" class="form-control">
            <option value="">{{ trans('lms/update.not_preferenced') }}</option>

            <option value="man" {{ (request()->get('gender') == 'man') ? 'selected' : '' }}>{{ trans('lms/update.man') }}</option>
            <option value="woman" {{ (request()->get('gender') == 'woman') ? 'selected' : '' }}>{{ trans('lms/update.woman') }}</option>
        </select>
    </div>

    <div class="form-group">
        <label for="instructor_type">{{ trans('lms/update.instructor_type') }}</label>

        <select name="role" id="instructor_type" class="form-control">
            <option value="">{{ trans('lms/update.not_preferenced') }}</option>
            <option value="{{ \App\Models\LMS\Role::$teacher }}" {{ (request()->get('role') == \App\Models\LMS\Role::$teacher) ? 'selected' : '' }}>{{ trans('lms/public.instructor') }}</option>
            <option value="{{ \App\Models\LMS\Role::$organization }}" {{ (request()->get('role') == \App\Models\LMS\Role::$organization) ? 'selected' : '' }}>{{ trans('lms/home.organization') }}</option>
        </select>
    </div>

    <div class="form-group">
        <label class="input-label">{{ trans('lms/update.meeting_type') }}</label>

        <div class="d-flex align-items-center wizard-custom-radio mt-5">
            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="meeting_type" value="all" id="all" class="" {{ (request()->get('meeting_type') == 'all') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="all">{{ trans('lms/public.all') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="meeting_type" value="in_person" id="in_person" class="" {{ (request()->get('meeting_type') == 'in_person') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="in_person">{{ trans('lms/update.in_person') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="meeting_type" value="online" id="online" class="" {{ (request()->get('meeting_type') == 'online') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="online">{{ trans('lms/update.online') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="input-label">{{ trans('lms/update.population') }}</label>

        <div class="d-flex align-items-center wizard-custom-radio mt-5">
            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="population" value="all" id="population_all" class="" {{ (request()->get('population') == 'all') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="population_all">{{ trans('lms/public.all') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="population" value="single" id="population_single" class="" {{ (request()->get('population') == 'single') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="population_single">{{ trans('lms/update.single') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="population" value="group" id="population_group" class="" {{ (request()->get('population') == 'group') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="population_group">{{ trans('lms/update.group') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group pb-20">
        <label class="form-label">{{ trans('lms/update.price_range') }}</label>
        <div
            class="range wrunner-value-bottom"
            id="priceRange"
            data-minLimit="0"
            data-maxLimit="1000"
        >
            <input type="hidden" name="min_price" value="{{ request()->get('min_price') ?? null }}">
            <input type="hidden" name="max_price" value="{{ request()->get('max_price') ?? null }}">
        </div>
    </div>

    <div class="form-group pb-20">
        <label class="form-label">{{ trans('lms/update.instructor_age') }}</label>
        <div
            class="range wrunner-value-bottom"
            id="instructorAgeRange"
            data-minLimit="0"
            data-maxLimit="100"
        >
            <input type="hidden" name="min_age" value="{{ request()->get('min_age') ?? null }}">
            <input type="hidden" name="max_age" value="{{ request()->get('max_age') ?? null }}">
        </div>
    </div>
</div>
