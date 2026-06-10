@extends('layouts.dashboard')

@section('breadcrumb', 'Profil Akademik')

@section('content')
    @if (! $profile)
        {{-- ============================================ --}}
        {{-- STATE A: Belum ada profile (Welcome screen)   --}}
        {{-- ============================================ --}}

        <x-page-header
            title="Profil Akademik"
            subtitle="Lengkapi profil untuk mendapatkan pathway personal dari AI." />

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="lingkup-card">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: var(--lingkup-primary-light); color: var(--lingkup-primary); border-radius: 50%; font-size: 2rem;">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>

                    <h2 class="text-center mb-3" style="font-size: 1.5rem; font-weight: 700;">
                        Mari mulai dengan mengenal kamu
                    </h2>

                    <p class="text-center mb-4" style="color: var(--lingkup-text-muted); font-size: 1rem;">
                        Profile assessment ini membantu AI memahami latar belakang akademik,
                        kemampuan bahasa, pengalaman, dan target globalmu untuk menyusun
                        roadmap personal yang relevan.
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2-circle" style="color: var(--lingkup-success); font-size: 1.25rem; flex-shrink: 0;"></i>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.125rem;">Data Akademik</strong>
                                    <span style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                                        Jurusan, jenjang, semester, IPK
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2-circle" style="color: var(--lingkup-success); font-size: 1.25rem; flex-shrink: 0;"></i>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.125rem;">Kemampuan Bahasa</strong>
                                    <span style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                                        Inggris dan bahasa asing lain
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2-circle" style="color: var(--lingkup-success); font-size: 1.25rem; flex-shrink: 0;"></i>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.125rem;">Pengalaman & Minat</strong>
                                    <span style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                                        Skills, organisasi, minat bidang
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2-circle" style="color: var(--lingkup-success); font-size: 1.25rem; flex-shrink: 0;"></i>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.125rem;">Target Karier</strong>
                                    <span style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                                        Negara dan tujuan jangka panjang
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-center gap-2">
                        <a href="{{ route('profile-assessment.edit') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-pencil-square me-1"></i> Mulai Assessment
                        </a>
                        <span style="color: var(--lingkup-text-muted); font-size: 0.8125rem;">
                            <i class="bi bi-clock me-1"></i> Estimasi waktu: ± 5 menit
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="lingkup-card">
                    <h3 class="lingkup-card-title">Mengapa Penting?</h3>
                    <ul style="padding-left: 1.25rem; color: var(--lingkup-text-muted); font-size: 0.9375rem; margin: 0;">
                        <li class="mb-2">Pathway dipersonalisasi berdasarkan profilmu</li>
                        <li class="mb-2">Rekomendasi target lebih relevan</li>
                        <li class="mb-2">Estimasi timeline lebih akurat</li>
                        <li>Bisa diupdate kapan saja</li>
                    </ul>

                    <hr style="margin: var(--space-lg) 0; border-color: var(--lingkup-border);">

                    <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted);">
                        <i class="bi bi-shield-lock me-1"></i>
                        Data kamu hanya digunakan untuk personalisasi pathway.
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- ============================================ --}}
        {{-- STATE B: Profile sudah ada (Summary)          --}}
        {{-- ============================================ --}}

        <x-page-header
            title="Profil Akademik"
            subtitle="Profil kamu tersimpan. Edit kapan saja jika ada perubahan.">
            <x-slot name="actions">
                <a href="{{ route('profile-assessment.show') }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i> Lihat Detail
                </a>
                <a href="{{ route('profile-assessment.edit') }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Profil
                </a>
            </x-slot>
        </x-page-header>

        {{-- Completion Card --}}
        <div class="lingkup-card mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if ($profile->isComplete())
                            <span class="badge" style="background: var(--lingkup-success); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-full); font-weight: 500;">
                                <i class="bi bi-check-circle me-1"></i> Lengkap
                            </span>
                        @else
                            <span class="badge" style="background: var(--lingkup-warning); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-full); font-weight: 500;">
                                <i class="bi bi-clock me-1"></i> Belum Lengkap
                            </span>
                        @endif
                        <span style="font-size: 0.875rem; color: var(--lingkup-text-muted);">
                            Terakhir diperbarui {{ $profile->updated_at->translatedFormat('d F Y, H:i') }}
                        </span>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0;">
                        {{ $profile->completion_percentage }}% data terisi
                    </h3>
                </div>
            </div>

            <div class="progress" style="height: 8px; border-radius: var(--radius-full); background: var(--lingkup-bg);">
                <div class="progress-bar"
                     style="background: var(--lingkup-primary); border-radius: var(--radius-full); width: {{ $profile->completion_percentage }}%;"
                     role="progressbar"
                     aria-valuenow="{{ $profile->completion_percentage }}"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>

            @if (! $profile->isComplete())
                <p style="margin-top: var(--space-md); margin-bottom: 0; color: var(--lingkup-text-muted); font-size: 0.9375rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    Beberapa field wajib masih kosong. Lengkapi untuk hasil pathway optimal.
                </p>
            @endif
        </div>

        {{-- Quick Summary Cards --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="lingkup-card h-100">
                    <h4 class="lingkup-card-title" style="font-size: 1rem;">
                        <i class="bi bi-mortarboard me-1" style="color: var(--lingkup-primary);"></i>
                        Data Akademik
                    </h4>
                    <div style="font-size: 0.9375rem;">
                        <div class="mb-2">
                            <span style="color: var(--lingkup-text-muted);">Jurusan:</span>
                            <span style="font-weight: 500;">{{ $profile->major ?? '—' }}</span>
                        </div>
                        <div class="mb-2">
                            <span style="color: var(--lingkup-text-muted);">Jenjang:</span>
                            <span style="font-weight: 500;">{{ $profile->education_level ?? '—' }}</span>
                        </div>
                        <div class="mb-2">
                            <span style="color: var(--lingkup-text-muted);">Semester:</span>
                            <span style="font-weight: 500;">{{ $profile->semester ?? '—' }}</span>
                        </div>
                        <div>
                            <span style="color: var(--lingkup-text-muted);">IPK:</span>
                            <span style="font-weight: 500;">{{ $profile->gpa ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="lingkup-card h-100">
                    <h4 class="lingkup-card-title" style="font-size: 1rem;">
                        <i class="bi bi-translate me-1" style="color: var(--lingkup-primary);"></i>
                        Kemampuan Bahasa
                    </h4>
                    <div style="font-size: 0.9375rem;">
                        <div class="mb-2">
                            <span style="color: var(--lingkup-text-muted);">English Level:</span>
                            <span style="font-weight: 500;">{{ ucfirst($profile->english_level ?? '—') }}</span>
                        </div>
                        @if ($profile->english_test_type)
                            <div class="mb-2">
                                <span style="color: var(--lingkup-text-muted);">Tes:</span>
                                <span style="font-weight: 500;">{{ str_replace('_', ' ', $profile->english_test_type) }}
                                    @if ($profile->english_test_score)
                                        · Skor {{ $profile->english_test_score }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        @if (! empty($profile->other_languages))
                            <div>
                                <span style="color: var(--lingkup-text-muted);">Bahasa Lain:</span>
                                <span style="font-weight: 500;">{{ count($profile->other_languages) }} bahasa</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="lingkup-card h-100">
                    <h4 class="lingkup-card-title" style="font-size: 1rem;">
                        <i class="bi bi-stars me-1" style="color: var(--lingkup-primary);"></i>
                        Skills & Minat
                    </h4>
                    <div style="font-size: 0.9375rem;">
                        <div class="mb-2">
                            <span style="color: var(--lingkup-text-muted);">Skills:</span>
                            <span style="font-weight: 500;">
                                {{ ! empty($profile->current_skills) ? count($profile->current_skills) . ' skill' : '—' }}
                            </span>
                        </div>
                        <div>
                            <span style="color: var(--lingkup-text-muted);">Minat:</span>
                            <span style="font-weight: 500;">
                                {{ ! empty($profile->interests) ? count($profile->interests) . ' bidang' : '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="lingkup-card h-100">
                    <h4 class="lingkup-card-title" style="font-size: 1rem;">
                        <i class="bi bi-bullseye me-1" style="color: var(--lingkup-primary);"></i>
                        Target Karier
                    </h4>
                    <div style="font-size: 0.9375rem;">
                        <div class="mb-2">
                            <span style="color: var(--lingkup-text-muted);">Negara Tujuan:</span>
                            <span style="font-weight: 500;">{{ $profile->target_country ?? '—' }}</span>
                        </div>
                        <div>
                            <span style="color: var(--lingkup-text-muted);">Tujuan Karier:</span>
                            <span style="font-weight: 500;">
                                {{ $profile->career_goal ? \Illuminate\Support\Str::limit($profile->career_goal, 60) : '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection