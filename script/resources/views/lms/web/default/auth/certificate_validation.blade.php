@extends('lms.'.getTemplate().'.layouts.app')

@section('content')
    <div class="container">
        <div class="row login-container">
            <div class="col-12 col-md-6 pl-0">
                <img src="{{get_path_lms()}}{{ getPageBackgroundSettings('certificate_validation') }}" class="img-cover" alt="Login">
            </div>

            <div class="col-12 col-md-6">

                <div class="login-card">
                    <h1 class="font-20 font-weight-bold">{{ trans('lms/site.certificate_validation') }}</h1>
                    <p class="font-14 text-gray mt-15">{{ trans('lms/site.certificate_validation_hint') }}</p>


                    <form method="post" action="/lms/certificate_validation/validate" class="mt-35">
                        {{ csrf_field() }}


                        <div class="form-group">
                            <label class="input-label" for="code">{{ trans('lms/public.certificate_id') }}:</label>
                            <input type="tel" name="certificate_id" class="form-control" id="certificate_id" aria-describedby="certificate_idHelp">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="input-label">{{ trans('lms/site.captcha') }}</label>
                            <div class="row align-items-center">
                                <div class="col">
                                    <input type="text" name="captcha" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <img id="captchaImageComment" class="captcha-image" src="">

                                    <button type="button" id="refreshCaptcha" class="btn-transparent ml-15">
                                        <i data-feather="refresh-ccw" width="24" height="24" class=""></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="formSubmit" class="btn btn-primary btn-block mt-20">{{ trans('lms/cart.validate') }}</button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div id="certificateModal" class="d-none">
        <h3 class="section-title after-line">{{ trans('lms/site.certificate_is_valid') }}</h3>
        <div class="mt-25 d-flex flex-column align-items-center">
            <img src="/assets/lms/assets/default/img/check.png" alt="" width="120" height="117">
            <p class="mt-10">{{ trans('lms/site.certificate_is_valid_hint') }}</p>
            <div class="w-75">

                <div class="mt-15 d-flex justify-content-between">
                    <span class="text-gray font-weight-bold">{{ trans('lms/quiz.student') }}:</span>
                    <span class="text-gray modal-student"></span>
                </div>

                <div class="mt-10 d-flex justify-content-between">
                    <span class="text-gray font-weight-bold">{{ trans('lms/public.date') }}:</span>
                    <span class="text-gray"><span class="modal-date"></span></span>
                </div>

                <div class="mt-10 d-flex justify-content-between">
                    <span class="text-gray font-weight-bold">{{ trans('lms/webinars.webinar') }}:</span>
                    <span class="text-gray"><span class="modal-webinar"></span></span>
                </div>
            </div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('lms/public.close') }}</button>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script>
        var certificateNotFound = '{{ trans('lms/site.certificate_not_found') }}';
        var close = '{{ trans('lms/public.close') }}';
    </script>

    <script src="/assets/lms/assets/default/js/parts/certificate_validation.min.js"></script>
@endpush
