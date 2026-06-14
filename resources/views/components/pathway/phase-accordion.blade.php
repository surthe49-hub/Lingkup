@props(['phase', 'expanded' => false])

@php
    $collapseId = 'phase-' . $phase->id;
    $taskCount = $phase->tasks->count();
@endphp

<div class="phase-accordion-item">
    <button class="phase-accordion-header {{ $expanded ? '' : 'collapsed' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $collapseId }}"
            aria-expanded="{{ $expanded ? 'true' : 'false' }}"
            aria-controls="{{ $collapseId }}">
        <div class="phase-accordion-header-content">
            <div class="phase-number">{{ $phase->phase_order }}</div>
            <div class="phase-info">
                <h3 class="phase-title">{{ $phase->title }}</h3>
                <div class="phase-meta">
                    <span><i class="bi bi-clock"></i> {{ $phase->estimated_duration }}</span>
                    <span><i class="bi bi-list-check"></i> {{ $taskCount }} task</span>
                </div>
            </div>
            <i class="bi bi-chevron-down phase-accordion-chevron"></i>
        </div>
    </button>

    <div id="{{ $collapseId }}" class="phase-accordion-body collapse {{ $expanded ? 'show' : '' }}">
        <div class="phase-accordion-body-inner">
            <p class="phase-description">{{ $phase->description }}</p>

            <div class="task-list">
                @foreach ($phase->tasks as $task)
                    <x-pathway.task-card :task="$task" />
                @endforeach
            </div>
        </div>
    </div>
</div>