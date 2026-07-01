<div class="col-sm-6 col-xl-3">
    <div class="indicator-card h-100">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <div class="indicator-title">{{ $title }}</div>
            </div>
            @if(!empty($icon))
                <div class="text-muted fs-4"><i class="bi {{ $icon }}"></i></div>
            @endif
        </div>
        <div class="indicator-value" id="{{ $id ?? '' }}">{!! $value !!}</div>
        <div class="indicator-meta">{{ $meta }}</div>
        @if(!empty($note))
            <div class="text-muted small mt-2">{{ $note }}</div>
        @endif
    </div>
</div>
