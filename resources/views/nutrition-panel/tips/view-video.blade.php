@php
    $videoId = $video->link;
    if (!empty($video->link) && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $video->link, $match)) {
        $videoId = $match[1];
    }
@endphp
<div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 18px 24px; background: #ffffff;">
        <h5 class="modal-title" style="font-size: 17px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-play-circle text-danger"></i> {{ $video->name ?? 'View Video Tip' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" style="padding: 0; background: #000000;">
        <div class="ratio ratio-16x9">
            <iframe width="100%" height="450" src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border: none; width: 100%; min-height: 420px;"></iframe>
        </div>
    </div>
    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 14px 24px; background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 13px; color: #64748b;">
            <i class="fa fa-user me-1"></i> Coach: <strong style="color: #0f172a;">{{ $video->coach_name ?? 'N/A' }}</strong>
        </div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 6px 18px;">Close</button>
    </div>
</div>