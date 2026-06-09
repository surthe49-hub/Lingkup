@extends('layouts.dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <x-page-header
        title="Halo, {{ $user->name }}!"
        subtitle="Selamat datang di LINGKUP. Mari mulai perjalanan menuju targetmu." />

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <x-stat-card
                label="Status Profil"
                value="Belum Lengkap"
                icon="bi-person-circle"
                meta="Lengkapi profilmu untuk memulai" />
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

    {{-- Welcome Section --}}
    <x-section-card title="Langkah Memulai">
        <div class="d-flex align-items-start gap-3 mb-3">
            <div style="width: 32px; height: 32px; background: var(--lingkup-primary-light); color: var(--lingkup-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">1</div>
            <div>
                <h4 style="font-size: 1rem; font-weight: 600; margin: 0 0 0.25rem;">Lengkapi Profil Akademik</h4>
                <p style="color: var(--lingkup-text-muted); margin: 0;">
                    Profile Assessment akan membantu AI menyusun roadmap yang relevan untukmu.
                </p>
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