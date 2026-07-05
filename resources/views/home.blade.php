@extends('layouts.landing', ['title' => 'Home — LINGKUP'])

@section('content')
{{--
    Sprint 5.6.C: USER HOME PAGE — Full Design
    Audience: Authenticated users (post-login welcome area)

    Structure:
    1. Navbar (user authenticated state with dropdown)
    2. Hero Welcome (personalized greeting + state-based message)
    3. Journey Status (focal card: pathway active OR action needed)
    4. Final CTA (Masuk ke Dashboard)
    5. Footer (minimal)
--}}

@php
    // State detection
    $currentUser = auth()->user();
    $profile = $currentUser->profile;
    $userTarget = $currentUser->userTarget?->target;
    $pathway = $currentUser->pathway;

    $profileComplete = $profile && $profile->isComplete();
    $hasTarget = (bool) $userTarget;
    $hasPathway = (bool) $pathway;

    // Determine user journey state
    if ($hasPathway) {
        $journeyState = 'active';
    } elseif ($profileComplete && $hasTarget) {
        $journeyState = 'ready_generate';
    } elseif ($profileComplete && ! $hasTarget) {
        $journeyState = 'need_target';
    } else {
        $journeyState = 'need_profile';
    }

    $userInitial = strtoupper(substr($currentUser->name, 0, 1));
@endphp

{{-- ============================================ --}}
{{-- NAVBAR (Authenticated State)                  --}}
{{-- ============================================ --}}
<nav class="landing-navbar home-nav-extended">
    <div class="landing-navbar-container">
        <a href="{{ route('home') }}" class="landing-navbar-brand">
            <img src="{{ asset('images/logo-transparent.png') }}" alt="LINGKUP" class="lingkup-logo-img">
            <span>LINGKUP</span>
        </a>

        {{-- Desktop navbar items --}}
        <div class="home-nav-items">
            <a href="{{ route('dashboard') }}" class="home-nav-item">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('home') }}#countries" class="home-nav-item">
                <i class="bi bi-globe2"></i>
                <span>Negara</span>
            </a>
            <a href="{{ route('about') }}" class="home-nav-item">
                <i class="bi bi-info-circle"></i>
                <span>About Us</span>
            </a>
            <a href="{{ route('reviews') }}" class="home-nav-item">
                <i class="bi bi-chat-quote"></i>
                <span>Reviews</span>
            </a>
        </div>

        <div class="landing-navbar-actions">
            {{-- Mobile menu toggle --}}
            <button type="button" class="home-mobile-toggle" id="mobile-menu-toggle" aria-label="Toggle menu">
                <i class="bi bi-list"></i>
            </button>

            {{-- User dropdown --}}
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
                    <a href="{{ route('dashboard') }}" class="home-user-dropdown-item">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('profile-assessment.index') }}" class="home-user-dropdown-item">
                        <i class="bi bi-mortarboard"></i>
                        <span>Profil Akademik</span>
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
        </div>
    </div>

    {{-- Mobile menu (collapsed) --}}
    <div class="home-mobile-menu" id="mobile-menu">
        <a href="{{ route('dashboard') }}" class="home-mobile-menu-item">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('home') }}#countries" class="home-mobile-menu-item">
            <i class="bi bi-globe2"></i>
            <span>Negara</span>
        </a>
        <a href="{{ route('about') }}" class="home-mobile-menu-item">
            <i class="bi bi-info-circle"></i>
            <span>About Us</span>
        </a>
        <a href="{{ route('reviews') }}" class="home-mobile-menu-item">
            <i class="bi bi-chat-quote"></i>
            <span>Reviews</span>
        </a>
    </div>
</nav>

{{-- ============================================ --}}
{{-- HERO WELCOME (Personalized)                   --}}
{{-- ============================================ --}}
<section class="home-hero">
    {{-- Decorative shapes --}}
    <div class="landing-hero-shapes" aria-hidden="true">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
    </div>

    <div class="landing-container">
        <div class="home-hero-content">
            {{-- Brand illustration --}}
            <div class="home-hero-illustration">
                <img src="{{ asset('images/logo-transparent.png') }}" alt="LINGKUP">
            </div>

            {{-- Greeting badge --}}
            <div class="landing-hero-badge home-hero-badge">
                <span style="font-size: var(--fs-lg);">👋</span>
                <span>Halo, {{ $currentUser->name }}</span>
            </div>

            {{-- State-based title + subtitle --}}
            @if ($journeyState === 'active')
                <h1 class="landing-hero-title home-hero-title">
                    Selamat datang kembali di
                    <span class="text-gradient-primary">LINGKUP</span>
                </h1>
                <p class="landing-hero-subtitle home-hero-subtitle">
                    Pathway <strong>{{ $pathway->target->name }}</strong> sedang berjalan.
                    Lanjutkan perjalananmu di dashboard.
                </p>
            @elseif ($journeyState === 'ready_generate')
                <h1 class="landing-hero-title home-hero-title">
                    Siap menyusun roadmap
                    <span class="text-gradient-primary">{{ $userTarget->name }}</span>?
                </h1>
                <p class="landing-hero-subtitle home-hero-subtitle">
                    Profil dan targetmu sudah lengkap.
                    Saatnya AI menyusun roadmap personal untukmu.
                </p>
            @elseif ($journeyState === 'need_target')
                <h1 class="landing-hero-title home-hero-title">
                    Mari pilih
                    <span class="text-gradient-primary">target studimu</span>
                </h1>
                <p class="landing-hero-subtitle home-hero-subtitle">
                    Profilmu sudah lengkap. Langkah berikutnya adalah memilih beasiswa
                    atau program internasional yang ingin kamu kejar.
                </p>
            @else {{-- need_profile --}}
                <h1 class="landing-hero-title home-hero-title">
                    Mari mulai perjalanan menuju
                    <span class="text-gradient-primary">studi internasional</span>
                </h1>
                <p class="landing-hero-subtitle home-hero-subtitle">
                    Lengkapi profil akademikmu untuk memulai pengalaman LINGKUP.
                </p>
            @endif

            {{-- Primary CTA --}}
            <div class="landing-hero-actions home-hero-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg landing-cta-primary">
                    <i class="bi bi-grid-1x2 me-1"></i>
                    Masuk ke Dashboard
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- JOURNEY STATUS (Focal Card)                   --}}
{{-- ============================================ --}}
<section class="home-journey-section">
    <div class="landing-container">

        @if ($journeyState === 'active')
            {{-- ============== State: Active Pathway ============== --}}
            <div class="home-journey-card home-journey-card-active">
                <div class="home-journey-card-shapes" aria-hidden="true">
                    <div class="journey-shape journey-shape-1"></div>
                </div>

                <div class="home-journey-card-content">
                    <div class="home-journey-eyebrow">
                        <i class="bi bi-flag-fill"></i>
                        <span>Pathway Aktif</span>
                    </div>

                    <h2 class="home-journey-title">
                        {{ \Illuminate\Support\Str::limit($pathway->title, 80) }}
                    </h2>

                    <p class="home-journey-summary">
                        {{ \Illuminate\Support\Str::limit($pathway->summary, 180) }}
                    </p>

                    {{-- Stats grid --}}
                    <div class="home-journey-stats">
                        <div class="home-journey-stat">
                            <div class="home-journey-stat-icon home-journey-stat-icon-primary">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="home-journey-stat-value">{{ $pathway->estimated_total_duration }}</div>
                            <div class="home-journey-stat-label">Estimasi Durasi</div>
                        </div>

                        <div class="home-journey-stat">
                            <div class="home-journey-stat-icon home-journey-stat-icon-peach">
                                <i class="bi bi-layers"></i>
                            </div>
                            <div class="home-journey-stat-value">{{ $pathway->phases()->count() }}</div>
                            <div class="home-journey-stat-label">Fase Persiapan</div>
                        </div>

                        <div class="home-journey-stat">
                            <div class="home-journey-stat-icon home-journey-stat-icon-teal">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <div class="home-journey-stat-value">{{ $pathway->tasks()->count() }}</div>
                            <div class="home-journey-stat-label">Total Task</div>
                        </div>

                        <div class="home-journey-stat">
                            <div class="home-journey-stat-icon home-journey-stat-icon-green">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <div class="home-journey-stat-value">
                                {{ \Illuminate\Support\Str::limit($pathway->target->name, 12) }}
                            </div>
                            <div class="home-journey-stat-label">Target</div>
                        </div>
                    </div>

                    <div class="home-journey-actions">
                        <a href="{{ route('user.pathway.show', $pathway) }}" class="btn btn-primary">
                            <i class="bi bi-map me-1"></i>
                            Lihat Pathway
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            Buka Dashboard
                        </a>
                    </div>
                </div>
            </div>

        @elseif ($journeyState === 'ready_generate')
            {{-- ============== State: Ready to Generate ============== --}}
            <div class="home-journey-card home-journey-card-ready">
                <div class="home-journey-card-content text-center">
                    <div class="home-journey-icon-big home-journey-icon-big-primary">
                        <i class="bi bi-stars"></i>
                    </div>

                    <h2 class="home-journey-title">Pathway-mu menunggu disusun</h2>

                    <p class="home-journey-summary">
                        Target <strong>{{ $userTarget->name }}</strong> sudah dipilih.
                        AI akan menyusun roadmap multi-fase yang disesuaikan dengan profilmu.
                    </p>

                    <div class="home-journey-actions home-journey-actions-center">
                        <a href="{{ route('user.pathway.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-stars me-1"></i>
                            Mulai Generate Pathway
                        </a>
                    </div>
                </div>
            </div>

        @elseif ($journeyState === 'need_target')
            {{-- ============== State: Need Target ============== --}}
            <div class="home-journey-card home-journey-card-action">
                <div class="home-journey-card-content text-center">
                    <div class="home-journey-icon-big home-journey-icon-big-peach">
                        <i class="bi bi-bullseye"></i>
                    </div>

                    <h2 class="home-journey-title">Pilih target studimu</h2>

                    <p class="home-journey-summary">
                        Pilih beasiswa atau program internasional dari 8+ pilihan
                        seperti Chevening, MEXT, AAS, Fulbright, dan lainnya.
                    </p>

                    <div class="home-journey-actions home-journey-actions-center">
                        <a href="{{ route('target.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-bullseye me-1"></i>
                            Pilih Target Sekarang
                        </a>
                    </div>
                </div>
            </div>

        @else
            {{-- ============== State: Need Profile ============== --}}
            <div class="home-journey-card home-journey-card-action">
                <div class="home-journey-card-content text-center">
                    <div class="home-journey-icon-big home-journey-icon-big-teal">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <h2 class="home-journey-title">Lengkapi profil akademikmu</h2>

                    <p class="home-journey-summary">
                        Isi data jurusan, semester, IPK, dan kemampuan bahasa.
                        Profil membantu AI memahami konteks unik perjalananmu.
                    </p>

                    <div class="home-journey-actions home-journey-actions-center">
                        <a href="{{ route('profile-assessment.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-mortarboard me-1"></i>
                            Mulai Lengkapi Profil
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>

{{-- ============================================ --}}
{{-- COUNTRY HIGHLIGHTS — Inspiration Section      --}}
{{-- ============================================ --}}
<section class="home-countries-section" id="countries">
    <div class="landing-container">
        <div class="landing-section-header">
            <div class="landing-section-eyebrow">
                <i class="bi bi-globe2"></i>
                <span>Inspirasi · Negara Tujuan</span>
            </div>
            <h2 class="landing-section-title">
                Negara impianmu menanti
            </h2>
            <p class="landing-section-subtitle">
                Pilih destinasi studi internasional yang sesuai dengan visi karier dan minat akademikmu.
            </p>
        </div>

        <div class="countries-grid">

            {{-- ============ Country: Japan ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/japan.jpg') }}"
                         alt="Studi di Jepang"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇯🇵</div>
                    <h3 class="country-card-name">Jepang</h3>
                    <p class="country-card-scholarship">MEXT Scholarship</p>
                </div>
            </div>

            {{-- ============ Country: UK ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/uk.jpg') }}"
                         alt="Studi di Inggris"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇬🇧</div>
                    <h3 class="country-card-name">Inggris</h3>
                    <p class="country-card-scholarship">Chevening Scholarship</p>
                </div>
            </div>

            {{-- ============ Country: Australia ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/australia.jpg') }}"
                         alt="Studi di Australia"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇦🇺</div>
                    <h3 class="country-card-name">Australia</h3>
                    <p class="country-card-scholarship">Australia Awards (AAS)</p>
                </div>
            </div>

            {{-- ============ Country: USA ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/usa.jpg') }}"
                         alt="Studi di Amerika Serikat"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇺🇸</div>
                    <h3 class="country-card-name">Amerika Serikat</h3>
                    <p class="country-card-scholarship">Fulbright Scholarship</p>
                </div>
            </div>

            {{-- ============ Country: Germany ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/germany.jpg') }}"
                         alt="Studi di Jerman"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇩🇪</div>
                    <h3 class="country-card-name">Jerman</h3>
                    <p class="country-card-scholarship">DAAD Scholarship</p>
                </div>
            </div>

            {{-- ============ Country: Korea ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/korea.jpg') }}"
                         alt="Studi di Korea Selatan"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇰🇷</div>
                    <h3 class="country-card-name">Korea Selatan</h3>
                    <p class="country-card-scholarship">Global Korea Scholarship</p>
                </div>
            </div>

            {{-- ============ Country: Netherlands ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/netherlands.jpg') }}"
                         alt="Studi di Belanda"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇳🇱</div>
                    <h3 class="country-card-name">Belanda</h3>
                    <p class="country-card-scholarship">Erasmus+ Programme</p>
                </div>
            </div>

            {{-- ============ Country: Indonesia ============ --}}
            <div class="country-card">
                <div class="country-card-image">
                    <img src="{{ asset('images/countries/indonesia.jpg') }}"
                         alt="Studi di Indonesia (LPDP)"
                         loading="lazy">
                    <div class="country-card-overlay"></div>
                </div>
                <div class="country-card-content">
                    <div class="country-card-flag">🇮🇩</div>
                    <h3 class="country-card-name">Indonesia</h3>
                    <p class="country-card-scholarship">LPDP Scholarship</p>
                </div>
            </div>

        </div>

        {{-- Section CTA --}}
        <div class="home-countries-footer">
            <a href="{{ route('target.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-bullseye me-1"></i>
                Lihat Semua Target Studi
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FINAL CTA SECTION                             --}}
{{-- ============================================ --}}
<section class="landing-cta-section home-final-cta">
    <div class="landing-container">
        <div class="landing-cta-card">
            <div class="landing-cta-shapes" aria-hidden="true">
                <div class="cta-shape cta-shape-1"></div>
                <div class="cta-shape cta-shape-2"></div>
            </div>

            <div class="landing-cta-content">
                @if ($journeyState === 'active')
                    <h2 class="landing-cta-title">Lanjutkan perjalananmu</h2>
                    <p class="landing-cta-subtitle">
                        Buka dashboard untuk mengakses semua fitur dan progress pathway-mu.
                    </p>
                @else
                    <h2 class="landing-cta-title">Akses semua fitur di dashboard</h2>
                    <p class="landing-cta-subtitle">
                        Dashboard berisi semua tool yang kamu butuhkan untuk persiapan studimu.
                    </p>
                @endif

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
{{-- FOOTER MINIMAL                                --}}
{{-- ============================================ --}}
<footer class="landing-footer">
    <div class="landing-container">
        <div class="landing-footer-content">
            <div class="landing-footer-brand">
                <img src="{{ asset('images/logo-transparent.png') }}" alt="LINGKUP" class="landing-footer-logo-img">
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
// User menu dropdown toggle
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('user-menu-trigger');
    const dropdown = document.getElementById('user-menu-dropdown');

    if (trigger && dropdown) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdown.classList.toggle('show');
            trigger.setAttribute('aria-expanded', isOpen);
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (! trigger.contains(e.target) && ! dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Mobile menu toggle (Phase 5.6.D)
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('show');
            mobileToggle.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        mobileMenu.querySelectorAll('.home-mobile-menu-item').forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('show');
                mobileToggle.classList.remove('active');
            });
        });
    }

    // Smooth scroll for anchor links (Phase 5.6.D)
    document.querySelectorAll('a[href*="#countries"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const url = new URL(this.href, window.location.href);
            // Only handle same-page anchors
            if (url.pathname === window.location.pathname) {
                const target = document.getElementById('countries');
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});
</script>
@endpush