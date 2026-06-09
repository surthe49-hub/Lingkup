@props([
    'icon' => 'bi-inbox',
    'title' => 'Belum ada data',
    'text' => null,
])

<div class="lingkup-empty">
    <div class="lingkup-empty-icon">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h3 class="lingkup-empty-title">{{ $title }}</h3>
    @if ($text)
        <p class="lingkup-empty-text">{{ $text }}</p>
    @endif
    @isset($action)
        <div>{{ $action }}</div>
    @endisset
</div>