@extends('layouts.dashboard')

@section('content')
<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Progress Persiapan</h1>
    @if ($pathway)
        <p class="lingkup-page-subtitle">
            Target: <strong>{{ $pathway->target->name }}</strong>
        </p>
    @endif
</div>

@if (! $pathway)
    {{-- ============================ --}}
    {{-- Empty State                  --}}
    {{-- ============================ --}}
    <div class="lingkup-progress-empty">
        <i class="bi bi-map lingkup-progress-empty-icon"></i>
        <h2>Belum Ada Pathway Aktif</h2>
        <p>Anda perlu generate pathway terlebih dahulu sebelum bisa melacak progress.</p>
        <a href="{{ route('user.pathway.index') }}" class="btn btn-primary">
            Buat Pathway Sekarang
        </a>
    </div>
@else
    {{-- ============================ --}}
    {{-- Overall Progress Bar         --}}
    {{-- ============================ --}}
    <div class="lingkup-progress-overall">
        <div class="lingkup-progress-overall-info">
            <span class="lingkup-progress-overall-label">Progress Keseluruhan</span>
            <span class="lingkup-progress-overall-count">
                {{ $overallCompleted }} / {{ $overallTotal }} task selesai
            </span>
        </div>
        <div class="lingkup-progress-bar-track">
            <div class="lingkup-progress-bar-fill"
                 style="width: {{ $overallPercentage }}%"></div>
        </div>
        <span class="lingkup-progress-overall-percentage">{{ $overallPercentage }}%</span>
    </div>

    {{-- ============================ --}}
    {{-- Timeline Vertikal            --}}
    {{-- ============================ --}}
    <div class="lingkup-progress-timeline">
        @foreach ($phases as $phase)
            @php
                $isCurrent = $phase['id'] === $currentPhaseId;
                $isComplete = $phase['is_complete'];
                $stepClass = $isComplete
                    ? 'lingkup-timeline-step-complete'
                    : ($isCurrent ? 'lingkup-timeline-step-current' : 'lingkup-timeline-step-upcoming');
            @endphp

            <div class="lingkup-timeline-item {{ $stepClass }}">
                <div class="lingkup-timeline-marker">
                    @if ($isComplete)
                        <i class="bi bi-check-lg"></i>
                    @else
                        <span>{{ $phase['phase_order'] }}</span>
                    @endif
                </div>

                @if (! $loop->last)
                    <div class="lingkup-timeline-line"></div>
                @endif

                <div class="lingkup-timeline-content">
                    <button class="lingkup-timeline-header"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#phase-collapse-{{ $phase['id'] }}"
                            aria-expanded="{{ $isCurrent ? 'true' : 'false' }}">
                        <div class="lingkup-timeline-header-text">
                            <h3>{{ $phase['title'] }}</h3>
                            @if ($isCurrent)
                                <span class="lingkup-timeline-badge-current">Sedang Berjalan</span>
                            @endif
                            @if ($phase['estimated_duration'])
                                <span class="lingkup-timeline-duration">
                                    <i class="bi bi-clock"></i> {{ $phase['estimated_duration'] }}
                                </span>
                            @endif
                        </div>
                        <div class="lingkup-timeline-header-progress">
                            <span>{{ $phase['completed_tasks'] }}/{{ $phase['total_tasks'] }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </button>

                    <div class="lingkup-progress-bar-track lingkup-progress-bar-track-sm">
                        <div class="lingkup-progress-bar-fill"
                             style="width: {{ $phase['percentage'] }}%"></div>
                    </div>

                    <div id="phase-collapse-{{ $phase['id'] }}"
                         class="collapse {{ $isCurrent ? 'show' : '' }}">
                        @if ($phase['description'])
                            <p class="lingkup-timeline-description">{{ $phase['description'] }}</p>
                        @endif

                        <ul class="lingkup-task-list">
                            @foreach ($phase['tasks'] as $task)
                                @php
                                    $statusIcon = match ($task['status']) {
                                        'selesai' => 'bi-check-circle-fill',
                                        'sedang_dikerjakan' => 'bi-dash-circle-fill',
                                        default => 'bi-circle',
                                    };
                                    $priorityClass = match ($task['priority']) {
                                        'high' => 'lingkup-task-priority-high',
                                        'medium' => 'lingkup-task-priority-medium',
                                        default => 'lingkup-task-priority-low',
                                    };
                                @endphp
                                <li class="lingkup-task-item lingkup-task-status-{{ $task['status'] }}">
                                    <button type="button"
                                            class="lingkup-task-toggle"
                                            data-task-id="{{ $task['id'] }}"
                                            data-current-status="{{ $task['status'] }}"
                                            title="Klik untuk ubah status">
                                        <i class="bi {{ $statusIcon }}"></i>
                                    </button>
                                    <div class="lingkup-task-info">
                                        <span class="lingkup-task-title">{{ $task['title'] }}</span>
                                        @if ($task['description'])
                                            <span class="lingkup-task-description">{{ $task['description'] }}</span>
                                        @endif
                                        <div class="lingkup-task-meta">
                                            <span class="lingkup-task-priority {{ $priorityClass }}">
                                                {{ ucfirst($task['priority']) }}
                                            </span>
                                            @if ($task['estimated_duration'])
                                                <span class="lingkup-task-duration">
                                                    <i class="bi bi-clock"></i> {{ $task['estimated_duration'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cycle = {
        'belum_dimulai': 'sedang_dikerjakan',
        'sedang_dikerjakan': 'selesai',
        'selesai': 'belum_dimulai',
    };

    document.querySelectorAll('.lingkup-task-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const taskId = button.dataset.taskId;
            const currentStatus = button.dataset.currentStatus;
            const nextStatus = cycle[currentStatus] ?? 'belum_dimulai';

            button.disabled = true;

            fetch(`/progress/tasks/${taskId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: nextStatus }),
            })
                .then(function (response) {
                    if (! response.ok) {
                        throw new Error('Gagal update status task.');
                    }
                    return response.json();
                })
                .then(function () {
                    window.location.reload();
                })
                .catch(function (error) {
                    console.error(error);
                    alert('Gagal mengubah status task. Silakan coba lagi.');
                    button.disabled = false;
                });
        });
    });
});
</script>
@endpush
@endsection