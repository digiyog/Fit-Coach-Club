@php
    $member = !empty($community) && !empty($community->user) ? $community->user : null;
    $memberName = $member ? $member->name : 'Member';
    $memberPhone = $member && !empty($member->mobile_number) ? $member->mobile_number : '';
    $formattedDate = !empty($community) && !empty($community->created_at) ? date('d M Y, h:i A', strtotime($community->created_at)) : '';
    $photoCount = count($photos ?? []);
@endphp

<div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">
    <!-- Modal Header -->
    <div class="modal-header py-3 px-4" style="border-bottom: 1px solid #f1f5f9; background: #ffffff; display: flex; align-items: center; justify-content: space-between;">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                <i class="fa fa-picture-o"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="modal-title fw-bold mb-0" style="color: #0f172a; font-size: 17px; font-weight: 700;">Community Photos</h5>
                    <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700; font-size: 11.5px; padding: 4px 10px; border-radius: 999px;">
                        {{ $photoCount }} {{ $photoCount === 1 ? 'Photo' : 'Photos' }}
                    </span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 12.5px;">
                    Shared by <strong style="color: #334155;">{{ $memberName }}</strong>
                    @if($memberPhone)
                        <span class="text-muted">({{ $memberPhone }})</span>
                    @endif
                    @if($formattedDate)
                        &bull; <span>{{ $formattedDate }}</span>
                    @endif
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="font-size: 13px;"></button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body p-4" style="background: #f8fafc; max-height: 72vh; overflow-y: auto;">
        @if(!empty($community) && !empty($community->message))
            <div class="p-3 mb-3 rounded-3" style="background: #ffffff; border: 1px solid #e2e8f0; border-left: 4px solid #2563eb;">
                <div class="d-flex align-items-center gap-2 mb-1 text-muted" style="font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fa fa-comment-o text-primary"></i> Member Message
                </div>
                <p class="mb-0 text-dark" style="font-size: 13.5px; line-height: 1.5; white-space: pre-wrap;">{{ $community->message }}</p>
            </div>
        @endif

        @if($photoCount > 0)
            <div class="row g-3 justify-content-center">
                @foreach($photos as $index => $photo)
                    @php
                        $photoUrl = get_image_url(config('constants.communities.image_path'), $photo->image);
                        $colClass = $photoCount === 1 ? 'col-12' : ($photoCount === 2 ? 'col-md-6 col-12' : 'col-md-6 col-lg-4 col-12');
                    @endphp
                    <div class="{{ $colClass }} mb-2">
                        <div class="position-relative overflow-hidden rounded-3 text-center" style="background: #ffffff; border: 1.5px solid #e2e8f0; padding: 10px; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);">
                            <a href="{{ $photoUrl }}" target="_blank" title="Click to view full resolution" style="display: block; overflow: hidden; border-radius: 8px; text-decoration: none;">
                                <img src="{{ $photoUrl }}" alt="Community Photo {{ $index + 1 }}" class="img-fluid" style="max-height: {{ $photoCount === 1 ? '500px' : '320px' }}; width: 100%; object-fit: contain; border-radius: 8px; display: block; margin: 0 auto; background: #f8fafc;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'py-5 text-muted\'><i class=\'fa fa-file-image-o fa-3x text-muted mb-2\'></i><p class=\'mb-0 small fw-semibold\'>Image unavailable</p></div>';" />
                            </a>
                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                <span class="small text-muted" style="font-size: 11.5px; font-weight: 500;">
                                    Photo {{ $index + 1 }} of {{ $photoCount }}
                                </span>
                                <a href="{{ $photoUrl }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-2 d-inline-flex align-items-center gap-1" style="font-size: 11.5px; height: 26px;">
                                    <i class="fa fa-external-link"></i>
                                    <span>Full size</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-5 text-center">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 12px;">
                    <i class="fa fa-picture-o"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">No Photos Found</h6>
                <p class="text-muted small mb-0">No uploaded photos could be found for this community post.</p>
            </div>
        @endif
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer py-3 px-4" style="border-top: 1px solid #f1f5f9; background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
        <span class="text-muted small" style="font-size: 12px;">
            <i class="fa fa-info-circle me-1 text-primary"></i> Click any photo to view in full resolution
        </span>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal" style="border-radius: 9px; font-weight: 600; font-size: 13px; padding: 8px 20px; border: 1px solid #e2e8f0;">Close</button>
    </div>
</div>