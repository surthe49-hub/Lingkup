@extends('layouts.dashboard')

@section('breadcrumb', 'Pilih Target · ' . $target->name)

@section('content')
    {{-- Back Link --}}
    <div class="mb-3">
        <a href="{{ route('target.index') }}"
           class="text-decoration-none"
           style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Target
        </a>
    </div>

    {{-- ============================================ --}}
    {{-- Hero Section: Target Summary                  --}}
    {{-- ============================================ --}}
    <div class="lingkup-card mb-4"
         style="{{ $isCurrentlyActive ? 'border-color: var(--lingkup-primary); border-width: 2px;' : '' }}">

        {{-- Active Badge (jika target ini aktif) --}}
        @if ($isCurrentlyActive)
            <div class="mb-3">
                <span class="badge"
                      style="background: var(--lingkup-primary); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-full); font-weight: 500; font-size: 0.8125rem;">
                    <i class="bi bi-check-circle-fill me-1"></i> Target Aktif Kamu
                </span>
            </div>
        @endif

        {{-- Target Name + Country --}}
        <div class="d-flex align-items-start gap-3 flex-wrap mb-3">
            <div style="width: 56px; height: 56px; border-radius: var(--radius-lg); background: var(--lingkup-primary-light); color: var(--lingkup-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: 700;">
                {{ strtoupper(substr($target->name, 0, 1)) }}
            </div>
            <div class="flex-grow-1">
                <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.5rem; line-height: 1.3;">
                    {{ $target->name }}
                </h1>
                <div class="d-flex flex-wrap gap-3" style="color: var(--lingkup-text-muted); font-size: 0.9375rem;">
                    <span><i class="bi bi-geo-alt me-1"></i>{{ $target->country }}</span>
                    <span><i class="bi bi-mortarboard me-1"></i>{{ $target->education_level }}</span>
                    <span><i class="bi bi-tag me-1"></i>{{ ucfirst(str_replace('_', ' ', $target->program_type)) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- Section: Persyaratan Utama                    --}}
    {{-- ============================================ --}}
    <div class="lingkup-card mb-4">
        <h3 class="lingkup-card-title">
            <i class="bi bi-list-check me-2" style="color: var(--lingkup-primary);"></i>
            Persyaratan Utama
        </h3>
        <p style="white-space: pre-line; line-height: 1.7; margin: 0; color: var(--lingkup-text);">
            {{ $target->requirements_summary }}
        </p>
    </div>

    {{-- ============================================ --}}
    {{-- Section: Detail Persyaratan (jika ada)        --}}
    {{-- ============================================ --}}
    @if (! empty($target->structured_requirements) && is_array($target->structured_requirements))
        <div class="lingkup-card mb-4">
            <h3 class="lingkup-card-title">
                <i class="bi bi-card-checklist me-2" style="color: var(--lingkup-primary);"></i>
                Detail Persyaratan
            </h3>
            <div class="row g-3">
                @foreach ($target->structured_requirements as $key => $value)
                    <div class="col-md-6">
                        <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.025em;">
                            {{ str_replace('_', ' ', $key) }}
                        </div>
                        <div style="font-weight: 500;">
                            @if (is_array($value))
                                <ul style="padding-left: 1.25rem; margin: 0;">
                                    @foreach ($value as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $value }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================ --}}
    {{-- Section: Info Tambahan                        --}}
    {{-- ============================================ --}}
    @if ($target->typical_deadline || $target->official_url)
        <div class="lingkup-card mb-4">
            <h3 class="lingkup-card-title">
                <i class="bi bi-info-circle me-2" style="color: var(--lingkup-primary);"></i>
                Info Tambahan
            </h3>

            <div class="row g-3">
                @if ($target->typical_deadline)
                    <div class="col-md-6">
                        <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">
                            <i class="bi bi-calendar-event me-1"></i> Periode Pendaftaran
                        </div>
                        <div style="font-weight: 500;">{{ $target->typical_deadline }}</div>
                    </div>
                @endif

                @if ($target->official_url)
                    <div class="col-md-6">
                        <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">
                            <i class="bi bi-link-45deg me-1"></i> Sumber Resmi
                        </div>
                        <a href="{{ $target->official_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="font-weight: 500; word-break: break-all;">
                            {{ $target->official_url }}
                            <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.75rem;"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ============================================ --}}
    {{-- Action Bar: 3-State                           --}}
    {{-- ============================================ --}}
    <div class="lingkup-card" style="background: var(--lingkup-primary-light); border-color: var(--lingkup-primary);">

        @if ($isCurrentlyActive)
            {{-- State B: Target ini ADALAH active target user --}}
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check-circle-fill" style="font-size: 1.5rem; color: var(--lingkup-success);"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <div style="font-weight: 600; margin-bottom: 0.125rem;">
                        Sudah Dipilih sebagai Target Aktif
                    </div>
                    <div style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                        Target ini adalah pilihan aktif kamu saat ini.
                    </div>
                </div>
                <a href="{{ route('target.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-grid me-1"></i> Lihat Target Lain
                </a>
            </div>

        @elseif ($activeTarget)
            {{-- State C: User punya target lain (akan diganti) --}}
            <div class="mb-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill" style="color: #D97706; font-size: 1.125rem; flex-shrink: 0; margin-top: 2px;"></i>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">
                            Memilih target ini akan mengganti target aktif kamu
                        </div>
                        <div style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                            Target aktif saat ini: <strong>{{ $activeTarget->name }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmSelectModal">
                <i class="bi bi-arrow-repeat me-1"></i> Ganti ke Target Ini
            </button>

        @else
            {{-- State A: User belum punya target apapun --}}
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-bullseye" style="font-size: 1.5rem; color: var(--lingkup-primary);"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 200px;">
                    <div style="font-weight: 600; margin-bottom: 0.125rem;">
                        Pilih target ini untuk mulai menyusun pathway
                    </div>
                    <div style="color: var(--lingkup-text-muted); font-size: 0.875rem;">
                        Kamu bisa ganti target kapan saja jika berubah pikiran.
                    </div>
                </div>
                <button type="button" class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmSelectModal">
                    <i class="bi bi-check2 me-1"></i> Pilih Target Ini
                </button>
            </div>
        @endif

    </div>

    {{-- ============================================ --}}
    {{-- Modal Konfirmasi (hanya render jika belum aktif) --}}
    {{-- ============================================ --}}
    @unless ($isCurrentlyActive)
        <div class="modal fade" id="confirmSelectModal" tabindex="-1" aria-labelledby="confirmSelectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: var(--radius-lg); border: 1px solid var(--lingkup-border);">

                    <form action="{{ route('target.select', $target) }}" method="POST">
                        @csrf

                        <div class="modal-header" style="border-bottom: 1px solid var(--lingkup-border);">
                            <h5 class="modal-title" id="confirmSelectModalLabel" style="font-weight: 600;">
                                @if ($activeTarget)
                                    Ganti Target?
                                @else
                                    Pilih Target Ini?
                                @endif
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            @if ($activeTarget)
                                {{-- Variant: Replace existing --}}
                                <p style="margin-bottom: var(--space-md);">
                                    Target aktif kamu akan diganti:
                                </p>
                                <div class="lingkup-card mb-3" style="background: var(--lingkup-bg); padding: var(--space-md);">
                                    <div class="d-flex align-items-center gap-2" style="font-size: 0.875rem; color: var(--lingkup-text-muted); margin-bottom: 0.25rem;">
                                        <i class="bi bi-x-circle"></i> Target lama
                                    </div>
                                    <div style="font-weight: 600;">{{ $activeTarget->name }}</div>
                                </div>
                                <div class="lingkup-card mb-3" style="background: var(--lingkup-primary-light); border-color: var(--lingkup-primary); padding: var(--space-md);">
                                    <div class="d-flex align-items-center gap-2" style="font-size: 0.875rem; color: var(--lingkup-primary); margin-bottom: 0.25rem;">
                                        <i class="bi bi-arrow-down-circle"></i> Target baru
                                    </div>
                                    <div style="font-weight: 600;">{{ $target->name }}</div>
                                </div>
                                <p style="color: var(--lingkup-text-muted); font-size: 0.875rem; margin: 0;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pathway yang sudah dibuat untuk target lama akan tetap tersimpan, tapi tidak akan jadi pathway aktif lagi.
                                </p>
                            @else
                                {{-- Variant: First-time selection --}}
                                <p style="margin-bottom: var(--space-md);">
                                    Kamu akan memilih target berikut sebagai target aktif:
                                </p>
                                <div class="lingkup-card mb-3" style="background: var(--lingkup-primary-light); border-color: var(--lingkup-primary); padding: var(--space-md);">
                                    <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ $target->name }}</div>
                                    <div style="font-size: 0.875rem; color: var(--lingkup-text-muted);">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $target->country }} ·
                                        <i class="bi bi-mortarboard me-1"></i>{{ $target->education_level }}
                                    </div>
                                </div>
                                <p style="color: var(--lingkup-text-muted); font-size: 0.875rem; margin: 0;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Kamu bisa ganti target kapan saja jika berubah pikiran.
                                </p>
                            @endif
                        </div>

                        <div class="modal-footer" style="border-top: 1px solid var(--lingkup-border);">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                @if ($activeTarget)
                                    <i class="bi bi-arrow-repeat me-1"></i> Ya, Ganti Target
                                @else
                                    <i class="bi bi-check2 me-1"></i> Ya, Pilih Target
                                @endif
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endunless
@endsection