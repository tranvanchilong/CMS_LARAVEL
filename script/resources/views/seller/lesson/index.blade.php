@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => __('Lesson')])
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-30">
                <div class="col-lg-6">
                    <h4>{{ __('Lesson') }}</h4>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
            <br>
            <br>
            <div class="card-action-filter">
                
                <form method="post" class="basicform_with_reload" action="{{ route('seller.module.lesson.destroys') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex">
                                <div class="single-filter">
                                    <div class="form-group">
                                        <select class="form-control selectric" name="status">
                                            <option disabled="" selected="">Select Action</option>
                                            <option value="delete">{{ __('Delete Permanently') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="single-filter">
                                    <button type="submit" class="btn btn-primary btn-lg ml-2">{{ __('Apply') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">

                        </div>
                        <div class="col-lg-4">
                            <div class="add-new-btn">
                                <a href="#" class="btn btn-primary float-right" data-toggle="modal"
                                    data-target="#createModal"><i class="fas fa-plus"></i> Add Lesson</a>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="table-responsive custom-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">Lesson Name</th>
                            <th scope="col">Lesson Duration</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lessons as $key => $lesson)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ base64_encode($lesson->id) }}"></td>
                                <td>{{ $lesson->name }}</td>
                                <td>{{ $lesson->duration }}</td>

                                <td>
                                    <a class="btn btn-primary btn-sm editbtn"
                                        href="{{ route('seller.module.lesson.edit', $lesson->id) }}">
                                       <span class="btn-label"><i class="fas fa-edit"></i></span>{{ __('Edit') }}</a>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </form>
                    <tfoot>
                        <tr>
                            <th><input type="checkbox" class="checkAll"></th>
                            <th scope="col">Lesson Name</th>
                            <th scope="col">Lesson Duration</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>
    {{-- ADD MODULE --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.module.lesson.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                        <input type="hidden" name="module_id" value="{{ $module->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add Lesson</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" >
                                <label for="">Lesson Name **</label>
                                <input type="text" class="form-control" name="name" value=""
                                    placeholder="Enter Lesson Name">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
    
                            <div class="form-group mb-1">
                                <label for="">Lesson Video **</label>
                                <div class="d-flex flex-row">
                                    {{-- <div class="mr-5">
                                        <input type="radio" id="video_file" name="video" value="1">
                                        <p class="d-inline-block">Upload Video</p>
                                    </div> --}}
    
                                    <div>
                                        <input type="radio" id="video_link" name="video" value="2">
                                        <p class="d-inline-block">Enter Video Link</p>
                                    </div>
                                </div>
    
                                <div>
                                    <div id="upload_btn_id" class="d-none">
                                        {{-- Video Part --}}
                                        <div class="form-group p-0">
                                            <div class="video-preview" id="videoPreview2">
                                                <video width="320" height="240" controls id="video_src">
                                                    <source src="" type="video/mp4">
                                                </video>
                                            </div>
                                            <br>
    
    
                                            <input id="fileInput2" type="hidden" name="video_file">
                                            <button id="chooseVideo2" class="choose-video btn btn-primary" type="button"
                                                data-multiple="false" data-video="true" data-toggle="modal"
                                                data-target="#lfmModal2">Choose Video</button>
    
    
                                            <p class="text-warning mb-0">MP4 video is allowed</p>
                                            <p class="em text-danger mb-0" id="errvideo_file"></p>
    
                                        </div>
                                    </div>
    
                                    <div id="video_link_id" class="d-none">
                                        <input class="form-control" type="text" name="video_link"
                                            placeholder="Enter Embed Video Link" value="">
                                    </div>
                                </div>
                                <p id="errvideo" class="mb-0 text-danger em"></p>
                            </div>
    
    
    
                            <div class="form-group">
                                <label for="">Lesson Duration **</label>
                                <input type="text" class="form-control" name="duration" value=""
                                    placeholder="eg: 20m 25s">
                                <p id="errduration" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    {{-- EDIT MODULE --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Module Lesson</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="margin: 0px 20px 0px 20px;">
                    <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.module.lesson.update') }}" method="POST">
                        @csrf
                        <input id="inmodule_id" type="hidden" name="module_id" value="">
                        <div class="form-group">
                            <label for="">Module Name **</label>
                            <input id="inname" type="name" class="form-control" name="name" value=""
                                placeholder="Enter Name">
                            <p id="eerrname" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label for="">Module Duration **</label>
                            <input id="induration" type="text" class="form-control" name="duration" value=""
                                placeholder="eg: 10h 15m">
                            <p id="eerrduration" class="mb-0 text-danger em"></p>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="ajaxFormLoad" class="modal-form" action="{{ route('seller.module.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Module</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="ajaxForm" class="modal-form create" action="{{ route('seller.module.store') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">

                            <div class="form-group">
                                <label for="">Module Name **</label>
                                <input type="text" class="form-control" name="name" value=""
                                    placeholder="Enter Module Name">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>

                            <div class="form-group">
                                <label for="">Module Duration **</label>
                                <input type="text" class="form-control" name="duration" value=""
                                    placeholder="eg: 10h 15m">
                                <p id="errduration" class="mb-0 text-danger em"></p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
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
