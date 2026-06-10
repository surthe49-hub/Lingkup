@extends('layouts.dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <x-page-header
        title="Halo, {{ $user->name }}!"
        subtitle="Selamat datang di LINGKUP. Mari mulai perjalanan menuju targetmu." />
    @php
    $profilePercentage = $profile?->completion_percentage ?? 0;

    $profileStatus = $profile
        ? $profilePercentage . '% Lengkap'
        : 'Belum Dimulai';

    $profileMeta = $profile
        ? 'Profile Assessment sudah tersedia'
        : 'Lengkapi profilmu untuk memulai';
@endphp
    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <x-stat-card
                label="Status Profil"
                :value="$profileStatus"
                icon="bi-person-circle"
                :meta="$profileMeta" />
        </div>
        <div class="col-md-4">
            <x-stat-card
                label="Pathway Aktif"
                value="0"
                icon="bi-map"
                meta="Belum ada pathway dibuat" />
        </div>
        <div class="col-md-4">
            <x-stat-card
                label="Tasks Selesai"
                value="0"
                icon="bi-check2-square"
                meta="Dari 0 total tasks" />
        </div>
    </div>


 @php
    $profileCompleted = $profile && $profile->isComplete();
@endphp

    {{-- Welcome Section --}}
    <x-section-card title="Langkah Memulai">

    <div class="d-flex align-items-start gap-3 mb-3">
    <div
        style="
            width:32px;
            height:32px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:600;
            flex-shrink:0;

            {{ $profileCompleted
                ? 'background:#dcfce7;color:#16a34a;'
                : 'background:var(--lingkup-primary-light);color:var(--lingkup-primary);'
            }}
        "
    >
        {{ $profileCompleted ? '✓' : '1' }}
    </div>

    <div>
        <h4 style="font-size:1rem;font-weight:600;margin:0 0 .25rem;">

            @if($profileCompleted)
                Profil Akademik Lengkap
            @else
                Lengkapi Profil Akademik
            @endif

        </h4>

        <p style="color:var(--lingkup-text-muted);margin:0;">
            Profile Assessment akan membantu AI menyusun roadmap yang relevan untukmu.
        </p>

        <div class="mt-2">

            @if(!$profile)

                <a href="{{ route('profile-assessment.index') }}"
                   class="btn btn-primary btn-sm">
                    Mulai Assessment
                </a>

            @else

                <a href="{{ route('profile-assessment.show') }}"
                   class="btn btn-outline-primary btn-sm">
                    Lihat Profil
                </a>

            @endif

        </div>
    </div>

</div>

        <div class="d-flex align-items-start gap-3 mb-3">
            <div style="width: 32px; height: 32px; background: var(--lingkup-bg); color: var(--lingkup-text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">2</div>
            <div>
                <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-text-muted);">Pilih Target Beasiswa</h4>
                <p style="color: var(--lingkup-text-light); margin: 0;">
                    Pilih beasiswa atau program internasional yang ingin kamu kejar.
                </p>
            </div>
        </div>

        <div class="d-flex align-items-start gap-3">
            <div style="width: 32px; height: 32px; background: var(--lingkup-bg); color: var(--lingkup-text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">3</div>
            <div>
                <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem; color: var(--lingkup-text-muted);">Dapatkan Pathway Personal</h4>
                <p style="color: var(--lingkup-text-light); margin: 0;">
                    AI akan menyusun roadmap personal lengkap berdasarkan profil dan target.
                </p>
            </div>
        </div>
    </x-section-card>

    {{-- Account Info --}}
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
                <div style="font-weight: 500;">{{ $user->created_at->format('d M Y') }}</div>
            </div>
        </div>
    </x-section-card>
@endsection