@extends('layouts.dashboard')

@section('title', 'Detail Pathway')

@section('content')
    <x-page-header
        title="Roadmap Persiapan Anda"
        subtitle="Detail roadmap personal yang dihasilkan AI untuk target studi internasional."
    />

    <div class="pathway-detail-container">
        {{-- Header Pathway --}}
        <x-pathway.header :pathway="$pathway" />

        {{-- Phases Accordion --}}
        <div class="phases-section">
            <div class="section-heading">
                <h2>Fase Persiapan</h2>
                <p class="text-muted">Klik setiap fase untuk melihat task-task konkret yang perlu Anda jalankan.</p>
            </div>

            <div class="phase-accordion-container">
                @foreach ($pathway->phases as $phase)
                    <x-pathway.phase-accordion 
                        :phase="$phase" 
                        :expanded="$loop->first" />
                @endforeach
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="pathway-footer-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection