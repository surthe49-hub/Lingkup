@extends('layouts.dashboard')

@section('breadcrumb', 'Profil Saya')

@section('content')
    {{-- Page Header --}}
    <x-page-header
        title="Profil Saya"
        subtitle="Kelola informasi akun dan keamanan." />

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Update Profile Information --}}
            @include('profile.partials.update-profile-information-form')

            {{-- Update Password --}}
            @include('profile.partials.update-password-form')

            {{-- Delete Account --}}
            @include('profile.partials.delete-user-form')

        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="lingkup-card">
                <h3 class="lingkup-card-title">Tentang Profil</h3>
                <p style="color: var(--lingkup-text-muted); font-size: 0.9375rem; margin-bottom: var(--space-md);">
                    Informasi di halaman ini digunakan untuk:
                </p>
                <ul style="padding-left: 1.25rem; color: var(--lingkup-text-muted); font-size: 0.9375rem; margin: 0;">
                    <li class="mb-2">Identifikasi akun dan komunikasi</li>
                    <li class="mb-2">Keamanan login</li>
                    <li>Personalisasi pengalaman</li>
                </ul>

                <hr style="margin: var(--space-lg) 0; border-color: var(--lingkup-border);">

                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted);">
                    Member sejak<br>
                    <span style="color: var(--lingkup-text); font-weight: 500;">
                        {{ Auth::user()->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection