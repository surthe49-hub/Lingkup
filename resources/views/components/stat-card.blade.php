@props([
    'label',
    'value',
    'icon' => 'bi-bar-chart',
    'meta' => null,
])

<div class="lingkup-stat-card">
    <div class="lingkup-stat-card-icon">
        <i class="bi {{ $icon }}"></i>
    </div>
    <p class="lingkup-stat-card-label">{{ $label }}</p>
    <p class="lingkup-stat-card-value">{{ $value }}</p>
    @if ($meta)
        <div class="lingkup-stat-card-meta">{{ $meta }}</div>
    @endif
</div>