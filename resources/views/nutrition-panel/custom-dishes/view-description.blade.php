<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">View Description</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12" id="custom-error"> </div>
            <div class="col-md-12">
                <div class="form-group select-box">
                    {!! $customDish->description !!}
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
    </div>
</div>