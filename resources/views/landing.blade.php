@extends('layouts.landing', ['title' => 'LINGKUP — AI Pathway untuk Studi Internasional'])

@section('content')
{{--
    Sprint 5.6.B: PUBLIC LANDING PAGE — Full Design
    Audience: Guest visitors (not logged in)

    Update: konten teks (hero, cards, dll) sekarang dibaca dari
    $content (array dari PageContent::getForPage('landing')),
    dikelola lewat Admin > Page Content. Fallback ke teks asli
    disediakan di setiap field kalau key belum ada di database
    (misal sebelum seeder pernah dijalankan).

    Structure:
    1. Navbar (sticky, solid white) — TETAP hardcoded, bukan editable
    2. Hero Section (gradient + CSS shapes)
    3. How It Works (3 horizontal cards)
    4. Value Propositions (4 cards)
    5. Final CTA Section
    6. Footer (minimal)
--}}

{{-- ============================================ --}}
{{-- NAVBAR (Phase 5.6.B) — Tidak editable          --}}
{{-- ============================================ --}}
<nav class="landing-navbar">
    <div class="landing-navbar-container">
        <a href="{{ route('landing') }}" class="landing-navbar-brand">
            <div class="landing-navbar-logo">L</div>
            <span>LINGKUP</span>
        </a>

        <div class="landing-navbar-actions">
            <a href="{{ route('login') }}" class="btn btn-link landing-navbar-login">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary landing-navbar-cta">
                Daftar Gratis
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</nav>

{{-- ============================================ --}}
{{-- HERO SECTION                                  --}}
{{-- ============================================ --}}
<section class="landing-hero">
    {{-- Decorative CSS shapes --}}
    <div class="landing-hero-shapes" aria-hidden="true">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
        <div class="hero-shape hero-shape-4"></div>
    </div>

    <div class="landing-container">
        <div class="landing-hero-content">
            {{-- Pill badge --}}
            <div class="landing-hero-badge">
                <span class="badge-dot"></span>
                <span>{{ $content['hero_badge'] ?? 'AI-Powered Career Navigator' }}</span>
            </div>

            {{-- Main title --}}
            <h1 class="landing-hero-title">
                {{ $content['hero_title_prefix'] ?? 'Roadmap personal AI untuk' }}
                <span class="text-gradient-primary">{{ $content['hero_title_highlight'] ?? 'studi internasional' }}</span>
                {{ $content['hero_title_suffix'] ?? 'impianmu.' }}
            </h1>

            {{-- Subtitle --}}
            <p class="landing-hero-subtitle">
                {!! $content['hero_subtitle'] ?? 'LINGKUP membantu mahasiswa Indonesia menyusun langkah persiapan beasiswa dan studi luar negeri dengan AI yang memahami profilmu.' !!}
            </p>

            {{-- CTA buttons --}}
            <div class="landing-hero-actions">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg landing-cta-primary">
                    <i class="bi bi-stars me-1"></i>
                    {{ $content['hero_cta_primary'] ?? 'Mulai Gratis Sekarang' }}
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg landing-cta-secondary">
                    {{ $content['hero_cta_secondary'] ?? 'Sudah punya akun?' }}
                </a>
            </div>

            {{-- Trust micro-copy --}}
            <p class="landing-hero-trust">
                <i class="bi bi-shield-check"></i>
                {{ $content['hero_trust_text'] ?? 'Gratis untuk mahasiswa Indonesia · Tidak perlu kartu kredit' }}
            </p>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- HOW IT WORKS — 3 Horizontal Cards             --}}
{{-- ============================================ --}}
<section class="landing-section" id="how-it-works">
    <div class="landing-container">
        <div class="landing-section-header">
            <div class="landing-section-eyebrow">
                <i class="bi bi-lightning-charge-fill"></i>
                <span>{{ $content['how_eyebrow'] ?? 'Cara Kerja' }}</span>
            </div>
            <h2 class="landing-section-title">
                {{ $content['how_title'] ?? 'Tiga langkah menuju pathway personalmu' }}
            </h2>
            <p class="landing-section-subtitle">
                {!! $content['how_subtitle'] ?? 'Sederhana, terstruktur, dan dipandu AI sepanjang prosesnya.' !!}
            </p>
        </div>

        <div class="how-it-works-grid">
            {{-- Step 1 --}}
            <div class="how-step-card">
                <div class="how-step-number">01</div>
                <div class="how-step-icon-wrap how-step-icon-primary">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h3 class="how-step-title">{{ $content['step1_title'] ?? 'Lengkapi Profil Akademik' }}</h3>
                <p class="how-step-desc">
                    {!! $content['step1_desc'] ?? 'Isi data jurusan, semester, IPK, kemampuan bahasa, dan minat karier untuk membantu AI memahami konteks unikmu.' !!}
                </p>
            </div>

            {{-- Step 2 --}}
            <div class="how-step-card">
                <div class="how-step-number">02</div>
                <div class="how-step-icon-wrap how-step-icon-peach">
                    <i class="bi bi-bullseye"></i>
                </div>
                <h3 class="how-step-title">{{ $content['step2_title'] ?? 'Pilih Target Studi' }}</h3>
                <p class="how-step-desc">
                    {!! $content['step2_desc'] ?? 'Tentukan beasiswa atau program internasional yang ingin kamu kejar dari 8+ pilihan: MEXT, LPDP, Chevening, AAS, dan lainnya.' !!}
                </p>
            </div>

            {{-- Step 3 --}}
            <div class="how-step-card">
                <div class="how-step-number">03</div>
                <div class="how-step-icon-wrap how-step-icon-teal">
                    <i class="bi bi-map"></i>
                </div>
                <h3 class="how-step-title">{{ $content['step3_title'] ?? 'Dapatkan Pathway AI' }}</h3>
                <p class="how-step-desc">
                    {!! $content['step3_desc'] ?? 'AI akan menyusun roadmap multi-fase dengan task konkret, estimasi durasi, dan prioritas yang disesuaikan denganmu.' !!}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- VALUE PROPOSITIONS — 4 Cards                  --}}
{{-- ============================================ --}}
<section class="landing-section landing-section-alt">
    <div class="landing-container">
        <div class="landing-section-header">
            <div class="landing-section-eyebrow">
                <i class="bi bi-gem"></i>
                <span>{{ $content['value_eyebrow'] ?? 'Mengapa LINGKUP' }}</span>
            </div>
            <h2 class="landing-section-title">
                {{ $content['value_title'] ?? 'Dirancang khusus untuk mahasiswa Indonesia' }}
            </h2>
        </div>

        <div class="value-props-grid">
            {{-- Value 1 --}}
            <div class="value-prop-card">
                <div class="value-prop-icon value-prop-icon-primary">
                    <i class="bi bi-robot"></i>
                </div>
                <h4 class="value-prop-title">{{ $content['value1_title'] ?? 'Pathway AI Personalisasi' }}</h4>
                <p class="value-prop-desc">
                    {!! $content['value1_desc'] ?? 'Setiap roadmap dibuat unik berdasarkan profil akademik, bahasa, dan target studimu — bukan template generik.' !!}
                </p>
            </div>

            {{-- Value 2 --}}
            <div class="value-prop-card">
                <div class="value-prop-icon value-prop-icon-peach">
                    <i class="bi bi-globe2"></i>
                </div>
                <h4 class="value-prop-title">{{ $content['value2_title'] ?? '8+ Target Beasiswa' }}</h4>
                <p class="value-prop-desc">
                    {!! $content['value2_desc'] ?? 'MEXT (Jepang), Chevening (UK), AAS (Australia), Fulbright (US), DAAD (Jerman), GKS (Korea), Erasmus+ (Eropa), LPDP.' !!}
                </p>
            </div>

            {{-- Value 3 --}}
            <div class="value-prop-card">
                <div class="value-prop-icon value-prop-icon-yellow">
                    <i class="bi bi-translate"></i>
                </div>
                <h4 class="value-prop-title">{{ $content['value3_title'] ?? 'Bahasa Indonesia' }}</h4>
                <p class="value-prop-desc">
                    {!! $content['value3_desc'] ?? 'Antarmuka dan output AI sepenuhnya berbahasa Indonesia. Lebih mudah dipahami dan terasa lebih dekat.' !!}
                </p>
            </div>

            {{-- Value 4 --}}
            <div class="value-prop-card">
                <div class="value-prop-icon value-prop-icon-green">
                    <i class="bi bi-piggy-bank"></i>
                </div>
                <h4 class="value-prop-title">{{ $content['value4_title'] ?? 'Gratis Sepenuhnya' }}</h4>
                <p class="value-prop-desc">
                    {!! $content['value4_desc'] ?? 'Dibangun untuk membantu mahasiswa Indonesia berani melangkah ke kancah internasional — tanpa biaya berlangganan.' !!}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FINAL CTA SECTION                             --}}
{{-- ============================================ --}}
<section class="landing-cta-section">
    <div class="landing-container">
        <div class="landing-cta-card">
            <div class="landing-cta-shapes" aria-hidden="true">
                <div class="cta-shape cta-shape-1"></div>
                <div class="cta-shape cta-shape-2"></div>
            </div>

            <div class="landing-cta-content">
                <h2 class="landing-cta-title">
                    {{ $content['cta_title'] ?? 'Siap memulai perjalananmu?' }}
                </h2>
                <p class="landing-cta-subtitle">
                    {!! $content['cta_subtitle'] ?? 'Bergabung dengan LINGKUP hari ini dan dapatkan pathway AI personalmu.' !!}
                </p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg landing-cta-button">
                    <i class="bi bi-stars me-1"></i>
                    {{ $content['cta_button'] ?? 'Daftar Sekarang' }}
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FOOTER MINIMAL                                --}}
{{-- ============================================ --}}
<footer class="landing-footer">
    <div class="landing-container">
        <div class="landing-footer-content">
            <div class="landing-footer-brand">
                <div class="landing-footer-logo">L</div>
                <span>LINGKUP</span>
            </div>
            <p class="landing-footer-tagline">
                © {{ date('Y') }} LINGKUP · {{ $content['footer_tagline'] ?? 'Your Global Pathway Starts Here' }}
            </p>
        </div>
    </div>
</footer>
@endsection