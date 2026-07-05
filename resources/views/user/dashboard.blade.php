@extends('layouts.dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
    @php
        // ============================================
        // Dashboard state variables
        // ============================================
        $userProfile = auth()->user()->profile;
        $userTarget = auth()->user()->userTarget?->target;
        $userPathway = auth()->user()->pathway;
        $profileComplete = $userProfile && $userProfile->isComplete();
        $mismatchInfo = auth()->user()->getPathwayTargetMismatchInfo();

        // Show "Langkah Memulai" hanya jika belum semua done
        $allComplete = $isProfileComplete && $activeTarget && $userPathway;
        $showOnboarding = ! $allComplete;
    @endphp

    {{-- ============================================ --}}
    {{-- HERO SECTION (Phase 5.4.B Redesign)            --}}
    {{-- ============================================ --}}
    <div class="dashboard-hero mb-4">
        @if ($userPathway && $activeTarget)
            {{-- State: User has pathway --}}
            <div class="dashboard-hero-content">
                <div class="dashboard-hero-greeting">
                    <span class="hero-emoji">👋</span>
                    <span>Halo, {{ $user->name }}</span>
                </div>
                <h1 class="dashboard-hero-title">
                    Kamu sedang mempersiapkan <span class="text-primary-emphasis">{{ $activeTarget->name }}</span>.
                </h1>
                <p class="dashboard-hero-subtitle">
                    Pathway {{ $userPathway->estimated_total_duration }} sedang berjalan. Lanjutkan perjalananmu.
                </p>
                <div class="dashboard-hero-actions">
                    <a href="{{ route('user.pathway.show', $userPathway) }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-map me-1"></i>
                        Lanjutkan Roadmap
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @elseif ($profileComplete && $activeTarget)
            {{-- State: Ready to generate --}}
            <div class="dashboard-hero-content">
                <div class="dashboard-hero-greeting">
                    <span class="hero-emoji">✨</span>
                    <span>Halo, {{ $user->name }}</span>
                </div>
                <h1 class="dashboard-hero-title">
                    Siap menyusun roadmap menuju <span class="text-primary-emphasis">{{ $activeTarget->name }}</span>?
                </h1>
                <p class="dashboard-hero-subtitle">
                    Profil dan targetmu sudah lengkap. Saatnya AI menyusun roadmap personal untukmu.
                </p>
                <div class="dashboard-hero-actions">
                    <a href="{{ route('user.pathway.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-magic me-1"></i>
                        Mulai Generate Pathway
                    </a>
                </div>
            </div>
        @else
            {{-- State: Onboarding (new user) --}}
            <div class="dashboard-hero-content">
                <div class="dashboard-hero-greeting">
                    <span class="hero-emoji">👋</span>
                    <span>Halo, {{ $user->name }}</span>
                </div>
                <h1 class="dashboard-hero-title">
                    Mari mulai perjalanan menuju target studi internasionalmu.
                </h1>
                <p class="dashboard-hero-subtitle">
                    Selesaikan 3 langkah berikut untuk mendapatkan roadmap personal dari AI.
                </p>
            </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- Stat Cards (3 cards) - Conditional show       --}}
    {{-- Hide stat card 2 if active target highlighted --}}
    {{-- ============================================ --}}
    @unless ($activeTarget && $isProfileComplete && $userPathway)
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
                    <div class="lingkup-stat-card lingkup-stat-card-locked">
                        <div class="lingkup-stat-card-icon stat-icon-locked">
                            <i class="bi bi-lock"></i>
                        </div>
                        <p class="lingkup-stat-card-label">Target Aktif</p>
                        <p class="lingkup-stat-card-value stat-value-locked">Terkunci</p>
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
                            <div class="lingkup-stat-card-icon stat-icon-primary">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <p class="lingkup-stat-card-label">Target Aktif</p>
                            <p class="lingkup-stat-card-value stat-value-compact">
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
                @if ($userPathway)
                    <a href="{{ route('user.pathway.show', $userPathway) }}" class="text-decoration-none">
                        <div class="lingkup-stat-card">
                            <div class="lingkup-stat-card-icon stat-icon-success">
                                <i class="bi bi-map"></i>
                            </div>
                            <p class="lingkup-stat-card-label">Pathway</p>
                            <p class="lingkup-stat-card-value stat-value-compact">Tersedia</p>
                            <div class="lingkup-stat-card-meta">Lihat roadmap →</div>
                        </div>
                    </a>
                @else
                    <div class="lingkup-stat-card lingkup-stat-card-locked">
                        <div class="lingkup-stat-card-icon stat-icon-locked">
                            <i class="bi bi-map"></i>
                        </div>
                        <p class="lingkup-stat-card-label">Pathway</p>
                        <p class="lingkup-stat-card-value stat-value-locked">Belum Ada</p>
                        <div class="lingkup-stat-card-meta">Generate di bawah ini</div>
                    </div>
                @endif
            </div>
        </div>
    @endunless

    {{-- ============================================ --}}
    {{-- Mismatch Banner (Phase 5.2) - dekat focal     --}}
    {{-- ============================================ --}}
    <x-pathway.mismatch-banner :mismatch-info="$mismatchInfo" />

    {{-- ============================================ --}}
    {{-- Pathway Card (Focal Point)                   --}}
    {{-- ============================================ --}}
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
            <div class="dashboard-pathway-card pathway-card-ready">
                <div class="dashboard-pathway-header">
                    <div>
                        <h3 class="dashboard-pathway-title pathway-title-primary">
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
            <div class="dashboard-pathway-card pathway-card-prereq">
                <div class="dashboard-pathway-header">
                    <div>
                        <h3 class="dashboard-pathway-title">
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
    {{-- Active Target Highlight Card                  --}}
    {{-- Show hanya jika belum punya pathway           --}}
    {{-- ============================================ --}}
    @if ($activeTarget && $isProfileComplete && ! $userPathway)
        <div class="lingkup-card target-highlight-card mb-4">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="target-highlight-avatar">
                    {{ strtoupper(substr($activeTarget->name, 0, 1)) }}
                </div>

                <div class="flex-grow-1" style="min-width: 200px;">
                    <div class="target-highlight-label">
                        <i class="bi bi-bullseye me-1"></i> Target aktif kamu
                    </div>
                    <h3 class="target-highlight-name">
                        {{ $activeTarget->name }}
                    </h3>
                    <div class="target-highlight-meta">
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
    {{-- Langkah Memulai - hidden jika all complete    --}}
    {{-- ============================================ --}}
    @if ($showOnboarding)
        <x-section-card title="Langkah Memulai" class="mb-4">
            {{-- Step 1: Profile Assessment --}}
            <div class="onboarding-step">
                @if ($isProfileComplete)
                    <div class="onboarding-step-icon onboarding-step-complete">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title onboarding-step-title-complete">
                            Profil Akademik Lengkap
                        </h4>
                        <p class="onboarding-step-desc">
                            Profil sudah lengkap, siap untuk langkah berikutnya.
                            <a href="{{ route('profile-assessment.show') }}" class="onboarding-link">Lihat profil →</a>
                        </p>
                    </div>
                @elseif ($hasProfile)
                    <div class="onboarding-step-icon onboarding-step-active">1</div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title">
                            Lanjutkan Profil Akademik ({{ $completionPercentage }}%)
                        </h4>
                        <p class="onboarding-step-desc">
                            Beberapa data masih kosong.
                            <a href="{{ route('profile-assessment.edit') }}" class="onboarding-link">Lanjutkan →</a>
                        </p>
                    </div>
                @else
                    <div class="onboarding-step-icon onboarding-step-active">1</div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title">
                            Mulai dengan Profil Akademik
                        </h4>
                        <p class="onboarding-step-desc">
                            Lengkapi data akademik untuk mendapatkan pathway personal.
                            <a href="{{ route('profile-assessment.index') }}" class="onboarding-link">Mulai sekarang →</a>
                        </p>
                    </div>
                @endif
            </div>

            {{-- Step 2: Pilih Target --}}
            <div class="onboarding-step">
                @if ($activeTarget)
                    <div class="onboarding-step-icon onboarding-step-complete">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title onboarding-step-title-complete">
                            Target Sudah Dipilih
                        </h4>
                        <p class="onboarding-step-desc">
                            Target aktif: <strong>{{ $activeTarget->name }}</strong>.
                            <a href="{{ route('target.index') }}" class="onboarding-link">Ganti target →</a>
                        </p>
                    </div>
                @elseif ($isProfileComplete)
                    <div class="onboarding-step-icon onboarding-step-active">2</div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title">
                            Pilih Target Beasiswa
                        </h4>
                        <p class="onboarding-step-desc">
                            Pilih beasiswa atau program internasional yang ingin kamu kejar.
                            <a href="{{ route('target.index') }}" class="onboarding-link">Pilih sekarang →</a>
                        </p>
                    </div>
                @else
                    <div class="onboarding-step-icon onboarding-step-locked">2</div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title onboarding-step-title-locked">
                            Pilih Target Beasiswa
                        </h4>
                        <p class="onboarding-step-desc onboarding-step-desc-locked">
                            Lengkapi profil terlebih dahulu untuk membuka langkah ini.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Step 3: AI Pathway --}}
            <div class="onboarding-step onboarding-step-last">
                @if ($userPathway)
                    <div class="onboarding-step-icon onboarding-step-complete">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title onboarding-step-title-complete">
                            Pathway Telah Dibuat
                        </h4>
                        <p class="onboarding-step-desc">
                            AI berhasil menyusun roadmap studi milikmu.
                            <a href="{{ route('user.pathway.show', $userPathway) }}" class="onboarding-link">Akses roadmap →</a>
                        </p>
                    </div>
                @elseif ($profileComplete && $userTarget)
                    <div class="onboarding-step-icon onboarding-step-active">3</div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title">
                            Dapatkan Pathway Personal
                        </h4>
                        <p class="onboarding-step-desc">
                            Langkah terakhir siap! Silakan klik tombol generate di atas untuk menyusun roadmap AI Anda.
                        </p>
                    </div>
                @else
                    <div class="onboarding-step-icon onboarding-step-locked">3</div>
                    <div class="flex-grow-1">
                        <h4 class="onboarding-step-title onboarding-step-title-locked">
                            Dapatkan Pathway Personal
                        </h4>
                        <p class="onboarding-step-desc onboarding-step-desc-locked">
                            AI akan menyusun roadmap personal lengkap setelah profil dan target beasiswa terpenuhi.
                        </p>
                    </div>
                @endif
            </div>
        </x-section-card>
    @endif

    {{-- ============================================ --}}
    {{-- Account Info Footer (minimal)                 --}}
    {{-- ============================================ --}}
    <div class="dashboard-account-footer">
        <div class="account-footer-content">
            <span class="account-footer-item">
                <i class="bi bi-envelope"></i>
                {{ $user->email }}
            </span>
            <span class="account-footer-divider">•</span>
            <span class="account-footer-item">
                <i class="bi bi-person-badge"></i>
                {{ ucfirst($user->role) }}
            </span>
            <span class="account-footer-divider">•</span>
            <span class="account-footer-item">
                <i class="bi bi-calendar"></i>
                Member sejak {{ $user->created_at->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>
@endsection