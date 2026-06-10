@extends('layouts.dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <x-page-header
        title="Halo, {{ $user->name }}!"
        subtitle="Selamat datang di LINGKUP. Mari mulai perjalanan menuju targetmu." />

    {{-- ============================================ --}}
    {{-- Stat Cards (3 cards)                          --}}
    {{-- ============================================ --}}
    <div class="row g-3 mb-4">

        {{-- Card 1: Status Profil --}}
        <div class="col-md-4">
            @if (! $hasProfile)
                <a href="{{ route('profile-assessment.index') }}" class="text-decoration-none">
                    <x-stat-card
                        label="Status Profil"
                        value="Belum Dimulai"
                        icon="bi-exclamation-circle"
                        meta="Klik untuk memulai →" />
                </a>
            @elseif (! $isProfileComplete)
                <a href="{{ route('profile-assessment.edit') }}" class="text-decoration-none">
                    <x-stat-card
                        label="Status Profil"
                        value="{{ $completionPercentage }}%"
                        icon="bi-clock"
                        meta="Lengkapi yang tersisa →" />
                </a>
            @else
                <x-stat-card
                    label="Status Profil"
                    value="Lengkap"
                    icon="bi-check-circle-fill"
                    meta="{{ $profile->major }} · Semester {{ $profile->semester }}" />
            @endif
        </div>

        {{-- Card 2: Target Aktif (3 state) --}}
        <div class="col-md-4">
            @if (! $isProfileComplete)
                {{-- State A: Profile belum lengkap → disabled --}}
                <div class="lingkup-stat-card" style="opacity: 0.6;">
                    <div class="lingkup-stat-card-icon" style="background: var(--lingkup-bg); color: var(--lingkup-text-light);">
                        <i class="bi bi-lock"></i>
                    </div>
                    <p class="lingkup-stat-card-label">Target Aktif</p>
                    <p class="lingkup-stat-card-value" style="color: var(--lingkup-text-light);">Terkunci</p>
                    <div class="lingkup-stat-card-meta">Lengkapi profil dulu</div>
                </div>
            @elseif (! $activeTarget)
                {{-- State B: Profile lengkap, belum pilih target --}}
                <a href="{{ route('target.index') }}" class="text-decoration-none">
                    <x-stat-card
                        label="Target Aktif"
                        value="Belum Dipilih"
                        icon="bi-bullseye"
                        meta="Pilih target sekarang →" />
                </a>
            @else
                {{-- State C: Sudah pilih target --}}
                <a href="{{ route('target.show', $activeTarget) }}" class="text-decoration-none">
                    <div class="lingkup-stat-card">
                        <div class="lingkup-stat-card-icon" style="background: var(--lingkup-primary); color: white;">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <p class="lingkup-stat-card-label">Target Aktif</p>
                        <p class="lingkup-stat-card-value" style="font-size: 1.0625rem; line-height: 1.4;">
                            {{ \Illuminate\Support\Str::limit($activeTarget->name, 30) }}
                        </p>
                        <div class="lingkup-stat-card-meta">
                            <i class="bi bi-geo-alt me-1"></i>{{ $activeTarget->country }}
                        </div>
                    </div>
                </a>
            @endif
        </div>

        {{-- Card 3: Pathway (placeholder Sprint 4) --}}
        <div class="col-md-4">
            <div class="lingkup-stat-card" style="opacity: 0.6;">
                <div class="lingkup-stat-card-icon" style="background: var(--lingkup-bg); color: var(--lingkup-text-light);">
                    <i class="bi bi-map"></i>
                </div>
                <p class="lingkup-stat-card-label">Pathway</p>
                <p class="lingkup-stat-card-value" style="color: var(--lingkup-text-light);">Coming Soon</p>
                <div class="lingkup-stat-card-meta">Tersedia di Sprint 4</div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- Active Target Highlight Card (jika ada)       --}}
    {{-- ============================================ --}}
    @if ($activeTarget && $isProfileComplete)
        <div class="lingkup-card mb-4" style="background: var(--lingkup-primary-light); border-color: var(--lingkup-primary);">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div style="width: 56px; height: 56px; border-radius: var(--radius-lg); background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: 700; color: var(--lingkup-primary);">
                    {{ strtoupper(substr($activeTarget->name, 0, 1)) }}
                </div>

                <div class="flex-grow-1" style="min-width: 200px;">
                    <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">
                        <i class="bi bi-bullseye me-1"></i> Target aktif kamu
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0 0 0.5rem; line-height: 1.3;">
                        {{ $activeTarget->name }}
                    </h3>
                    <div class="d-flex flex-wrap gap-3" style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $activeTarget->country }}</span>
                        <span><i class="bi bi-mortarboard me-1"></i>{{ $activeTarget->education_level }}</span>
                        @if ($activeTarget->typical_deadline)
                            <span><i class="bi bi-calendar-event me-1"></i>{{ $activeTarget->typical_deadline }}</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('target.show', $activeTarget) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-eye me-1"></i> Lihat Detail
                    </a>
                    <a href="{{ route('target.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Ganti Target
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================ --}}
    {{-- Langkah Memulai (3 steps)                     --}}
    {{-- ============================================ --}}
    <x-section-card title="Langkah Memulai">
        {{-- Step 1: Profile Assessment --}}
        <div class="d-flex align-items-start gap-3 mb-3">
            @if ($isProfileComplete)
                <div style="width: 32px; height: 32px; background: var(--lingkup-success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-success);">
                        Profil Akademik Lengkap
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        Profil sudah lengkap, siap untuk langkah berikutnya.
                        <a href="{{ route('profile-assessment.show') }}" style="color: var(--lingkup-primary);">Lihat profil →</a>
                    </p>
                </div>
            @elseif ($hasProfile)
                <div style="width: 32px; height: 32px; background: var(--lingkup-primary-light); color: var(--lingkup-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">1</div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem;">
                        Lanjutkan Profil Akademik ({{ $completionPercentage }}%)
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        Beberapa data masih kosong.
                        <a href="{{ route('profile-assessment.edit') }}" style="color: var(--lingkup-primary);">Lanjutkan →</a>
                    </p>
                </div>
            @else
                <div style="width: 32px; height: 32px; background: var(--lingkup-primary-light); color: var(--lingkup-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">1</div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem;">
                        Mulai dengan Profil Akademik
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        Lengkapi data akademik untuk mendapatkan pathway personal.
                        <a href="{{ route('profile-assessment.index') }}" style="color: var(--lingkup-primary);">Mulai sekarang →</a>
                    </p>
                </div>
            @endif
        </div>

        {{-- Step 2: Pilih Target --}}
        <div class="d-flex align-items-start gap-3 mb-3">
            @if ($activeTarget)
                {{-- Step 2 selesai --}}
                <div style="width: 32px; height: 32px; background: var(--lingkup-success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-success);">
                        Target Sudah Dipilih
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        Target aktif: <strong>{{ $activeTarget->name }}</strong>.
                        <a href="{{ route('target.index') }}" style="color: var(--lingkup-primary);">Ganti target →</a>
                    </p>
                </div>
            @elseif ($isProfileComplete)
                {{-- Step 2 aktif, bisa dikerjakan --}}
                <div style="width: 32px; height: 32px; background: var(--lingkup-primary-light); color: var(--lingkup-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">2</div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem;">
                        Pilih Target Beasiswa
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        Pilih beasiswa atau program internasional yang ingin kamu kejar.
                        <a href="{{ route('target.index') }}" style="color: var(--lingkup-primary);">Pilih sekarang →</a>
                    </p>
                </div>
            @else
                {{-- Step 2 disabled, profile belum lengkap --}}
                <div style="width: 32px; height: 32px; background: var(--lingkup-bg); color: var(--lingkup-text-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">2</div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-text-light);">
                        Pilih Target Beasiswa
                    </h4>
                    <p style="color: var(--lingkup-text-light); margin: 0; font-size: 0.9375rem;">
                        Lengkapi profil terlebih dahulu untuk membuka langkah ini.
                    </p>
                </div>
            @endif
        </div>

        {{-- Step 3: AI Pathway (placeholder Sprint 4) --}}
        <div class="d-flex align-items-start gap-3">
            <div style="width: 32px; height: 32px; background: var(--lingkup-bg); color: var(--lingkup-text-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">3</div>
            <div class="flex-grow-1">
                <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-text-light);">
                    Dapatkan Pathway Personal
                </h4>
                <p style="color: var(--lingkup-text-light); margin: 0; font-size: 0.9375rem;">
                    AI akan menyusun roadmap personal lengkap berdasarkan profil dan target. (Coming Soon)
                </p>
            </div>
        </div>
    </x-section-card>

    {{-- ============================================ --}}
    {{-- Account Info (footer)                         --}}
    {{-- ============================================ --}}
    <x-section-card title="Informasi Akun">
        <div class="row g-3">
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Email</div>
                <div style="font-weight: 500;">{{ $user->email }}</div>
            </div>
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Role</div>
                <div style="font-weight: 500;">{{ ucfirst($user->role) }}</div>
            </div>
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Member Sejak</div>
                <div style="font-weight: 500;">{{ $user->created_at->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </x-section-card>
@endsection