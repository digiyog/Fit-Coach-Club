<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">View Video</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12" id="custom-error"> </div>
            <div class="col-md-12">
                <div class="form-group select-box">
                    <iframe width="100%" height="500" src="https://www.youtube.com/embed/{{ $video->link }}"></iframe>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>