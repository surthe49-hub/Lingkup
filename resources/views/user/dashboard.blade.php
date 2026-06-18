@extends('layouts.dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <x-page-header
        title="Halo, {{ $user->name }}!"
        subtitle="Selamat datang di LINGKUP. Mari mulai perjalanan menuju targetmu." />

    {{-- ============================================ --}}
    {{-- Stat Cards (3 cards)                         --}}
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

        {{-- Card 3: Pathway --}}
        <div class="col-md-4">
            @if (auth()->user()->pathway)
                <a href="{{ route('user.pathway.show', auth()->user()->pathway) }}" class="text-decoration-none">
                    <div class="lingkup-stat-card">
                        <div class="lingkup-stat-card-icon" style="background: var(--lingkup-success); color: white;">
                            <i class="bi bi-map"></i>
                        </div>
                        <p class="lingkup-stat-card-label">Pathway</p>
                        <p class="lingkup-stat-card-value" style="font-size: 1.0625rem; line-height: 1.4;">Tersedia</p>
                        <div class="lingkup-stat-card-meta">Lihat progress roadmap →</div>
                    </div>
                </a>
            @else
                <div class="lingkup-stat-card" style="opacity: 0.6;">
                    <div class="lingkup-stat-card-icon" style="background: var(--lingkup-bg); color: var(--lingkup-text-light);">
                        <i class="bi bi-map"></i>
                    </div>
                    <p class="lingkup-stat-card-label">Pathway</p>
                    <p class="lingkup-stat-card-value" style="color: var(--lingkup-text-light);">Belum Ada</p>
                    <div class="lingkup-stat-card-meta">Generate di bawah ini</div>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- Pathway Card (Full Width) - Phase 4 & 5.2     --}}
    {{-- ============================================ --}}
    @php
        $userProfile = auth()->user()->profile;
        $userTarget = auth()->user()->userTarget?->target;
        $userPathway = auth()->user()->pathway;
        $profileComplete = $userProfile && $userProfile->isComplete();
        $mismatchInfo = auth()->user()->getPathwayTargetMismatchInfo();
    @endphp

    {{-- Mismatch Banner (Phase 5.2) --}}
    <x-pathway.mismatch-banner :mismatch-info="$mismatchInfo" />

    <div class="mb-4">
        @if ($userPathway)
            {{-- State: Active Pathway --}}
            <div class="dashboard-pathway-card pathway-card-active">
                <div class="dashboard-pathway-header">
                    <div>
                        <h3 class="dashboard-pathway-title">{{ $userPathway->title }}</h3>
                        <p class="text-muted mb-0">{{ Str::limit($userPathway->summary, 150) }}</p>
                    </div>
                    <span class="dashboard-pathway-badge">
                        <i class="bi bi-check-circle-fill"></i> Aktif
                    </span>
                </div>

                <div class="dashboard-pathway-meta mt-3">
                    <span class="dashboard-pathway-meta-item me-3">
                        <i class="bi bi-flag-fill me-1"></i>
                        {{ $userPathway->target->name }}
                    </span>
                    <span class="dashboard-pathway-meta-item me-3">
                        <i class="bi bi-clock me-1"></i>
                        {{ $userPathway->estimated_total_duration }}
                    </span>
                    <span class="dashboard-pathway-meta-item me-3">
                        <i class="bi bi-layers me-1"></i>
                        {{ $userPathway->phases()->count() }} fase
                    </span>
                    <span class="dashboard-pathway-meta-item">
                        <i class="bi bi-list-check me-1"></i>
                        {{ $userPathway->tasks()->count() }} task
                    </span>
                </div>

                <div class="dashboard-pathway-actions mt-3">
                    <a href="{{ route('user.pathway.show', $userPathway) }}" class="btn btn-primary">
                        Lihat Pathway
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @elseif ($profileComplete && $userTarget)
            {{-- State: Ready to Generate --}}
            <div class="dashboard-pathway-card pathway-card-empty p-4" style="background: white; border: 1px dashed var(--lingkup-primary); border-radius: var(--radius-lg);">
                <div class="dashboard-pathway-header">
                    <div>
                        <h3 class="dashboard-pathway-title" style="color: var(--lingkup-primary); font-weight: 700;">
                            <i class="bi bi-magic me-2"></i>Siap Generate Pathway
                        </h3>
                        <p class="text-muted mb-0">Profil dan target Anda sudah lengkap. Saatnya menghasilkan roadmap personal dengan AI.</p>
                    </div>
                </div>

                <div class="dashboard-pathway-actions mt-3">
                    <a href="{{ route('user.pathway.index') }}" class="btn btn-primary">
                        <i class="bi bi-magic me-1"></i> Mulai Generate
                    </a>
                </div>
            </div>
        @else
            {{-- State: Prerequisites Incomplete --}}
            <div class="dashboard-pathway-card pathway-card-empty p-4" style="background: white; border: 1px dashed var(--lingkup-border); border-radius: var(--radius-lg); opacity: 0.85;">
                <div class="dashboard-pathway-header">
                    <div>
                        <h3 class="dashboard-pathway-title" style="font-weight: 600;">
                            <i class="bi bi-info-circle me-2"></i>Pathway Anda Menanti
                        </h3>
                        <p class="text-muted mb-0">
                            @if (! $profileComplete)
                                Lengkapi Profile Assessment terlebih dahulu untuk dapat menggunakan fitur AI pathway generator.
                            @else
                                Pilih target studi internasional Anda untuk dapat menyusun AI pathway.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="dashboard-pathway-actions mt-3">
                    @if (! $profileComplete)
                        <a href="{{ route('profile-assessment.edit') }}" class="btn btn-primary">
                            Lengkapi Profil
                        </a>
                    @else
                        <a href="{{ route('target.index') }}" class="btn btn-primary">
                            Pilih Target
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- Active Target Highlight Card (jika ada)      --}}
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
    <x-section-card title="Langkah Memulai" class="mb-4">
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
                        Some data are missing.
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

        {{-- Step 3: AI Pathway --}}
        <div class="d-flex align-items-start gap-3">
            @if ($userPathway)
                <div style="width: 32px; height: 32px; background: var(--lingkup-success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-success);">
                        Pathway Telah Dibuat
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        AI berhasil menyusun roadmap studi milikmu. <a href="{{ route('user.pathway.show', $userPathway) }}" style="color: var(--lingkup-primary);">Akses roadmap →</a>
                    </p>
                </div>
            @elseif ($profileComplete && $userTarget)
                <div style="width: 32px; height: 32px; background: var(--lingkup-primary-light); color: var(--lingkup-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">3</div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem;">
                        Dapatkan Pathway Personal
                    </h4>
                    <p style="color: var(--lingkup-text-muted); margin: 0; font-size: 0.9375rem;">
                        Langkah terakhir siap! Silakan klik tombol generate di atas untuk menyusun roadmap AI Anda.
                    </p>
                </div>
            @else
                <div style="width: 32px; height: 32px; background: var(--lingkup-bg); color: var(--lingkup-text-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">3</div>
                <div class="flex-grow-1">
                    <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-text-light);">
                        Dapatkan Pathway Personal
                    </h4>
                    <p style="color: var(--lingkup-text-light); margin: 0; font-size: 0.9375rem;">
                        AI akan menyusun roadmap personal lengkap setelah profil dan target beasiswa terpenuhi.
                    </p>
                </div>
            @endif
        </div>
    </x-section-card>

    {{-- ============================================ --}}
    {{-- Account Info (footer)                        --}}
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