@extends('layouts.dashboard')

@section('breadcrumb', 'Admin · Dashboard')

@section('content')
    {{-- Page Header --}}
    <x-page-header
        title="Admin Dashboard"
        subtitle="Overview sistem dan aktivitas LINGKUP." />

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <x-stat-card
                label="Total User"
                :value="$stats['total_users']"
                icon="bi-people-fill"
                meta="Pengguna terdaftar" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-stat-card
                label="Total Admin"
                :value="$stats['total_admins']"
                icon="bi-shield-fill-check"
                meta="Administrator aktif" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-stat-card
                label="Total Target"
                value="0"
                icon="bi-bullseye"
                meta="Akan tersedia di Sprint 2" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-stat-card
                label="Total Pathway"
                value="0"
                icon="bi-map-fill"
                meta="Akan tersedia di Sprint 3" />
        </div>
    </div>

    {{-- Quick Info --}}
    <div class="row g-3">
        <div class="col-lg-8">
            <x-section-card title="Aktivitas Sistem">
                <x-empty-state
                    icon="bi-activity"
                    title="Belum ada aktivitas terbaru"
                    text="Data aktivitas akan muncul setelah user mulai menggunakan platform." />
            </x-section-card>
        </div>

        <div class="col-lg-4">
            <x-section-card title="Fitur Admin Mendatang">
                <ul style="padding-left: 1.25rem; color: var(--lingkup-text-muted); margin: 0;">
                    <li class="mb-2">User Monitoring</li>
                    <li class="mb-2">Target Management (CRUD)</li>
                    <li class="mb-2">Feedback Monitoring</li>
                    <li>Reports & Analytics</li>
                </ul>
            </x-section-card>
        </div>
    </div>
@endsection