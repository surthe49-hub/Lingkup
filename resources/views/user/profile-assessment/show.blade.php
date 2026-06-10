@extends('layouts.dashboard')

@section('breadcrumb', 'Profil Akademik · Detail')

@section('content')
    <x-page-header
        title="Detail Profil Akademik"
        :subtitle="'Terakhir diperbarui ' . $profile->updated_at->translatedFormat('d F Y, H:i')">
        <x-slot name="actions">
            <a href="{{ route('dashboard') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
            <a href="{{ route('profile-assessment.edit') }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Profil
            </a>
        </x-slot>
    </x-page-header>

    {{-- Completion Status Banner --}}
    <div class="lingkup-card mb-4" style="background: {{ $profile->isComplete() ? 'var(--lingkup-primary-light)' : '#FEF3C7' }}; border-color: {{ $profile->isComplete() ? 'var(--lingkup-primary)' : '#F59E0B' }};">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                @if ($profile->isComplete())
                    <i class="bi bi-check-circle-fill" style="font-size: 1.5rem; color: var(--lingkup-success);"></i>
                @else
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 1.5rem; color: var(--lingkup-warning);"></i>
                @endif
            </div>
            <div class="flex-grow-1">
                <div style="font-weight: 600; margin-bottom: 0.25rem;">
                    @if ($profile->isComplete())
                        Profil sudah lengkap
                    @else
                        Profil belum lengkap
                    @endif
                </div>
                <div style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                    Kelengkapan data: {{ $profile->completion_percentage }}%
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 1: Data Akademik                      --}}
    {{-- ============================================ --}}
    <div class="lingkup-card mb-4">
        <div class="lingkup-section-header">
            <span class="lingkup-section-number">1</span>
            <div class="lingkup-section-header-text">
                <h3>Data Akademik</h3>
                <p>Informasi dasar studi</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Jurusan</div>
                <div style="font-weight: 500;">{{ $profile->major ?? '—' }}</div>
            </div>
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Jenjang Pendidikan</div>
                <div style="font-weight: 500;">{{ $profile->education_level ?? '—' }}</div>
            </div>
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Semester</div>
                <div style="font-weight: 500;">{{ $profile->semester ?? '—' }}</div>
            </div>
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">IPK</div>
                <div style="font-weight: 500;">{{ $profile->gpa ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 2: Kemampuan Bahasa                   --}}
    {{-- ============================================ --}}
    <div class="lingkup-card mb-4">
        <div class="lingkup-section-header">
            <span class="lingkup-section-number">2</span>
            <div class="lingkup-section-header-text">
                <h3>Kemampuan Bahasa</h3>
                <p>Tingkat penguasaan bahasa Inggris dan bahasa lain</p>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Tingkat Bahasa Inggris</div>
                <div style="font-weight: 500;">{{ ucfirst($profile->english_level ?? '—') }}</div>
            </div>
            @if ($profile->english_test_type)
                <div class="col-md-6">
                    <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Sertifikasi Tes</div>
                    <div style="font-weight: 500;">
                        {{ str_replace('_', ' ', $profile->english_test_type) }}
                        @if ($profile->english_test_score)
                            <span style="color: var(--lingkup-text-muted);">·</span>
                            Skor {{ $profile->english_test_score }}
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if (! empty($profile->other_languages))
            <div style="padding-top: var(--space-md); border-top: 1px solid var(--lingkup-border);">
                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-sm);">Bahasa Lain</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($profile->other_languages as $lang)
                        @if (! empty($lang['lang']))
                            <span class="lingkup-tag">
                                {{ $lang['lang'] }}
                                @if (! empty($lang['level']))
                                    <span style="opacity: 0.7;">· {{ ucfirst($lang['level']) }}</span>
                                @endif
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 3: Skill & Pengalaman                 --}}
    {{-- ============================================ --}}
    <div class="lingkup-card mb-4">
        <div class="lingkup-section-header">
            <span class="lingkup-section-number">3</span>
            <div class="lingkup-section-header-text">
                <h3>Skill & Pengalaman</h3>
                <p>Keahlian, pengalaman organisasi, dan minat bidang</p>
            </div>
        </div>

        {{-- Skills --}}
        <div class="mb-3">
            <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-sm);">Current Skills</div>
            @if (! empty($profile->current_skills))
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($profile->current_skills as $skill)
                        <span class="lingkup-tag">{{ $skill }}</span>
                    @endforeach
                </div>
            @else
                <div style="color: var(--lingkup-text-light); font-style: italic;">Belum ada skill ditambahkan</div>
            @endif
        </div>

        {{-- Organization Experience --}}
        <div class="mb-3" style="padding-top: var(--space-md); border-top: 1px solid var(--lingkup-border);">
            <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-sm);">Pengalaman Organisasi</div>
            @if ($profile->organization_experience)
                <div style="white-space: pre-line; line-height: 1.6;">{{ $profile->organization_experience }}</div>
            @else
                <div style="color: var(--lingkup-text-light); font-style: italic;">Belum diisi</div>
            @endif
        </div>

        {{-- Interests --}}
        <div style="padding-top: var(--space-md); border-top: 1px solid var(--lingkup-border);">
            <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-sm);">Minat Bidang</div>
            @if (! empty($profile->interests))
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($profile->interests as $interest)
                        <span class="lingkup-tag">{{ $interest }}</span>
                    @endforeach
                </div>
            @else
                <div style="color: var(--lingkup-text-light); font-style: italic;">Belum ada minat ditambahkan</div>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION 4: Target Karier                      --}}
    {{-- ============================================ --}}
    <div class="lingkup-card mb-4">
        <div class="lingkup-section-header">
            <span class="lingkup-section-number">4</span>
            <div class="lingkup-section-header-text">
                <h3>Target Karier</h3>
                <p>Tujuan jangka panjang dan negara tujuan</p>
            </div>
        </div>

        <div class="mb-3">
            <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">Negara Tujuan</div>
            @if ($profile->target_country)
                <div style="font-weight: 500;">
                    <i class="bi bi-geo-alt me-1" style="color: var(--lingkup-primary);"></i>
                    {{ $profile->target_country }}
                </div>
            @else
                <div style="color: var(--lingkup-text-light); font-style: italic;">Belum diisi</div>
            @endif
        </div>

        <div style="padding-top: var(--space-md); border-top: 1px solid var(--lingkup-border);">
            <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-sm);">Tujuan Karier</div>
            @if ($profile->career_goal)
                <div style="white-space: pre-line; line-height: 1.6;">{{ $profile->career_goal }}</div>
            @else
                <div style="color: var(--lingkup-text-light); font-style: italic;">Belum diisi</div>
            @endif
        </div>
    </div>

    {{-- Action Footer --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
        <a href="{{ route('profile-assessment.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit Profil
        </a>
    </div>
@endsection