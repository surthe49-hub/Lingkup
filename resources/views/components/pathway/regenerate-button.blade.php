@props(['pathway', 'quotaInfo' => null])

@php
    // Tombol hanya muncul untuk pathway aktif
    $isActive = $pathway->status === 'active';

    // Quota state
    $hasQuotaInfo = $isActive && $quotaInfo !== null;
    $canRegenerate = $hasQuotaInfo ? $quotaInfo['can_regenerate'] : true;
    $quotaRemaining = $hasQuotaInfo ? $quotaInfo['remaining'] : null;
    $quotaUsage = $hasQuotaInfo ? $quotaInfo['current_usage'] : null;
    $quotaMax = $hasQuotaInfo ? $quotaInfo['max_quota'] : null;
    $resetAt = $hasQuotaInfo ? $quotaInfo['reset_at'] : null;

    // Quota class state (untuk styling)
    $quotaClass = match (true) {
        ! $hasQuotaInfo => '',
        $quotaRemaining === 0 => 'quota-exhausted',
        $quotaRemaining === 1 => 'quota-low',
        default => 'quota-healthy',
    };
@endphp

@if ($isActive)
    <div class="pathway-regenerate-section">
        <div class="pathway-regenerate-info">
            <h3>Tidak Puas dengan Pathway Ini?</h3>
            <p>
                Anda dapat regenerate pathway untuk mendapatkan roadmap baru.
                Pathway ini akan menjadi history dan tetap dapat diakses.
            </p>

            {{-- Quota Indicator (Phase 5.2.F) --}}
            @if ($hasQuotaInfo)
                <div class="quota-indicator {{ $quotaClass }}">
                    <div class="quota-indicator-header">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span class="quota-label">Quota Regenerate Minggu Ini</span>
                        <span class="quota-count">{{ $quotaUsage }}/{{ $quotaMax }}</span>
                    </div>

                    <div class="quota-progress-bar">
                        <div class="quota-progress-fill" style="width: {{ ($quotaUsage / $quotaMax) * 100 }}%"></div>
                    </div>

                    <p class="quota-status-text">
                        @if ($quotaRemaining === 0)
                            <i class="bi bi-x-circle-fill"></i>
                            Quota habis. Reset dalam {{ $resetAt?->diffForHumans(null, true) ?? 'beberapa hari' }}.
                        @elseif ($quotaRemaining === 1)
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Tersisa 1 regenerate untuk target ini minggu ini.
                        @else
                            <i class="bi bi-check-circle-fill"></i>
                            Tersisa {{ $quotaRemaining }} regenerate untuk target ini minggu ini.
                        @endif
                    </p>
                </div>
            @else
                <p class="text-muted small">
                    <i class="bi bi-info-circle"></i>
                    Maks 3 regenerate per target per minggu.
                </p>
            @endif
        </div>

        @if ($canRegenerate)
            <button type="button" class="btn btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#regenerateConfirmModal">
                <i class="bi bi-arrow-clockwise"></i>
                Regenerate Pathway
            </button>
        @else
            <button type="button" class="btn btn-outline-secondary" disabled>
                <i class="bi bi-lock-fill"></i>
                Quota Habis
            </button>
        @endif
    </div>

    {{-- Confirmation Modal (only renders if can regenerate) --}}
    @if ($canRegenerate)
        <div class="modal fade" id="regenerateConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-arrow-clockwise"></i>
                            Regenerate Pathway?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan regenerate pathway untuk target <strong>{{ $pathway->target->name }}</strong>.</p>

                        @if ($hasQuotaInfo && $quotaRemaining === 1)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <strong>Ini regenerate terakhir Anda minggu ini.</strong>
                                Jika hasilnya tidak memuaskan, Anda harus menunggu hingga
                                {{ $resetAt?->format('d M Y') ?? 'minggu depan' }} untuk regenerate lagi.
                            </div>
                        @endif

                        <div class="regenerate-modal-info">
                            <div class="info-item">
                                <i class="bi bi-archive"></i>
                                <div>
                                    <strong>Pathway saat ini akan jadi history</strong>
                                    <small>Tetap dapat diakses, tapi tidak aktif</small>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-magic"></i>
                                <div>
                                    <strong>AI akan generate roadmap baru</strong>
                                    <small>Berdasarkan profil & target Anda saat ini</small>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-clock"></i>
                                <div>
                                    <strong>Membutuhkan waktu 15-30 detik</strong>
                                    <small>Mohon tidak menutup halaman saat proses berlangsung</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" id="confirm-regenerate-btn" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            Ya, Regenerate
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div id="pathway-regenerate-overlay" class="pathway-loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h3 id="regenerate-loading-title">Sedang me-regenerate pathway...</h3>
            <p id="regenerate-loading-subtitle">AI sedang menganalisis ulang profil dan target Anda.</p>
            <div class="loading-progress-text">
                <span id="regenerate-loading-elapsed">0</span> detik
            </div>
        </div>
    </div>

    {{-- Error State --}}
    <div id="pathway-regenerate-error" class="pathway-error-state" style="display: none;">
        <i class="bi bi-exclamation-triangle"></i>
        <h3>Regeneration Gagal</h3>
        <p id="regenerate-error-message">Terjadi kesalahan. Silakan coba lagi.</p>
        <button class="btn btn-outline-primary" onclick="document.getElementById('pathway-regenerate-error').style.display='none'">
            Tutup
        </button>
    </div>
@else
    {{-- Archived state: show notice instead of regenerate button --}}
    <div class="pathway-archived-notice">
        <i class="bi bi-archive"></i>
        <div>
            <strong>Pathway Archived</strong>
            <p>Pathway ini adalah history. Untuk regenerate, akses pathway aktif Anda.</p>
        </div>
    </div>
@endif