@props(['task'])

@php
    $categoryLabels = [
        'language' => 'Bahasa',
        'academic' => 'Akademik',
        'document' => 'Dokumen',
        'experience' => 'Pengalaman',
        'test' => 'Tes',
        'application' => 'Aplikasi',
        'other' => 'Lainnya',
    ];
    $categoryIcons = [
        'language' => 'bi-translate',
        'academic' => 'bi-mortarboard',
        'document' => 'bi-file-text',
        'experience' => 'bi-briefcase',
        'test' => 'bi-clipboard-check',
        'application' => 'bi-send',
        'other' => 'bi-bookmark',
    ];
    $priorityLabels = [
        'high' => 'Prioritas Tinggi',
        'medium' => 'Prioritas Menengah',
        'low' => 'Prioritas Rendah',
    ];
@endphp

<div class="task-card task-priority-{{ $task->priority }}">
    <div class="task-card-header">
        <div class="task-order">{{ $task->task_order }}</div>
        <div class="task-card-title-wrap">
            <h4 class="task-title">{{ $task->title }}</h4>
            <div class="task-badges">
                <span class="task-badge task-badge-category">
                    <i class="bi {{ $categoryIcons[$task->category] ?? 'bi-bookmark' }}"></i>
                    {{ $categoryLabels[$task->category] ?? ucfirst($task->category) }}
                </span>
                <span class="task-badge task-badge-priority task-badge-priority-{{ $task->priority }}">
                    <i class="bi bi-flag"></i>
                    {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority) }}
                </span>
                <span class="task-badge task-badge-duration">
                    <i class="bi bi-clock"></i>
                    {{ $task->estimated_duration }}
                </span>
            </div>
        </div>
    </div>

    <p class="task-description">{{ $task->description }}</p>
</div>