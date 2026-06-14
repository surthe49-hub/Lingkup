@props(['target', 'profile'])

<div class="pathway-generate-section">
    <div class="pathway-generate-card">
        <div class="pathway-generate-header">
            <h2>Siap Generate Pathway Anda?</h2>
            <p class="text-muted">Roadmap personal akan disusun oleh AI berdasarkan profil dan target Anda.</p>
        </div>

        <div class="pathway-generate-summary">
            <div class="generate-summary-item">
                <small>Profil Anda</small>
                <strong>{{ $profile->major }}</strong>
                <span>{{ $profile->education_level }} • Semester {{ $profile->semester }} • IPK {{ $profile->gpa }}</span>
            </div>
            <div class="generate-summary-divider">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="generate-summary-item">
                <small>Target</small>
                <strong>{{ $target->name }}</strong>
                <span>{{ $target->country }} • {{ $target->education_level }}</span>
            </div>
        </div>

        <button id="generate-pathway-btn" class="btn btn-primary btn-lg generate-btn">
            <span class="generate-btn-text">
                <i class="bi bi-magic"></i>
                Generate Pathway Sekarang
            </span>
        </button>

        <p class="generate-disclaimer">
            <i class="bi bi-info-circle"></i>
            Proses generation membutuhkan waktu 15-30 detik. Mohon tidak menutup halaman.
        </p>
    </div>

    {{-- Loading Overlay --}}
    <div id="pathway-loading-overlay" class="pathway-loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h3 id="loading-title">Sedang menyusun roadmap untuk Anda...</h3>
            <p id="loading-subtitle">AI sedang menganalisis profil dan target Anda. Mohon tunggu sebentar.</p>
            <div class="loading-progress-text">
                <span id="loading-elapsed">0</span> detik
            </div>
        </div>
    </div>

    {{-- Error State --}}
    <div id="pathway-error-state" class="pathway-error-state" style="display: none;">
        <i class="bi bi-exclamation-triangle"></i>
        <h3>Generation Gagal</h3>
        <p id="error-message">Terjadi kesalahan. Silakan coba lagi.</p>
        <button class="btn btn-outline-primary" onclick="location.reload()">
            Coba Lagi
        </button>
    </div>
</div>