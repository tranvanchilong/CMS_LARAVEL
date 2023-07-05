<div class="modal fade" id="templateModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preview Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <form action="{{route('admin.customer.feature_template')}}" id="templateForm{{$row->id}}" method="POST" enctype="multipart/form-data">
	                @csrf
	                <input type="hidden" name="user_id" value="{{$row->id}}">
	                <input type="hidden" name="template" value="1">
	                <div class="form-group">
	                    <div class="col-md-12 showImage mb-3">
	                        <img src="{{asset($row->user_domain->thumbnail ?? asset('uploads/default.png'))}}" alt="..." class="img-thumbnail">
	                    </div>
	                    <input type="file" name="preview_image" class="image">
	                    <p id="errpreview_image{{$row->id}}" class="mb-0 text-danger em"></p>
	                </div>
	                <div class="form-group">
	                    <label for="">{{ __('Serial Number') }} **</label>
	                    <input type="number" class="form-control ltr" name="serial_number" value="{{$row->user_domain->featured ?? ''}}" placeholder="Enter Serial Number">
	                    <p id="errserial_number" class="mb-0 text-danger em"></p>
	                    <p class="text-warning"><small>The higher the serial number is, the later the feature will be shown.</small></p>
	                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button form="templateForm{{$row->id}}" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>