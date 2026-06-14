@props(['pathway'])

<div class="pathway-header">
    <div class="pathway-header-content">
        <div class="pathway-meta-top">
            <span class="pathway-meta-badge">
                <i class="bi bi-flag-fill"></i>
                {{ $pathway->target->name }}
            </span>
            <span class="pathway-meta-badge">
                <i class="bi bi-geo-alt-fill"></i>
                {{ $pathway->target->country }}
            </span>
            <span class="pathway-meta-badge">
                <i class="bi bi-arrow-clockwise"></i>
                Generation #{{ $pathway->generation_count }}
            </span>
        </div>

        <h1 class="pathway-title">{{ $pathway->title }}</h1>

        <p class="pathway-summary">{{ $pathway->summary }}</p>

        <div class="pathway-meta-bottom">
            <div class="pathway-meta-item">
                <i class="bi bi-clock"></i>
                <div>
                    <small>Estimasi Total Durasi</small>
                    <strong>{{ $pathway->estimated_total_duration }}</strong>
                </div>
            </div>
            <div class="pathway-meta-item">
                <i class="bi bi-layers"></i>
                <div>
                    <small>Jumlah Fase</small>
                    <strong>{{ $pathway->phases->count() }} fase</strong>
                </div>
            </div>
            <div class="pathway-meta-item">
                <i class="bi bi-list-check"></i>
                <div>
                    <small>Total Task</small>
                    <strong>{{ $pathway->phases->sum(fn($p) => $p->tasks->count()) }} task</strong>
                </div>
            </div>
            <div class="pathway-meta-item">
                <i class="bi bi-calendar-event"></i>
                <div>
                    <small>Dibuat</small>
                    <strong>{{ $pathway->generated_at?->diffForHumans() ?? 'Baru' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>