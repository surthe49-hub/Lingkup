@props([
    'title',
    'subtitle' => null,
])

<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">{{ $title }}</h1>
    @if ($subtitle)
        <p class="lingkup-page-subtitle">{{ $subtitle }}</p>
    @endif
    @isset($actions)
        <div class="lingkup-page-actions">
            {{ $actions }}
        </div>
    @endisset
</div>