@extends('layouts.dashboard')

@section('breadcrumb', 'Pilih Target')

@section('content')
    <x-page-header
        title="Pilih Target"
        subtitle="Pilih satu beasiswa atau program internasional yang ingin kamu kejar." />

    {{-- ============================================ --}}
    {{-- Banner Status: Active Target                  --}}
    {{-- ============================================ --}}
    @if ($activeTarget)
        {{-- State B: User sudah punya active target --}}
        <div class="lingkup-card mb-4" style="background: var(--lingkup-primary-light); border-color: var(--lingkup-primary);">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-bullseye" style="font-size: 1.5rem; color: var(--lingkup-primary);"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.125rem;">
                        Target aktif kamu saat ini
                    </div>
                    <div style="font-weight: 600; font-size: 1.0625rem; color: var(--lingkup-text);">
                        {{ $activeTarget->name }}
                    </div>
                    <div style="font-size: 0.875rem; color: var(--lingkup-text-muted); margin-top: 0.25rem;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $activeTarget->country }}
                    </div>
                </div>
                <a href="{{ route('target.show', $activeTarget) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    @else
        {{-- State A: Belum pilih target --}}
        <div class="lingkup-card mb-4" style="background: #FFFBEB; border-color: #FCD34D;">
            <div class="d-flex align-items-start gap-3">
                <div style="flex-shrink: 0;">
                    <i class="bi bi-info-circle-fill" style="font-size: 1.25rem; color: #D97706;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">
                        Kamu belum memilih target
                    </div>
                    <div style="color: var(--lingkup-text-muted); font-size: 0.9375rem;">
                        Pilih satu target di bawah untuk mulai menyusun pathway. Kamu bisa ganti target kapan saja.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================ --}}
    {{-- Empty State: Tidak ada target di database     --}}
    {{-- ============================================ --}}
    @if ($targets->isEmpty())
        <div class="lingkup-card">
            <x-empty-state
                icon="bi-bullseye"
                title="Belum Ada Target Tersedia"
                text="Saat ini belum ada target beasiswa yang tersedia di sistem. Silakan hubungi admin untuk informasi lebih lanjut atau coba lagi nanti.">
                <x-slot name="action">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                </x-slot>
            </x-empty-state>
        </div>
    @else
        {{-- ============================================ --}}
        {{-- Grid Cards: Daftar Target                    --}}
        {{-- ============================================ --}}
        <div class="row g-4">
            @foreach ($targets as $target)
                @php
                    $isThisActive = $activeTarget?->id === $target->id;
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="lingkup-card h-100 d-flex flex-column"
                         style="{{ $isThisActive ? 'border-color: var(--lingkup-primary); border-width: 2px;' : '' }}">

                        {{-- Card Header: Name + Active Badge --}}
                        <div class="mb-3">
                            @if ($isThisActive)
                                <span class="badge mb-2"
                                      style="background: var(--lingkup-primary); color: white; padding: 0.25rem 0.625rem; border-radius: var(--radius-full); font-weight: 500; font-size: 0.75rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Target Aktif
                                </span>
                            @endif

                            <h3 style="font-size: 1.0625rem; font-weight: 600; margin: 0 0 0.5rem; line-height: 1.4;">
                                {{ $target->name }}
                            </h3>

                            {{-- Country + Education Level --}}
                            <div class="d-flex flex-wrap gap-2" style="font-size: 0.8125rem; color: var(--lingkup-text-muted);">
                                <span>
                                    <i class="bi bi-geo-alt me-1"></i>{{ $target->country }}
                                </span>
                                <span>·</span>
                                <span>
                                    <i class="bi bi-mortarboard me-1"></i>{{ $target->education_level }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body: Snippet --}}
                        <div class="flex-grow-1 mb-3">
                            <p style="font-size: 0.875rem; color: var(--lingkup-text-muted); line-height: 1.5; margin: 0;">
                                {{ \Illuminate\Support\Str::limit($target->requirements_summary, 130) }}
                            </p>
                        </div>

                        {{-- Card Footer: Deadline + Action --}}
                        <div>
                            @if ($target->typical_deadline)
                                <div style="font-size: 0.75rem; color: var(--lingkup-text-light); margin-bottom: 0.75rem;">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Deadline: {{ $target->typical_deadline }}
                                </div>
                            @endif

                            <a href="{{ route('target.show', $target) }}"
                               class="btn btn-sm w-100 {{ $isThisActive ? 'btn-outline-primary' : 'btn-primary' }}">
                                @if ($isThisActive)
                                    Lihat Detail
                                @else
                                    Lihat Detail
                                    <i class="bi bi-arrow-right ms-1"></i>
                                @endif
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Helper text di bawah grid --}}
        <div class="text-center mt-4" style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
            <i class="bi bi-info-circle me-1"></i>
            Menampilkan {{ $targets->count() }} target aktif. Klik card untuk melihat detail dan memilih.
        </div>
    @endif
@endsection