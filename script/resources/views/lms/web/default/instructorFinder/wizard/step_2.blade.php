<div class="wizard-step-1">
    <h3 class="font-20 text-dark font-weight-bold">{{ trans('lms/update.meeting_type') }}</h3>

    <span class="d-block mt-30 text-gray wizard-step-num">
        {{ trans('lms/update.step') }} 2/4
    </span>

    <span class="d-block font-16 font-weight-500 mt-30">{{ trans('lms/update.choose_the_meeting_type') }}</span>

    <div class="form-group mt-10">
        <label class="input-label">{{ trans('lms/update.meeting_type') }}</label>

        <div class="d-flex align-items-center wizard-custom-radio mt-5">
            <div class="wizard-custom-radio-item">
                <input type="radio" name="meeting_type" checked value="all" id="all" class="">
                <label class="font-12 cursor-pointer" for="all">{{ trans('lms/public.all') }}</label>
            </div>

            <div class="wizard-custom-radio-item">
                <input type="radio" name="meeting_type" value="in_person" id="in_person" class="">
                <label class="font-12 cursor-pointer" for="in_person">{{ trans('lms/update.in_person') }}</label>
            </div>

            <div class="wizard-custom-radio-item">
                <input type="radio" name="meeting_type" value="online" id="online" class="">
                <label class="font-12 cursor-pointer" for="online">{{ trans('lms/update.online') }}</label>
            </div>
        </div>
    </div>

    <div id="regionCard" class="d-none">
        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('lms/update.country') }}</label>

            <select name="country_id" class="form-control">
                <option value="">{{ trans('lms/update.select_country') }}</option>

                @if(!empty($countries))
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->title }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('lms/update.province') }}</label>

            <select name="province_id" class="form-control" disabled>
                <option value="">{{ trans('lms/update.select_province') }}</option>
            </select>
        </div>

        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('lms/update.city') }}</label>

            <select name="city_id" class="form-control" disabled>
                <option value="">{{ trans('lms/update.select_city') }}</option>
            </select>
        </div>

        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('lms/update.district') }}</label>

            <select name="district_id" class="form-control" disabled>
                <option value="">{{ trans('lms/update.select_district') }}</option>
            </select>
        </div>
    </div>

    <div class="">
        <label class="input-label">{{ trans('lms/update.population') }}</label>

        <div class="d-flex align-items-center wizard-custom-radio mt-5">
            <div class="wizard-custom-radio-item">
                <input type="radio" name="population" value="all" checked id="population_all" class="">
                <label class="font-12 cursor-pointer" for="population_all">{{ trans('lms/public.all') }}</label>
            </div>

            <div class="wizard-custom-radio-item">
                <input type="radio" name="population" value="single" id="population_single" class="">
                <label class="font-12 cursor-pointer" for="population_single">{{ trans('lms/update.single') }}</label>
            </div>

            <div class="wizard-custom-radio-item">
                <input type="radio" name="population" value="group" id="population_group" class="">
                <label class="font-12 cursor-pointer" for="population_group">{{ trans('lms/update.group') }}</label>
            </div>
        </div>
    </div>
</div>
