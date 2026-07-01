@props([
    'title',
    'value',
    'meta',
    'icon' => null,
    'colorClass' => 'text-primary',
    'note' => null,
])

<div class="indicator-card card h-100 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <div class="indicator-title">{{ $title }}</div>
            </div>
            @if(!empty($icon))
                <div class="indicator-icon {{ $colorClass }}"><i class="bi {{ $icon }}"></i></div>
            @endif
        </div>
        <div class="indicator-value">{!! $value !!}</div>
        <div class="indicator-meta">{{ $meta }}</div>
        @if(!empty($note))
            <div class="text-muted small mt-2">{{ $note }}</div>
        @endif
    </div>
</div>
