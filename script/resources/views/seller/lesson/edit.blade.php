@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Edit Lesson'])
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="PUT" action="{{ route('seller.module.lesson.update', $lesson->id) }}" id="ajaxForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title d-inline-block">Edit Lesson</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>Name Lesson **</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Title"
                                        value="{{ $lesson->name }}" />
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Lesson Video **</label>
                                    <input class="form-control" type="text" name="video_link"
                                    placeholder="Enter Embed Video Link" value="{{ $lesson->video_link }}">
                                </div>

                                <div class="form-group mb-1">
                                    <label for="">Lesson Duration **</label>
                                    <input type="text" class="form-control ltr" name="duration"
                                        placeholder="Enter YouTube Video Link" value="{{ $lesson->duration }}">
                                    <p id="errvideo_link" class="mb-0 text-danger em"></p>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        CKFinder.setupCKEditor();
    </script>
    <script>
        $(document).ready(function() {
            // on page load show default input field for video upload
            let btnName = $('input:radio[name=video]');

            if (btnName.is(':checked') === false) {
                btnName.filter('[value=1]').prop('checked', true);

                let radioValue = $("input[name='video']:checked").val();

                if (radioValue == 1) {
                    $('#upload_btn_id').removeClass('d-none');
                }
            }

            // show different video input field by toggling radio button
            $("input[type='radio']").click(function() {
                let radioValue = $("input[name='video']:checked").val();

                if (radioValue == 1) {
                    $('#upload_btn_id').removeClass('d-none');
                    $('#video_link_id').addClass('d-none');
                } else {
                    $('#video_link_id').removeClass('d-none');
                    $('#upload_btn_id').addClass('d-none');
                }
            });

            /*=========================
            jquery code for edit modal
            =========================*/

            // $(".lessonEditBtn").on('click', function() {
            //     let datas = $(this).data();

            //     // first, get the value of which video field has data. either video_file = 1 or the video_link = 2
            //     for (let x in datas) {
            //         if ($("input[name='" + x + "']").attr('type') == 'radio') {
            //             $("input[name='" + x + "']").each(function(i) {
            //                 if ($(this).val() == datas[x]) {
            //                     $(this).prop('checked', true);
            //                 }
            //             });
            //         } else if (x == 'file' && datas['file']) {
            //             $("#editModal").find('source').attr('src',
            //                 "{{ url('assets/front/video/lesson_videos') }}/" + datas['file']);
            //             $("#editModal video")[0].load();
            //         } else {
            //             $("#in" + x).val(datas[x]);
            //         }
            //     }

            //     // then, on page load show previous checked input field for edit modal
            //     let radioVal = $("input[name='edit_video']:checked").val();

            //     if (radioVal == 1) {
            //         $('#edit_upload_btn_id').removeClass('d-none');
            //         $('#edit_video_link_id').addClass('d-none');
            //     } else {
            //         $('#edit_video_link_id').removeClass('d-none');
            //         $('#edit_upload_btn_id').addClass('d-none');
            //     }

            //     // show different video input field by toggling radio button for edit modal
            //     $("input[type='radio']").click(function() {
            //         let radioBtnVal = $("input[name='edit_video']:checked").val();

            //         if (radioBtnVal == 1) {
            //             $('#edit_upload_btn_id').removeClass('d-none');
            //             $('#edit_video_link_id').addClass('d-none');
            //         } else {
            //             $('#edit_video_link_id').removeClass('d-none');
            //             $('#edit_upload_btn_id').addClass('d-none');
            //         }
            //     });
            // });
        });
    </script>
@endpush
