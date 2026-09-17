@php
    $imageUrl = '';
    if (!empty($weightImage->weight_image)) {
        if (str_starts_with($weightImage->weight_image, 'http')) {
            $imageUrl = $weightImage->weight_image;
        } else {
            $cloudUrl = rtrim(env('AWS_CloudFront_URL', ''), '/');
            $folder = trim(config('constants.weights.image_path', 'weights'), '/');
            $imageUrl = $cloudUrl . '/' . $folder . '/' . $weightImage->weight_image;
        }
    }
    $formattedDate = !empty($weightImage->date) ? date('d M Y', strtotime($weightImage->date)) : 'N/A';
    $weightVal = !empty($weightImage->weight) ? number_format((float)$weightImage->weight, 1) . ' kg' : 'N/A';
@endphp

<div class="fcc-weight-modal-wrapper" style="font-family: 'Outfit', sans-serif;">
    <!-- Modal Header -->
    <div class="modal-header py-3 px-4" style="border-bottom: 1px solid #f1f5f9; background: #ffffff; display: flex; align-items: center; justify-content: space-between;">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: #eef2ff; color: #3b46f1; display: flex; align-items: center; justify-content: center; font-size: 17px;">
                <i class="fa fa-picture-o"></i>
            </div>
            <div>
                <h5 class="modal-title fw-bold mb-0" style="color: #0f172a; font-size: 16px;">Weight Evidence Image</h5>
                <p class="text-muted mb-0" style="font-size: 12.5px;">Measurement Date: <strong style="color: #334155;">{{ $formattedDate }}</strong> &bull; Weight: <span class="badge" style="background: #eef2ff; color: #3b46f1; font-weight: 700; font-size: 11.5px; padding: 3px 8px; border-radius: 6px;">{{ $weightVal }}</span></p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="font-size: 13px;"></button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body p-4" style="background: #f8fafc; text-align: center;">
        @if(!empty($imageUrl))
            <div style="background: #ffffff; border-radius: 14px; padding: 12px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06); display: inline-block; max-width: 100%;">
                <img src="{{ $imageUrl }}" alt="Weight measurement evidence" class="img-fluid" style="max-height: 520px; border-radius: 10px; object-fit: contain; width: auto; max-width: 100%; display: block; margin: 0 auto;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-4 text-muted\'><i class=\'fa fa-file-image-o fa-3x text-muted mb-2\'></i><p class=\'mb-0 fw-semibold\'>Image could not be loaded</p></div>';" />
            </div>
        @else
            <div class="py-5 text-center">
                <i class="fa fa-file-image-o fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                <h6 class="fw-bold text-dark">No Image Available</h6>
                <p class="text-muted small mb-0">There was no evidence photo uploaded for this weight entry.</p>
            </div>
        @endif
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer py-3 px-4" style="border-top: 1px solid #f1f5f9; background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
        @if(!empty($imageUrl))
            <a href="{{ $imageUrl }}" target="_blank" download class="btn btn-outline-primary btn-sm" style="border-radius: 9px; font-weight: 600; font-size: 12.5px; padding: 7px 14px;">
                <i class="fa fa-external-link me-1"></i> Open full size
            </a>
        @else
            <div></div>
        @endif
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal" style="border-radius: 9px; font-weight: 600; font-size: 13px; padding: 8px 18px; border: 1px solid #e2e8f0;">Close</button>
    </div>
</div>