@props([
    'title' => null,
])

<div class="lingkup-card mb-4">
    @if ($title)
        <h3 class="lingkup-card-title">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>