<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">View Photos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            @foreach($photos as $photo)
                <div class="col-md-6 col-sm-6 col-xs-12 data-row mb-2 text-center">
                    <div class="image-area mb-2">
                        <img src="{{ get_image_url(config('constants.communities.image_path'), $photo->image) }}" width="100%" height="500px">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>