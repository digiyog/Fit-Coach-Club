<div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
    <div class="modal-header border-0 pb-0" style="padding: 20px 24px 10px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h5 class="modal-title fw-bold" style="color: #0f172a; font-weight: 700; margin-bottom: 2px;">Community Photos</h5>
            <p class="text-muted small mb-0">{{ count($photos) }} {{ count($photos) == 1 ? 'photo' : 'photos' }} uploaded</p>
        </div>
        <button type="button" class="btn-close close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; line-height: 1; outline: none;"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body" style="padding: 20px 24px; max-height: 72vh; overflow-y: auto;">
        @if(count($photos) > 0)
            <div class="row g-3">
                @foreach($photos as $photo)
                    <div class="col-md-{{ count($photos) == 1 ? '12' : '6' }} mb-3 text-center">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; padding: 8px; display: flex; align-items: center; justify-content: center; min-height: 240px; max-height: 480px;">
                            <img src="{{ get_image_url(config('constants.communities.image_path'), $photo->image) }}" alt="Community Photo" style="max-width: 100%; max-height: 460px; object-fit: contain; border-radius: 8px;">
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fa fa-picture-o fa-3x mb-3" style="color: #cbd5e1;"></i>
                <p class="mb-0">No photos found for this post.</p>
            </div>
        @endif
    </div>
    <div class="modal-footer border-0 pt-0" style="padding: 10px 24px 20px;">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 8px 20px;">Close</button>
    </div>
</div>