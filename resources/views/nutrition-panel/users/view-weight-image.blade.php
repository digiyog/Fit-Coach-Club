<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">View Weight Image</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12" id="custom-error"> </div>
            <div class="col-md-12">
                <div class="form-group select-box">
                    @php
                        $imagePath = env('AWS_CloudFront_URL').'/'.config('constants.weights.image_path').'/'.$weightImage->weight_image;
                    @endphp

                    <img width="100%" src="{{ $imagePath }}">
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>