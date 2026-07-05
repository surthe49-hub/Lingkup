@extends('layouts.landing', ['title' => 'About Us — LINGKUP'])

@section('content')
{{--
    Sprint 5.6.D: ABOUT US PAGE
    Content: Draft Claude (user dapat edit)
    Audience: Authenticated user (post-login)
--}}

{{-- Reuse home navbar component pattern --}}
@php
    $currentUser = auth()->user();
    $userInitial = $currentUser ? strtoupper(substr($currentUser->name, 0, 1)) : '?';
@endphp

{{-- ============================================ --}}
{{-- NAVBAR (Same as home for consistency)         --}}
{{-- ============================================ --}}
<nav class="landing-navbar home-nav-extended">
    <div class="landing-navbar-container">
        <a href="{{ route('home') }}" class="landing-navbar-brand">
            <div class="landing-navbar-logo">L</div>
            <span>LINGKUP</span>
        </a>

        <div class="home-nav-items">
            <a href="{{ route('dashboard') }}" class="home-nav-item">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('home') }}#countries" class="home-nav-item">
                <i class="bi bi-globe2"></i>
                <span>Negara</span>
            </a>
            <a href="{{ route('about') }}" class="home-nav-item active">
                <i class="bi bi-info-circle"></i>
                <span>About Us</span>
            </a>
            <a href="{{ route('reviews') }}" class="home-nav-item">
                <i class="bi bi-chat-quote"></i>
                <span>Reviews</span>
            </a>
        </div>

        <div class="landing-navbar-actions">
            <button type="button" class="home-mobile-toggle" id="mobile-menu-toggle" aria-label="Toggle menu">
                <i class="bi bi-list"></i>
            </button>

            @if ($currentUser)
                <div class="home-user-menu">
                    <button type="button" class="home-user-trigger" id="user-menu-trigger" aria-haspopup="true" aria-expanded="false">
                        <div class="home-user-avatar">{{ $userInitial }}</div>
                        <span class="home-user-name">{{ \Illuminate\Support\Str::limit($currentUser->name, 15) }}</span>
                        <i class="bi bi-chevron-down home-user-chevron"></i>
                    </button>

                    <div class="home-user-dropdown" id="user-menu-dropdown" role="menu">
                        <div class="home-user-dropdown-header">
                            <div class="home-user-dropdown-name">{{ $currentUser->name }}</div>
                            <div class="home-user-dropdown-email">{{ $currentUser->email }}</div>
                        </div>
                        <div class="home-user-dropdown-divider"></div>
                        <a href="{{ route('home') }}" class="home-user-dropdown-item">
                            <i class="bi bi-house"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="home-user-dropdown-item">
                            <i class="bi bi-grid-1x2"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="home-user-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="home-user-dropdown-form">
                            @csrf
                            <button type="submit" class="home-user-dropdown-item home-user-dropdown-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="home-mobile-menu" id="mobile-menu">
        <a href="{{ route('dashboard') }}" class="home-mobile-menu-item">
            <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('home') }}#countries" class="home-mobile-menu-item">
            <i class="bi bi-globe2"></i><span>Negara</span>
        </a>
        <a href="{{ route('about') }}" class="home-mobile-menu-item active">
            <i class="bi bi-info-circle"></i><span>About Us</span>
        </a>
        <a href="{{ route('reviews') }}" class="home-mobile-menu-item">
            <i class="bi bi-chat-quote"></i><span>Reviews</span>
        </a>
    </div>
</nav>

{{-- ============================================ --}}
{{-- HERO ABOUT                                    --}}
{{-- ============================================ --}}
<section class="page-hero">
    <div class="landing-hero-shapes" aria-hidden="true">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
    </div>

    <div class="landing-container">
        <div class="page-hero-content">
            <div class="landing-hero-badge">
                <i class="bi bi-info-circle"></i>
                <span>About LINGKUP</span>
            </div>

            <h1 class="page-hero-title">
                Membantu mahasiswa Indonesia menuju
                <span class="text-gradient-primary">studi internasional</span>
            </h1>

            <p class="page-hero-subtitle">
                LINGKUP adalah platform persiapan studi luar negeri berbasis AI yang dirancang
                khusus untuk mahasiswa Indonesia dengan ambisi global.
            </p>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- MISSION SECTION                               --}}
{{-- ============================================ --}}
<section class="page-section">
    <div class="landing-container">
        <div class="about-mission-grid">
            <div class="about-mission-content">
                <div class="landing-section-eyebrow">
                    <i class="bi bi-target"></i>
                    <span>Misi Kami</span>
                </div>

                <h2 class="about-section-title">
                    Memberdayakan akses pendidikan global
                </h2>

                <p class="about-section-text">
                    Persiapan studi luar negeri sering kali terbatas pada mereka yang punya akses
                    ke konsultan beasiswa premium atau jaringan alumni. LINGKUP hadir untuk menutup
                    kesenjangan itu dengan menghadirkan panduan AI yang dapat diakses oleh siapa saja.
                </p>

                <p class="about-section-text">
                    Kami percaya bahwa setiap mahasiswa Indonesia berhak mendapatkan roadmap yang
                    terstruktur, personal, dan dapat ditindaklanjuti, terlepas dari lokasi geografis
                    atau latar belakang sosial-ekonomi.
                </p>
            </div>

            <div class="about-mission-visual">
                <div class="about-mission-card">
                    <div class="about-mission-stat">
                        <i class="bi bi-globe2"></i>
                        <div>
                            <div class="about-mission-stat-value">8+</div>
                            <div class="about-mission-stat-label">Target beasiswa internasional</div>
                        </div>
                    </div>
                    <div class="about-mission-stat">
                        <i class="bi bi-robot"></i>
                        <div>
                            <div class="about-mission-stat-value">AI</div>
                            <div class="about-mission-stat-label">Pathway personalisasi</div>
                        </div>
                    </div>
                    <div class="about-mission-stat">
                        <i class="bi bi-translate"></i>
                        <div>
                            <div class="about-mission-stat-value">100%</div>
                            <div class="about-mission-stat-label">Bahasa Indonesia</div>
                        </div>
                    </div>
                    <div class="about-mission-stat">
                        <i class="bi bi-piggy-bank"></i>
                        <div>
                            <div class="about-mission-stat-value">Gratis</div>
                            <div class="about-mission-stat-label">Untuk semua mahasiswa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- WHY LINGKUP — 3 PRINCIPLES                    --}}
{{-- ============================================ --}}
<section class="page-section landing-section-alt">
    <div class="landing-container">
        <div class="landing-section-header">
            <div class="landing-section-eyebrow">
                <i class="bi bi-stars"></i>
                <span>Prinsip Kami</span>
            </div>
            <h2 class="landing-section-title">Yang membedakan LINGKUP</h2>
        </div>

        <div class="about-principles-grid">
            <div class="about-principle-card">
                <div class="about-principle-icon about-principle-icon-primary">
                    <i class="bi bi-person-check"></i>
                </div>
                <h3 class="about-principle-title">Personal, bukan generik</h3>
                <p class="about-principle-text">
                    Setiap pathway dibuat berdasarkan profil akademik, kemampuan bahasa, dan target
                    studi unik dari masing-masing pengguna — bukan template yang sama untuk semua.
                </p>
            </div>

            <div class="about-principle-card">
                <div class="about-principle-icon about-principle-icon-peach">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <h3 class="about-principle-title">Terstruktur dan bertahap</h3>
                <p class="about-principle-text">
                    Persiapan beasiswa dibagi ke dalam fase-fase yang jelas dengan task konkret di
                    setiap fase. Pengguna tidak perlu kewalahan memikirkan semua hal sekaligus.
                </p>
            </div>

            <div class="about-principle-card">
                <div class="about-principle-icon about-principle-icon-teal">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="about-principle-title">Konteks lokal Indonesia</h3>
                <p class="about-principle-text">
                    Output AI disesuaikan dengan realitas mahasiswa Indonesia: kalender akademik,
                    deadline LPDP, persiapan IELTS, hingga referensi institusi dalam negeri.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- THE STORY (Developer & Research Context)     --}}
{{-- ============================================ --}}
<section class="page-section">
    <div class="landing-container">
        <div class="about-story-wrapper">
            <div class="landing-section-eyebrow">
                <i class="bi bi-book"></i>
                <span>Cerita di Balik LINGKUP</span>
            </div>

            <h2 class="about-section-title text-center">
                Dibangun sebagai proyek penelitian Design Science Research
            </h2>

            <div class="about-story-content">
                <p class="about-section-text">
                    LINGKUP merupakan implementasi dari penelitian skripsi yang berfokus pada penerapan
                    teknologi AI generatif untuk mendukung perjalanan akademik mahasiswa Indonesia.
                    Metodologi Design Science Research digunakan untuk memastikan setiap iterasi platform
                    dievaluasi secara sistematis berdasarkan kebutuhan nyata pengguna.
                </p>

                <p class="about-section-text">
                    Platform ini dikembangkan oleh <strong>Muhammad Rafi Awallaisal</strong>, mahasiswa
                    Sistem Informasi Telkom University Purwokerto, sebagai kontribusi nyata bagi komunitas
                    akademik Indonesia. Setiap fitur dibangun dengan mempertimbangkan keterbatasan akses
                    yang sering dihadapi mahasiswa di luar pusat-pusat kota besar.
                </p>

                <div class="about-story-tech">
                    <h4>Stack teknologi:</h4>
                    <div class="about-story-tech-badges">
                        <span class="about-tech-badge">Laravel 12</span>
                        <span class="about-tech-badge">PHP 8.3</span>
                        <span class="about-tech-badge">MySQL</span>
                        <span class="about-tech-badge">Bootstrap 5</span>
                        <span class="about-tech-badge">Vite</span>
                        <span class="about-tech-badge">Gemini AI</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FINAL CTA                                     --}}
{{-- ============================================ --}}
<section class="landing-cta-section">
    <div class="landing-container">
        <div class="landing-cta-card">
            <div class="landing-cta-shapes" aria-hidden="true">
                <div class="cta-shape cta-shape-1"></div>
                <div class="cta-shape cta-shape-2"></div>
            </div>

            <div class="landing-cta-content">
                <h2 class="landing-cta-title">Siap memulai perjalananmu?</h2>
                <p class="landing-cta-subtitle">
                    Lanjutkan ke dashboard dan dapatkan pathway AI personal untuk target studimu.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg landing-cta-button">
                    <i class="bi bi-grid-1x2 me-1"></i>
                    Masuk ke Dashboard
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FOOTER                                        --}}
{{-- ============================================ --}}
<footer class="landing-footer">
    <div class="landing-container">
        <div class="landing-footer-content">
            <div class="landing-footer-brand">
                <div class="landing-footer-logo">L</div>
                <span>LINGKUP</span>
            </div>
            <p class="landing-footer-tagline">
                © {{ date('Y') }} LINGKUP · Your Global Pathway Starts Here
            </p>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User menu dropdown
    const trigger = document.getElementById('user-menu-trigger');
    const dropdown = document.getElementById('user-menu-dropdown');

    if (trigger && dropdown) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdown.classList.toggle('show');
            trigger.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', function(e) {
            if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Mobile menu
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('show');
            mobileToggle.classList.toggle('active');
        });
    }
});
</script>
@endpush