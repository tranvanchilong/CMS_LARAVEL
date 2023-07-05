<!-- Modal -->
<div class="d-none" id="webinarFileModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('lms/public.add_file') }}</h3>
    <form action="/lms{{ getAdminPanelUrl() }}/files/store" method="post" enctype="multipart/form-data">
        <input type="hidden" name="webinar_id" value="{{  !empty($webinar) ? $webinar->id :''  }}">

        @if(!empty(getGeneralSettings('content_translate')))
            <div class="form-group">
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

        <div class="form-group">
            <label class="input-label">{{ trans('lms/public.title') }}</label>
            <input type="text" name="title" class="form-control" placeholder="{{ trans('lms/forms.maximum_255_characters') }}"/>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('lms/public.chapter') }}</label>
            <select class="custom-select" name="chapter_id">
                <option value="">{{ trans('lms/admin/main.no_chapter') }}</option>

                @if(!empty($chapters))
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                @endif
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('lms/public.source') }}</label>
                    <select name="storage"
                            class="js-file-storage form-control"
                    >
                        @foreach(\App\Models\LMS\File::$fileSources as $source)
                            <option value="{{ $source }}">{{ trans('lms/update.file_source_'.$source) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('lms/public.accessibility') }}</label>
                    <select class="custom-select" name="accessibility" required>
                        <option selected disabled>{{ trans('lms/public.choose_accessibility') }}</option>
                        <option value="free">{{ trans('lms/public.free') }}</option>
                        <option value="paid">{{ trans('lms/public.paid') }}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group js-file-path-input">
            <div class="local-input input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="file_path_record" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <input type="text" name="file_path" id="file_path_record" value="" class="js-ajax-file_path form-control" placeholder="{{ trans('lms/webinars.file_upload_placeholder') }}"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="form-group js-s3-file-path-input d-none">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <div class="custom-file">
                    <input type="file" name="s3_file" class="js-s3-file-input custom-file-input cursor-pointer" id="s3File_record">
                    <label class="custom-file-label cursor-pointer" for="s3File_record">{{ trans('lms/update.choose_file') }}</label>
                    <div class="invalid-feedback" style="position: absolute;bottom: -20px"></div>
                </div>
            </div>
        </div>

        <div class="row form-group js-file-type-volume d-none">
            <div class="col-6">
                <label class="input-label">{{ trans('lms/webinars.file_type') }}</label>
                <select name="file_type" class="js-ajax-file_type form-control">
                    <option value="">{{ trans('lms/webinars.select_file_type') }}</option>

                    @foreach(\App\Models\LMS\File::$fileTypes as $fileType)
                        <option value="{{ $fileType }}">{{ trans('lms/update.file_type_'.$fileType) }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-6">
                <label class="input-label">{{ trans('lms/webinars.file_volume') }}</label>
                <input type="text" name="volume" value="" class="js-ajax-volume form-control" placeholder="{{ trans('lms/webinars.online_file_volume') }}"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('lms/public.description') }}</label>
            <textarea name="description" class="js-ajax-description form-control" rows="6"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="js-online_viewer-input form-group mt-20">
            <div class="d-flex align-items-center justify-content-between">
                <label class="cursor-pointer input-label" for="online_viewerSwitch_record">{{ trans('lms/update.online_viewer') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="online_viewer" class="custom-control-input" id="online_viewerSwitch_record">
                    <label class="custom-control-label" for="online_viewerSwitch_record"></label>
                </div>
            </div>
        </div>

        <div class="js-downloadable-input form-group mt-20">
            <div class="d-flex align-items-center justify-content-between">
                <label class="cursor-pointer input-label" for="downloadableSwitch_record">{{ trans('lms/home.downloadable') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="downloadable" class="custom-control-input" id="downloadableSwitch_record">
                    <label class="custom-control-label" for="downloadableSwitch_record"></label>
                </div>
            </div>
        </div>

        <div class="form-group mt-20">
            <div class="d-flex align-items-center justify-content-between">
                <label class="cursor-pointer input-label" for="fileStatusSwitch_record">{{ trans('lms/public.active') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="status" class="custom-control-input" id="fileStatusSwitch_record">
                    <label class="custom-control-label" for="fileStatusSwitch_record"></label>
                </div>
            </div>
        </div>

        @if(getFeaturesSettings('sequence_content_status'))
            <div class="form-group mb-1">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="cursor-pointer input-label" for="SequenceContentSwitch_record">{{ trans('lms/update.sequence_content') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="sequence_content" class="js-sequence-content-switch custom-control-input" id="SequenceContentSwitch_record">
                        <label class="custom-control-label" for="SequenceContentSwitch_record"></label>
                    </div>
                </div>
            </div>

            <div class="js-sequence-content-inputs pl-2 d-none">
                <div class="form-group mb-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label" for="checkPreviousPartsSwitch_record">{{ trans('lms/update.check_previous_parts') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" checked name="check_previous_parts" class="custom-control-input" id="checkPreviousPartsSwitch_record">
                            <label class="custom-control-label" for="checkPreviousPartsSwitch_record"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('lms/update.access_after_day') }}</label>
                    <input type="number" name="access_after_day" value="" class="js-ajax-access_after_day form-control" placeholder="{{ trans('lms/update.access_after_day_placeholder') }}"/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        @endif

        <div class="mt-3 d-flex align-items-center justify-content-end">
            <button type="button" id="saveFile" class="btn btn-primary">{{ trans('lms/public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl">{{ trans('lms/public.close') }}</button>
        </div>
    </form>
</div>
